<?php
include 'i18n.php';
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
    <script type="text/javascript" src="./js/app.js"></script>
    <title><?php echo $translations['title']; ?></title>
</head>
<style>

</style>
<body>
    <main>
        <header>
            <?php
                $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
                $buttonText = $lang == 'fr' ? 'Fr' : 'En';
            ?>
                <div class="traduction-container">
                    <button class="traduction__button medium primary">
                        <span><?php echo $buttonText; ?></span>
                        <img src="./assets/svg/earth.svg" alt="Circle X Icon">
                    </button>
                    <ul class="traduction__list">
                        <li class="traduction__item">
                            <button class="traduction__item-button <?php echo $lang == 'fr' ? 'primary' : ''; ?>">Fr</button>
                        </li>
                        <li class="traduction__item">
                            <button class="traduction__item-button <?php echo $lang == 'en' ? 'primary' : ''; ?>">En</button>
                        </li>
                    </ul>
                </div>
        </header>
        <h1 class="uppercase"><?php echo $translations['heading']; ?></h1>
        <form action="../src/convert.php" method="post">
            <div class="line-container">
                <label for="input-count"><?php echo $translations['line']; ?> :</label>
                <input type="number" id="input-count" name="input-count" value="1" min="1" max="100" oninput="generateInputs()">
            </div>
            <div class="links">
                <div id="input-container" data-placeholder="<?php echo $translations['url_placeholder']; ?>"></div>
                <button type="submit" class="primary"><?php echo $translations['submit_button']; ?></button>
            </div>
        </form>
    </main>
</body>
</html>




