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
        $this->pageReturnsCodeN(200);
    }

    protected function pageReturnsNotFoundCode()
    {
        $this->pageReturnsCodeN(404);
    }

    protected function pageReturnsCodeN($n)
    {
        $this->assertEquals($n, $this->client->getResponse()->getStatusCode());
    }

    protected function followRedirect()
    {
        $this->crawler = $this->client->followRedirect();
    }

    protected function onTrainingIndex()
    {
        $this->getPageWithUrl('/training/');
    }

    protected function getPageWithUrl($url)
    {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', $url);
    }

    protected function onTrainingModeIndex()
    {
        if (!$this->client)
        {
            $this->client = static::createClient();
        }

        $this->crawler = $this->client->request('GET', '/training-mode/');
    }

    protected function clickFirstLinkWithClass($class)
    {
        $this->crawler = $this->client->click(
                $this->crawler->filter($class)->eq(0)->link()
        );
    }
    
    protected function clickLink($link)
    {
        $this->crawler = $this->client->click($link);
    }
    
    protected function assertCountElementsByClass($expectedNumber, $class)
    {
        $this->assertEquals($expectedNumber, $this->crawler->filter($class)
                        ->count());
    }

}
