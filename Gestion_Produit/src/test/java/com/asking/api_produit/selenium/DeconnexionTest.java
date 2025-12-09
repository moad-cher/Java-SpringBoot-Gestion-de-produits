package com.asking.api_produit.selenium;

import org.junit.jupiter.api.Test;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.support.ui.ExpectedConditions;
import java.time.Duration;
import static org.junit.jupiter.api.Assertions.*;

/**
 * Test de déconnexion
 */
public class DeconnexionTest extends BaseSeleniumTest {

    @Test
    public void testDeconnexion() {
        driver.get(baseUrl + "/listeAvecCon");

        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(10));

        // Cliquer sur le bouton navbar-toggler pour ouvrir le menu mobile
        try {
            WebElement navbarToggler = wait
                    .until(ExpectedConditions.elementToBeClickable(By.className("navbar-toggler")));
            navbarToggler.click();
            Thread.sleep(500); // Petite pause pour l'animation
        } catch (Exception e) {
            System.out.println("Navbar toggler non trouvé ou déjà ouvert");
        }

        // Cliquer sur "Se déconnecter" dans le menu
        WebElement seDeconnecterLink = wait.until(ExpectedConditions.elementToBeClickable(
                By.xpath("//a[contains(text(), 'Se déconnecter') or contains(text(), 'Déconnexion')]")));
        seDeconnecterLink.click();

        // Attendre et cliquer sur le bouton "Log Out" avec la classe exacte
        WebElement logoutButton = wait.until(ExpectedConditions.elementToBeClickable(
                By.cssSelector("button.btn.btn-lg.btn-primary.btn-block[type='submit']")));
        logoutButton.click();

        // Vérifier redirection vers la page d'accueil
        wait.until(ExpectedConditions.or(
                ExpectedConditions.urlToBe(baseUrl + "/"),
                ExpectedConditions.presenceOfElementLocated(By.linkText("Se connecter"))));

        String currentUrl = driver.getCurrentUrl();
        assertTrue(currentUrl.equals(baseUrl + "/") ||
                driver.findElements(By.linkText("Se connecter")).size() > 0,
                "Devrait être redirigé vers la page d'accueil après déconnexion");

        System.out.println("✓ Déconnexion réussie, redirigé vers: " + currentUrl);
    }
}
