<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact', methods:['GET','POST'])]
    public function index(Request $request,
     EntityManagerInterface $manager): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
              $contact = $form ->getData();

                $manager->persist($contact);
                $manager->flush();

            $this->addFlash(
                'success',
                'Votre demande a été envoyé avec succès !'
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
   
    #[Route('/contact/supression/{id}', name: 'app_contact_delete', methods:['GET','POST'])]
    public function delete(Contact  $contact, Request $request, EntityManagerInterface $manager ) : Response
    {    
        $manager->remove($contact);
        $manager->flush();
        $this->addFlash(
            'success',
            'Contact suprimé avec succès'
        );
        return $this->redirectToRoute('app_contact_index');   
    }  


}

    

