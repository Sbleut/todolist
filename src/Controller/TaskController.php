<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function index(): Response
    {
        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    #[Route('/tasks/create', name: "task_create")]
    public function createAction(Request $request)
    {

        return $this->render('task/create.html.twig');
    }

    #[Route('/tasks/{id}/edit', name: "task_edit")]
    public function editAction(Task $task, Request $request)
    {

        return $this->render('task/edit.html.twig');
    }
    
    #[Route('/tasks/{id}/toggle', name: "task_toggle")]
    public function toggleTaskAction(Task $task)
    {

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: "task_delete")]
    public function deleteTaskAction(Task $task)
    {

        return $this->redirectToRoute('task_list');
    }

}
