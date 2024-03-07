<?php
namespace App\Controller;

use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    #[Route('/send-sms', name: 'send_sms')]
    public function sendSms(TwilioService $twilioService): Response
    {
       

        $twilioService->sendSMS();


        return $this->redirectToRoute('app_front', [], Response::HTTP_SEE_OTHER);

    }}