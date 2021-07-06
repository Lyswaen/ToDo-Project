<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @uses \App\Controller\HomeController
 */
class HomeControllerTest extends WebTestCase
{

    /**
     * @covers \App\Controller\HomeController::index()
     */
    public function testIndexPage() {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @covers \App\Controller\HomeController::index()
     */
    public function testH1IndexPage() {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertSelectorTextContains('h1', 'MA TO-DO LIST PERSONNELLE');
    }
}
