<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class FilterEngine
{
    private PDO $pdo;
    private string $table;
    private array $allowedSortColumns;

    public function __construct(PDO $pdo, string $table, array $allowedSortColumns)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->allowedSortColumns = $allowedSortColumns;
    }

    public function run(array $filters, int $page = 1, int $limit = 50, string $sortBy = 'id', string $sortDir = 'DESC'): array
    {
        $page = max(1, $page);
        $limit = max(1, min(200, $limit));
        $offset = ($page - 1) * $limit;

        $sortBy = in_array($sortBy, $this->allowedSortColumns, true) ? $sortBy : 'id';
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        $builder = (new QueryBuilder())
            ->table($this->table)
            ->select(['id', 'site_url', 'category', 'country', 'da', 'dr', 'traffic', 'price', 'created_at', 'updated_at']);

        $this->applyFilters($builder, $filters);

        $dataSql = $builder
            ->orderBy($sortBy, $sortDir)
            ->limit($limit)
            ->offset($offset)
            ->toSql();

        $dataStmt = $this->pdo->prepare($dataSql);
        foreach ($builder->bindings() as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $dataStmt->bindValue(':' . $key, $value, $paramType);
        }
        $dataStmt->execute();
        $rows = $dataStmt->fetchAll();

        $countSql = $builder->toSql(true);
        $countStmt = $this->pdo->prepare($countSql);
        foreach ($this->filterBindings($builder->bindings()) as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $countStmt->bindValue(':' . $key, $value, $paramType);
        }
        $countStmt->execute();
        $total = (int) ($countStmt->fetchColumn() ?: 0);

        return [
            'data' => $rows,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'pages' => (int) ceil($total / $limit),
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
        ];
    }

    private function applyFilters(QueryBuilder $builder, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $builder->where('site_url LIKE :search', 'search', '%' . $search . '%');
        }

        foreach (['category', 'country'] as $field) {
            $value = trim((string) ($filters[$field] ?? ''));
            if ($value !== '') {
                $builder->where("{$field} = :{$field}", $field, $value);
            }
        }

        $this->applyRange($builder, 'da', $filters['da_min'] ?? null, $filters['da_max'] ?? null);
        $this->applyRange($builder, 'dr', $filters['dr_min'] ?? null, $filters['dr_max'] ?? null);
        $this->applyRange($builder, 'traffic', $filters['traffic_min'] ?? null, $filters['traffic_max'] ?? null);
        $this->applyRange($builder, 'price', $filters['price_min'] ?? null, $filters['price_max'] ?? null);
    }

    private function applyRange(QueryBuilder $builder, string $column, mixed $min, mixed $max): void
    {
        if ($this->isNumeric($min)) {
            $builder->where("{$column} >= :{$column}_min", "{$column}_min", $this->castNumber($min));
        }

        if ($this->isNumeric($max)) {
            $builder->where("{$column} <= :{$column}_max", "{$column}_max", $this->castNumber($max));
        }
    }

    private function isNumeric(mixed $value): bool
    {
        return $value !== null && $value !== '' && is_numeric((string) $value);
    }

    private function castNumber(mixed $value): int|float
    {
        $num = (string) $value;
        return str_contains($num, '.') ? (float) $num : (int) $num;
    }

    private function filterBindings(array $bindings): array
    {
        return array_filter(
            $bindings,
            static fn (string $key): bool => !in_array($key, ['_limit', '_offset'], true),
            ARRAY_FILTER_USE_KEY
        );
    }
}
