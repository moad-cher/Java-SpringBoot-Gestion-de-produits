package com.asking.api_produit.selenium;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.support.ui.ExpectedConditions;
import java.time.Duration;
import java.util.List;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Test du listing des produits
 */
public class ListingProduitTest extends BaseSeleniumTest {

    @Test
    public void testAffichageListeProduits() {
        driver.get(baseUrl + "/listeAvecCon");

        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(5));

        // Attendre que la table soit chargée
        wait.until(ExpectedConditions.presenceOfElementLocated(By.tagName("table")));

        // Vérifier que la table existe
        WebElement table = driver.findElement(By.tagName("table"));
        assertNotNull(table, "La table des produits doit exister");

        // Vérifier qu'il y a au moins une ligne de produit
        List<WebElement> rows = driver.findElements(By.cssSelector("table tbody tr"));
        assertTrue(rows.size() > 0, "La table doit contenir au moins un produit");

        // Vérifier que les colonnes de base sont présentes (nom, prix, etc.)
        List<WebElement> headers = driver.findElements(By.cssSelector("table thead th"));
        assertTrue(headers.size() >= 3, "La table doit avoir au moins 3 colonnes");

        System.out.println("✓ Liste des produits affichée avec " + rows.size() + " produit(s)");
    }
}
