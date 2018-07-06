<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 17.08.2017
 * Time: 20:40
 */

class IndexTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient(array('environment' => 'test'));

        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}