
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
        <div id="loader" style="display: none;">Chargement...</div>
        <div id="progress-container" style="display: none;">
            <progress id="progress-bar" value="0" max="100"></progress>
            <span id="progress-text">Progression : 0%</span>
        </div>
    <?php include 'footer.php'; ?>