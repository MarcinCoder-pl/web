<?php
namespace Tools_Controller;

use Tools_Validation\FormValidator;

class LanguageManager
{
    private string $defaultLang = 'pl';
    private array $allowedLangs = ['pl', 'en', 'cs'];
    private string $langDirectory;
    private string $currentLang;
    private array $translations = [];

    public function __construct(string $langDirectory = __DIR__ . '/../lang')
    {
        $this->langDirectory = rtrim($langDirectory, '/');
        $this->startSessionIfNeeded();
        $this->detectLanguage();
        $this->loadLanguageFile();
    }

    private function startSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function detectLanguage(): void
    {
        // Pobierz surowy input
        $input = ['lang' => $_GET['lang'] ?? ($_SESSION['lang'] ?? $this->defaultLang)];

        // Waliduj i oczyść przy użyciu FormValidator
        $validator = new \Tools_Validation\FormValidator();
        $validator->sanitize($input);

        $rules = [
            'lang' => [
                'required' => true,
                'pattern' => '/^[a-z]{2}$/'
            ]
        ];

        if ($validator->validate($rules)) {
            $lang = $validator->getSanitizedData()['lang'];
            if (in_array($lang, $this->allowedLangs)) {
                $_SESSION['lang'] = $lang;
            } else {
                $_SESSION['lang'] = $this->defaultLang;
            }
        } else {
            $_SESSION['lang'] = $this->defaultLang;
        }

        $this->currentLang = $_SESSION['lang'];
    }

    private function loadLanguageFile(): void
    {
        $file = "{$this->langDirectory}/{$this->currentLang}.php";
        if (file_exists($file)) {
            $this->translations = include $file;
        } else {
            $this->translations = include "{$this->langDirectory}/{$this->defaultLang}.php";
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

    public function translate(string $key): string
    {
        return $this->translations[$key] ?? $key;
    }
}
