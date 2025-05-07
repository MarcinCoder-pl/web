<body>
    <?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	/////////////////////////////
	
    $structurePath = 'structure/';
    require_once "{$structurePath}header.php";
    require_once "{$structurePath}navigation.php";
    require_once "{$structurePath}aside.php";
    
    // Przekazanie zmiennej $page do main.php
    require_once "{$structurePath}main_content.php";
    
    require_once "{$structurePath}footer.php";
    ?>
</body>
</html>
