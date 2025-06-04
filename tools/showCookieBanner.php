<?php if (showCookieBanner()): ?>
<div class="cookie-banner">
    <form method="POST" class="cookie-form">
        <div class="cookie-text">
            <?= $languageManager->t('cookie_banner_text'); ?>
            <a href="/?page=privacy" class="cookie-link">
                <?= $languageManager->t('cookie_banner_link_text'); ?>
            </a>.
        </div>
        <button type="submit" name="cookie_accept" class="cookie-accept-btn">
            <?= $languageManager->t('cookie_banner_accept_button'); ?>
        </button>
    </form>
</div>
<?php endif; ?>
