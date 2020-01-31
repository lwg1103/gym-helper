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
        $this->markFirstExcerciseAsOk();
        $this->followRedirect();
        $this->seeNExcercises(6 - 1);
    }

    public function testRemoveEasyExcercise()
    {
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
        $this->markFirstExcerciseAsEasy();
        $this->followRedirect();
        $this->seeNExcercises(6 - 1);
    }

    public function testRemoveHardExcercise()
    {
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
        $this->markFirstExcerciseAsHard();
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
        $this->markExcerciseWithIdAsOk("99999");
        $this->pageReturnsNotFoundCode();
    }

    public function testThrows404IfRestartedTrainingDoesNotExists()
    {
        $this->restartTrainingWithId("99999");
        $this->pageReturnsNotFoundCode();
    }

    public function testThrows404IfRequestedTrainingDoesNotExists()
    {
        $this->seeTrainingWithId("99999");
        $this->pageReturnsNotFoundCode();
    }
    
    public function testThrows400IfTryToStartStartedTraining()
    {
        $this->onTrainingModeIndex();
        $startLink = $this->getStartTrainingLink();
        $this->clickLink($startLink);
        $this->clickLink($startLink);
        $this->pageReturnsCodeN(400);
    }
    
    public function testRestartButtonCreatesNewTrainingSoISeeDoneExcercisesAgain()
    {
        //start training and mark one excercise as done
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
        $this->markFirstExcerciseAsOk();
        $this->followRedirect();
        $this->seeNExcercises(6 - 1);
        
        //restart training
        $this->onTrainingModeIndex();
        $this->restartFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
    }
    
    public function testFinishTraining()
    {
        //start training and mark three excercise as done
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeNExcercises(6);
        $this->markFirstExcerciseAsOk();
        $this->followRedirect();
        $this->seeNExcercises(6 - 1); 
        $this->markFirstExcerciseAsEasy();
        $this->followRedirect();
        $this->seeNExcercises(6 - 2); 
        $this->markFirstExcerciseAsHard();
        $this->followRedirect();
        $this->seeNExcercises(6 - 3); 
        
        $this->finishTraining();
        $this->followRedirect();
        //see training report
        $this->seeTrainingReport();
        //navigate to training mode index
        $this->onTrainingModeIndex();
        $this->seeNoContinueButton();
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

    private function markFirstExcerciseAsOk()
    {
        $this->clickFirstLinkWithClass(".gh-excercise-ok");
    }

    private function markFirstExcerciseAsEasy()
    {
        $this->clickFirstLinkWithClass(".gh-excercise-easy");
    }

    private function markFirstExcerciseAsHard()
    {
        $this->clickFirstLinkWithClass(".gh-excercise-hard");
    }

    private function markExcerciseWithIdAsOk($id)
    {
        $this->getPageWithUrl("/training-mode/excercise/" . $id . "/ok");
    }

    private function restartTrainingWithId($id)
    {
        $this->getPageWithUrl("/training-mode/" . $id . "/restart");
    }

    private function seeTrainingWithId($id)
    {
        $this->getPageWithUrl("/training-mode/" . $id);
    }

    private function seeNoContinueButton()
    {
        $this->assertCountElementsByClass(0, ".gh-continue-training-button");
    }

    private function seeContinueButton()
    {        
        $this->assertCountElementsByClass(1, ".gh-continue-training-button");
    }
    
    private function getStartTrainingLink()
    {
        return $this->crawler->filter(".gh-start-training-button")->eq(0)->link();
    }
    
    private function finishTraining()
    {
        $this->clickFirstLinkWithClass(".gh-finish-training");
    }
    
    private function seeTrainingReport()
    {
        $this->assertCountElementsByClass(1, ".gh-training-report-header");
    }

}
