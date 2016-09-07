<?php

namespace Back\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LotsControllerTest extends WebTestCase
{
    public function testListlot()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list');
    }

    public function testAddlot()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');
    }

    public function testEditlot()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit/{id}');
    }

    public function testRemovelot()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/remove/{id}');
    }

}
