<?php

namespace Back\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PubliciteControllerTest extends WebTestCase
{
    public function testIndexpublicite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testAddpublicite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add-pub');
    }

    public function testEditpublicite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit-pub');
    }

    public function testRemovepublicite()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/remove-pub/{id}');
    }

}
