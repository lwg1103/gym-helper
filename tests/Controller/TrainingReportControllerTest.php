<?php

namespace App\Tests\Controller;

class TrainingReportControllerTest extends BaseController
{

    public function testShowReport()
    {
        $this->enterTrainingReportPage();
        $this->seeTrainingReportHeader();
        $this->seeTrainingName("training 1");
        $this->seeExcerciseNames(["exc 1", "exc 2"]);
        $this->seeExcerciseResults(["Ok", "Hard"]);
    }

    public function testThrows404IfRequestedTrainingDoesNotExists()
    {
        $this->seeTrainingReportWithId("99999");
        $this->pageReturnsNotFoundCode();
    }

    private function seeTrainingReportWithId($id)
    {
        $this->getPageWithUrl("/training-report/" . $id);
    }

    private function enterTrainingReportPage()
    {
        $this->client   = static::createClient();
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

}
