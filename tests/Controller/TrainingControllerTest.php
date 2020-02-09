<?php

namespace App\Tests\Controller;

class TrainingControllerTest extends BaseController
{
    public function testSeeTrainingPage()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->pageReturnsCode200();
    }   
    
    public function testSeeTrainigsList()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->seeTrainingListDetails();
    }
    
    public function testSeeExcerciseDetails()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->seeExcerciseDetails();
    }
    
    public function testAddTraining()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->seeNTrainingsListed(3);      
        $this->clickFirstLinkWithClass(".gh-add-training-button");
        $this->fillTrainingForm("Friday");
        $this->seeNTrainingsListed(4);
        $this->trainingNameOnPositionIs("Friday", 3);
    }
    
    public function testDeleteTraining()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->seeNTrainingsListed(3);  
        $this->clickFirstLinkWithClass(".gh-delete-training-button");
        $this->followRedirect();
        $this->pageReturnsCode200();
        $this->seeNTrainingsListed(2);  
    }
    
    public function testEditTraining()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->trainingNameOnPositionIs("Day 1", 0);
        $this->clickFirstLinkWithClass(".gh-edit-training-button");
        $this->fillTrainingForm("Tuesday");
        $this->pageReturnsCode200();
        $this->trainingNameOnPositionIs("Tuesday", 0);
    }
    
    public function testThrows404IfEditedTrainingDoesNotExists()
    {
        $this->asAUser();
        $this->getPageWithUrl("/training/99999/edit");
        $this->pageReturnsNotFoundCode();
    }
    
    public function testAccessDeniedWhenEditingOtherUserTraining()
    {
        $this->loginAsUser();
        $this->getPageWithUrl("/training/{$this->getOtherUserTrainingId()}/edit");
        $this->pageReturnsAccessDeniedCode();
    }
    
    public function testAccessDeniedWhenDeletingOtherUserTraining()
    {
        $this->loginAsUser();
        $this->getPageWithUrl("/training/{$this->getOtherUserTrainingId()}/delete");
        $this->pageReturnsAccessDeniedCode();
    }
    
    private function fillTrainingForm($trainingName)
    {
        $this->client->submitForm("Save",[
            'training[name]' => $trainingName
        ]);
        
        $this->crawler = $this->client->followRedirect();
    }
    
    private function seeTrainingListDetails()
    {
        $this->assertSelectorTextContains('h1', 'trainings');
        $this->assertSelectorTextContains('h2.gh-training-name', 'Day 1');
        $this->assertSelectorTextContains('td.gh-excercise-name', 'exc1');
    }
    
    private function seeExcerciseDetails()
    {
        $firstRowFields = $this->crawler->filter("td");
        
        $this->assertEquals("exc1", $firstRowFields->eq(0)->html());
        $this->assertEquals("30", $firstRowFields->eq(1)->html());
        $this->assertEquals("12", $firstRowFields->eq(2)->html());
        $this->assertEquals("3", $firstRowFields->eq(3)->html());
        $this->assertEquals("60", $firstRowFields->eq(4)->html());
        $this->assertEquals("10 - 15", $firstRowFields->eq(5)->html());
    }
    
    private function seeNTrainingsListed(int $n)
    {
        $this->assertCountElementsByClass($n, "h2.gh-training-name");
    }
    
    private function trainingNameOnPositionIs(string $name, int $position)
    {
        $this->assertEquals($name, $this->crawler->filter("h2.gh-training-name")
                ->eq($position)->text());
    }

    private function getOtherUserTrainingId()
    {
        $doctrine = $this->client
                ->getContainer()
                ->get('doctrine');

        $otherUser = $doctrine->getRepository(\App\Entity\User::class)
                ->findOneBy(["email" => "user2@ex.com"]);

        return $doctrine->getRepository(\App\Entity\Training::class)
                ->findOneBy(["user" => $otherUser])->getId();
    }
}
