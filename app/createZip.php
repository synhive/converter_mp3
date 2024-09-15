<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $files = $_POST['files'] ?? [];
    if (!empty($files)) {
        $zipFile = 'downloads/all_files.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $zip->addFile($file, basename($file));
                }
            }
            $zip->close();
        } 
    }
}
?>