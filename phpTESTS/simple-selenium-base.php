<?php
/**
 * Simple Selenium Base Class - No frameworks needed!
 * Just download: composer require php-webdriver/webdriver
 */

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;

// Try to load autoloader
$autoloadPaths = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
];

$loaded = false;
foreach ($autoloadPaths as $autoload) {
    if (file_exists($autoload)) {
        require_once $autoload;
        $loaded = true;
        break;
    }
}

if (!$loaded) {
    echo "❌ ERROR: Composer autoloader not found!\n";
    echo "Please run: composer require php-webdriver/webdriver\n";
    exit(1);
}

abstract class SimpleSeleniumTest
{
    protected $driver;
    protected $baseUrl = 'http://localhost:8080';
    
    public function __construct()
    {
        // Setup Chrome
        $options = new ChromeOptions();
        $options->addArguments(['--headless', '--no-sandbox', '--disable-dev-shm-usage']);
        
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        
        try {
            $this->driver = RemoteWebDriver::create('http://localhost:9515', $capabilities);
            echo "✓ Chrome WebDriver started\n";
        } catch (Exception $e) {
            echo "❌ ERROR: Could not connect to ChromeDriver on localhost:9515\n";
            echo "Please start ChromeDriver: chromedriver --port=9515\n";
            exit(1);
        }
        
        $this->driver->manage()->timeouts()->implicitlyWait(10);
    }
    
    protected function login($email = 'Charbel', $password = 'admin123')
    {
        echo "🔐 Logging in...\n";
        $this->driver->get($this->baseUrl . '/login');
        
        $this->driver->findElement(WebDriverBy::name('email'))->sendKeys($email);
        $this->driver->findElement(WebDriverBy::name('password'))->sendKeys($password);
        $this->driver->findElement(WebDriverBy::cssSelector('button[type="submit"]'))->click();
        
        sleep(2);
        echo "  ✓ Logged in\n";
    }
    
    public function run()
    {
        try {
            $this->login();
            $result = $this->test();
            $this->driver->quit();
            return $result;
        } catch (Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
            if ($this->driver) {
                $this->driver->quit();
            }
            return false;
        }
    }
    
    // Override this in child classes
    abstract protected function test();
}
