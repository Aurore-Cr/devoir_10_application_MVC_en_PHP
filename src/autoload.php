<?php

declare(strict_types=1);

/**
 * Autoloader PSR-4 minimaliste pour le namespace `App\`.:
 * public/index.php essaie composer en premier et se rabat sur celui-ci.
 */

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
