<?php
/**
 * Test d'ajout de produit - HTTP version matching AjoutProduitTest.java
 */

require_once 'test-helpers.php';

$baseUrl = 'http://localhost:8080';
$cookieFile = 'cookies_ajout.txt';

try {
    echo "🧪 Testing Product Creation...\n";
    
    // Login first
    echo "  ↳ Logging in...\n";
    if (!login($baseUrl, $cookieFile)) {
        throw new Exception("Login failed");
    }
    echo "  ✓ Logged in\n";
    
    // Navigate to creation page to verify form exists
    $result = getWithAuth("$baseUrl/creation/", $cookieFile);
    $response = $result['response'];
    $httpCode = $result['httpCode'];
    
    if ($httpCode != 200 || strpos($response, '<form') === false) {
        throw new Exception("Creation page not accessible or form not found");
    }
    echo "  ✓ Navigated to creation page\n";
    echo "  ✓ Form found\n";
    
    // Extract CSRF token from form
    $csrfToken = '';
    if (preg_match('/<input[^>]*name=["\']_csrf["\'][^>]*value=["\']([^"\']+)["\']/', $response, $matches)) {
        $csrfToken = $matches[1];
    } elseif (preg_match('/<input[^>]*value=["\']([^"\']+)["\'][^>]*name=["\']_csrf["\']/', $response, $matches)) {
        $csrfToken = $matches[1];
    }
    
    // Fill and submit form
    echo "  ↳ Filling form...\n";
    $formData = [
        'nom' => 'Test Produit Selenium',
        'prix' => '99',
        'devise' => 'EUR',
        'taxe' => '20',
        'dateExpiration' => '2025-12-31',
        'fournisseur' => 'Test Fournisseur'
    ];
    
    if ($csrfToken) {
        $formData['_csrf'] = $csrfToken;
    }
    
    echo "  ✓ Form filled\n";
    
    // Submit form
    echo "  ↳ Submitting form...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseUrl/create");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($formData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_exec($ch);
    curl_close($ch);
    echo "  ✓ Form submitted\n";
    
    // Verify product was created
    $listResult = getWithAuth("$baseUrl/listeAvecCon", $cookieFile);
    if (strpos($listResult['response'], 'Test Produit Selenium') === false) {
        throw new Exception("Product not found in list after creation");
    }
    echo "  ✓ Product created and appears in list\n";
    
    @unlink('cookies_ajout.txt');
    echo "  ✅ TEST PASSED - Product created!\n";
    exit(0);
    
} catch (Exception $e) {
    @unlink('cookies_ajout.txt');
    echo "  ❌ TEST FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
