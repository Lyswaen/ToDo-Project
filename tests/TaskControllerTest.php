<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\TaskController
 */
class TaskControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES À FAIRE');
    }

    public function testDone()
    {
        $client = static::createClient();
        $client->request('GET', '/task/done');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES TERMINÉES');
    }

    public function testCreate()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/task/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'CRÉER UNE NOUVELLE TÂCHE');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $taskRepository = static::$container->get(TaskRepository::class);
        $testTask = $taskRepository->findOneBy(['id' => 1]);


        $client->loginUser($testUser);
        $client->request('GET', '/task/edit/{id}', ['id' => $testTask->getId()]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'ÉDITER LA TÂCHE - '. $testTask->getTitle());
    }
}
