<?php

namespace Api\DBBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AchatControllerTest extends WebTestCase
{
    public function testIndexachat()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testAddachat()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');
    }

    public function testEditachat()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit-achat/{id}');
    }

    public function testDeleteachat()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete/{id}');
    }

}
