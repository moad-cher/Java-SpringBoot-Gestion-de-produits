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

        System.out.println("Remplissage du formulaire...");
        
        // Remplir le formulaire
        driver.findElement(By.name("nom")).sendKeys("Test Produit Selenium");
        driver.findElement(By.name("prix")).sendKeys("99");
        driver.findElement(By.name("devise")).sendKeys("EUR");
        driver.findElement(By.name("taxe")).sendKeys("20");
        driver.findElement(By.name("dateExpiration")).sendKeys("31/12/2025");
        driver.findElement(By.name("fournisseur")).sendKeys("Test Fournisseur");

        // Ne pas remplir le champ image - laisser vide pour que le formulaire utilise
        // la valeur par défaut

        System.out.println("Soumission du formulaire...");
        
        // Attendre que le bouton soit cliquable et cliquer
        var submitButton = wait.until(ExpectedConditions.elementToBeClickable(
                By.xpath("//button[contains(text(),'Ajouter')]")));
        submitButton.click();

        System.out.println("Attente de la redirection...");
        
        // Attendre et vérifier la redirection avec un timeout plus long
        wait = new WebDriverWait(driver, Duration.ofSeconds(15));
        wait.until(ExpectedConditions.or(
                ExpectedConditions.urlContains("liste"),
                ExpectedConditions.urlContains("error"),
                ExpectedConditions.urlContains("500")));

        String currentUrl = driver.getCurrentUrl();
        System.out.println("URL après soumission: " + currentUrl);

        // Si on est sur une page d'erreur, afficher plus d'info
        if (currentUrl.contains("error") || currentUrl.contains("500")) {
            System.out.println("ERREUR: Le formulaire a généré une erreur");
            System.out.println("Contenu de la page: "
                    + driver.getPageSource().substring(0, Math.min(500, driver.getPageSource().length())));
        }
        
        // Si toujours sur /create, afficher la page source pour déboguer
        if (currentUrl.contains("/create")) {
            System.err.println("ERREUR: Toujours sur la page de création après soumission!");
            System.err.println("Contenu de la page: "
                    + driver.getPageSource().substring(0, Math.min(1000, driver.getPageSource().length())));
        }

        assertTrue(currentUrl.contains("liste"), "Devrait rediriger vers la liste des produits");
    }
}
