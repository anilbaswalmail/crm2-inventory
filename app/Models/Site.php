<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\FilterEngine;
use PDO;

class Site
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function filter(array $filters, int $page, int $limit, string $sortBy, string $sortDir): array
    {
        $engine = new FilterEngine(
            $this->pdo,
            'sites',
            ['id', 'site_url', 'category', 'country', 'da', 'dr', 'traffic', 'price', 'created_at', 'updated_at']
        );

        return $engine->run($filters, $page, $limit, $sortBy, $sortDir);
    }

    public function distinctValues(string $column): array
    {
        $allowed = ['category', 'country'];
        if (!in_array($column, $allowed, true)) {
            return [];
        }

        $stmt = $this->pdo->query("SELECT DISTINCT {$column} FROM sites WHERE {$column} IS NOT NULL AND {$column} != '' ORDER BY {$column} ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }
}
