<?php
//  Pobieranie postów
const GET_POST_BY_SLUG_AND_LANGUAGE = 	"SELECT pt.title, pt.content FROM posts p JOIN post_translations pt ON p.id = pt.post_id WHERE p.slug = ? AND pt.language_code = ?";
const GET_POST_WEB = "SELECT pt.title, pt.content FROM posts p JOIN post_translations pt ON pt.post_id = p.id WHERE p.slug = ? AND pt.language_code = ?";
const GET_POST_WEB_DOWNLOAD = "SELECT pt.title, pt.content FROM posts p JOIN post_translations pt ON pt.post_id = p.id WHERE p.slug = ? AND pt.language_code = ?";

//  Operacje na kontach użytkowników
const ADD_ACC = "INSERT INTO uzytkownicy (login, haslo) VALUES (?, ?)";
const IS_LOGIN_TAKEN = "SELECT 1 FROM uzytkownicy WHERE login = ? LIMIT 1";
const LOGIN_ACC = "SELECT haslo FROM uzytkownicy WHERE login = ?";

//  Komunikaty błędów
const GET_ERROR_MESSAGE = "SELECT * FROM registration_errors WHERE error_code = ?";
?>
