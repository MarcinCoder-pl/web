<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
?>
<div class="navigation-navs">
<p><?= $lang['choose_language'] ?></p>

<a href="?lang=en">
	<img src="/../../image/flag/united-states-of-america.png" alt="USA Flag" width="32" height="32">
</a>

<a href="?lang=pl">
	<img src="/../../image/flag/poland.png" alt="Poland Flag" width="32" height="32">
</a>

<a href="?lang=cz">
	<img src="/../../image/flag/czech-republic.png" alt="Czech Republic Flag" width="32" height="32">
</a>

</div>
