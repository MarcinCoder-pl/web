<?php 

if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
	}


?>
<!DOCTYPE html>
<html lang="<?= $languageManager->t('language'); ?>">
	<head>
	<?php include_once __DIR__ . '/head.php'; ?> 
	</head>
	<body>
		<?php include_once __DIR__ . '/aside.php'; ?>
		<?php include_once __DIR__ . '/header.php'; ?>
		<?php include_once __DIR__ . '/nav.php'; ?>
		<?php include_once __DIR__ . '/main.php'; ?>

		<?php include_once __DIR__ . '/footer.php'; ?>
	</body>
</html>
