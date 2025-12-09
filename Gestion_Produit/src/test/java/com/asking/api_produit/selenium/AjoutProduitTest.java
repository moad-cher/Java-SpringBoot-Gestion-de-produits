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
        driver.findElement(By.name("nom")).sendKeys("Test Produit Selenium");
        driver.findElement(By.name("prix")).sendKeys("99");
        driver.findElement(By.name("devise")).sendKeys("EUR");
        driver.findElement(By.name("taxe")).sendKeys("20");
        driver.findElement(By.name("dateExpiration")).sendKeys("31/12/2025");
        driver.findElement(By.name("fournisseur")).sendKeys("Test Fournisseur");

        // Ne pas remplir le champ image - laisser vide pour que le formulaire utilise
        // la valeur par défaut

        // Soumettre le formulaire
        driver.findElement(By.xpath("//button[contains(text(),'Ajouter')]")).click();

        // Attendre et vérifier la redirection
        wait.until(ExpectedConditions.or(
                ExpectedConditions.urlContains("liste"),
                ExpectedConditions.urlContains("error")));

        String currentUrl = driver.getCurrentUrl();
        System.out.println("URL après soumission: " + currentUrl);

        // Si on est sur une page d'erreur, afficher plus d'info
        if (currentUrl.contains("error") || currentUrl.contains("500")) {
            System.out.println("ERREUR: Le formulaire a généré une erreur 500");
            System.out.println("Contenu de la page: "
                    + driver.getPageSource().substring(0, Math.min(500, driver.getPageSource().length())));
        }

        assertTrue(currentUrl.contains("liste"), "Devrait rediriger vers la liste des produits");
    }
}
