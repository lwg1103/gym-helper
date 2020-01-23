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
        $this->followRedirect();
        $this->seeExcerciseList();
    }

    public function testRemoveDoneExcercise()
    {
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
        $this->marFirstExcerciseAsDone();
        $this->followRedirect();
        $this->seeNExcercises(6 - 1);
    }

    public function testSeeContinueButtonIfTreningWasStarted()
    {
        $this->onTrainingModeIndex();
        $this->seeNoContinueButton();
        $this->enterFirstTraining();
        $this->onTrainingModeIndex();
        $this->seeContinueButton();
    }

    public function testThrows404IfDoneExcerciseDoesNotExists()
    {
        $this->markExcerciseWithIdAsDone("99999");
        $this->pageReturnsNotFoundCode();
    }

    public function testThrows404IfRestartedTrainingDoesNotExists()
    {
        $this->restartTrainingWithId("99999");
        $this->pageReturnsNotFoundCode();
    }
    
    public function testRestartButtonCreatesNewTrainingSoISeeDoneExcercisesAgain()
    {
        //start training and mark one excercise as done
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
        $this->marFirstExcerciseAsDone();
        $this->followRedirect();
        $this->seeNExcercises(6 - 1);
        
        //restart training
        $this->onTrainingModeIndex();
        $this->restartFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
    }

    private function seeAllTrainingsOnList()
    {
        $this->assertCountElementsByClass(3, "h2.gh-training-name");
        $this->assertEquals("Day 1", $this->crawler->filter("h2.gh-training-name")->eq(0)->html());
        $this->assertEquals("Day 2", $this->crawler->filter("h2.gh-training-name")->eq(1)->html());
        $this->assertEquals("Day 3", $this->crawler->filter("h2.gh-training-name")->eq(2)->html());
    }

    private function enterFirstTraining()
    {
        $this->clickFirstLinkWithClass(".gh-start-training-button");
    }

    private function restartFirstTraining()
    {
        $this->clickFirstLinkWithClass(".gh-restart-training-button");
    }

    private function seeExcerciseList()
    {
        $this->assertEquals("Day 1", $this->crawler->filter(".gh-training-name")->eq(0)->html());
        $this->assertCountElementsByClass(6, ".gh-excercise-name");
        $this->assertEquals("exc1", $this->crawler->filter(".gh-excercise-name")->eq(0)->html());
        $this->assertEquals("exc1", $this->crawler->filter(".gh-excercise-name")->eq(1)->html());
        $this->assertEquals("exc1", $this->crawler->filter(".gh-excercise-name")->eq(2)->html());
        $this->assertEquals("exc2", $this->crawler->filter(".gh-excercise-name")->eq(3)->html());
        $this->assertEquals("exc2", $this->crawler->filter(".gh-excercise-name")->eq(4)->html());
        $this->assertEquals("exc2", $this->crawler->filter(".gh-excercise-name")->eq(5)->html());
    }

    private function seeNExcercises($n)
    {
        $this->assertCountElementsByClass($n, ".gh-excercise-name");
    }

    private function marFirstExcerciseAsDone()
    {
        $this->clickFirstLinkWithClass(".gh-excercise-done");
    }

    private function markExcerciseWithIdAsDone($id)
    {
        $this->getPageWithUrl("/training-mode/excercise/" . $id . "/done");
    }

    private function restartTrainingWithId($id)
    {
        $this->getPageWithUrl("/training-mode/" . $id . "/restart");
    }

    private function seeNoContinueButton()
    {
        $this->assertCountElementsByClass(0, ".gh-continue-training-button");
    }

    private function seeContinueButton()
    {        
        $this->assertCountElementsByClass(1, ".gh-continue-training-button");
    }

}
