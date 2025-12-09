package com.asking.api_produit.selenium;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.support.ui.ExpectedConditions;
import java.time.Duration;
import static org.junit.jupiter.api.Assertions.*;

/**
 * Test de modification de produit
 */
public class ModificationProduitTest extends BaseSeleniumTest {

    @Test
    public void testAccesListeProduits() {
        driver.get(baseUrl + "/listeAvecCon");

        // Trouver le premier lien de modification
        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(10));
        WebElement modifierLink = wait.until(ExpectedConditions.presenceOfElementLocated(
                By.cssSelector("a[href*='/maj/']")));
        modifierLink.click();

        // Vérifier qu'on est sur la page de modification
        wait.until(ExpectedConditions.urlContains("/maj/"));
        assertTrue(driver.getCurrentUrl().contains("/maj/"));

        // Vérifier que le formulaire de modification existe
        assertNotNull(driver.findElement(By.tagName("form")));
    }
}
