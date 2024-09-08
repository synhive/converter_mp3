<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urls = $_POST['url'] ?? [];
    $createdFiles = [];
    $totalUrls = count($urls);

    $_SESSION['progress'] = 0;

    session_write_close();

    function updateProgress($progress) {
        session_start();
        $_SESSION['progress'] = $progress;
        session_write_close();
    }

    foreach ($urls as $index => $url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $progress = intval(($index + 1) / $totalUrls * 100);
            updateProgress($progress);

            $titleCommand = "yt-dlp --get-title $url";
            $title = shell_exec($titleCommand);
            $title = trim((string) $title);
            $cleanTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $title);
            $outputFile = "downloads/{$cleanTitle}.mp3";
            $command = "yt-dlp -x --audio-format mp3 -o '$outputFile' $url";
            shell_exec($command);

            if (file_exists($outputFile)) {
                $createdFiles[] = $outputFile;
            }
        }
    }

    if (!empty($createdFiles)) {
        $zipFile = 'downloads/all_files.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($createdFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }
    }

    updateProgress(100);

    echo "Conversion terminée. Téléchargez les fichiers ci-dessous :<br>";
    foreach ($createdFiles as $file) {
        echo "<a href='{$file}' download>Télécharger " . basename($file) . "</a><br>";
    }
    if (!empty($createdFiles)) {
        echo "<br><a href='{$zipFile}' download>Télécharger tous les fichiers ici</a>";
    }
}
?>