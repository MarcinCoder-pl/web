<?php
namespace Tools_Validation;

use DateTime;

class InputValidator
{
    // --- Stałe regex ---

    public const REGEX_NAME = '/^[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż\s\-]{2,50}$/u';
    public const REGEX_LOGIN = '/^[a-zA-Z0-9_]{3,20}$/';
    public const REGEX_EMAIL = '/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/';

    /**
     * Oczyszcza ciąg znaków (usuwając tagi HTML i przycinając).
     *
     * @param string $input
     * @return string
     */
    public static function sanitizeString(string $input): string
    {
        return trim(strip_tags($input));
    }

    /**
     * Oczyszcza i waliduje adres e-mail.
     *
     * @param string $input
     * @return string|null
     */
    public static function sanitizeEmail(string $input): ?string
    {
        $email = filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    /**
     * Oczyszcza i waliduje liczbę całkowitą.
     *
     * @param mixed $input
     * @return int|null
     */
    public static function sanitizeInt($input): ?int
    {
        $int = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        return filter_var($int, FILTER_VALIDATE_INT) !== false ? (int)$int : null;
    }

    /**
     * Oczyszcza i waliduje URL.
     *
     * @param string $input
     * @return string|null
     */
    public static function sanitizeUrl(string $input): ?string
    {
        $url = filter_var(trim($input), FILTER_SANITIZE_URL);
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    /**
     * Sprawdza, czy długość tekstu mieści się w zakresie.
     *
     * @param string $input
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function validateLength(string $input, int $min, int $max): bool
    {
        $length = mb_strlen($input);
        return $length >= $min && $length <= $max;
    }

    /**
     * Sprawdza, czy pole nie jest puste (akceptuje "0").
     *
     * @param mixed $input
     * @return bool
     */
    public static function validateRequired($input): bool
    {
        return !empty($input) || $input === '0';
    }

    /**
     * Sprawdza zgodność z wyrażeniem regularnym.
     *
     * @param string $input
     * @param string $pattern
     * @return bool
     */
    public static function validatePattern(string $input, string $pattern): bool
    {
        return
