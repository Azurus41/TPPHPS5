<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\SendMailService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, SendMailService $mailService, LoggerInterface $logger): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $mailService->send(
                    SendMailService::FROM,
                    SendMailService::ADMIN,
                    'Contact : '.$form->get('sujet')->getData(),
                    'contact',
                    [
                        'nom' => $form->get('nom')->getData(),
                        'expediteur' => $form->get('email')->getData(),
                        'sujet' => $form->get('sujet')->getData(),
                        'message' => $form->get('message')->getData(),
                    ]
                );
                $this->addFlash('success', 'Votre message a bien été envoyé.');
            } catch (TransportExceptionInterface $e) {
                $logger->error('Envoi du mail de contact impossible : '.$e->getMessage());
                $this->addFlash('danger', "Le message n'a pas pu être envoyé (serveur mail indisponible).");
            }

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', ['form' => $form]);
    }
}
