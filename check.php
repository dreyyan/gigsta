<?php
echo "Loaded ini: " . php_ini_loaded_file() . "<br>";
echo "SQLite class exists: " . (class_exists('SQLite3') ? 'YES' : 'NO') . "<br>";
echo "Extension dir: " . ini_get('extension_dir');