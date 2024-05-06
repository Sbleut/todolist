<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListOfUndoneTasks(): void
    {
        $client = static::createClient();
        // Loggin as Toto User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/undone');
        $this->assertSame('A faire', $crawler->filter('button.btn.btn-success')->text());
        $btnCrawler = $crawler->selectButton('A faire');
        $form = $btnCrawler->form();
        $client->submit($form);
        $client->followRedirect();
        $this->assertEquals('/tasks/done', $client->getRequest()->getRequestUri());
    }

    public function testListOfdoneTasks(): void
    {
        $client = static::createClient();
        // Loggin as Toto User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/done');
        $this->assertSame('Terminée', $crawler->filter('button.btn.btn-warning')->text());
        $btnCrawler = $crawler->selectButton('Terminée');
        $form = $btnCrawler->form();
        $client->submit($form);
        $client->followRedirect();
        $this->assertEquals('/tasks/undone', $client->getRequest()->getRequestUri());
    }


    public function testCreateTasks(): void
    {
        $client = static::createClient();
        // Loggin as Toto User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful('Response status 200');

        $this->assertEquals(1, $crawler->filter('label[for="task_title"]')->count());
        $this->assertEquals(1, $crawler->filter('input[name="task[title]"]')->count());
        $this->assertEquals(1, $crawler->filter('label[for="task_content"]')->count());
        $this->assertEquals(1, $crawler->filter('textarea[name="task[content]"]')->count());
        $this->assertEquals(1, $crawler->filter('input[name="task[_token]"]')->count());
        $btnCrawler = $crawler->selectButton('Ajouter');
        $form = $btnCrawler->form();
        $client->submit($form, [
            'task[title]' => 'TestTask 101',
            'task[content]' => 'Thsi is the best task ever.',
        ]);
        $client->followRedirect();
        $this->assertResponseIsSuccessful('Response status 200');
        $this->assertEquals('/tasks/undone', $client->getRequest()->getRequestUri());
    }

    public function testEditTasks(): void
    {
        $client = static::createClient();
        // Loggin as Toto User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/222/edit');
        $btnCrawler = $crawler->selectButton('Modifier');
        $form = $btnCrawler->form();
        $client->submit($form, [
            'task[title]' => 'TestTask 101',
            'task[content]' => 'This is the edit task test.',
        ]);
        $client->followRedirect();
        $this->assertEquals('/tasks/undone', $client->getRequest()->getRequestUri());
    }

    public function testDeleteTaskAction(): void
    {
        $client = static::createClient();
        // Loggin as Toto User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/tasks/258/delete');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertEquals('/tasks/undone', $client->getRequest()->getRequestUri());
    }


}
