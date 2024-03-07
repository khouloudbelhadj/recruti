<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Form\ParticipationType;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Picqer\Barcode\BarcodeGeneratorHTML;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

#[Route('/participation')]
class ParticipationController extends AbstractController
{

    #[Route('/generateExcel', name: 'excel')]
    public function generateParticipationExcel(ParticipationRepository $participationRepository): BinaryFileResponse
    {
        $participations = $participationRepository->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Define column names
        $columnNames = ['Name Participant', 'Role', 'Status', 'Feedback', 'Event Name'];
        
        // Set the entire first row at once and make it bold
        $sheet->fromArray([$columnNames], null, 'A1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2; // Start from the second row
        foreach ($participations as $participation) {
            $data = [
                $participation->getNomParticipant(),
                $participation->getRole(),
                $participation->getStatut(),
                $participation->getFeedback(),
                $participation->getEvent()->getNomE(), // Assuming getEvent() returns an Event entity
            ];

            // Set data starting from the second row
            $sheet->fromArray([$data], null, 'A' . $row);

            $row++;
        }

        // Apply bold style to the first row
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $writer = new Xlsx($spreadsheet);

        $fileName = 'participations.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($tempFile);

        return new BinaryFileResponse($tempFile, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
        ]);
    }
    
    #[Route('/', name: 'app_participation_index', methods: ['GET'])]
    public function index(ParticipationRepository $participationRepository): Response
    {
        return $this->render('participation/index.html.twig', [
            'participations' => $participationRepository->findAll(),
        ]);
    }
    
    #[Route('/front', name: 'app_participation_index_front', methods: ['GET'])]
    public function indexfront(Request $request,ParticipationRepository $participationRepository, PaginatorInterface $paginator): Response
    {
        $allparticipation =$participationRepository ->findAll();
        
        $participations = $paginator->paginate(
            $allparticipation, 
            $request->query->getInt('page', 1), 
            2//
        );
        
        return $this->render('participation/indexfront.html.twig', [
            'participations' => $participations ,
        ]);
    }
    
    #[Route('/new', name: 'app_participation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('participation/new.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }
    
    #[Route('/newfront', name: 'app_participation_new_front', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participation = new Participation();
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_participation_index_front', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('participation/newfront.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_participation_show', methods: ['GET'])]
    public function show(Participation $participation): Response
    {
        return $this->render('participation/show.html.twig', [
            'participation' => $participation,
        ]);
    }
    
   
    #[Route('front/{id}', name: 'app_participation_show_front', methods: ['GET'])]
    public function showfront(Participation $participation): Response
    {
        $generator = new BarcodeGeneratorHTML();
        
        // Sanitize the barcode content by removing unsupported characters
        $nomParticipant = preg_replace('/[^A-Z0-9]/', '', strtoupper($participation->getNomParticipant()));
        $role = preg_replace('/[^A-Z0-9]/', '', strtoupper($participation->getRole()));
        $eventname = preg_replace('/[^A-Z0-9]/', '', strtoupper($participation->getEvent()));
       

        // Concatenate sanitized attributes to build the barcode content
        $barcodeContent = $nomParticipant . "-" . $eventname . "-";

        $barcode = $generator->getBarcode($barcodeContent, $generator::TYPE_CODE_39);
        return $this->render('participation/showfront.html.twig', [
            'participation' => $participation,
            'barcode' => $barcode,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/edit.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editfront', name: 'app_participation_edit_front', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participation_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participation/editfront.html.twig', [
            'participation' => $participation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_participation_delete', methods: ['POST'])]
    public function delete(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('front/{id}', name: 'app_participation_delete_front', methods: ['POST'])]
    public function deletefront(Request $request, Participation $participation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($participation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_participation_index_front', [], Response::HTTP_SEE_OTHER);
    }


}