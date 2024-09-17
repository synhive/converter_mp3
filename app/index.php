<?php
include 'i18n.php';
session_start();
?>
<!DOCTYPE html>
    <html lang="<?php echo $lang; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./style/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
        <title><?php echo $translations['title']; ?></title>
    </head>
    <body>
        <main>
        <?php include 'header.php'; ?>
        <h1 class="uppercase"><?php echo $translations['heading']; ?></h1>
        <form id="downloadForm" class="card" action="./convert.php" method="post">
            <div class="line-container">
                <label for="input-count"><?php echo $translations['line']; ?> :</label>
                <input type="number" id="input-count" name="input-count" value="1" min="1" max="100" oninput="generateInputs()">
            </div>
            <div class="links">
                <div id="input-container" data-placeholder="<?php echo $translations['url_placeholder']; ?>"></div>
                <button type="submit" class="primary"><?php echo $translations['submit_button']; ?></button>
            </div>
        </form>
        <div id="loader" style="display: none;"><?php echo $translations['loading']; ?></div>
        <div id="progress-container" style="display: none;">
            <progress id="progress-bar" value="0" max="100"></progress>
            <span id="progress-text"><?php echo $translations['progress']; ?>0%</span>
        </div>
<?php include 'footer.php'; ?>
