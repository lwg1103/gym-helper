<?php

namespace App\Tests\Controller;

class MainControllerTest extends BaseController
{
    public function testSeeMainPage()
    {
        $this->asAUser();
        $this->getPageWithUrl('/');
        $this->pageReturnsCode200();
    }
    
    public function testNavigateToTrainingList()
    {
        $this->asAUser();
        $this->getPageWithUrl('/');
        $this->clickFirstLinkWithClass(".gh-training-editor-button");
        $this->pageReturnsCode200();
        $this->currentUrlIs("/training/");
    }
    
    public function testNavigateToTrainingMode()
    {
        $this->asAUser();
        $this->getPageWithUrl('/');
        $this->clickFirstLinkWithClass(".gh-training-mode-button");
        $this->pageReturnsCode200();
        $this->currentUrlIs("/training-mode/");
    }
}
