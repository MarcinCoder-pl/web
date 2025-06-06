<h2>Witaj w panelu użytkownika</h2>
<p>Cześć, <strong><?= htmlspecialchars($username ?? 'Użytkowniku') ?></strong>! Miło Cię widzieć.</p>

<ul>
    <li><a href="/?page=profile">Twój profil</a></li>
    <li><a href="/?page=settings">Ustawienia konta</a></li>
    <li><a href="/../tools/user_core/user_logout.php">Wyloguj się</a></li>
</ul>

<hr>
<h3>Twoje prawa RODO</h3>

<?php if ($exportedData): ?>
    <h4>📤 Dane Twojej sesji:</h4>
    <pre><?= htmlspecialchars(json_encode($exportedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">
    <button type="submit" name="gdpr_action" value="export">...</button>
    <button type="submit" name="gdpr_action" value="delete" ...>...</button>
</form>

