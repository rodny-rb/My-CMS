<?php
/**
 * Application configuration.
 * Update these values for your environment.
 */

date_default_timezone_set('UTC');

const APP_NAME = 'My CMS';
const APP_EMAIL = 'hello@example.com';
const BASE_URL = 'http://localhost';

const DB_HOST = 'localhost';
const DB_NAME = 'my_cms';
const DB_USER = 'root';
const DB_PASS = '';

function appUrl(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');

    return $path === '' ? $base : $base . '/' . $path;
}

function redirect(string $path): void
{
    header('Location: ' . appUrl($path));
    exit;
}
