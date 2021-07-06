<?php


namespace App\Tests\Controller;


use App\Controller\SecurityController;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @uses \App\Controller\SecurityController
 * @uses \App\Security\UserAuthenticator
 * @uses \App\Repository\UserRepository
 * @uses \App\Entity\User
 */
class SecurityControllerTest extends WebTestCase
{

    use FixturesTrait;


    public function testLoginPage() {
        $client = static::createClient();
        $client->request('GET', 'user/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @covers \App\Controller\SecurityController::login()
     */
    public function testH1LoginPage() {
        $client = static::createClient();
        $client->request('GET', 'user/login');
        $this->assertSelectorTextContains('h1', 'ME CONNECTER');
    }

    /**
     * @covers \App\Controller\SecurityController::login()
     */
    public function testLoginPageWithUserLogged() {
        $client = static::createClient();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/user/login');

        $this->assertResponseRedirects('/', 302);
    }

    /**
     * @covers \App\Controller\SecurityController::logout()
     */
    public function testLogoutPage() {
        $client = static::createClient();
        $this->loadFixtureFiles([
            dirname(__DIR__) . '/TestFixtures/UserFixtures.yaml'
        ]);
        $this->expectException('LogicException');
        $securityController = new SecurityController();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/user/logout');
        $securityController->logout();
        $this->assertResponseStatusCodeSame(302);
    }
}