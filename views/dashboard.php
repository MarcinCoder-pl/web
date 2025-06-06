<?php
use Tools_Manager\SessionManager;

$session = SessionManager::getInstance();

if (!$session->isLoggedIn()) {
    header('Location: /?page=login');
    exit;
}

$userId = $session->get('user_id');
$username = $session->get('username');

$exportedData = null;
$errorMessage = null;

// Obsługa eksportu/usunięcia danych sesji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gdpr_action'], $_POST['csrf_token'])) {
    if ($session->validateCsrfToken('gdpr_action', $_POST['csrf_token'])) {
        if ($_POST['gdpr_action'] === 'export') {
            $exportedData = $session->exportSessionData();
        } elseif ($_POST['gdpr_action'] === 'delete') {
            $session->deleteSessionData();
            echo '<p>✅ Dane sesji zostały usunięte. Zostaniesz wylogowany...</p>';
            echo '<meta http-equiv="refresh" content="1;url=/?page=home">';
            exit;
        }
    } else {
        $errorMessage = '❌ Błędny token CSRF. Spróbuj ponownie.';
    }
}

// Token generujemy dopiero po przetworzeniu POST, aby uniknąć jego nadpisania
$token = $session->getOrCreateCsrfToken('gdpr_action');
?>

<h2>Witaj w panelu użytkownika</h2>
<p>Cześć, <strong><?= htmlspecialchars($username ?? 'Użytkowniku') ?></strong>! Miło Cię widzieć.</p>

<ul>
    <li><a href="/?page=profile">Twój profil</a></li>
    <li><a href="/?page=settings">Ustawienia konta</a></li>
    <li><a href="/../tools/user_core/user_logout.php">Wyloguj się</a></li>
</ul>

<hr>
<h3>🛡️ Twoje prawa RODO</h3>

<?php if ($errorMessage): ?>
    <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>

<?php if ($exportedData): ?>
    <h4>📤 Dane Twojej sesji:</h4>
    <pre><?= htmlspecialchars(json_encode($exportedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) ?></pre>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">
    <button type="submit" name="gdpr_action" value="export">📤 Eksportuj dane sesji</button>
    <button type="submit" name="gdpr_action" value="delete" onclick="return confirm('Czy na pewno chcesz usunąć wszystkie dane sesji? Zostaniesz wylogowany.')">🗑️ Usuń dane sesji</button>
</form>
