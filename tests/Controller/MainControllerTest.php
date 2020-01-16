<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testSeeMainPage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testNavigateToTrainingList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('a:contains("trainings")')->eq(0)->link();
        
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
