<?php

declare(strict_types=1);

/**
 * Loads environment variables from .env file
 * Defaults are loaded from .env.example
 */

function loadEnv(string $envFile, string $exampleFile): array
{
    $env = [];

    // Load defaults from .env.example first
    if (file_exists($exampleFile)) {
        $env = parseEnvFile($exampleFile);
    }

    // Override with .env values if it exists
    if (file_exists($envFile)) {
        $env = array_merge($env, parseEnvFile($envFile));
    }

    return $env;
}

function parseEnvFile(string $filePath): array
{
    $env = [];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes if present
            if (($value[0] ?? null) === '"' && substr($value, -1) === '"') {
                $value = substr($value, 1, -1);
            }

            if (!empty($key)) {
                $env[$key] = $value;
            }
        }
    }

    return $env;
}

return loadEnv(__DIR__ . '/.env', __DIR__ . '/.env.example');
