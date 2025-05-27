<?php
namespace Tools_Controller;

use Tools_Manager\SessionManager;
use Tools_Validation\FormValidator;

class SimplePageRouter
{
    private string $defaultPage = 'home';
    private string $viewsPath;
    private SessionManager $sessionManager;
    private FormValidator $validator;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/');
        $this->sessionManager = new SessionManager();
        $this->validator = new FormValidator();
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
                'max' => 3
            ]
        ]);

        if (!$isValid) {
            $this->renderView('404');
            return;
        }

        $cleanSlug = $this->validator->getSanitizedData()['slug'];
        $viewFile = $this->viewsPath . '/' . $cleanSlug . '.php';

        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            $this->renderView('404');
        }
    }

    private function renderView(string $view): void
    {
        $fallbackView = $this->viewsPath . '/404.php';
        $file = $this->viewsPath . '/' . $view . '.php';
        require file_exists($file) ? $file : $fallbackView;
    }

    public function getSessionManager(): SessionManager
    {
        return $this->sessionManager;
    }
}
