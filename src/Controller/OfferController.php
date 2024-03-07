<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/offer')]
class OfferController extends AbstractController
{
    #[Route('/', name: 'app_offer_index', methods: ['GET'])]
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('offer/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }
    

    #[Route('/front', name: 'app_offer_index_front', methods: ['GET'])]
    public function indexfront(OfferRepository $offerRepository,Request $req,PaginatorInterface $paginator): Response
    {
        $data=$offerRepository->findAll();

        $offers=$paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            3
        );

        return $this->render('offer/indexfront.html.twig', [
            'offers' => $offers,
        ]);
    }
    #[Route('/frontcondidat', name: 'app_offer_show_front_condi', methods: ['GET'])]
    public function showfrontcondi(OfferRepository $offerRepository,Request $req,PaginatorInterface $paginator): Response
    {   
        $data=$offerRepository->findAll();

        $offers=$paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            4
        );
        return $this->render('offer/showoffercondid.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/new', name: 'app_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($offer->getDescriptionO());
            $offer->setDescriptionO($rr);

            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    #[Route('/newfront', name: 'app_offer_new_front', methods: ['GET', 'POST'])]
    public function newfont(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($offer->getDescriptionO());
            $offer->setDescriptionO($rr);
            
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/newfront.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_show', methods: ['GET'])]
    public function show(Offer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }
    #[Route('/front/{id}', name: 'app_offer_show_front', methods: ['GET'])]
    public function showfront(Offer $offer): Response
    {
        return $this->render('offer/showfront.html.twig', [
            'offer' => $offer,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_offer_pdf', methods: ['GET'])]     
    public function AfficheTicketPDF(OfferRepository $repo, $id)
    {
        $pdfoptions = new Options();
        $pdfoptions->set('defaultFont', 'Arial');
        $pdfoptions->setIsRemoteEnabled(true);
        
        $dompdf = new Dompdf($pdfoptions);
        $offers = $repo->find($id);

        // Check if the ticket exists
        if (!$offers) {
            throw $this->createNotFoundException('Votre offre does not exist');
        }

        $html = $this->renderView('offer/pdfExport.html.twig', [
            'offer' => $offers
        ]);

        $html = '<div>' . $html . '</div>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'landscape');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="offerPDF.pdf"'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editfront', name: 'app_offer_edit_front', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offer/editfront.html.twig', [
            'offer' => $offer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offer_delete', methods: ['POST'])]
    public function delete(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offer_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('front/{id}', name: 'app_offer_delete_front', methods: ['POST'])]
    public function deletefront(Request $request, Offer $offer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_offer_index_front', [], Response::HTTP_SEE_OTHER);
    }

    public function filterwords($text)
    {
        $filterWords = array('fokaleya', 'bhim', 'msatek', 'fuck', 'shit', 'yezi');
        $filterCount = count($filterWords);
        $str = "";
        $data = preg_split('/\s+/',  $text);
        foreach($data as $s){
            $g = false;
            foreach ($filterWords as $lib) {
                if($s == $lib){
                    $t = "";
                    for($i =0; $i<strlen($s); $i++) $t .= "*";
                    $str .= $t . " ";
                    $g = true;
                    break;
                }
            }
            if(!$g)
            $str .= $s . " ";
        }
        return $str;
    }

    
    
}
    

