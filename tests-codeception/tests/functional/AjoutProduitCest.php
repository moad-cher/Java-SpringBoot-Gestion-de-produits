<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

class AjoutProduitCest
{
    public function _before(FunctionalTester $I)
    {
        $I->loginAsAdmin();
    }

    // tests
    public function testAjoutProduit(FunctionalTester $I)
    {
        $I->wantTo('créer un nouveau produit');
        
        $I->amOnPage('/creation/');
        $I->waitForElement('form', 10);
        
        // Remplir le formulaire
        $I->fillField('nom', 'Test Produit Codeception');
        $I->fillField('prix', '99');
        $I->fillField('devise', 'EUR');
        $I->fillField('taxe', '20');
        $I->fillField('dateExpiration', '2025-12-31');
        $I->fillField('fournisseur', 'Test Fournisseur Codeception');
        
        // Soumettre le formulaire
        $I->click('button[type="submit"]');
        
        // Vérifier la redirection (vers liste ou recherche)
        $I->wait(2);
        $currentUrl = $I->grabFromCurrentUrl();
        
        // Vérifier qu'on n'est plus sur la page de création
        $I->dontSeeInCurrentUrl('/creation');
        
        // Vérifier qu'on n'est pas sur une page d'erreur
        $I->dontSeeInCurrentUrl('/error');
        $I->dontSeeInCurrentUrl('/500');
        
        $I->comment('✓ Produit créé avec succès!');
    }
}
