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
        System.out.println("Setting up Chrome driver...");

        // Only use WebDriverManager locally
        if (System.getenv("CI") == null) {
            WebDriverManager.chromedriver().setup();
        }

        ChromeOptions options = new ChromeOptions();
        options.addArguments("--remote-allow-origins=*");
        
        // Set capability to automatically dismiss alerts
        options.setCapability("unhandledPromptBehavior", "dismiss");

        // Add headless mode for CI/CD environments
        if (System.getenv("CI") != null) {
            System.out.println("Running in CI mode with headless Chrome");
            options.addArguments("--headless=new");
            options.addArguments("--no-sandbox");
            options.addArguments("--disable-dev-shm-usage");
            options.addArguments("--disable-gpu");
            options.addArguments("--window-size=1920,1080");
        }

        driver = new ChromeDriver(options);
        driver.manage().timeouts().implicitlyWait(Duration.ofSeconds(10));
        driver.manage().timeouts().pageLoadTimeout(Duration.ofSeconds(30));
        if (System.getenv("CI") == null) {
            driver.manage().window().maximize();
        }

        // Se connecter une seule fois
        System.out.println("Logging in to application...");
        login();
        System.out.println("Setup complete, ready for tests");
    }

    @AfterAll
    public void tearDownOnce() {
        if (driver != null) {
            driver.quit();
        }
    }

    private void login() {
        try {
            System.out.println("Navigating to login page: " + baseUrl + "/login");
            driver.get(baseUrl + "/login");

            // Check and dismiss any existing alerts
            try {
                driver.switchTo().alert().dismiss();
                System.out.println("Dismissed existing alert");
            } catch (Exception ignored) {
                // No alert present, continue
            }

            WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(15));
            wait.until(ExpectedConditions.presenceOfElementLocated(By.name("email")));

            System.out.println("Filling login form...");
            driver.findElement(By.name("email")).sendKeys("Charbel");
            driver.findElement(By.name("password")).sendKeys("admin123");
            driver.findElement(By.cssSelector("button[type='submit']")).click();

            // Handle any alert that appears after login
            Thread.sleep(1000);
            try {
                driver.switchTo().alert().dismiss();
                System.out.println("Dismissed alert after login attempt");
            } catch (Exception ignored) {
                // No alert present, continue
            }

            System.out.println("Waiting for redirect after login...");
            wait.until(ExpectedConditions.urlContains("/liste"));
            System.out.println("Login successful, current URL: " + driver.getCurrentUrl());
        } catch (Exception e) {
            System.err.println("Login failed: " + e.getMessage());
            System.err.println("Current URL: " + driver.getCurrentUrl());
            throw e;
        }
    }
}
