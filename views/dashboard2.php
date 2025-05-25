<?php if (!defined('ACCESS')) die('Brak dostępu.'); ?>

<!-- DEBUG: dane sesji -->
<pre>
<?php print_r($sessionData); ?>
</pre>

<!-- Status wiadomości -->
<?php if ($sendStatus === 'ok'): ?>
    <p><?= htmlspecialchars($lang['send']) ?></p>
<?php elseif ($sendStatus === 'nok'): ?>
    <p><?= htmlspecialchars($lang['sendnok']) ?></p>
<?php endif; ?>

<!-- Główna zawartość -->
<div class="post-container">
    <br>dashboard<br>
</div>

<!-- Formularz wysyłania wiadomości -->
<?php include_once __DIR__ . '/../tools/send_message.html'; ?>

<!-- Formularz wylogowania -->
<form method="post" action="../tools/logout.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <button type="submit" name="logout" value="logout">Wyloguj się</button>
</form>
<?php if (!defined('ACCESS')) die('Brak dostępu.'); ?>

<!-- DEBUG: dane sesji -->
<pre>
<?php print_r($sessionData); ?>
</pre>

<!-- Status wiadomości -->
<?php if ($sendStatus === 'ok'): ?>
    <p><?= htmlspecialchars($lang['send']) ?></p>
<?php elseif ($sendStatus === 'nok'): ?>
    <p><?= htmlspecialchars($lang['sendnok']) ?></p>
<?php endif; ?>

<!-- Główna zawartość -->
<div class="post-container">
    <br>dashboard<br>
</div>

<!-- Formularz wysyłania wiadomości -->
<?php include_once __DIR__ . '/../tools/send_message.html'; ?>

<!-- Formularz wylogowania -->
<form method="post" action="../tools/logout.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <button type="submit" name="logout" value="logout">Wyloguj się</button>
</form>
