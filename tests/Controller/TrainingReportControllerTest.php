<?php

namespace App\Tests\Controller;

class TrainingReportControllerTest extends BaseController
{

    public function testShowReport()
    {
        $this->loginAsUser();
        $this->enterTrainingReportPage();
        $this->seeTrainingReportHeader();
        $this->seeTrainingName("training 1");
        $this->seeExcerciseNames(["exc 1", "exc 2"]);
        $this->seeExcerciseResults(["Ok", "Hard"]);
    }
    
    public function testNavigateToTrainingMode()
    {
        $this->loginAsUser();
        $this->enterTrainingReportPage();
        $this->clickBackutton();
        $this->seeAllTrainingsOnList();
    }

    public function testThrows404IfRequestedTrainingDoesNotExists()
    {
        $this->asAUser();
        $this->seeTrainingReportWithId("99999");
        $this->pageReturnsNotFoundCode();
    }
    
    public function testAccessDeniedWhenViewingOtherUserTrainingReport()
    {
        $this->loginAsUser();
        $this->seeTrainingReportWithId($this->getOtherUserTrainingReportId());
        $this->pageReturnsAccessDeniedCode();  
    }

    private function seeTrainingReportWithId($id)
    {
        $this->getPageWithUrl("/training-report/" . $id);
    }

    private function enterTrainingReportPage()
    {
        $trainingReport = $this->client
                        ->getContainer()
                        ->get('doctrine')
                        ->getRepository(\App\Entity\TrainingReport::class)
                        ->findAll()[0];
        
        $this->crawler = $this->client
                ->request('GET', '/training-report/' . $trainingReport->getId());
    }

    private function seeTrainingReportHeader()
    {
        $this->assertCountElementsByClass(1, ".gh-training-report-header");
    }

    private function seeTrainingName($name)
    {
        $this->assertEquals($name, $this->crawler->filter("h2.gh-training-name")->eq(0)->html());
    }
    
    private function seeExcerciseNames(array $names)
    {
        foreach ($names as $key => $value)
        {
            $this->assertEquals($value, $this->crawler->filter(".gh-excercise-name")->eq($key)->html()); 
        }
    }
    
    private function seeExcerciseResults(array $results)
    {
        foreach ($results as $key => $value)
        {
            $this->assertEquals($value, $this->crawler->filter(".gh-excercise-result")->eq($key)->html()); 
        }
    }
    
    private function clickBackutton()
    {
        $this->clickFirstLinkWithClass(".gh-back-button");
    }

    private function seeAllTrainingsOnList()
    {
        $this->assertCountElementsByClass(3, "h2.gh-training-name");
        $this->assertEquals("Day 1", $this->crawler->filter("h2.gh-training-name")->eq(0)->html());
        $this->assertEquals("Day 2", $this->crawler->filter("h2.gh-training-name")->eq(1)->html());
        $this->assertEquals("Day 3", $this->crawler->filter("h2.gh-training-name")->eq(2)->html());
    }
    
    private function getOtherUserTrainingReportId()
    {
        $doctrine = $this->client
                ->getContainer()
                ->get('doctrine');

        $otherUser = $doctrine->getRepository(\App\Entity\User::class)
                ->findOneBy(["email" => "user2@ex.com"]); 
        
        $training = $doctrine->getRepository(\App\Entity\Training::class)
                ->findOneBy(["user" => $otherUser]);
        
        return $doctrine->getRepository(\App\Entity\TrainingReport::class)
                ->findOneBy(["baseTraining" => $training])
                ->getId();
    }

}
