<nav class="main-nav">
  <ul>
    <li><a href="/?page=home"><?= $languageManager->t('home'); ?></a></li>
    <li><a href="/?page=download"><?= $languageManager->t('download_game'); ?></a></li>
    <li><a href="/?page=event"><?= $languageManager->t('event'); ?></a></li>
    <li><a href="/?page=shop"><?= $languageManager->t('shop'); ?></a></li>
    <li><a href="/?page=contact"><?= $languageManager->t('contact'); ?></a></li>
<?php
	if($session->has('username') )
	{ ?>
		<li><a href="/?page=dashboard"><?= $languageManager->t('dashboard'); ?></a></li>
	<?php } ?>

  </ul>
</nav>