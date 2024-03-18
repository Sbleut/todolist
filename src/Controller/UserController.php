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
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    /**
    * Lists all users.
    *
    * This function retrieves a list of all users and renders them in a template for display.
    * Before displaying the list, it checks whether the current user has permission to view users.
    * If the current user does not have permission, an error flash message is added and the user is redirected to the home page.
    * If the current user has permission, the list of users is retrieved from the UserRepository and passed to the template for rendering.
    *
    * @param UserRepository $userRepository The repository for user entities.
    * @param Security $security The security component for checking user permissions.
    * @return Response A response containing the rendered list of users.
    */
    public function listUser( UserRepository $userRepository, Security $security, TranslatorInterface $translator): Response
    {
        
        if (!$this->isGranted('USER_VIEW', $security->getUser())){
            $this->addFlash('error', $translator->trans('User.View.Error', [], 'messages'));
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
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

            $this->addFlash('success', $translator->trans('User.Create.Success', [], 'messages'));
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
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager, Security $security, TranslatorInterface $translator): Response
    {
        if (!$this->isGranted('USER_EDIT', $security->getUser())){
            $this->addFlash('error', $translator->trans('User.Edit.Error', [], 'messages'));
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserType::class, $user)
            ->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($form->get('roles')->getData());

            $entityManager->flush();

            $this->addFlash('success',  $translator->trans('User.Edit.Success', [], 'messages'));

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

}
