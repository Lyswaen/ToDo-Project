<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\UserController
 */
class UserControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/user/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'LISTE DES UTILISATEURS');
    }

    public function testCreate()
    {
        $client = static::createClient();
        $client->request('GET', '/user/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'S\'INSCRIRE');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'vendalex01@gmail.com']);

        $client->loginUser($testUser);
        $client->request('GET', '/user/edit/{id}', ['id' => $testUser->getId()]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'ÉDITER <strong>'. $testUser->getUsername(). '</strong>');
    }
}
