<?php 
session_start();  // Rozpoczynamy sesję, jeżeli nie została jeszcze rozpoczęta.

$page = isset($_GET['strona']) ? $_GET['strona'] : 'home';  
// Zmienna $_GET['strona'] określa, która strona ma zostać załadowana,
 domyślnie ładowana jest strona "home".

// Ścieżki do plików (możesz zmieniać w zależności od potrzeb)
$allowed_pages = ['home', 'kontakt', 'o-nas'];

if (in_array($page, $allowed_pages)) {
    require_once "includes/{$page}.php";  // Ładowanie odpowiedniego pliku
} else {
    echo "Strona nie istnieje!";
}

?>
