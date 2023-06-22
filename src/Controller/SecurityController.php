<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'app_security_login', methods:['GET', 'POST'])]
    public function logiin( AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'Last_username' => $authenticationUtils->getLastUsername(),
             'error' =>$authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/inscription', name: 'app_security_registration', methods:['GET','POST'])] 
    public function inscription(Request $request, UserRepository $userRepository,
     EntityManagerInterface $manager) : Response
    {
        $user = new User();
        
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);     
            if ($form->isSubmitted()) { 
                $data = [
                    'role' => $form->get('role')->getData()];

                    foreach ($data as $r)
                $user = $form->getData();
                $user->setRoles([$r]); 
              
                 
                $userRepository->save($user, true);

                $manager->flush();
                
                    $this->addFlash(
                        'success',
                        ' Votre inscription a été effectuée avec succès'
                    );
                    return $this->redirectToRoute('app_security_login');   
                };
                
        return $this->render('pages/security/inscription.html.twig',[
            'form'=>$form->createView()          
        ]);
    }
   
    #[Route("/deconnexion", name:"app_security_logout")]
    public function logout(): void
    {
        //rien
    }




}




