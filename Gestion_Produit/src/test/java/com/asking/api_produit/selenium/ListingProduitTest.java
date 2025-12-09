package com.asking.api_produit.selenium;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import static org.junit.jupiter.api.Assertions.*;

/**
 * Test du listing des produits
 */
public class ListingProduitTest extends BaseSeleniumTest {

    @Test
    public void testAffichageListeProduits() {
        driver.get(baseUrl + "/listeAvecCon");

        // Vérifier que la table existe
        assertNotNull(driver.findElement(By.tagName("table")));

        // Vérifier qu'il y a au moins une ligne de produit
        assertTrue(driver.findElements(By.cssSelector("table tbody tr")).size() > 0);
    }
}
