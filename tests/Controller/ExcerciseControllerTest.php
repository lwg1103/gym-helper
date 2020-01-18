<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

class ExcerciseControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser 
     */
    protected $client;
    /**
     * @var Crawler
     */
    protected $crawler;

    public function testAddExcercise()
    {
        $this->onTrainingIndex();
        $this->seeNExcercisesListed(2);
        $this->clickFirstLinkWithClass(".gh-add-excercise-button");
        $this->fillExcerciseForm("pull-ups");
        $this->seeNExcercisesListed(3);
        $this->excerciseNameOnPositionIs("pull-ups", 2);
    }

    private function onTrainingIndex()
    {
        $this->client = static::createClient();

        $this->crawler = $this->client->request('GET', '/training/');
    }

    private function fillExcerciseForm($trainingName)
    {
        $this->client->submitForm("Save", [
            'excercise[name]'      => $trainingName,
            'excercise[weight]'    => 45,
            'excercise[repeats]'   => 12,
            'excercise[series]'    => 3,
            'excercise[breakTime]' => 60,
            'excercise[min]'       => 12,
            'excercise[max]'       => 15,
        ]);

        $this->crawler = $this->client->followRedirect();
    }

    private function clickFirstLinkWithClass($class)
    {
        $this->client->click(
                $this->crawler->filter($class)->eq(0)->link()
        );
    }

    private function seeNExcercisesListed(int $n)
    {
        $this->assertEquals($n, $this->crawler->filter("td.gh-excercise-name")
                        ->count());
    }

    private function excerciseNameOnPositionIs(string $name, int $position)
    {
        $this->assertEquals($name, $this->crawler->filter("td.gh-excercise-name")
                        ->eq($position)->text());
    }

    private function followRedirect()
    {
        $this->crawler = $this->client->followRedirect();
    }

}
