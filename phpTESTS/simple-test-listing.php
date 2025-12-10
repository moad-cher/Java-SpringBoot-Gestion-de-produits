<?php
/**
 * Test du listing des produits - HTTP version matching ListingProduitTest.java
 */

require_once 'test-helpers.php';

$baseUrl = 'http://localhost:8080';
$cookieFile = 'cookies_listing.txt';

try {
    echo "🧪 Testing Product Listing...\n";
    
    // Login first
    echo "  ↳ Logging in...\n";
    if (!login($baseUrl, $cookieFile)) {
        throw new Exception("Login failed");
    }
    
    // Navigate to product list
    $result = getWithAuth("$baseUrl/listeAvecCon", $cookieFile);
    $response = $result['response'];
    $httpCode = $result['httpCode'];
    
    if ($httpCode != 200) {
        throw new Exception("Failed to access product list (HTTP $httpCode)");
    }
    echo "  ✓ Navigated to product list\n";
    
    // Verify table exists
    if (strpos($response, '<table') === false) {
        throw new Exception("La table des produits doit exister");
    }
    echo "  ✓ Table found\n";
    
    // Verify at least one product row exists
    preg_match_all('/<tbody[^>]*>.*?<\/tbody>/s', $response, $tbody);
    if (empty($tbody[0])) {
        throw new Exception("Table body not found");
    }
    preg_match_all('/<tr[^>]*>/', $tbody[0][0], $rows);
    if (count($rows[0]) === 0) {
        throw new Exception("La table doit contenir au moins un produit");
    }
    echo "  ✓ Found " . count($rows[0]) . " product(s)\n";
    
    // Verify at least 3 columns
    preg_match_all('/<thead[^>]*>.*?<\/thead>/s', $response, $thead);
    if (!empty($thead[0])) {
        preg_match_all('/<th[^>]*>/', $thead[0][0], $headers);
        if (count($headers[0]) < 3) {
            throw new Exception("La table doit avoir au moins 3 colonnes");
        }
    }
    
    @unlink('cookies_listing.txt');
    echo "  ✅ TEST PASSED - Product list displayed!\n";
    exit(0);
    
} catch (Exception $e) {
    @unlink('cookies_listing.txt');
    echo "  ❌ TEST FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
