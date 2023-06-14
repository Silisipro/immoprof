<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

  #[Route("/profil",name:"admin_index", methods:['GET']) ]
   public function index(UserRepository $userRepository ) : Response
   {
    return $this->render('admin/user/index.html.twig',[
        'Users'=>$userRepository->findBy(
            [
                'deleted' => false,
                'bloque' => false,
            ],
            [
                'createdAt' => 'DESC'
            ]
        ),
            
    ]);
   } 


    /**
    * This controller edit user information
    *@param  User $choosenUser
    * @param UserPasswordHasherInterface $hasher
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */
    #[Security("is_granted('ROLE_USER') and user===choosenUser")]
    #[Route('/utilisateur/edition/{id}', name: 'app_user_edit', methods:['GET','POST']) ]
    public function edit(User $choosenUser,Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher ): Response
    {
       /**  ceci peut remplacer security
        *  if (!$this->getUser()){
         *return $this->redirectToRoute('app_security');
       * }

       * if ($this->getUser() !== $user)
       *{
        *    return $this->redirectToRoute('app_recipe');
        *}
        */

        $form = $this->createForm(UserType::class, $choosenUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                 
            if ($hasher->isPasswordValid($choosenUser , $form->getData()->getPlainPassword())){
                $user = $form ->getData();

                
            
                $this->addFlash(
                    'success',
                    'Les information de votre compte ont été modifiées avec succès'
                );

            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe incorret'
                );
            }      
        }
        return $this->render('admin/user/edit.html.twig', [
            'form' =>$form->createView(),
        ]);
    }

    /**
    * This controller edit password user
    *@param  User $choosenUser
    * @param UserPasswordHasherInterface $hasher
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */
    #[Security("is_granted('ROLE_USER') and user===choosenUser")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'app_user_edit_password', methods:['GET','POST']) ]
    public function editPassword(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher) : Response
    {

        $form = $this->createForm(UserPasswordType::class, $choosenUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                 
            if ($hasher->isPasswordValid($choosenUser, $form->getData()['PlainPassword'])){
                $choosenUser->setUpdatedAt( new \DateTimeImmutable());
                $choosenUser->setPlainPassword(
                    $form ->getData()['newPassword']
                );
            
                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié'
                );

                $manager->persist($choosenUser);
                $manager->flush();

                return $this->redirectToRoute('admin_index');

            }

           
        } 

        return $this->render('admin/user/edit_password.html.twig', [
            'form' =>$form->createView()
        ]);

   }

   #[Route('/utilisateur/liste', name: 'app_user_index', methods: ['GET'])]
   #[IsGranted('ROLE_ADMIN')]
    public function listuser (UserRepository $userRepository): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'users' => $userRepository->findBy(
                [
                    'deleted' => false,
                    'bloque' => false,
                ],
                [
                    'createdAt' => 'DESC'
                ]
            )
               
        ]);
    }
    #[Route('/bloque', name: 'app_user_index_bloque', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function userBloque(UserRepository $userRepository): Response
    {
        return $this->render('/admin/user/bloque.html.twig', [
            'users' => $userRepository->findBy(
                [
                    'deleted' => false,
                    'bloque' => true,
                ],
                [
                    'createdAt' => 'DESC'
                ]
            ),
        ]);
    }


    #[Route('/{id}/bloquer', name: 'app_user_bloquer', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function bloquer(User $user, UserRepository $userRepository): Response
    {
        $user->setBloque(true);
        $userRepository->save($user, true);
        return $this->redirectToRoute('app_user_index_bloque', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/{id}/debloquer', name: 'app_user_debloquer', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function debloquer(User $user, UserRepository $userRepository): Response
    {
        $user->setDeleted(false);
        $user->setBloque(false);
        $userRepository->save($user, true);
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/supprimer', name: 'app_user_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $user->setDeleted(true);
            $userRepository->remove($user, true);
        }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/supprime', name: 'app_user_index_supprime', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function userSupprime(UserRepository $userRepository): Response
        {
            return $this->render('admin/user/suprime.html.twig', [
                'users' => $userRepository->findBy(
                    [
                        'deleted' => true,
                    ],
                    [
                        'createdAt' => 'DESC'
                    ]
                ),
            ]);
        }



}
    

