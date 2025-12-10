<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

class DeconnexionCest
{
    public function _before(FunctionalTester $I)
    {
        $I->loginAsAdmin();
    }

    // tests
    public function testDeconnexion(FunctionalTester $I)
    {
        $I->wantTo('me déconnecter de l\'application');
        
        $I->amOnPage('/listeAvecCon');
        
        // Cliquer sur le bouton navbar-toggler (menu mobile)
        try {
            $I->click('.navbar-toggler');
            $I->wait(0.5);
        } catch (\Exception $e) {
            $I->comment('Navbar toggler non trouvé ou déjà ouvert');
        }
        
        // Cliquer sur "Se déconnecter"
        $I->click('//a[contains(text(), "Se déconnecter") or contains(text(), "Déconnexion")]');
        
        // Cliquer sur le bouton "Log Out"
        $I->waitForElement('button.btn.btn-lg.btn-primary.btn-block[type="submit"]', 5);
        $I->click('button.btn.btn-lg.btn-primary.btn-block[type="submit"]');
        
        // Vérifier la redirection vers la page d'accueil
        $I->wait(1);
        $I->seeInCurrentUrl('/');
        $I->seeLink('Se connecter');
        
        $I->comment('✓ Déconnexion réussie');
    }
}
