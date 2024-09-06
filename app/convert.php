<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $titleCommand = "yt-dlp --get-title $url";
            $title = shell_exec($titleCommand);
            $title = trim((string) $title);
            $cleanTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $title);
            $outputFile = "downloads/{$cleanTitle}.mp3";
            $command = "yt-dlp -x --audio-format mp3 -o '$outputFile' $url";
            shell_exec($command);

            if (file_exists($outputFile)) {
                echo "Le fichier <a href='{$outputFile}' download>{$outputFile}</a> a été créé avec succès.<br>";
            } else {
                echo "Le fichier pour l'URL {$url} n'a pas pu être créé.<br>";
            }
        } else {
            echo "URL invalide : {$url}.<br>";
        }
    }
?>