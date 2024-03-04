<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType as FormTaskType;
use App\Repository\TaskRepository;
use AppBundle\Form\TaskType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function index( TaskRepository $taskRepository): Response
    {
        $taskList =  $taskRepository->findAll();
        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $taskList,
        ]);
    }

    #[Route('/tasks/create', name: "task_create")]
    public function createAction(Request $request, EntityManagerInterface $entityManager, Security $security)
    {
        $task = new Task();
        $form = $this->createForm(FormTaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new DateTimeImmutable());
            $task->setIsDone(false);
            $task->setAuthor($security->getUser());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tasks/{id}/edit', name: "task_edit")]
    public function editAction(Task $task, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
    
    #[Route('/tasks/{id}/toggle', name: "task_toggle")]
    public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager)
    {
        $task->setIsDone(!$task->isIsDone());
        
        $entityManager->persist($task);
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: "task_delete")]
    public function deleteTaskAction(Task $task)
    {
        if (!$this->isGranted('TASK_DELETE', $task)) {
            
            $this->addFlash('success', sprintf('La tâche %s a bien été supprimée.', $task->getTitle()));
            return $this->redirectToRoute('task_list');
        }
        
        $this->addFlash('error', sprintf('La tâche %s n\'a pas été supprimée.', $task->getTitle()));
        return $this->redirectToRoute('task_list');
    }

}
