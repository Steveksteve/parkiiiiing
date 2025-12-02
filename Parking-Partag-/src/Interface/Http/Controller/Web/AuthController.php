<?php

namespace Interface\Http\Controller\Web;

class AuthController
{   private string $templatesPath;

    public function __construct()
    {
        $this->templatesPath = dirname(__DIR__, 5) . '/templates';
    }

    public function showRegisterForm(): void
    {
        $templatePath = $this->templatesPath . '/auth/register.php';

        if (!file_exists($templatePath)) {
            echo "Template register.php introuvable à : " . $templatePath;
            return;
        }

        require $templatePath;
    }

    public function showLoginForm(): void
    {
        $templatePath = $this->templatesPath . '/auth/login.php';

        if (!file_exists($templatePath)) {
            echo "Template login.php introuvable à : " . $templatePath;
            return;
        }

        require $templatePath;
    }
}