<?php

echo 'Loaded php.ini: ' . (php_ini_loaded_file() ?: '(none)') . PHP_EOL;
echo 'curl.cainfo: ' . (ini_get('curl.cainfo') ?: '(empty)') . PHP_EOL;
echo 'openssl.cafile: ' . (ini_get('openssl.cafile') ?: '(empty)') . PHP_EOL;
