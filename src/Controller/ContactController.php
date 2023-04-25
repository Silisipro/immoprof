<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager, MailerInterface $mailer): Response
    {
        $contact = new Contact();

       //if($this->getUser()){
      //  $contact->setFullName($this->getUser()->getFullName())
      //  ->setEmail($this->getUser()->getEmail());
      // }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
              $contact = $form ->getData();

                $manager->persist($contact);
                $manager->flush();
                
                $email = (new TemplatedEmail())
                ->from($contact ->getEmail())
                ->to('adminsylove@immopro.com')
                ->subject($contact->getObjet())
                ->htmlTemplate('emails/contact.html.twig')
                
                ->context([
                   'contact' => $contact
                ]);

            $mailer->send($email);

            
                $this->addFlash(
                    'success',
                    ' Votre demande est envoyéé avec succès'
                );
                return $this->redirectToRoute('app_contact');   
            };
        

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
     
    #[Route('/contact/liste', name: 'app_contact_index', methods: ['GET'])]
    public function listuser (ContactRepository $contact): Response
    {
        return $this->render('pages/contact/show.html.twig', [
            'contacts' => $contact->findAll()
               
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/contact/supression/{id}', name: 'app_contact_delete', methods:['GET','POST'])]
    public function delete(Contact  $contact, Request $request, EntityManagerInterface $manager ) : Response
    { 
        # if($this->isCsrfTokenValid('delete' . $bien->getId(), $request->get('_token'))) {
        #    $manager->remove($bien);
        #    $manager->flush();
        # }
        $manager->remove($contact);
        $manager->flush();
        $this->addFlash(
            'success',
            'Contact suprimé avec succès'
        );


        return $this->redirectToRoute('app_contact_index');   
    }  


}

    

