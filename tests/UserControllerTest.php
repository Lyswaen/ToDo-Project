<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testIndex() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'ToDo & Co | Liste des utilisateurs');
    }
}
