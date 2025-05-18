<?php
define('GET_POST_BY_SLUG_AND_LANG', 'SELECT * FROM posts WHERE slug = ? AND lang = ?');
define('GET_POST_BY_WEBSITE', '
    SELECT w.title, w.content, w.wsparcie, w.partnerzy_media, w.adres_korespondencyjny
    FROM posts p
    JOIN website w ON p.id = w.post_id
    WHERE p.slug = ? AND w.language_code = ?');
?>
