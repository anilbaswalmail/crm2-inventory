<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (!is_file($viewPath)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/layouts/sidebar.php';
        require $viewPath;
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
