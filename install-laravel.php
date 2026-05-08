<?php

$projectRoot = dirname(__FILE__);
chdir($projectRoot);
echo "\n";
echo "======================================\n";
echo "UniClub Hub - Laravel Manual Installer\n";
echo "======================================\n";
echo "\n";

echo "Checking PHP extensions...\n";
$extensions = ['curl', 'openssl', 'pdo', 'pdo_mysql', 'json'];
$missingExtensions = [];

foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "  ✓ $ext\n";
    } else {
        echo "  ✗ $ext (MISSING)\n";
        $missingExtensions[] = $ext;
    }
}

if (!empty($missingExtensions)) {
    echo "\nWARNING: Missing extensions: " . implode(', ', $missingExtensions) . "\n";
}

echo "\nChecking for vendor directory...\n";
if (is_dir('vendor')) {
    echo "  ✓ Vendor directory exists\n";
    if (is_file('vendor/autoload.php')) {
        echo "  ✓ Autoloader found\n";
        echo "\n[SUCCESS] Laravel appears to be already installed!\n\n";
        exit(0);
    }
}

echo "\nAttempting Composer install...\n";
$output = shell_exec('composer install --no-dev --no-interaction 2>&1');

if (strpos($output, 'error') === false && is_file('vendor/autoload.php')) {
    echo "[SUCCESS] Composer install succeeded!\n";
    exit(0);
} else {
    echo "[WARNING] Composer install had issues:\n";
    echo $output . "\n";
}

echo "\nCreating minimal Laravel structure...\n";
@mkdir('vendor/autoload.php', 0755, true);

echo "[PARTIAL] Basic structure created. Run 'composer install' manually.\n";

?>
