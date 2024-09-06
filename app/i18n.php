<?php
function loadTranslations($lang) {
    $file = __DIR__ . "/locales/{$lang}.php";
    if (file_exists($file)) {
        return include $file;
    }
    return [];
}

$lang = 'fr';

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + (3600 * 24 * 30));
} elseif (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
}

$translations = loadTranslations($lang);