<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

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
    
    protected function asAUser()
    {
        $this->createClientOnce();
        
        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('user@ex.com', null, $firewallName, ['ROLE_USER']);
        //$token = new PostAuthenticationGuardToken('user@ex.com', $firewallName, ['ROLE_USER']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

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
        $this->createClientOnce();

        $this->crawler = $this->client->request('GET', $url);
    }

    protected function onTrainingModeIndex()
    {
        $this->getPageWithUrl('/training-mode/');
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
    
    protected function currentUrlIs($url)
    {
        $this->assertStringEndsWith($url, $this->client->getHistory()->current()->getUri());
    }
    
    private function createClientOnce()
    {
        if (!$this->client)
        {
            $this->client = static::createClient();
        }
    }

}
