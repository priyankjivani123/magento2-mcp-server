<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = "PriyankJivani\\McpServer\\";
    if (strpos($class, $prefix) === 0) {
        $relative = substr($class, strlen($prefix));
        $file = __DIR__ . '/../src/' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
