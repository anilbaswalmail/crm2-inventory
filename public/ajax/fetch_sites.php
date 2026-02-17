<?php

declare(strict_types=1);

header('Content-Type: application/json');

require dirname(__DIR__, 2) . '/app/bootstrap.php';

use App\Models\Site;

$site = new Site();

$filters = [
    'search' => $_GET['search'] ?? '',
    'category' => $_GET['category'] ?? '',
    'country' => $_GET['country'] ?? '',
    'da_min' => $_GET['da_min'] ?? null,
    'da_max' => $_GET['da_max'] ?? null,
    'dr_min' => $_GET['dr_min'] ?? null,
    'dr_max' => $_GET['dr_max'] ?? null,
    'traffic_min' => $_GET['traffic_min'] ?? null,
    'traffic_max' => $_GET['traffic_max'] ?? null,
    'price_min' => $_GET['price_min'] ?? null,
    'price_max' => $_GET['price_max'] ?? null,
];

$page = max(1, (int) ($_GET['page'] ?? 1));
$limit = max(1, (int) ($_GET['limit'] ?? 50));
$sortBy = (string) ($_GET['sort_by'] ?? 'id');
$sortDir = (string) ($_GET['sort_dir'] ?? 'DESC');

$result = $site->filter($filters, $page, $limit, $sortBy, $sortDir);

echo json_encode($result, JSON_THROW_ON_ERROR);
