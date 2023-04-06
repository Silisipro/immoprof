<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
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

     /**
     * @Route("/logout",name="logout")
     */
    #[Route("/deconnexion",name:"app_security_logout")]
    public function logout()
    {
        //rien
    } 
    /**
    * This controller allow registration
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */
    #[Route('/inscription', name: 'app_security_registration', methods:['GET','POST'])] 
    public function inscription(Request $request, EntityManagerInterface $manager) : Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
            $user = $form ->getData();

                    $manager->persist($user);
                    $manager->flush();
                
                    $this->addFlash(
                        'success',
                        ' Votre inscription  a été effectuée avec succès'
                    );
                    return $this->redirectToRoute('app_security_login');   
                };
        return $this->render('pages/security/inscription.html.twig',[
            'form'=>$form->createView()
            
        ]);
    }



}




