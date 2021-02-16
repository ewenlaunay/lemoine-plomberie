<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\ContactType;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(\App\Form\ContactType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();
            
            $message = (new \Swift_Message('Nouveau contact'))
                    ->setFrom($contact['email'])
                    ->setTo('votre@adresse.com')
                    ->setBody(
                            $this->renderView(
                                    'emails/contact.html.twig', compact('contact')
                                    ),
                            'text/html'
                            )
                    ;
                    $mailer->send($message);
                    
                    $this->addFlash('message', 'Le message a bien été envoyé');
                    return $this->redirectToRoute('default');
            }
        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
