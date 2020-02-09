<?php

namespace App\Tests\Controller;

class SecurityControllerTest extends BaseController
{
    public function testLoginRedirect()
    {
        $this->getPageWithUrl('/');
        $this->followRedirect();
        $this->currentUrlIs("/login");
    }
    
    public function testLoginAction()
    {
        $this->getPageWithUrl('/');
        $this->followRedirect();
        $this->currentUrlIs("/login");
        $this->fillLoginForm('user@ex.com', 'pass');
        $this->currentUrlIs("/");
        $this->seeHomePageMenu();
    }
    
    public function testLogoutAction()
    {
        $this->asAUser();
        $this->getPageWithUrl('/logout');
        $this->followRedirect();
        $this->followRedirect();
        $this->currentUrlIs("/login");
    }
    
    private function seeHomePageMenu()
    {
        $this->assertCountElementsByClass(1, ".gh-training-mode-button");
        $this->assertCountElementsByClass(1, ".gh-training-editor-button");
    }
}
