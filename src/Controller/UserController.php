<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
    * This controller edit user information
    *@param  User $choosenUser
    * @param UserPasswordHasherInterface $hasher
    *@param EntityManagerInterface $manager
    * @param Request $request
    * @return Response
    */

    #[Route('/utilisateur/edition/{id}', name: 'app_user', methods:['GET','POST']) ]
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
                $manager->persist($user);
                $manager->flush();
            
                $this->addFlash(
                    'success',
                    'Les information de votre compte ont été modifiées avec succès'
                );
                return $this->redirectToRoute('app.admin.bien');
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
    
    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'app_user_edit_password', methods:['GET','POST']) ]
    public function editPassword(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher) : Response
    {

        $form = $this->createForm(UserPasswordType::class, $choosenUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                 
            if ($hasher->isPasswordValid($choosenUser, $form->getData()['PlainPassword'])){
                $choosenUser->setUpdateAt( new \DateTimeImmutable());
                $choosenUser->setPlainPassword(
                    $form ->getData()['newPassword']
                );
            
                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié'
                );

                $manager->persist($choosenUser);
                $manager->flush();

                return $this->redirectToRoute('app.admin.bien');

            }

           
        } 

        return $this->render('admin/user/edit_password.html.twig', [
            'form' =>$form->createView()
        ]);

   }

   #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll()
               
        ]);
    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $role = [$form->get('role')->getData()];
            $user->setRoles($role);
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
   }
}
