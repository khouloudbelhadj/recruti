<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationFormType;
use App\Form\LoginType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
class TemplateController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function indexFront(): Response
    {
        return $this->render('baseFront.html.twig', [
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

    
    #[Route('/SignIn', name: 'SignIn')]
    public function signin(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

   
    #[Route('/SignUp', name: 'SignUp')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,UserRepository $userRepository, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {  $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $form2 = $this->createForm(LoginType::class, $user);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $email = $form2->get('email_user')->getData();
            $password = $form2->get('password')->getData();
    
            // Find user by email
            $userA = $userRepository->findOneBy(['email_user' => $email]);
    
            if (!$userA) {
                $form2->get('email_user')->addError(new FormError('Email incorrecte'));
            } else {
                // Check if password is correct
                if ($userPasswordHasher->isPasswordValid($userA, $password)) {
                    // Password is correct, log the user in
                    $token = new UsernamePasswordToken($userA, null, 'main', $userA->getRoles());
                    $this->get('security.token_storage')->setToken($token);
                    // Fire the login event
                    $userAuthenticator->authenticateUser($userA, $token);
                    
                    // Redirect to the desired page after login
                    return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);
                } else {
                    $form2->get('password')->addError(new FormError('Mot de passe incorrect'));
                }
            }
        }

         else
        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
             

            $entityManager->persist($user);
            $entityManager->flush();

            return new RedirectResponse($this->generateUrl('send_sms'));
            // do anything else you need here, like send an email


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );


        }

        return $this->render('/signin.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),

        ]);
    }
    }

