<?php
// cookie-consent.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cookie_accept'])) {
    setcookie('cookie_consent', 'accepted', time() + (365 * 24 * 60 * 60), "/"); // 1 rok
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

function showCookieBanner() {
    return !isset($_COOKIE['cookie_consent']);
}
?>
