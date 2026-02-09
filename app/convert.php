<?php
session_start();
include 'i18n.php';
include 'header.php';

$titlesAndLinks = [];

function updateProgress($progress)
{
    $progressFile = 'progress.json';
    file_put_contents($progressFile, json_encode(['progress' => $progress]));
}

// Fonction pour obtenir un User-Agent aléatoire
function getRandomUserAgent()
{
    $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:133.0) Gecko/20100101 Firefox/133.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36',
    ];
    return $userAgents[array_rand($userAgents)];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urls = $_POST['url'] ?? [];
    $createdFiles = [];
    $totalUrls = count($urls);

    $_SESSION['progress'] = 0;
    updateProgress(0);

    session_write_close();

    $userAgent = getRandomUserAgent();

    foreach ($urls as $index => $url) {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            // Ajouter un délai entre chaque téléchargement (sauf pour le premier)
            if ($index > 0) {
                sleep(2); // Pause de 2 secondes entre chaque vidéo
            }

            $progress = intval(($index + 1) / $totalUrls * 100);
            updateProgress($progress);

            // LOG: Répertoire de travail
            $cwd = getcwd();
            error_log("Répertoire de travail: $cwd");

            // Mode par défaut de yt-dlp (plus compatible)
            $titleCommand = "yt-dlp " .
                "--user-agent " . escapeshellarg($userAgent) . " " .
                "--get-title " . escapeshellarg($url) . " 2>&1";

            $titleOutput = shell_exec($titleCommand);

            // Filtrer les warnings et ne garder que le titre (dernière ligne non vide)
            $lines = array_filter(explode("\n", $titleOutput), function ($line) {
                return !empty(trim($line)) && strpos($line, 'WARNING:') === false && strpos($line, 'ERROR:') === false;
            });
            $title = trim(end($lines));

            // LOG: Titre récupéré
            error_log("Titre récupéré pour $url: $title");

            // Gestion des erreurs
            if (empty($title)) {
                error_log("ERREUR titre - URL: $url | Sortie complète: $titleOutput");
                continue;
            }

            $cleanTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $title);
            $outputFile = "downloads/{$cleanTitle}.mp3";
            $absoluteOutputFile = $cwd . "/" . $outputFile;

            // LOG: Chemin du fichier de sortie
            error_log("Chemin de sortie: $absoluteOutputFile");

            // Commande de téléchargement optimisée
            $command = "yt-dlp " .
                "--user-agent " . escapeshellarg($userAgent) . " " .
                "--no-check-certificates " .
                "--sleep-interval 1 " .
                "--max-sleep-interval 3 " .
                "-x --audio-format mp3 " .
                "-o " . escapeshellarg($absoluteOutputFile) . " " .
                escapeshellarg($url) . " 2>&1";

            // LOG: Commande complète
            error_log("Commande: $command");

            $output = shell_exec($command);

            // Log complet pour debug
            error_log("yt-dlp output complet pour $url: " . $output);

            if (file_exists($absoluteOutputFile)) {
                $createdFiles[] = $outputFile;
                $titlesAndLinks[] = ['title' => $title, 'link' => $url, 'cleanTitle' => $cleanTitle];
                error_log("SUCCESS: Fichier créé - $absoluteOutputFile");
            } else {
                error_log("ERREUR: Fichier non créé pour: $url - Vérifier $absoluteOutputFile");
            }
        }
    }

    updateProgress(100);
}

?>
<h1 id="convert"><?php echo $translations["download_end"]; ?></h1>
<div class="card result">
    <div>
        <button class="primary mobile" onclick="download()"><?php echo $translations['download']; ?></button>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>
                        <label>
                            <input type="checkbox" class="input" data-file="<?php echo htmlspecialchars($item['file'] ?? ''); ?>" onclick="toggleCheckAll(event)">
                            <span class="custom-checkbox"></span>
                        </label>
                    </th>
                    <th><?php echo $translations['name']; ?></th>
                    <th><?php echo $translations['link']; ?></th>
                    <th>
                        <div>
                            <button class="primary" onclick="download()"><?php echo $translations['download']; ?></button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($titlesAndLinks)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">
                            <?php echo $translations['no_downloads'] ?? 'Aucun téléchargement réussi. Veuillez réessayer.'; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($titlesAndLinks as $item): ?>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="input" data-file="<?php echo htmlspecialchars($item['cleanTitle']) . '.mp3'; ?>" onclick="toggleCheck(event)">
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
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>