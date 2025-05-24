<?php
if (!defined('ACCESS')) {
    die('Brak dostępu.');
}
?>

<div class="navigation-navs">
    <nav>
        <ul>
            <li><a href="index.php?strona=home&znak=welcome"><?= htmlspecialchars($lang['home']) ?></a></li>
            <li><a href="index.php?strona=home&znak=download"><?= htmlspecialchars($lang['download']) ?></a></li>
            <li><a href="index.php?strona=event"><?= htmlspecialchars($lang['event']) ?></a></li>
            <li><a href="index.php?strona=forum"><?= htmlspecialchars($lang['forum']) ?></a></li>
            <li><a href="index.php?strona=shop"><?= htmlspecialchars($lang['shop']) ?></a></li>
            <li><a href="index.php?strona=contact"><?= htmlspecialchars($lang['contact']) ?></a></li>
            <?php if (!isset($_SESSION['username'])): ?>
                <li><a href="index.php?strona=register"><?= htmlspecialchars($lang['create_account']) ?></a></li>
                <li><a href="index.php?strona=login"><?= htmlspecialchars($lang['login']) ?></a></li>
            <?php else: ?>
                <li><a href="index.php?strona=dashboard"><?= htmlspecialchars($lang['dashboard']) ?></a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
