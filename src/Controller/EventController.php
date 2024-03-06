<?php

namespace App\Controller;

use Endroid\QrCode\Builder\Builder;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use App\Repository\ParticipationRepository;
use Symfony\Component\HttpFoundation\File\File;
use Knp\Component\Pager\PaginatorInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;





#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/generateExcel', name: 'excel')]
    public function generateeventExcel(EventRepository $eventRepository): BinaryFileResponse
    {
        $events = $eventRepository->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Define column names
        $columnNames = ['Title', 'Date', 'Time', 'Location', 'Description', 'Theme', 'Contact'];
    
        // Set the entire first row at once and make it bold
        $sheet->fromArray([$columnNames], null, 'A1');
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
    
        $row = 2; // Start from the second row
        foreach ($events as $event) {
            $data = [
                $event->getNomE(),
                $event->getDateE()->format('Y-m-d'),
                $event->getHeureE(),
                $event->getLieuE(),
                $event->getDescription(),
                $event->getThemeE(),
                $event->getCantactE(),
            ];
    
            // Set data starting from the second row
            $sheet->fromArray([$data], null, 'A' . $row);
    
            $row++;
        }
    
        // Apply bold style to the first row
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
    
        $writer = new Xlsx($spreadsheet);
    
        $fileName = 'events.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    
        $writer->save($tempFile);
    
        return new BinaryFileResponse($tempFile, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => sprintf('inline; filename="%s"', $fileName),
        ]);
    }

    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        // Calculer le nombre total d'événements
        $totalEvents = count($events);

        // Calculer le nombre d'événements par thème
        $themeCounts = $eventRepository->getThemeCounts();

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'totalEvents' => $totalEvents,
            'themeCounts' => $themeCounts,
        ]);
    }

    #[Route('/stats', name: 'app_event_stats', methods: ['GET'])]
    public function stats(EventRepository $eventRepository, ParticipationRepository $participationRepository): Response
    {
        $events = $eventRepository->findAll();
        $totalEvents = count($events);
        $themeCounts = $eventRepository->getThemeCounts();
        $participationByTheme = $eventRepository->getParticipationCountByTheme();
        $totalParticipations = $participationRepository->getTotalParticipations();

        return $this->render('event/stats.html.twig', [
            'totalEvents' => $totalEvents,
            'themeCounts' => $themeCounts,
            'events' => $events, // Ajout de la variable 'events'
            'participationByTheme' => $participationByTheme,
            'totalParticipations' => $totalParticipations,
        ]);
    }

    
    #[Route('/front', name: 'app_event_index_front', methods: ['GET'])]
