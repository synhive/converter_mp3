<?php
include 'i18n.php';
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
    $buttonText = $lang == 'fr' ? 'Fr' : 'En';
?>
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