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
        $this->translations = []; // reset

        $langPath = "{$this->langDirectory}/{$this->currentLang}";
        $fallbackPath = "{$this->langDirectory}/{$this->defaultLang}";

        $files = glob($langPath . "/*.php");

        foreach ($files as $file) {
            $loaded = include $file;
            if (is_array($loaded)) {
                $this->translations = array_merge($this->translations, $loaded);
            }
        }

        // fallback: jeśli jakiś plik nie istnieje w wybranym języku, ładuj z domyślnego
        if ($this->currentLang !== $this->defaultLang) {
            $fallbackFiles = glob($fallbackPath . "/*.php");
            foreach ($fallbackFiles as $fallbackFile) {
                $filename = basename($fallbackFile);
                $targetFile = $langPath . '/' . $filename;
                if (!file_exists($targetFile)) {
                    $loaded = include $fallbackFile;
                    if (is_array($loaded)) {
                        $this->translations = array_merge($this->translations, $loaded);
                    }
                }
            }
        }
    }

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
