<?php
namespace Tools_Controller;

use Tools_Validation\FormValidator;
use Tools_Manager\SessionManager;

if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

class LanguageManager
{
    private string $defaultLang = 'cs';
    private array $allowedLangs = ['pl', 'en', 'cs'];
    private string $langDirectory;
    private string $currentLang;
    private array $translations = [];

    public function __construct(string $langDirectory = __DIR__ . '/../lang')
    {
        $this->langDirectory = rtrim($langDirectory, '/');
        $this->startSessionIfNeeded();
        $this->detectLanguage();
        $this->loadLanguageFiles();
    }

    private function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            SessionManager::getInstance();
        }
    }

    private function detectLanguage(): void
    {
        $input = ['lang' => $_GET['lang'] ?? ($_SESSION['lang'] ?? $this->defaultLang)];

        $validator = new FormValidator();
        $validator->sanitize($input);

        $rules = [
            'lang' => [
                'required' => true,
                'pattern' => '/^[a-z]{2}$/'
            ]
        ];

        if ($validator->validate($rules)) {
            $lang = $validator->getSanitizedData()['lang'];
            $_SESSION['lang'] = in_array($lang, $this->allowedLangs) ? $lang : $this->defaultLang;
        } else {
            $_SESSION['lang'] = $this->defaultLang;
        }

        $this->currentLang = $_SESSION['lang'];
    }

private function loadLanguageFiles(): void
{
    $this->translations = [];

    $langFile = "{$this->langDirectory}/{$this->currentLang}.json";
    $fallbackFile = "{$this->langDirectory}/{$this->defaultLang}.json";

    if (file_exists($langFile)) {
        $json = file_get_contents($langFile);
        $this->translations = json_decode($json, true) ?? [];
    }

    // fallback dla brakujących kluczy
    if ($this->currentLang !== $this->defaultLang && file_exists($fallbackFile)) {
        $fallbackJson = file_get_contents($fallbackFile);
        $fallbackTranslations = json_decode($fallbackJson, true) ?? [];

        $this->translations = array_merge($fallbackTranslations, $this->translations);
    }
}

///
    public function getCurrentLang(): string
    {
        return $this->currentLang;
    }

    public function getTranslations(): array
    {
        return $this->translations;
    }

    public function t(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }
}
