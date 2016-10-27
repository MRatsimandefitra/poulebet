<?php

namespace Back\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassementControllerTest extends WebTestCase
{
    public function testIndexclassement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testAddclassement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add-classement');
    }

    public function testEditclassement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit-classement/{id}');
    }

    public function testRemoveclassement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/remove-classement/{id}');
    }

}
