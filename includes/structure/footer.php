<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
?>
<footer>
    <p>&copy; 2023–2025 Moja Strona. Wszystkie prawa zastrzeżone.</p>
<div class="footer-navs">
    <nav aria-label="Główne menu">
        <ul>
            <li><a href="/wiadomosci">Wiadomości</a></li>
            <li><a href="/sezon">Sezon</a></li>
            <li><a href="/sklep">Sklep internetowy</a></li>
            <li><a href="/realizacja-kodow">Realizacja kodów</a></li>
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
            <li><a href="/polityka-prywatnosci">Polityka prywatności</a></li>
            <li><a href="/prawa-autorskie">Ochrona praw autorskich</a></li>
            <li><a href="/regulamin">Warunki świadczenia usług</a></li>
        </ul>
    </nav>
</div>
</footer>
