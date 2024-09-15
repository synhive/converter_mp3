<?php
session_start();
include 'header.php';

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
                $createdFiles[] = [
                    'title' => $title,
                    'url' => $outputFile
                ];
            }
        }
    }

    if (!empty($createdFiles)) {
        $zipFile = 'downloads/all_files.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($createdFiles as $file) {
                $zip->addFile($file['url'], basename($file['url']));
            }
            $zip->close();
        }
    }

    updateProgress(100);
}
?>
<h1 id="convert"><?php echo $translations["download_end"];?></h1>
<div class="card result">
    <table>
        <thead>
            <tr>
                <th>
                    <label>
                        <input type="checkbox" class="input">
                        <span class="custom-checkbox"></span>
                    </label>
                </th>
                <th>Nom</th>
                <th>Lien</th>
                <th>
                    <div>
                        <button class="primary">Télécharger</button>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($createdFiles)): ?>
                <?php foreach ($createdFiles as $file): ?>
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" class="input">
                                <span class="custom-checkbox"></span>
                            </label>
                        </td>
                        <td><?php echo htmlspecialchars($file['title']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($file['url']); ?>" download><?php echo htmlspecialchars($file['url']); ?></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun fichier créé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>