<?php
$directory = __DIR__ . '/downloads/';
$files = glob($directory . '*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
        echo "Fichier supprimé : $file\n";
    }
}
?>