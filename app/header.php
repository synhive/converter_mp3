<?php
    $lang = $_SESSION['lang'] ?? 'fr';

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
        $_SESSION['lang'] = $lang;
    }
    $buttonText = $lang == 'fr' ? 'FR' : 'EN';
?>
<header>

    <div class="traduction-container">
        <button class="traduction__button medium primary" onclick="toggleTraductionList()">
            <span><?php echo $buttonText; ?></span>
            <img src="./assets/svg/earth.svg" alt="Circle X Icon">
        </button>
        <ul class="traduction__list" id="traduction-list">
            <li class="traduction__item">
                <button class="traduction__item-button <?php echo $lang == 'fr' ? 'primary' : ''; ?>" onclick="changeLanguage('fr')">FR</button>
            </li>
            <li class="traduction__item">
                <button class="traduction__item-button <?php echo $lang == 'en' ? 'primary' : ''; ?>" onclick="changeLanguage('en')">EN</button>
            </li>
        </ul>
    </div>


</header>