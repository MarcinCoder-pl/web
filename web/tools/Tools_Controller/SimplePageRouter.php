<?php
namespace Tools_Controller;

use Tools_Validation\FormValidator;
use Tools_Controller\LanguageManager;


if (!defined('ACCESS_DOOR')) {
    die('Brak dostępu.');
}

class SimplePageRouter
{
    private string $defaultPage = 'home';
    private string $viewsPath;
    private FormValidator $validator;
    private LanguageManager $languageManager;

public function __construct(string $viewsPath, LanguageManager $languageManager)
{
    $this->viewsPath = rtrim($viewsPath, '/');
    $this->validator = new FormValidator();
    $this->languageManager = $languageManager;
}


    public function route(): void
    {
        $slug = $_GET['page'] ?? $this->defaultPage;

        // Sanityzacja i walidacja sluga
        $this->validator->sanitize(['slug' => $slug]);
        $isValid = $this->validator->validate([
            'slug' => [
                'required' => true,
                'pattern' => '/^[a-zA-Z0-9\-_]+$/',
                'min' => 1,
                'max' => 20
            ]
        ]);

        if (!$isValid) {
            $this->renderView('404');
            return;
        }

        $cleanSlug = $this->validator->getSanitizedData()['slug'];
        $viewFile = $this->viewsPath . '/' . $cleanSlug . '.php';

        if (file_exists($viewFile)) {
            $this->renderView($cleanSlug);
        } else {
            $this->renderView('404');
        }
    }

    private function renderView(string $view): void
    {
        $languageManager = $this->languageManager;
        $fallbackView = $this->viewsPath . '/404.php';
        $file = $this->viewsPath . '/' . $view . '.php';

        // Przekazujemy $languageManager do widoku
        extract(['languageManager' => $languageManager]);

        require file_exists($file) ? $file : $fallbackView;
    }
}
