<?php

namespace App\Tests\Controller;

class TrainingReportControllerTest extends BaseController
{
    public function testShowReport()
    {
        
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
}
