package com.asking.api_produit.selenium;

import io.github.bonigarcia.wdm.WebDriverManager;
import org.junit.jupiter.api.*;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.support.ui.ExpectedConditions;

import java.time.Duration;

/**
 * Classe de base simple pour les tests Selenium
 */
@TestInstance(TestInstance.Lifecycle.PER_CLASS)
public abstract class BaseSeleniumTest {

    protected static WebDriver driver;
    protected static String baseUrl = "http://localhost:8080";

    @BeforeAll
    public void setUpOnce() {
        WebDriverManager.chromedriver().setup();

        ChromeOptions options = new ChromeOptions();
        options.addArguments("--remote-allow-origins=*");

        driver = new ChromeDriver(options);
        driver.manage().timeouts().implicitlyWait(Duration.ofSeconds(10));
        driver.manage().window().maximize();

        // Se connecter une seule fois
        login();
    }

    @AfterAll
    public void tearDownOnce() {
        if (driver != null) {
            driver.quit();
        }
    }

    private void login() {
        driver.get(baseUrl + "/login");

        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(10));
        wait.until(ExpectedConditions.presenceOfElementLocated(By.name("email")));

        driver.findElement(By.name("email")).sendKeys("Charbel");
        driver.findElement(By.name("password")).sendKeys("admin123");
        driver.findElement(By.cssSelector("button[type='submit']")).click();

        wait.until(ExpectedConditions.urlContains("/liste"));
    }
}
