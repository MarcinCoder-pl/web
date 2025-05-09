<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
        die('Brak dostępu.');
    }
    else
    {
		define('LINK' ,'index.php?strona=');
	}
?>
<footer>
    <p>&copy; 2023–2025 Moja Strona. Wszystkie prawa zastrzeżone.</p>

    <div class="footer-navs">
        <nav aria-label="Główne menu">
            <ul>
                <li><a href="<?= LINK ?>home"><?= $lang['home'] ?></a></li>
                <li><a href="<?= LINK ?>download"><?= $lang['download'] ?></a></li>
                <li><a href="<?= LINK ?>forum"><?= $lang['forum'] ?></a></li>
                <li><a href="<?= LINK ?>shop"><?= $lang['shop'] ?></a></li>
                <li><a href="<?= LINK ?>event"><?= $lang['event'] ?></a></li>
                <li><a href="<?= LINK ?>contact"><?= $lang['contact'] ?></a></li>
                <li><a href="<?= LINK ?>reate_account"><?= $lang['create_account'] ?></a></li>
                <li><a href="<?= LINK ?>login"><?= $lang['login'] ?></a></li>
                <li><a href="<?= LINK ?>messages"><?= $lang['messages'] ?></a></li>
                <li><a href="<?= LINK ?>code_execution"><?= $lang['code_execution'] ?></a></li>
            </ul>
        </nav>

        <nav aria-label="Media społecznościowe">
            <ul>
                <li><a href="https://instagram.com" target="_blank" rel="noopener">Instagram</a></li>
                <li><a href="https://youtube.com" target="_blank" rel="noopener">YouTube</a></li>
                <li><a href="https://twitch.tv" target="_blank" rel="noopener">Twitch</a></li>
                <li><a href="https://facebook.com" target="_blank" rel="noopener">Facebook</a></li>
                <li><a href="https://discord.com" target="_blank" rel="noopener">Discord</a></li>
                <li><a href="https://tiktok.com" target="_blank" rel="noopener">TikTok</a></li>
            </ul>
        </nav>

        <nav aria-label="Informacje prawne">
            <ul>
                <li><a href="<?= LINK ?>polityka-prywatnosci"><?= $lang['privacy_policy'] ?></a></li>
                <li><a href="<?= LINK ?>prawa-autorskie"><?= $lang['copyright'] ?></a></li>
                <li><a href="<?= LINK ?>warunki-swiadczenia-uslug"><?= $lang['service_agreement'] ?></a></li>
                <li><a href="<?= LINK ?>o-grze"><?= $lang['about_the_game'] ?></a></li>
                <li><a href="<?= LINK ?>o-tworcy"><?= $lang['about_the_developer'] ?></a></li>
                <li><a href="<?= LINK ?>wsparcie"><?= $lang['support'] ?></a></li>
            </ul>
        </nav>
    </div>
</footer>
