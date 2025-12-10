<?php
/**
 * Test de modification de produit - HTTP version matching ModificationProduitTest.java
 */

require_once 'test-helpers.php';

$baseUrl = 'http://localhost:8080';
$cookieFile = 'cookies_mod.txt';

try {
    echo "🧪 Testing Product Modification...\n";
    
    // Login first
    echo "  ↳ Logging in...\n";
    if (!login($baseUrl, $cookieFile)) {
        throw new Exception("Login failed");
    }
    
    // Get product list and find modification link
    $result = getWithAuth("$baseUrl/listeAvecCon", $cookieFile);
    if (!preg_match('/href="([^"]*\/maj\/[^"]*)"/i', $result['response'], $matches)) {
        throw new Exception("No modification link found");
    }
    echo "  ✓ Found modify link\n";
    
    // Access modification page
    $modifyUrl = str_starts_with($matches[1], 'http') ? $matches[1] : $baseUrl . $matches[1];
    $result = getWithAuth($modifyUrl, $cookieFile);
    
    if (strpos($result['url'], '/maj/') === false || strpos($result['response'], '<form') === false) {
        throw new Exception("Modification page not accessible");
    }
    echo "  ✓ Modification page accessible with form\n";
    
    @unlink('cookies_mod.txt');
    echo "  ✅ TEST PASSED - Can access product modification!\n";
    exit(0);
    
} catch (Exception $e) {
    @unlink('cookies_mod.txt');
    echo "  ❌ TEST FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
