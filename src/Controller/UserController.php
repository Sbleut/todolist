<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function listUser( UserRepository $userRepository): Response
    {
        $userList = $userRepository->findAll();
        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $userList,
        ]);
    }

    #[Route('/users/create', name: 'user_create')]
    /**
     * Register function allows to create a new user with unique identifier, a hashed password, a verified email.
     *
     * @param Request $request Stores data from form.
     * @param UserPasswordHasherInterface $userPasswordHasher Tool for hashing password.
     * @param EntityManagerInterface $entityManager Tool to push data to bdd.
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setRoles($form->get('roles')->getData());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            //Toto : g70Y*%ZH!bK5E3r1
            //Anonyme : Sans
            //user1 : d4W2Q$PR#2sH$D7v

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }


        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}/edit', name: 'user_edit')]
    /**
     * Register function allows to create a new user with unique identifier, a hashed password, a verified email.
     *
     * @param Request $request Stores data from form.
     * @param UserPasswordHasherInterface $userPasswordHasher Tool for hashing password.
     * @param EntityManagerInterface $entityManager Tool to push data to bdd.
     * @return Response
     */
    public function editUser(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

}
