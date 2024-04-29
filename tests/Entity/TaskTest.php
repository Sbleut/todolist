<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private Task $task;
    public function setUp(): void
    {
        $this->task = new Task();
    }

    /**
     * Test task title
     *
     * @return void
     */
    public function testTitle(): void
    {
        $this->task->setTitle('TestTask');
        $this->assertSame('TestTask', $this->task->getTitle());
    }

    /**
     * Test task content
     *
     * @return void
     */
    public function testContent(): void
    {
        $this->task->setContent('TestTask');
        $this->assertSame('TestTask', $this->task->getContent());
    }

    /**
     * Test task CreatedAt
     *
     * @return void
     */
    public function testCreatedAt(): void
    {
        $date = new DateTimeImmutable();
        $this->task->setCreatedAt($date);
        $this->assertSame($date, $this->task->getCreatedAt());
    }
    /**
     * Content Not Blank
     * Content Length
     */

    /**
     * isDone s 
     * 
     */

    /**
     * User not nullable
     */

    
}
