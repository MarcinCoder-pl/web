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
			<li><a href="index.php?strona=home">Home</a></li>
			<li><a href="index.php?strona=download">Download</a></li>
			<li><a href="index.php?strona=forum">Forum</a></li>
			<li><a href="index.php?strona=shop"><?= $lang['shop'] ?></a></li>
			<li><a href="index.php?strona=event">Event</a></li>
			<li><a href="index.php?strona=contact">Contact</a></li>
	<?php if (!isset($_SESSION['username'])) { ?>
			<li><a href="index.php?strona=register">Register</a></li>
			<li><a href="index.php?strona=login">Login</a></li>
	<?php } 
	else
	{	?>
	<li><a href="index.php?strona=dashboard">Dashboard</a></li>
	<?php } ?>



			<!-- Możesz dodać więcej linków do innych stron -->
		</ul>
	</nav>
</div>

