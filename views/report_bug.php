<?php
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

use Tools_Manager\SessionManager;

$session = SessionManager::getInstance();
$csrfToken = $session->getOrCreateCsrfToken('report_bug');

$errors = $session->get('form_errors') ?? [];
$data = $session->get('form_data') ?? [];
$success = $session->get('form_success') ?? '';

$session->delete('form_errors');
$session->delete('form_data');
$session->delete('form_success');
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $fieldErrors): ?>
                <?php foreach ((array)$fieldErrors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="contact-container">
    <div class="contact-form-section">
        <h2><?= $languageManager->t('bug_report_title') ?></h2>
        <p><?= $languageManager->t('bug_report_intro') ?></p>
        <form action="../tools/Tools_Message/report_bug.php" method="post">
            <div class="form-group">
                <label for="name"><?= $languageManager->t('full_name') ?></label>
                <input type="text" id="name" name="name" required minlength="2" maxlength="100" class="form-control" />
            </div>
            <div class="form-group">
                <label for="email"><?= $languageManager->t('email_address') ?></label>
                <input type="email" id="email" name="email" required maxlength="150" class="form-control" />
            </div>
            <div class="form-group">
                <label for="message"><?= $languageManager->t('bug_description') ?></label>
                <textarea id="message" name="message" rows="10" required maxlength="2000" class="form-control"></textarea>
            </div>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>" />
            <button type="submit" class="btn-submit"><?= $languageManager->t('send_bug_report') ?></button>
        </form>
    </div>
</div>
