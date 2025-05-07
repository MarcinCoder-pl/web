<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	
function isAlphanumeric($string) :bool {
    // Sprawdzanie, czy ciąg składa się tylko z liter i cyfr (alphanumeric)
    if (preg_match('/^[a-zA-Z0-9]+$/', $string)) {
        return true;  // Napis zawiera tylko litery i cyfry
    } else {
        return false; // Napis zawiera znaki specjalne
    }
}

// Kodowanie do UTF-8
function convert_to_utf8($tekst) : string {

    $encoding_list = ['UTF-8', 'ISO-8859-1', 'ISO-8859-2', 'Windows-1252', 'ASCII'];
    $detected_encoding = mb_detect_encoding($tekst, $encoding_list, true);

    if ($detected_encoding === false) {
        $detected_encoding = 'ISO-8859-1';
    }

    if ($detected_encoding !== 'UTF-8') {
        $tekst = mb_convert_encoding($tekst, 'UTF-8', $detected_encoding);
    }

    return $tekst;
}



// Oczyszczenie tekstu (do bezpiecznego wyświetlania w HTML)
function sanitize_for_output($tekst) : string {
    $tekst = convert_to_utf8($tekst); // Upewnij się, że UTF-8
    return htmlspecialchars($tekst, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}///////////////////////

function isLoginTaken($conn, $login) : bool {
    $check_sql = "SELECT id FROM uzytkownicy WHERE login = ? LIMIT 1";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $login);
    $check_stmt->execute();
    $check_stmt->store_result();
    $exists = $check_stmt->num_rows > 0;
    $check_stmt->close();
    return $exists;
}
function haszujHaslo($haslo) : string {
    return password_hash($haslo, PASSWORD_DEFAULT);
}


