<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListUser(): void
    {
        $client = static::createClient();
        // Loggin as Admin1 User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin1@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users');
        $this->assertSame('Liste des utilisateurs', $crawler->filter('h1')->text());
        $this->assertResponseIsSuccessful();
        
    }

    public function testCreateUser(): void
    {
        $client = static::createClient();
        // Loggin as Admin1 User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin1@gmail.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users/create');
        $btnCrawler = $crawler->selectButton('Enregistrer');
        $form = $btnCrawler->form();
        $client->submit($form, [
            'user[username]' => 'TestUser',
            'user[email]' => 'testuser@gmail.com',
            'user[password][first]' => 'Az0&EWdsEoBGxc*l',
            'user[password][second]' => 'Az0&EWdsEoBGxc*l',
            'user[roles]' => 'ROLE_USER'
        ]);
        $client->followRedirect();
        $this->assertEquals('/', $client->getRequest()->getRequestUri());
        //$this->assertSame('Superbe ! L\'utilisateur a bien été ajouté.', $crawler->filter('alert alert-success')->text());
        $this->assertResponseIsSuccessful();
    }

    public function testSwitchToAdmin(): void
    {
        $client = static::createClient();
        // Loggin as Admin1 User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin1@gmail.com');
        $client->loginUser($testUser);
        $testAsmin2 = $userRepository->findOneByEmail('toto@gmail.com');
        $testAdminUrl= '/user/'.$testAsmin2->getId() .'/role/admin';
        $crawler = $client->request('GET', $testAdminUrl);
        $client->followRedirect();
        $this->assertEquals('/users', $client->getRequest()->getRequestUri());
        $this->assertResponseIsSuccessful();
    }

    public function testSwitchToUser(): void
    {
        $client = static::createClient();
        // Loggin as Admin1 User
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('admin1@gmail.com');
        $client->loginUser($testUser);
        $testAsmin2 = $userRepository->findOneByEmail('admin2@gmail.com');
        $testAdminUrl= '/user/'.$testAsmin2->getId() .'/role/user';
        $crawler = $client->request('GET', $testAdminUrl);
        $client->followRedirect();
        $this->assertEquals('/users', $client->getRequest()->getRequestUri());
        $this->assertResponseIsSuccessful();
    }
}
