<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
?>
header
<p><?= $lang['choose_language'] ?></p>
<a href="?lang=pl">Polski</a> | <a href="?lang=en">English</a>
