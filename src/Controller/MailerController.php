<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Routing\Attribute\Route;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): JsonResponse
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>')
            ->addPart(new DataPart(new File('pdf/user_1.pdf')));

       try {
           $mailer->send($email);
           return new JsonResponse([
               'success' => true,
               'message' => 'Email sent!'
           ]);
       } catch (TransportExceptionInterface) {
           return new JsonResponse(['error' => 'TransportExceptionInterface'], Response::HTTP_INTERNAL_SERVER_ERROR);
       }


    }
}

