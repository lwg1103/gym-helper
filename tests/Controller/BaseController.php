<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

class BaseController extends WebTestCase
{
    /**
     * @var KernelBrowser 
     */
    protected $client;
    /**
     * @var Crawler
     */
    protected $crawler;
    
    protected function pageReturnsCode200()
    {
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    
    protected function followRedirect()
    {
        $this->crawler = $this->client->followRedirect();
    }

    protected function onTrainingIndex()
    {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', '/training/');
    }
    
    protected function clickFirstLinkWithClass($class)
    {
        $this->client->click(
            $this->crawler->filter($class)->eq(0)->link()
        );
    }
    
}
