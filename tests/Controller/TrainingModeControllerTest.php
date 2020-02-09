<?php

namespace App\Tests\Controller;

class TrainingModeControllerTest extends BaseController
{

    public function testSeeTrainingPage()
    {
        $this->loginAsUser();
        $this->onTrainingModeIndex();
        $this->pageReturnsCode200();
    }

    public function testSeeTrainigsList()
    {
        $this->loginAsUser();
        $this->onTrainingModeIndex();
        $this->seeAllTrainingsOnList();
    }

    public function testSeeTrainigDetails()
    {
        $this->loginAsUser();
        $this->onTrainingModeIndex();
        $this->enterFirstTraining();
        $this->followRedirect();
        $this->seeExcerciseList();
    }

    public function testRemoveDoneExcercise()
    {
        $this->loginAsUser();
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
        $this->loginAsUser();
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
        $this->loginAsUser();
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
        $this->loginAsUser();
        $this->onTrainingModeIndex();
        $this->seeNoContinueButton();
        $this->enterFirstTraining();
        $this->onTrainingModeIndex();
        $this->seeContinueButton();
    }

    public function testThrows404IfDoneExcerciseDoesNotExists()
    {
        $this->asAUser();
        $this->markExcerciseWithIdAsOk("99999");
        $this->pageReturnsNotFoundCode();
    }

    public function testThrows404IfRestartedTrainingDoesNotExists()
    {
        $this->asAUser();
        $this->restartTrainingWithId("99999");
        $this->pageReturnsNotFoundCode();
    }

    public function testThrows404IfRequestedTrainingDoesNotExists()
    {
        $this->asAUser();
        $this->seeTrainingWithId("99999");
        $this->pageReturnsNotFoundCode();
    }

    public function testThrows400IfTryToStartStartedTraining()
    {
        $this->loginAsUser();
        $this->onTrainingModeIndex();
        $startLink = $this->getStartTrainingLink();
        $this->clickLink($startLink);
        $this->clickLink($startLink);
        $this->pageReturnsCodeN(400);
    }

    public function testRestartButtonCreatesNewTrainingSoISeeDoneExcercisesAgain()
    {
        $this->loginAsUser();
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
        $this->loginAsUser();
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

    public function testAccessDeniedWhenViewingOtherUserTrainingInstance()
    {
        $this->loginAsUser();
        $this->getPageWithUrl("/training-mode/{$this->getOtherUserTrainingId()}");
        $this->pageReturnsAccessDeniedCode();
    }

    public function testAccessDeniedWhenStartingOtherUserTrainingInstance()
    {
        $this->loginAsUser();
        $this->getPageWithUrl("/training-mode/{$this->getOtherUserTrainingId()}/start");
        $this->pageReturnsAccessDeniedCode();
    }

    public function testAccessDeniedWhenRestartingOtherUserTrainingInstance()
    {
        $this->loginAsUser();
        $this->getPageWithUrl("/training-mode/{$this->getOtherUserTrainingId()}/restart");
        $this->pageReturnsAccessDeniedCode();
    }

    public function testAccessDeniedWhenFinishingOtherUserTrainingInstance()
    {
        $this->loginAsUser();
        $this->getPageWithUrl("/training-mode/{$this->getOtherUserTrainingId()}/finish");
        $this->pageReturnsAccessDeniedCode();
    }

    public function testAccessDeniedWhenChangingOtherUserExcerciseInstanceStatus()
    {
        $this->loginAsUser();

        $this->getPageWithUrl("/training-mode/excercise/{$this->getOtherUserExcerciseInstanceId()}/ok");
        $this->pageReturnsAccessDeniedCode();

        $this->getPageWithUrl("/training-mode/excercise/{$this->getOtherUserExcerciseInstanceId()}/easy");
        $this->pageReturnsAccessDeniedCode();

        $this->getPageWithUrl("/training-mode/excercise/{$this->getOtherUserExcerciseInstanceId()}/hard");
        $this->pageReturnsAccessDeniedCode();
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

    private function getOtherUserTrainingId()
    {
        $doctrine = $this->client
                ->getContainer()
                ->get('doctrine');

        $otherUser = $doctrine->getRepository(\App\Entity\User::class)
                ->findOneBy(["email" => "user2@ex.com"]);

        return $doctrine->getRepository(\App\Entity\Training::class)
                        ->findOneBy(["user" => $otherUser])
                        ->getId();
    }

    private function getOtherUserExcerciseInstanceId()
    {
        $doctrine = $this->client
                ->getContainer()
                ->get('doctrine');

        $otherUser = $doctrine->getRepository(\App\Entity\User::class)
                ->findOneBy(["email" => "user2@ex.com"]);

        $training = $doctrine->getRepository(\App\Entity\Training::class)
                ->findOneBy(["user" => $otherUser]);

        $trainingInstance = $doctrine->getRepository(\App\Entity\TrainingInstance::class)
                ->findOneBy(["baseTraining" => $training]);

        return $doctrine->getRepository(\App\Entity\ExcerciseInstance::class)
                        ->findOneBy(["trainingInstance" => $trainingInstance])
                        ->getId();
    }

}
