<?php

namespace App\Tests\Controller;

class ExcerciseControllerTest extends BaseController
{

    public function testAddExcercise()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->seeNExcercisesListed(6);
        $this->clickFirstLinkWithClass(".gh-add-excercise-button");
        $this->fillExcerciseForm("pull-ups");
        $this->seeNExcercisesListed(7);
        $this->excerciseNameOnPositionIs("pull-ups", 2);
    }

    public function testDeleteExcercise()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->seeNExcercisesListed(6);
        $this->clickFirstLinkWithClass(".gh-delete-excercise-button");
        $this->followRedirect();
        $this->pageReturnsCode200();
        $this->seeNExcercisesListed(5);
    }
    
    public function testEditExcercise()
    {
        $this->loginAsUser();
        $this->onTrainingIndex();
        $this->excerciseNameOnPositionIs("exc1", 0);
        $this->clickFirstLinkWithClass(".gh-edit-excercise-button");
        $this->fillExcerciseForm("edited exc");
        $this->pageReturnsCode200();
        $this->excerciseNameOnPositionIs("edited exc", 0);
    }
    
    public function testThrows404IfEditedExcerciseDoesNotExists()
    {
        $this->asAUser();
        $this->getPageWithUrl("/excercise/99999/edit");
        $this->pageReturnsNotFoundCode();
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

    private function seeNExcercisesListed(int $n)
    {
        $this->assertCountElementsByClass($n, "td.gh-excercise-name");
    }

    private function excerciseNameOnPositionIs(string $name, int $position)
    {
        $this->assertEquals($name, $this->crawler->filter("td.gh-excercise-name")
                        ->eq($position)->text());
    }

}
