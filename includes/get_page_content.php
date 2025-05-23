<?php
    //W pliku, który chcesz chronić, dodaj na górze coś takiego:
    if (!defined('ACCESS')) {
    die('Brak dostępu.');
	}
	
function getPageContent(Database $db, string $slug, string $language): array {
    try {
        $result = $db->prepareAndExecute(GET_POST_WEB, [$slug, $language]);
        if ($result && $row = $result->fetch_assoc()) {
            return [
                'title' => htmlspecialchars($row['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'content' => nl2br(htmlspecialchars($row['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            ];
        } else {
            return [
                'title' => 'Strona główna',
                'content' => 'Nie znaleziono zawartości dla tego języka.'
            ];
        }
    } catch (Exception $e) {
        return [
            'title' => 'Błąd',
            'content' => 'Wystąpił błąd: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_HTML5, 'UTF-8')
        ];
    }
}
