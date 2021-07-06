<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskEntityTest extends KernelTestCase
{

    use FixturesTrait;

    public function testValidEntity()
    {
        self::bootKernel();
        $userRepository = self::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);
        $task = (new Task())
            ->setTitle('Tâche de test')
            ->setContent('Ouais ouais je fais des tests')
            ->setCreatedAt(new \DateTime())
            ->setIsDone(false)
            ->setUser($user);
        $errors = self::$container->get('validator')->validate($task);
        $this->assertCount(0, $errors);
    }

    public function testGetters() {
        self::bootKernel();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);
        $taskRepository = self::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['id' => 1]);

        $this->assertIsNumeric($task->getId());
        $this->assertIsObject($task->getCreatedAt());
        $this->assertIsString($task->getTitle());
        $this->assertIsString($task->getContent());
        $this->assertIsBool($task->IsDone());
        $this->assertIsObject($task->getUser());
    }

    public function testToggle() {
        self::bootKernel();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);
        $taskRepository = self::$container->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(['id' => 1]);
        $this->assertEquals(1, $task->IsDone());
        $task->toggle(false);
        $this->assertEquals(0, $task->IsDone());
    }
}