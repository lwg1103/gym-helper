<?php

namespace App\Tests\Controller;

class TrainingModeControllerTest extends BaseController
{

    public function testSeeTrainingPage()
    {
        $this->onTrainingModeIndex();
        $this->pageReturnsCode200();
    }

    public function testSeeTrainigsList()
    {
        $this->onTrainingModeIndex();
        $this->seeAllTrainingsOnList();
    }

    public function testSeeTrainigDetails()
    {
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->seeExcerciseList();
    }

    private function seeAllTrainingsOnList()
    {
        $this->assertEquals(3, $this->crawler->filter("h2.gh-training-name")->count());
        $this->assertEquals("Day 1", $this->crawler->filter("h2.gh-training-name")->eq(0)->html());
        $this->assertEquals("Day 2", $this->crawler->filter("h2.gh-training-name")->eq(1)->html());
        $this->assertEquals("Day 3", $this->crawler->filter("h2.gh-training-name")->eq(2)->html());
    }

    private function enterFirstTraining()
    {
        $this->client->click(
                $this->crawler->filter(".gh-start-trainingbutton")->eq(0)->link()
        );
    }
    
    private function seeExcerciseList()
    {
        $this->assertEquals("Day 1", $this->crawler->filter("h2.gh-training-name")->eq(0)->html());
        $this->assertEquals("exc1", $this->crawler->filter(".gh-excercise-name")->eq(0)->html());
        $this->assertEquals("exc2", $this->crawler->filter(".gh-excercise-name")->eq(1)->html());
    }

}
