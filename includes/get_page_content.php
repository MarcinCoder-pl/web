<?php

function getPageContent(Database $db, string $slug, string $language): array
{
    $query = "SELECT title, content FROM page_contents WHERE slug = ? AND language = ?";
    $result = $db->prepareAndExecute($query, [$slug, $language]);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    // Jeśli nie znaleziono treści – fallback
    return [
        'title' => 'Strona nie znaleziona',
        'content' => 'Zawartość dla tej strony nie została znaleziona.'
    ];
}