public function indexfront(Request $request, EventRepository $eventRepository, PaginatorInterface $paginator): Response
{
    $nameSearch = $request->query->get('nameSearch');
    $themeSearch = $request->query->get('themeSearch');
    $dateSearch = $request->query->get('dateSearch');
    $locationSearch = $request->query->get('locationSearch');
    $sortOrder = $request->query->get('sortOrder', null);
    
    $queryBuilder = $eventRepository->createQueryBuilder('e');
    
    if ($nameSearch) {
        $queryBuilder->andWhere('e.nom_e LIKE :nameSearch')
            ->setParameter('nameSearch', '%' . $nameSearch . '%');
    }
    
    if ($themeSearch) {
        $queryBuilder->andWhere('e.theme_e LIKE :themeSearch')
            ->setParameter('themeSearch', '%' . $themeSearch . '%');
    }
    
    if ($dateSearch) {
        $queryBuilder->andWhere('e.date_e LIKE :dateSearch')
            ->setParameter('dateSearch', '%' . $dateSearch . '%');
    }
    
    if ($locationSearch) {
        $queryBuilder->andWhere('e.lieu_e LIKE :locationSearch')
            ->setParameter('locationSearch', '%' . $locationSearch . '%');
    }

    if ($sortOrder !== null) {
        $queryBuilder = $this->sortEventsQueryBuilder($queryBuilder, $sortOrder);
    }

    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(),
        $request->query->getInt('page', 1),
        9
    );

    $events = $pagination->getItems();
    
    return $this->render('event/indexfront.html.twig', [
        'pagination' => $pagination,
        'events' => $events,
        'sortOrder' => $sortOrder,
    ]);
}
    #[Route('/front/condidat', name: 'app_event_index_front_condidat', methods: ['GET'])]
    public function indexfrontcondidat(Request $request,EventRepository $eventRepository): Response
    {
        $nameSearch = $request->query->get('nameSearch');
        $themeSearch = $request->query->get('themeSearch');
        $dateSearch = $request->query->get('dateSearch');
        $locationSearch = $request->query->get('locationSearch');
        $sortOrder = $request->query->get('sortOrder', null);
        
        $queryBuilder = $eventRepository->createQueryBuilder('e');
        $events = $queryBuilder->getQuery()->getResult();

        if ($nameSearch) {
            $queryBuilder->andWhere('e.nom_e LIKE :nameSearch')
                ->setParameter('nameSearch', '%' . $nameSearch . '%');
        }

        if ($themeSearch) {
            $queryBuilder->andWhere('e.theme_e LIKE :themeSearch')
                ->setParameter('themeSearch', '%' . $themeSearch . '%');
        }

        if ($dateSearch) {
            $queryBuilder->andWhere('e.date_e LIKE :dateSearch')
                ->setParameter('dateSearch', '%' . $dateSearch . '%');
        }

        if ($locationSearch) {
            $queryBuilder->andWhere('e.lieu_e LIKE :locationSearch')
                ->setParameter('locationSearch', '%' . $locationSearch . '%');
        }

        $events = $queryBuilder->getQuery()->getResult();
        
        if ($sortOrder !== null) {
            $events = $this->sortEvents($events, $sortOrder);
        }

        return $this->render('event/indexfrontcondidat.html.twig', [
            'events' => $events,
            'sortOrder' => $sortOrder,
        ]);
    }

    private function sortEventsQueryBuilder($queryBuilder, $sortOrder)
    {
         $alias = $queryBuilder->getRootAliases()[0];

    // Add sorting conditions based on $sortOrder
    if ($sortOrder === 'asc') {
        $queryBuilder->orderBy($alias . '.date_e', 'ASC');
    } elseif ($sortOrder === 'desc') {
        $queryBuilder->orderBy($alias . '.date_e', 'DESC');
    } elseif ($sortOrder === 'participationCountAsc') {
        $queryBuilder->orderBy('SIZE(' . $alias . '.participations)', 'ASC');
    } elseif ($sortOrder === 'participationCountDesc') {
        $queryBuilder->orderBy('SIZE(' . $alias . '.participations)', 'DESC');
    }

        return $queryBuilder;;
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $filesystem = new Filesystem();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            $uploadedFile = $form->get('image_e')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgevent/'.$event->getNomE().strval($event->getId()).'.png';
            $event->setImageE($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/newfront', name: 'app_event_new_front', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $filesystem = new Filesystem();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            $uploadedFile = $form->get('image_e')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgevent/'.$event->getNomE().strval($event->getId()).'.png';
            $event->setImageE($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/newfront.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }
    

    #[Route('/front/{id}', name: 'app_event_show_front', methods: ['GET'])]
    public function showfront(eventRepository $eventRepository, Event $event, $id): Response
    {
        $writer = new PngWriter();

        $event = $eventRepository->find($id);
        // Concaténer tous les champs de l'entité event pour générer le contenu du code QR
        $eventData = $event->geteventDataForQrCode();
        $qrCode = new QrCode($eventData);

        $pngResult = $writer->write($qrCode);
        $qrCodeImage = base64_encode($pngResult->getString());

        return $this->render('event/showfront.html.twig', [
            'event' => $event,
            'participations' => $event->getParticipations(),
            'qrCodeImage' => $qrCodeImage,
        ]);
    }
    
    #[Route('frontcondidat/{id}', name: 'app_event_show_front_condidat', methods: ['GET'])]
    public function showfrontcondidat(Event $event): Response
    {
        return $this->render('event/showfrontcondidat.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $filesystem = new Filesystem();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('image_e')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgevent/'.$event->getNomE().strval($event->getId()).'.png';
            $event->setImageE($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editfront', name: 'app_event_edit_front', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $filesystem = new Filesystem();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('image_e')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgevent/'.$event->getNomE().strval($event->getId()).'.png';
            $event->setImageE($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/editfront.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('front/{id}', name: 'app_event_delete_front', methods: ['POST'])]
    public function deletefront(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index_front', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/load-event-content/{id}', name: 'load_event_content', methods: ['GET'])]
    public function loadeventContent(eventRepository $eventRepository, $id): Response
    {   
        $writer = new PngWriter();

        $event = $eventRepository->find($id);
        // Concaténer tous les champs de l'entité event pour générer le contenu du code QR
        $eventData = $event->geteventDataForQrCode();
        $qrCode = new QrCode($eventData);

        $pngResult = $writer->write($qrCode);
        $qrCodeImage = base64_encode($pngResult->getString());

        return $this->render('event/qr.html.twig', [  
            'event'        => $event,
            'qrCodeImage' => $qrCodeImage,
        ]);
    }

    

}
