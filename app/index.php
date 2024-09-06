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
    <title><?php echo $translations['title']; ?></title>
</head>
<body>
    <main>
        <h1><?php echo $translations['heading']; ?></h1>
        <form action="convert.php" method="post">
            <input type="text" placeholder="<?php echo $translations['url_placeholder']; ?>" id="url" name="url" required>
            <input type="submit" value="<?php echo $translations['submit_button']; ?>">
        </form>
        <form method="get" action="">
            <label for="lang">Choisir la langue :</label>
            <select name="lang" id="lang" onchange="this.form.submit()">
                <option value="fr" <?php echo $lang == 'fr' ? 'selected' : ''; ?>>Français</option>
                <option value="en" <?php echo $lang == 'en' ? 'selected' : ''; ?>>English</option>
            </select>
        </form>
    </main>
</body>
</html>