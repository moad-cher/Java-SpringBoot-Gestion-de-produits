<?php
/**
 * Test de déconnexion - HTTP version matching DeconnexionTest.java
 */

require_once 'test-helpers.php';

$baseUrl = 'http://localhost:8080';
$cookieFile = 'cookies_logout.txt';

try {
    echo "🧪 Testing Logout...\n";
    
    // Login first
    echo "  ↳ Logging in...\n";
    if (!login($baseUrl, $cookieFile)) {
        throw new Exception("Login failed");
    }
    
    // Verify we're authenticated by accessing protected page
    $result = getWithAuth("$baseUrl/listeAvecCon", $cookieFile);
    $httpCode = $result['httpCode'];
    
    if ($httpCode != 200) {
        throw new Exception("Not authenticated properly (HTTP $httpCode)");
    }
    echo "  ✓ On authenticated page\n";
    // Get logout page to extract CSRF token
    $logoutPageResult = getWithAuth("$baseUrl/logout", $cookieFile);
    $csrfToken = '';
    if (preg_match('/<input[^>]*name=["\']_csrf["\'][^>]*value=["\']([^"\']+)["\']/', $logoutPageResult['response'], $matches)) {
        $csrfToken = $matches[1];
    }
    
    // POST logout with CSRF token
    $logoutData = $csrfToken ? ['_csrf' => $csrfToken] : [];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseUrl/logout");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($logoutData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_exec($ch);
    curl_close($ch);
    echo "  ✓ Logout submitted\n";
    
    // Verify logout by trying to access protected page
    $testResult = getWithAuth("$baseUrl/listeAvecCon", $cookieFile);
    $loggedOut = ($testResult['httpCode'] == 302) || (strpos($testResult['response'], 'Se connecter') !== false);
    
    if (!$loggedOut) {
        throw new Exception("Still authenticated after logout");
    }
    echo "  ✓ Successfully logged out\n";
    
    @unlink('cookies_logout.txt');
    echo "  ✅ TEST PASSED - Logged out successfully!\n";
    exit(0);
    
} catch (Exception $e) {
    @unlink('cookies_logout.txt');
    echo "  ❌ TEST FAILED: " . $e->getMessage() . "\n";
    exit(1);
}
