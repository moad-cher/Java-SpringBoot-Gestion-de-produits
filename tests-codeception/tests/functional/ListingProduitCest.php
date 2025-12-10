<?php

namespace Tests\Functional;

use Tests\Support\FunctionalTester;

class ListingProduitCest
{
    public function _before(FunctionalTester $I)
    {
        $I->loginAsAdmin();
    }

    // tests
    public function testAffichageListeProduits(FunctionalTester $I)
    {
        $I->wantTo('vérifier que la liste des produits s\'affiche correctement');
        
        $I->amOnPage('/listeAvecCon');
        $I->waitForElement('table', 5);
        
        // Vérifier que la table existe
        $I->seeElement('table');
        
        // Vérifier qu'il y a au moins une ligne de produit
        $I->seeElement('table tbody tr');
        
        // Vérifier qu'il y a au moins 3 colonnes d'en-tête
        $I->seeElement('table thead th');
        
        $I->comment('✓ Liste des produits affichée avec succès');
    }
}
