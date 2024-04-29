<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $btnCrawler = $crawler->selectButton('Se connecter');
        $form = $btnCrawler->form();
        $client->submit($form, [
            'email' => 'toto@gmail.com',
            'password' => '9g7DyjDEv3',
        ]);
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
        // System pour rester connecté et faire les tests avec le même user et faire la suite des tests.
    }

    public function testLogout(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/logout');
        
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

    public function testLoginAsCurrentUser(): void
    {
        $client = static::createClient();
        // Loggin as Toto User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('toto@gmail.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/login');
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }
}
