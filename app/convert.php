<?php
session_start();
include 'i18n.php';
include 'header.php';

$titlesAndLinks = [];

function updateProgress($progress) {
    $progressFile = 'progress.json';
    file_put_contents($progressFile, json_encode(['progress' => $progress]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urls = $_POST['url'] ?? [];
    $createdFiles = [];
    $totalUrls = count($urls);

    $_SESSION['progress'] = 0;
    updateProgress(0);

    session_write_close();

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
                $titlesAndLinks[] = ['title' => $title, 'link' => $url,'cleanTitle' => $cleanTitle];
            }
            
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
                        <input type="checkbox" class="input" data-file="<?php echo htmlspecialchars($item['file']); ?>" onclick="toggleCheckAll(event)">
                        <span class="custom-checkbox"></span>
                    </label>
                </th>
                <th> <?php echo $translations['name']; ?></th>
                <th> <?php echo $translations['link']; ?></th>
                <th>
                    <div>
                        <button class="primary" onclick="download()"><?php echo $translations['download']; ?></button>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($titlesAndLinks as $item): ?>
            <tr>
                <td>
                    <label>
                    
                        <input type="checkbox" class="input" data-file="<?php echo htmlspecialchars($item['cleanTitle']).'.mp3'; ?>" onclick="toggleCheck(event)">
                        <span class="custom-checkbox"></span>
                    </label>
                </td>
                <td>
                    <?php 
                    $maxLength = 60;
                    $title = htmlspecialchars($item['title']);
                    if (mb_strlen($title) > $maxLength) {
                        $title = mb_strimwidth($title, 0, $maxLength, '...');
                    }
                    echo $title;
                    ?>
                </td>
                <td>
                    <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank">
                        <?php echo $translations['openLink']; ?>
                        <img src="./assets/svg/square-arrow-out-up-right.svg">
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>