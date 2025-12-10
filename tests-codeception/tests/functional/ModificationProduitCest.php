<?php

namespace Tests\Support;

use Tests\Support\FunctionalTester;

class ModificationProduitCest
{
    public function _before(FunctionalTester $I)
    {
        // Login as admin
        $I->amOnPage('/login');
        $I->fillField('email', 'Charbel');
        $I->fillField('password', 'admin123');
        $I->click('button[type="submit"]');
        $I->waitForElement('table', 10);
    }

    // tests
    public function testModificationProduit(FunctionalTester $I)
    {
        $I->wantTo('modifier un produit existant');
        
        $I->amOnPage('/listeAvecCon');
        $I->waitForElement('table', 5);
        
        // Cliquer sur le premier lien "Modifier" disponible
        $I->click('//a[contains(@href, "/maj/")]');
        
        // Attendre le formulaire de modification
        $I->waitForElement('form', 10);
        $I->seeInCurrentUrl('/maj/');
        
        // Modifier le nom du produit
        $I->fillField('nom', 'Produit Modifié Codeception');
        
        // Soumettre le formulaire
        $I->click('button[type="submit"]');
        
        // Vérifier la redirection vers la liste
        $I->wait(2);
        $I->seeInCurrentUrl('liste');
        
        $I->comment('✓ Produit modifié avec succès');
    }
}
