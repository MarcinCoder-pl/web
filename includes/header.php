<header>
  <div class="header-container">
    <!-- Logo po lewej -->
    <div class="logo">
      <a href="/">
        <img src="/images/logo.png" alt="Logo TwojaGra">
      </a>
    </div>

    <!-- Panel logowania po prawej -->
    <div class="panel-ster">
      <div class="auth-links">
        <?php
        use Tools_Manager\SessionManager;

        $session = SessionManager::getInstance();

        if ($session->isLoggedIn()):
            $username = htmlspecialchars($session->get('username'));
        ?>
            <span><?php echo $languageManager->t('hello'); ?>, <?php echo $username; ?>!</span>
            <span class="separator">|</span>
            <a href="/../tools/user_core/user_logout.php"><?php echo $languageManager->t('logout'); ?></a>
           
        <?php else: ?>
            <a href="/?page=login"><?php echo $languageManager->t('login'); ?></a>
            <span class="separator">|</span>
            <a href="/?page=register"><?php echo $languageManager->t('create_account'); ?></a>
            
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
