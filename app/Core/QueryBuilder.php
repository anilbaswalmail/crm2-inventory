<?php

declare(strict_types=1);

namespace App\Core;

class QueryBuilder
{
    private string $table;
    private array $select = ['*'];
    private array $where = [];
    private array $bindings = [];
    private ?string $orderBy = null;
    private ?int $limit = null;
    private ?int $offset = null;

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select(array $columns): self
    {
        $this->select = $columns;
        return $this;
    }

    public function where(string $condition, string $bindingKey, mixed $value): self
    {
        $this->where[] = $condition;
        $this->bindings[$bindingKey] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction): self
    {
        $this->orderBy = sprintf('%s %s', $column, strtoupper($direction));
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = max(1, $limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = max(0, $offset);
        return $this;
    }

    public function toSql(bool $countOnly = false): string
    {
        $select = $countOnly ? 'COUNT(*) AS total' : implode(', ', $this->select);
        $sql = "SELECT {$select} FROM {$this->table}";

        if ($this->where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }

        if (!$countOnly) {
            if ($this->orderBy !== null) {
                $sql .= ' ORDER BY ' . $this->orderBy;
            }

            if ($this->limit !== null) {
                $sql .= ' LIMIT :_limit';
            }

            if ($this->offset !== null) {
                $sql .= ' OFFSET :_offset';
            }
        }

        return $sql;
    }

    public function bindings(): array
    {
        $bindings = $this->bindings;

        if ($this->limit !== null) {
            $bindings['_limit'] = $this->limit;
        }

        if ($this->offset !== null) {
            $bindings['_offset'] = $this->offset;
        }

        return $bindings;
    }
}
