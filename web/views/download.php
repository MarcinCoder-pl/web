<?php
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

$gameFile = '/downloads/mojagra.zip'; // Ścieżka względna do pliku gry
?>

<div class="download-container">
    <h1><?= $languageManager->t('download_title') ?></h1>
    <p><?= $languageManager->t('download_description') ?></p>

    <a class="download-button" href="<?= $gameFile ?>" download>
        <?= $languageManager->t('download_button') ?>
    </a>
</div>
