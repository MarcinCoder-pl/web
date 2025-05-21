<?php
define('GET_POST_WEB_HOME', 'SELECT * FROM posts WHERE slug = ? AND lang = ?');




define('GET_POST_WEB_CONTACT',
	'SELECT w.title, w.content, w.wsparcie, w.partnerzy_media, w.adres_korespondencyjny
    FROM posts p
    JOIN website w ON p.id = w.post_id
    WHERE p.slug = ? AND w.language_code = ?');


// Dodanie nowego konta
const ADD_ACC = "INSERT INTO uzytkownicy (login, haslo) VALUES (?, ?)";

// Sprawdzenie, czy login jest zajęty
const IS_LOGIN_TAKEN = "SELECT 1 FROM uzytkownicy WHERE login = ? LIMIT 1";

const GET_ERROR_MESSAGE = "SELECT * FROM registration_errors WHERE error_code = ?";

const LOGIN_ACC = "SELECT haslo FROM uzytkownicy WHERE login = ?";

const GET_POST_WEB_HOME = "SELECT pt.title, pt.content FROM posts p JOIN post_translations pt ON pt.post_id = p.id WHERE p.slug = ? AND pt.language_code = ?");
const GET_POST_WEB_DOWNLOAD = "SELECT pt.title, pt.content FROM posts p JOIN post_translations pt ON pt.post_id = p.id WHERE p.slug = ? AND pt.language_code = ?");



?>
