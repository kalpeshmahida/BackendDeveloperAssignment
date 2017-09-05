<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AsteroidControllerTest extends WebTestCase
{
    public function testHazardous()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/hazardous');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $content);
    }

    public function testFastest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/fastest');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('date', $content);
        $this->assertArrayHasKey('reference', $content);
        $this->assertArrayHasKey('name', $content);
        $this->assertArrayHasKey('speed', $content);
        $this->assertArrayHasKey('is_hazardous', $content);
    }

    public function testBestYear()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/best-year');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('year', $content);
        $this->assertArrayHasKey('neocount', $content);
    }

    public function testBestMonth()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/best-month');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('month', $content);
        $this->assertArrayHasKey('neocount', $content);
    }
}
