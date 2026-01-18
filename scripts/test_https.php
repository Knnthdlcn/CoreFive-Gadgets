<?php

$url = 'https://www.google.com';
$data = @file_get_contents($url);
if ($data === false) {
    $error = error_get_last();
    fwrite(STDERR, "HTTPS fetch failed: " . ($error['message'] ?? 'unknown') . PHP_EOL);
    exit(1);
}

echo "HTTPS fetch OK, bytes=" . strlen($data) . PHP_EOL;
