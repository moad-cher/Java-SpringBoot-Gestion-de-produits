<?php
/**
 * Run all simple tests
 */

echo "========================================\n";
echo "  Running All Selenium PHP Tests\n";
echo "========================================\n\n";

$tests = [
    'simple-test-ajout.php' => 'Product Creation',
    'simple-test-listing.php' => 'Product Listing',
    'simple-test-modification.php' => 'Product Modification',
    'simple-test-deconnexion.php' => 'Logout',
];

$results = [];

foreach ($tests as $file => $name) {
    echo "\n--- Running: $name ---\n";
    $output = [];
    $returnCode = 0;
    $filePath = __DIR__ . DIRECTORY_SEPARATOR . $file;
    exec("php \"$filePath\"", $output, $returnCode);
    
    foreach ($output as $line) {
        echo $line . "\n";
    }
    
    $results[$name] = ($returnCode === 0);
}

echo "\n========================================\n";
echo "  Test Summary\n";
echo "========================================\n";

$passed = 0;
$failed = 0;

foreach ($results as $test => $result) {
    if ($result) {
        echo "✅ PASSED: $test\n";
        $passed++;
    } else {
        echo "❌ FAILED: $test\n";
        $failed++;
    }
}

echo "\nTotal: " . ($passed + $failed) . " tests\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

exit($failed > 0 ? 1 : 0);
