<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ConatctType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{


    #[Route('/contact', name: 'contact')]
    public function conatct(Request $request, MailerInterface $mailer): Response
    {
        $conatct = new ContactDTO();
        $form = $this->createForm(ConatctType::class, $conatct);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $email = (new TemplatedEmail())
                    ->from($conatct->email)
                    ->to($conatct->services)
                    ->subject('Message via formulaire de contact')
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context([
                        'name' => $conatct->name,
                        'mail' => $conatct->email,
                        'message' => $conatct->message
                    ]);
                $mailer->send($email);
                $this->addFlash('success', 'email envoyé');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible d\'envoyer votre email !');
            }
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
