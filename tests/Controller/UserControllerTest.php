<?php


namespace App\Tests\Controller;


use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @uses \App\Controller\UserController
 * @uses \App\Entity\User
 * @uses \App\Form\UserType
 * @uses \App\Repository\UserRepository
 * @uses \App\Security\UserAuthenticator
 *
 * @covers \App\Controller\UserController
 */
class UserControllerTest extends WebTestCase
{

    use FixturesTrait;

    /**
     * @covers \App\Controller\UserController::index()
     */
    public function testIndex()
    {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/user/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'LISTE DES UTILISATEURS');
    }

    public function testCreatePage() {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $crawler = $client->request('GET', '/user/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'toto',
            'user[email]' => 'toto@gmail.com',
            'user[password][first]' => '1234',
            'user[password][second]' => '1234',
            'roles' => 'ROLE_USER'
        ]);
        $client->submit($form);
        $testUser = $userRepository->findOneBy(['email' => 'toto@gmail.com']);
        $this->assertNotNull($testUser);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testEditPage() {
        $client = static::createClient();

        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserCRUDFixtures.yaml'
        ]);

        $userRepository = static::$container->get(UserRepository::class);
        $userAdmin = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);
        $userToEdit = $userRepository->findOneBy(['email' => 'zigzaton@gmail.com']);

        $client->loginUser($userAdmin);
        $crawler = $client->request('GET', "/user/edit/{$userToEdit->getId()}");
        $form = $crawler->selectButton('Confirmer')->form([
            'user[username]' => 'Lyswaen',
            'user[email]' => 'lyswaen@gmail.com',
            'user[password][first]' => '6425',
            'user[password][second]' => '6425',
            '_roles' => 'ROLE_ADMIN'
        ]);
        $client->submit($form);
        $testUser = $userRepository->findOneBy(['email' => 'lyswaen@gmail.com']);
        $this->assertNotNull($testUser);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }
}