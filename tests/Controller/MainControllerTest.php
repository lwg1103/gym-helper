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
        
        $link = $crawler->filter('a:contains("customize trainings")')->eq(0)->link();
        
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains("/training/", $client->getHistory()->current()->getUri());
    }
    
    public function testNavigateToTrainingMode()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        
        $link = $crawler->filter('a:contains("training mode")')->eq(0)->link();
        
        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode()); 
        $this->assertContains("/training-mode/", $client->getHistory()->current()->getUri());
    }
}
