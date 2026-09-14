<?php

$requiredPaths = [
    __DIR__ . '/../index.php',
    __DIR__ . '/../admin',
    __DIR__ . '/../donors',
    __DIR__ . '/../blood_stock',
    __DIR__ . '/../database',
];

foreach ($requiredPaths as $path) {
    if (!file_exists($path)) {
        fwrite(STDERR, "Missing required project path: $path\n");
        exit(1);
    }
}

echo "Blood Bank Management project structure test passed.\n";
