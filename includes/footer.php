    <link rel="stylesheet" href="/../style/footer.css">

<footer class="footer">
  <div class="footer-container">

    <!-- Getting Started -->
    <div class="footer-column">
      <h3><?= $languageManager->t('getting_started'); ?></h3>
      <ul>
        <li><a href="/?page=download"><?= $languageManager->t('download'); ?></a></li>
        <li><a href="/?page=register"><?= $languageManager->t('create_account'); ?></a></li>
        <!-- <li><a href="/?page=how-to-play.php"> <?= $languageManager->t('how_to_play'); ?></a></li> -->
      </ul>
    </div>

    <!-- Resources -->
    <div class="footer-column">
      <h3><?= $languageManager->t('resources'); ?></h3>
      <ul>
        <!-- <li><a href="/?page=wiki.php"><?= $languageManager->t('game_wiki'); ?></a></li> -->
        <!-- <li><a href="/?page=blog.php"><?= $languageManager->t('dev_blog'); ?></a></li> -->
        <!-- <li><a href="/?page=media-kit.php"><?= $languageManager->t('media_kit'); ?></a></li> -->
      </ul>
    </div>

    <!-- Community -->
    <div class="footer-column">
      <h3><?= $languageManager->t('community'); ?></h3>
      <ul>
        <li><a href="https://discord.gg/TwojaGra" target="_blank">Discord</a></li>
        <li><a href="/forum.php">Forum</a></li>
        <li><a href="https://reddit.com/r/TwojaGra" target="_blank">Reddit</a></li>
      </ul>
    </div>

    <!-- Support -->
    <div class="footer-column">
      <h3><?= $languageManager->t('support'); ?></h3>
      <ul>
        <li><a href="/?page=help_center"><?= $languageManager->t('help_center'); ?></a></li>
        <li><a href="/?page=report_bug"><?= $languageManager->t('report_bug'); ?></a></li>
        <li><a href="/?page=contact"><?= $languageManager->t('contact_us'); ?></a></li>
        <li><a href="/?page=privacy"><?= $languageManager->t('privacy'); ?></a></li>
      </ul>
    </div>

    <!-- Solutions -->
    <div class="footer-column">
      <h3><?= $languageManager->t('solutions'); ?></h3>
      <ul>
        <!-- <li><a href="/?page=puzzle-guide.php"><?= $languageManager->t('puzzle_guide'); ?></a></li> -->
        <!-- <li><a href="/?page=walkthrough.php"><?= $languageManager->t('walkthrough'); ?></a></li> -->
        <!-- <li><a href="/?page=tips.php"><?= $languageManager->t('tips_tricks'); ?></a></li> -->
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    &copy; <?= date("Y") ?> TwojaGra. Wszelkie prawa zastrzeżone.
  </div>
</footer>
