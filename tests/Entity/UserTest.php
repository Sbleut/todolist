<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;
    public function setUp(): void
    {
        $this->user = new User();
    }

    public function testEmail(): void
    {
        $this->user->setEmail('test@gmail.com');
        $this->assertSame('test@gmail.com', $this->user->getEmail());
    }

    public function testUsername(): void
    {
        $this->user->setUsername('André');
        $this->assertSame('André', $this->user->getUsername());
    }

    public function testGetTasks(): void
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testTask()
    {
        $user = $this->user;
        $task = new Task();
        $user->addTask($task);
        $this->assertTrue($user->getTasks()->contains($task));
        $user->removeTask($task);
        $this->assertFalse($user->getTasks()->contains($task));
    }
}
