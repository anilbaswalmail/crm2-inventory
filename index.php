<?php

declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';

use App\Core\Router;
use App\Controllers\DashboardController;
use App\Controllers\SitesController;

$router = new Router();
$router->get('/', [DashboardController::class, 'index']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/sites', [SitesController::class, 'index']);
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
