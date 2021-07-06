<?php


namespace App\Tests\Controller;


use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @uses   \App\Controller\TaskController
 * @uses   \App\Repository\TaskRepository
 * @uses   \App\Entity\Task
 * @uses   \App\Entity\User
 * @uses   \App\Form\TaskType
 * @uses   \App\Repository\UserRepository
 * @uses   \App\Security\UserAuthenticator
 *
 * @uses \App\Controller\TaskController
 * @covers \App\Controller\TaskController
 */
class TaskControllerTest extends WebTestCase
{

    use FixturesTrait;

    /**
     * @covers \App\Controller\TaskController::index()
     */
    public function testIndexPage() {
        $client = static::createClient();
        $client->request('GET', '/task/');
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @covers \App\Controller\TaskController::index()
     */
    public function testH1IndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/task/');
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES À FAIRE');
    }

    /**
     * @covers \App\Controller\TaskController::index()
     */
    public function testTaskAreNotFinishedOnIndexPage() {
        $client = static::createClient();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/TaskFixtures.yaml'
        ]);

        $crawler = $client->request('GET', '/task/');
        $this->assertEquals(3, $crawler->filter('.card')->count());
    }

    /**
     * @covers \App\Controller\TaskController::finishedTask()
     */
    public function testFinishedTaskPage()
    {
        $client = static::createClient();
        $client->request('GET', '/task/done');
        $this->assertResponseStatusCodeSame(200);
    }
    /**
     * @covers \App\Controller\TaskController::finishedTask()
     */
    public function testH1FinishedTaskPage()
    {
        $client = static::createClient();
        $client->request('GET', '/task/done');
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES TERMINÉES');
    }

    /**
     * @covers \App\Controller\TaskController::finishedTask()
     */
    public function testTaskAreFinishedOnFinishedTaskPage()
    {
        $client = static::createClient();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/TaskFixtures.yaml'
        ]);

        $crawler = $client->request('GET', '/task/done');
        $this->assertEquals(3, $crawler->filter('.card')->count());
    }

    /**
     * @covers \App\Controller\TaskController::create()
     */
    public function testUnauthorizedCreatePage()
    {
        $client = static::createClient();
        $client->request('GET', '/task/create');
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'ME CONNECTER');
    }


    /**
     * @covers \App\Controller\TaskController::create()
     */
    public function testAuthorizedCreatePage()
    {
        $client = static::createClient();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/task/create');

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @covers \App\Controller\TaskController::create()
     */
    public function testCreationForm()
    {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);

        $userRepository = static::$container->get(UserRepository::class);
        $taskRepository = static::$container->get(TaskRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/task/create');
        $form = $crawler->selectButton('Ajouter')->form([
           'task[title]' => 'Tâche de test',
           'task[content]' => "Je suis le contenu de cette tâche de test"
        ]);
        $client->submit($form);
        $testTask = $taskRepository->findOneBy(['title' => 'Tâche de test']);
        $this->assertNotNull($testTask);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES À FAIRE');
    }

    /**
     * @covers \App\Controller\TaskController::edit()
     */
    public function testUnauthorizedEditPage()
    {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);
        $taskRepository = static::$container->get(TaskRepository::class);
        $testTask = $taskRepository->findOneBy(['title' => 'Titre à modifier']);

        $client->request('GET', "/task/edit/{$testTask->getId()}");
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'ME CONNECTER');
    }

    /**
     * @covers \App\Controller\TaskController::edit()
     */
    public function testAuthorizedEditPage()
    {
        $client = static::createClient();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml',
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);
        $taskRepository = static::$container->get(TaskRepository::class);
        $testTask = $taskRepository->findOneBy(['title' => 'Titre à modifier']);

        $client->loginUser($testUser);
        $client->request('GET', "/task/edit/{$testTask->getId()}");

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @covers \App\Controller\TaskController::edit()
     */
    public function testEditionForm() {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml',
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);
        $userRepository = static::$container->get(UserRepository::class);
        $taskRepository = static::$container->get(TaskRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $taskToEdit = $taskRepository->findOneBy(['title' => 'Titre à modifier']);
        $crawler = $client->request('GET', "/task/edit/{$taskToEdit->getId()}");
        $form = $crawler->selectButton('Éditer')->form([
            'task[title]' => 'Titre définitif',
            'task[content]' => 'Contenue final'
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES À FAIRE');
    }

    /**
     * @covers \App\Controller\TaskController::toggle()
     */
    public function testToggle() {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml',
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);
        $userRepository = static::$container->get(UserRepository::class);
        $taskRepository = static::$container->get(TaskRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $taskToEdit = $taskRepository->findOneBy(['title' => 'Titre à modifier']);
        $this->assertEquals(1, $taskToEdit->IsDone());

        $client->request('GET', "/task/toggle/{$taskToEdit->getId()}");
        $this->assertEquals(0, $taskToEdit->IsDone());


        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'LISTE DES TÂCHES À FAIRE');
    }

    /**
     * @covers \App\Controller\TaskController::delete()
     */
    public function testDelete() {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml',
            dirname(__DIR__) . '/TestFixtures/TaskCRUDFixtures.yaml'
        ]);
        $userRepository = static::$container->get(UserRepository::class);
        $taskRepository = static::$container->get(TaskRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $taskToEdit = $taskRepository->findOneBy(['title' => 'Titre à modifier']);

        $this->assertNotNull($taskToEdit);
        $client->request('GET', "/task/delete/{$taskToEdit->getId()}");
        $taskToEdit = $taskRepository->findOneBy(['title' => 'Titre à modifier']);
        $this->assertNull($taskToEdit);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1','LISTE DES TÂCHES À FAIRE');
    }
}