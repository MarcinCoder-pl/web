<?php
define('GET_POST_WEB_HOME', 'SELECT * FROM posts WHERE slug = ? AND lang = ?');
define('GET_POST_WEB_DOWNLOAD', 'SELECT pt.title, pt.content FROM posts p JOIN post_translations pt ON p.id = pt.post_id WHERE p.slug = ? AND pt.language_code = ?');




define('GET_POST_WEB_CONTACT',
	'SELECT w.title, w.content, w.wsparcie, w.partnerzy_media, w.adres_korespondencyjny
    FROM posts p
    JOIN website w ON p.id = w.post_id
    WHERE p.slug = ? AND w.language_code = ?');
    
define('ADD_ACC', 'INSERT INTO uzytkownicy (login, haslo) VALUES (?, ?)');
define('LOGIN_ACC','SELECT haslo FROM uzytkownicy WHERE login = ?');
