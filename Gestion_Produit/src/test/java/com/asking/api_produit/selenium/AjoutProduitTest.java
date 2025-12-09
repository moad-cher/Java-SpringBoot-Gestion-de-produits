package com.asking.api_produit.selenium;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.support.ui.ExpectedConditions;
import java.time.Duration;
import static org.junit.jupiter.api.Assertions.*;

/**
 * Test d'ajout de produit
 */
public class AjoutProduitTest extends BaseSeleniumTest {

    @Test
    public void testAccesPageCreation() {
        driver.get(baseUrl + "/creation/");

        // Attendre que le formulaire soit visible
        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(10));
        wait.until(ExpectedConditions.presenceOfElementLocated(By.tagName("form")));

        // Remplir le formulaire
        driver.findElement(By.name("nom")).sendKeys("Test Produit");
        driver.findElement(By.name("prix")).sendKeys("99");
        driver.findElement(By.name("devise")).sendKeys("EUR");
        driver.findElement(By.name("taxe")).sendKeys("20");
        driver.findElement(By.name("dateExpiration")).sendKeys("2025-12-31");
        driver.findElement(By.name("fournisseur")).sendKeys("Test Fournisseur");

        // Soumettre
        driver.findElement(By.xpath("//button[contains(text(),'Ajouter')]")).click();

        // Vérifier redirection vers liste
        wait.until(ExpectedConditions.urlContains("liste"));
        assertTrue(driver.getCurrentUrl().contains("liste"));
    }
}
