<?php
/**
 * Helper functions for HTTP-based Selenium tests
 */

function login($baseUrl, $cookieFile) {
    // First, get the login page to extract CSRF token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseUrl/login");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $loginPage = curl_exec($ch);
    curl_close($ch);
    
    // Extract CSRF token if present
    $csrfToken = '';
    if (preg_match('/<input[^>]*name=["\']_csrf["\'][^>]*value=["\']([^"\']+)["\']/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
    } elseif (preg_match('/<input[^>]*value=["\']([^"\']+)["\'][^>]*name=["\']_csrf["\']/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
    }
    
    // Perform login POST
    $postData = [
        'email' => 'Charbel',
        'password' => 'admin123'
    ];
    
    if ($csrfToken) {
        $postData['_csrf'] = $csrfToken;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$baseUrl/login");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode == 200;
}

function getWithAuth($url, $cookieFile) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    
    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'url' => $effectiveUrl
    ];
}

function postWithAuth($url, $data, $cookieFile, $followRedirect = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followRedirect);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);
    
    return [
        'response' => $response,
        'httpCode' => $httpCode,
        'redirectUrl' => $redirectUrl
    ];
}
