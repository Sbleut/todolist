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
    
    /**
     * Displays a list of tasks.
     *
     * This function retrieves a list of all tasks from the database and renders the task list view with the obtained data.
     *
     * @param TaskRepository $taskRepository The task repository to fetch tasks from the database.
     * @return Response A response containing the rendered task list view.
     */
    #[Route('/tasks', name: 'task_list')]
    public function index( TaskRepository $taskRepository): Response
    {
        $taskList =  $taskRepository->findAll();
        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $taskList,
        ]);
    }

    /**
     * Creates a new task.
     *
     * This function creates a new task based on the form data submitted.
     * If the user does not have the necessary rights to create a task, they are    redirected to the task list with an error message.
     * After creating the task, the user is redirected to the task list with a success  message.
     *
     * @param Request $request The HTTP request.
     * @param EntityManagerInterface $entityManager The entity manager to perform create    operations.
     * @param Security $security The security component to access user information.
     * @return Response A response containing the form to create a task or a redirect   response to the task list.
     */
    #[Route('/tasks/create', name: "task_create")]
    public function createAction(Request $request, EntityManagerInterface $entityManager, Security $security)
    {
        $task = new Task();
        if (!$this->isGranted('TASK_CREATE', $task)) {
            $this->addFlash('error', 'Task.Create.Error');
            return $this->redirectToRoute('task_list');
        }
        $form = $this->createForm(FormTaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new DateTimeImmutable());
            $task->setIsDone(false);
            $task->setAuthor($security->getUser());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Task.Create.Success');

            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edits a task.
     *
     * This function allows editing of a specified task based on its identifier.
     * If the user does not have the necessary rights to edit the task, they are    redirected to the task list with an error message.
     * After editing the task, the user is redirected to the task list with a success   message.
     *
     * @param Task $task The task to edit.
     * @param Request $request The HTTP request.
     * @param EntityManagerInterface $entityManager The entity manager to perform edit  operations.
     * @return Response A response containing the form to edit the task or a redirect   response to the task list.
     */
    #[Route('/tasks/{id}/edit', name: "task_edit")]
    public function editAction(Task $task, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$this->isGranted('TASK_EDIT', $task)) {
            $this->addFlash('error', sprintf('Task.Edit.Error', $task->getTitle()));
            return $this->redirectToRoute('task_list');
        }
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Task.Edit.Success');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
    
    /**
     * Toggles the status of a task.
     *
     * This function toggles the status (done or not done) of a specified task based on     its identifier.
     * After toggling the status, the user is redirected to the task list with a success    message.
     *
     * @param Task $task The task to toggle.
     * @param EntityManagerInterface $entityManager The entity manager to perform toggle    operations.
     * @return RedirectResponse A redirect response to the task list.
     */
    #[Route('/tasks/{id}/toggle', name: "task_toggle")]
    public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager)
    {
        $task->setIsDone(!$task->isIsDone());
        
        $entityManager->persist($task);
        $entityManager->flush();
        if ($task->isIsDone()) {
            $this->addFlash('success', sprintf('Task.isDone' ));
        } else {
            $this->addFlash('success', sprintf('Task.isUndone.Success' ));
        }
        

        return $this->redirectToRoute('task_list');
    }

    /**
     * Deletes a task.
     *
     * This function deletes a specified task based on its identifier.
     * If the user does not have the necessary rights to delete the task, they are  redirected to the task list with an error message.
     * After the task is deleted, the user is redirected to the task list with a success    message.
     *
     * @param Task $task The task to delete.
     * @param EntityManagerInterface $entityManager The entity manager to perform delete    operations.
     * @return RedirectResponse A redirect response to the task list.
     */
    #[Route('/tasks/{id}/delete', name: "task_delete")]
    public function deleteTaskAction(Task $task, EntityManagerInterface $entityManager)
    {
        if (!$this->isGranted('TASK_DELETE', $task)) {
            
            $this->addFlash('error', sprintf('La tâche %s n\'a pas été supprimée.', $task->getTitle()));
            return $this->redirectToRoute('task_list');
        }
        
        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été supprimée.', $task->getTitle()));
        return $this->redirectToRoute('task_list');
    }

}
