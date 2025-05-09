<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
?>
<!-- structure/nav.php -->
<div class="navigation-navs">
	<nav>
		<ul>
			<li><a href="index.php?strona=home"><?= $lang['home'] ?></a></li>
			<li><a href="index.php?strona=download"><?= $lang['download'] ?></a></li>
			<li><a href="index.php?strona=forum"><?= $lang['forum'] ?></a></li>
			<li><a href="index.php?strona=shop"><?= $lang['shop'] ?></a></li>
			<li><a href="index.php?strona=event"><?= $lang['event'] ?></a></li>
			<li><a href="index.php?strona=contact"><?= $lang['contact'] ?></a></li>
	<?php if (!isset($_SESSION['username'])) { ?>
			<li><a href="index.php?strona=register"><?= $lang['create_account'] ?></a></li>
			<li><a href="index.php?strona=login"><?= $lang['login'] ?></a></li>
	<?php } 
	else
	{	?>
	<li><a href="index.php?strona=dashboard"><?= $lang['home'] ?></a></li>
	<?php } ?>



			<!-- Możesz dodać więcej linków do innych stron -->
		</ul>
	</nav>
</div>

