<?php

namespace App\Tests\Controller;

class ExcerciseControllerTest extends BaseController
{

    public function testAddExcercise()
    {
        $this->onTrainingIndex();
        $this->seeNExcercisesListed(2);
        $this->clickFirstLinkWithClass(".gh-add-excercise-button");
        $this->fillExcerciseForm("pull-ups");
        $this->seeNExcercisesListed(3);
        $this->excerciseNameOnPositionIs("pull-ups", 2);
    }

    public function testDeleteExcercise()
    {
        $this->onTrainingIndex();
        $this->seeNExcercisesListed(2);
        $this->clickFirstLinkWithClass(".gh-delete-excercise-button");
        $this->followRedirect();
        $this->pageReturnsCode200();
        $this->seeNExcercisesListed(1);
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
        $this->assertEquals($n, $this->crawler->filter("td.gh-excercise-name")
                        ->count());
    }

    private function excerciseNameOnPositionIs(string $name, int $position)
    {
        $this->assertEquals($name, $this->crawler->filter("td.gh-excercise-name")
                        ->eq($position)->text());
    }

}
