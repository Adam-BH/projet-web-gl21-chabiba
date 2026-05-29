<?php
spl_autoload_register(function($className) {
    $file = __DIR__ . '/src/Classes/' . $className . '.php';
    if (file_exists($file)) {
        include_once $file;
    }
});
