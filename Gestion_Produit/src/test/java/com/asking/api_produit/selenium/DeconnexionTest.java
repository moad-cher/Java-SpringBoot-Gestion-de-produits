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

        // Chercher et cliquer sur le lien de déconnexion
        WebElement logoutLink;
        try {
            logoutLink = driver.findElement(By.linkText("Déconnexion"));
        } catch (Exception e) {
            logoutLink = driver.findElement(By.cssSelector("a[href*='logout']"));
        }
        logoutLink.click();

        // Vérifier redirection vers login
        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(10));
        wait.until(ExpectedConditions.or(
                ExpectedConditions.urlContains("login"),
                ExpectedConditions.presenceOfElementLocated(By.name("email"))));

        assertTrue(driver.getCurrentUrl().contains("login") ||
                driver.findElements(By.name("email")).size() > 0);
    }
}
