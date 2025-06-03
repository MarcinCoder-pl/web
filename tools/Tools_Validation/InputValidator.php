<?php
namespace Tools_Validation;
use DateTime;
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
	}


class InputValidator
{
    // --- Stałe regex ---
    
    /** Regex do walidacji imienia i nazwiska: tylko litery (w tym polskie), spacje oraz myślniki */
    public const REGEX_NAME = '/^[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż\s\-]{2,50}$/u';

    /** Regex do walidacji nazwy użytkownika: tylko litery, cyfry i podkreślenia */
    public const REGEX_USERNAME = '/^[a-zA-Z0-9_]{3,20}$/';

    /** Prosty regex dla e-maila */
    public const REGEX_EMAIL = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    // --- Filtry i walidacja ---

    /**
     * Oczyszcza ciąg znaków (usuwa tagi i przycina białe znaki).
     */
    public static function sanitizeString(string $input): string
    {
        return trim(strip_tags($input));
    }

    /**
     * Czyści i waliduje e-mail.
     */
    public static function sanitizeEmail(string $input): ?string
    {
        $email = filter_var(trim($input), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ?: null;
    }

    /**
     * Czyści i waliduje liczbę całkowitą.
     */
    public static function sanitizeInt($input): ?int
    {
        if (filter_var($input, FILTER_VALIDATE_INT) !== false) {
            return (int) $input;
        }
        return null;
    }

    /**
     * Czyści i waliduje URL.
     */
    public static function sanitizeUrl(string $input): ?string
    {
        $url = trim($input);
        return filter_var($url, FILTER_VALIDATE_URL) ?: null;
    }

    /**
     * Sprawdza długość tekstu.
     */
    public static function validateLength(string $input, int $min, int $max): bool
    {
        $length = mb_strlen($input);
        return $length >= $min && $length <= $max;
    }

    /**
     * Sprawdza, czy wartość jest podana (dozwolone "0").
     */
    public static function validateRequired($input): bool
    {
        return !(is_null($input) || $input === '' || (is_array($input) && empty($input)));
    }

    /**
     * Walidacja z użyciem regexu.
     */
    public static function validatePattern(string $input, string $pattern): bool
    {
        return preg_match($pattern, $input) === 1;
    }

    /**
     * Waliduje dane formularza na podstawie reguł.
     */
    public static function validateForm(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? null;

            foreach ($ruleSet as $rule => $ruleValue) {
                switch ($rule) {
                    case 'required':
                        if ($ruleValue && !self::validateRequired($value)) {
                            $errors[$field][] = 'To pole jest wymagane.';
                        }
                        break;

                    case 'min':
                    case 'max':
                        $min = $ruleSet['min'] ?? 0;
                        $max = $ruleSet['max'] ?? PHP_INT_MAX;
                        if (!self::validateLength((string)$value, $min, $max)) {
                            $errors[$field][] = "Pole musi mieć od $min do $max znaków.";
                        }
                        break;

                    case 'email':
                        if ($ruleValue && self::sanitizeEmail($value) === null) {
                            $errors[$field][] = 'Nieprawidłowy adres e-mail.';
                        }
                        break;

                    case 'pattern':
                        if (!self::validatePattern((string)$value, $ruleValue)) {
                            $errors[$field][] = 'Nieprawidłowy format danych.';
                        }
                        break;
                }
            }
        }

        return $errors;
    }

    /**
     * Sprawdza poprawność daty względem formatu.
     */
    public static function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Czyści tekst wieloliniowy:
     * - usuwa HTML
     * - usuwa nadmiar pustych linii (>2)
     */
    public static function sanitizeMultilineText(string $input): string
    {
        $input = strip_tags($input);
        $input = preg_replace('/[\r\n]{3,}/', "\n\n", $input);
        return trim($input);
    }
}
