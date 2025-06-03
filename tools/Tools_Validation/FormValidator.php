<?php

namespace Tools_Validation;
if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
	}
class FormValidator
{
    private InputSanitizer $sanitizer;
    private array $sanitizedData = [];
    private array $errors = [];

    public function __construct()
    {
        $this->sanitizer = new InputSanitizer();
    }

    public function sanitize(array $formData): void
    {
        $this->sanitizedData = $this->sanitizer->sanitizeArrayUtf8($formData);
    }

    /**
     * Waliduje i konwertuje dane na podstawie reguł.
     *
     * Reguły obsługują typy:
     * - 'email' => true
     * - 'int' => true
     * - 'url' => true
     *
     * @param array $rules
     * @return bool
     */
    public function validate(array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleSet) {
            $value = $this->sanitizedData[$field] ?? null;

            // Sprawdź required
            if (!empty($ruleSet['required']) && !InputValidator::validateRequired($value)) {
                $this->errors[$field][] = 'Pole jest wymagane.';
                continue;
            }

            // Jeśli brak wartości i pole nie jest wymagane, pomijamy dalszą walidację
            if ($value === null || $value === '') {
                continue;
            }

            // Konwersja i walidacja typów
            if (!empty($ruleSet['email'])) {
                $email = InputValidator::sanitizeEmail($value);
                if ($email === null) {
                    $this->errors[$field][] = 'Nieprawidłowy adres e-mail.';
                } else {
                    $this->sanitizedData[$field] = $email;
                }
            } elseif (!empty($ruleSet['int'])) {
                $intVal = InputValidator::sanitizeInt($value);
                if ($intVal === null) {
                    $this->errors[$field][] = 'Pole musi być liczbą całkowitą.';
                } else {
                    $this->sanitizedData[$field] = $intVal;
                }
            } elseif (!empty($ruleSet['url'])) {
                $url = InputValidator::sanitizeUrl($value);
                if ($url === null) {
                    $this->errors[$field][] = 'Nieprawidłowy adres URL.';
                } else {
                    $this->sanitizedData[$field] = $url;
                }
            } else {
                // Długość
                $min = $ruleSet['min'] ?? 0;
                $max = $ruleSet['max'] ?? PHP_INT_MAX;
                if (!InputValidator::validateLength((string)$value, $min, $max)) {
                    $this->errors[$field][] = "Pole musi mieć od {$min} do {$max} znaków.";
                }

                // Wzorzec
                if (!empty($ruleSet['pattern']) && !InputValidator::validatePattern((string)$value, $ruleSet['pattern'])) {
                    $this->errors[$field][] = 'Nieprawidłowy format pola.';
                }
            }
        }

        return empty($this->errors);
    }

    public function getSanitizedData(): array
    {
        return $this->sanitizedData;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}
