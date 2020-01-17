<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrainingControllerTest extends WebTestCase
{
    public function testSeeTrainingPage()
    {
        $client = static::createClient();

        $client->request('GET', '/training');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }   
    
    public function testSeeTrainigsList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/training');
        
        $this->assertSelectorTextContains('h1', 'trainings');
        $this->assertSelectorTextContains('h2', 'Monday');
        $this->assertSelectorTextContains('td', 'exc1');
        
    }
    
    public function testAddTraining()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/training');
        $this->assertEquals(1, $crawler->filter("h2")->count());
        
        $newTrainingName = "new training name";
        
        $client->clickLink("add training");
        $client->submitForm("Save",[
            'training[name]' => $newTrainingName
        ]);
        
        $crawler = $client->followRedirect();
        
        $this->assertEquals(2, $crawler->filter("h2")->count());
        $this->assertEquals($newTrainingName, $crawler->filter("h2")->eq(1)->html());
    }
}
