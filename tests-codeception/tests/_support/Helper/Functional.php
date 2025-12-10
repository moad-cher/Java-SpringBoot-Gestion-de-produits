<?php

namespace Tests\Support\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    public function loginAsAdmin()
    {
        $I = $this->getModule('WebDriver');
        $I->amOnPage('/login');
        $I->fillField('email', 'Charbel');
        $I->fillField('password', 'admin123');
        $I->click('button[type="submit"]');
        $I->waitForElement('table', 10); // Wait for liste page
    }
}
