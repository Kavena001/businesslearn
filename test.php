<?php
header('Content-Type: text/plain');
echo "PHP Test:\n\n";
echo "1. PHP Version: ".phpversion()."\n";
echo "2. System: ".php_uname()."\n";
echo "3. Memory: ".ini_get('memory_limit')."\n";
echo "4. Extensions: ".implode(", ", get_loaded_extensions());