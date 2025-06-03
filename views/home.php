<div class="home-container">
    <section class="hero">
        <h1><?= $languageManager->t('home_welcome_title') ?></h1>
        <p><?= $languageManager->t('home_welcome_subtitle') ?></p>
        <a class="home-cta" href="/?page=register.php"><?= $languageManager->t('home_get_started') ?></a>
    </section>

    <section class="features">
        <div class="feature">
            <h2><?= $languageManager->t('home_feature_one_title') ?></h2>
            <p><?= $languageManager->t('home_feature_one_desc') ?></p>
        </div>
        <div class="feature">
            <h2><?= $languageManager->t('home_feature_two_title') ?></h2>
            <p><?= $languageManager->t('home_feature_two_desc') ?></p>
        </div>
        <div class="feature">
            <h2><?= $languageManager->t('home_feature_three_title') ?></h2>
            <p><?= $languageManager->t('home_feature_three_desc') ?></p>
        </div>
    </section>
</div>
