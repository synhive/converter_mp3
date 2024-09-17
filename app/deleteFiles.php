<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $files = $data['files'] ?? [];

    foreach ($files as $file) {
        $filePath = __DIR__ . '/downloads/' . basename($file);
        if (file_exists($filePath)) {
            unlink($filePath);
            echo "Fichier supprimé : $filePath\n";
        } else {
            echo "Fichier non trouvé : $filePath\n";
        }
    }
} else {
    echo 'Requête non valide.';
}
?>