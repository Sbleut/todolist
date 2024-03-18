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
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function listUser( UserRepository $userRepository, Security $security): Response
    {
        
        if (!$this->isGranted('USER_VIEW', $security->getUser())){
            $this->addFlash('error', sprintf('Vous ne pouvez pas accéder à la liste des utilisateurs.'));
            return $this->redirectToRoute('app_home');
        }
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

            //Toto : 9g7DyjDEv3
            //Anonyme : Sans
            //Admin1 : d4W2Q$PR#2sH$D7v

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('app_home');
        }


        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}/edit', name: 'user_edit')]
    /**
     * Undocumented function
     *
     * @param User $user
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        if (!$this->isGranted('USER_VIEW', $security->getUser())){
            $this->addFlash('error', sprintf('Vous ne pouvez pas accéder à la liste des utilisateurs.'));
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createFormBuilder($user)
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'label' => "Choisir le role de l'utilisateur",
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                    'Utilisateur' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'data' => ['ROLE_USER']
            ])
            ->add('username')
            ->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ))
            ->get('email')
            ->get('username')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
            $user->setRoles($form->get('roles')->getData());

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
