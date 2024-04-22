<?php

namespace App\Tests;

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

}
