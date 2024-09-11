<?php
include 'i18n.php';
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
    $buttonText = $lang == 'fr' ? 'Fr' : 'En';
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
    <style>
        .loading-bar-container {
            display: none;
            width: 100%;
            background-color: #f3f3f3;
            margin-top: 20px;
        }
        .loading-bar {
            width: 0;
            height: 30px;
            background-color: #3498db;
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <main>
        <header>
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
        