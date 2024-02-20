<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function indexFront(): Response
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
    #[Route('/frontabout', name: 'app_front_about')]
    public function indexFrontabout(): Response
    {
        return $this->render('about.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
    #[Route('/frontcontact', name: 'app_front_contact')]
    public function indexFrontcontact(): Response
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

    #[Route('/back', name: 'app_back')]
    public function indexBack(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
}
