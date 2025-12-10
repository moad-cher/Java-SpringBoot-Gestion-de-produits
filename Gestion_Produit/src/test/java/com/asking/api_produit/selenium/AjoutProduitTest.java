package com.asking.api_produit.selenium;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.WebElement;
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

        System.out.println("Remplissage du formulaire...");
        
        // Remplir le formulaire
        driver.findElement(By.name("nom")).sendKeys("Test Produit Selenium");
        driver.findElement(By.name("prix")).sendKeys("99");
        driver.findElement(By.name("devise")).sendKeys("EUR");
        driver.findElement(By.name("taxe")).sendKeys("20");
        driver.findElement(By.name("dateExpiration")).sendKeys("2025-12-31"); // Format YYYY-MM-DD pour input type="date"
        driver.findElement(By.name("fournisseur")).sendKeys("Test Fournisseur");

        // Ne pas remplir le champ image - laisser vide pour que le formulaire utilise
        // la valeur par défaut

        System.out.println("Soumission du formulaire...");
        
        // Try using JavaScript to submit the form as a workaround
        WebElement form = driver.findElement(By.tagName("form"));
        ((JavascriptExecutor) driver).executeScript("arguments[0].submit();", form);

        System.out.println("Attente de la redirection...");
        
        // Attendre et vérifier la redirection avec un timeout plus long
        wait = new WebDriverWait(driver, Duration.ofSeconds(15));
        
        wait.until(ExpectedConditions.or(
                ExpectedConditions.urlContains("liste"),
                ExpectedConditions.urlContains("recherche"), // Sometimes redirects to search page
                ExpectedConditions.urlContains("error"),
                ExpectedConditions.urlContains("500")));

        String currentUrl = driver.getCurrentUrl();
        System.out.println("URL après soumission: " + currentUrl);

        // Vérifier que nous ne sommes plus sur la page de création et pas sur une page d'erreur
        assertFalse(currentUrl.contains("/creation"), "Ne devrait plus être sur la page de création");
        assertFalse(currentUrl.contains("error"), "Ne devrait pas être sur une page d'erreur");
        assertFalse(currentUrl.contains("500"), "Ne devrait pas avoir une erreur 500");
        
        // Si on a redirigé vers liste ou recherche, le produit a été créé avec succès
        assertTrue(currentUrl.contains("liste") || currentUrl.contains("recherche"), 
                   "Devrait rediriger vers la liste ou la recherche après création");
        
        System.out.println("✓ Produit créé avec succès!");
    }
}
