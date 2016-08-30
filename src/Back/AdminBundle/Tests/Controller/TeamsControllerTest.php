<?php

namespace Back\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TeamsControllerTest extends WebTestCase
{
    public function testListteams()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list-teams');
    }

    public function testExportteams()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/export/all-teams');
    }

}
