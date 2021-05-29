<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler =$client->request('GET', '/task/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'ToDo & Co | Liste des tâches');
    }

    public function testCreate() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'ToDo & Co | Créer une tâche');
    }

    public function testEdit()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/task/edit/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'ToDo & Co | Modifier une tâche');
    }
}
