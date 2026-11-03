<?php

declare(strict_types=1);

class Category
{
    public function __construct(private PDO $db) {}

    /** List categories; supports ['search', 'page', 'per_page']. */
    public function all(array $filters = []): array
    {
        [$where, $params] = $this->buildWhere($filters);
        $sql = 'SELECT * FROM categories' . $where . ' ORDER BY name ASC';

        if (!empty($filters['per_page'])) {
            $sql .= ' LIMIT :limit OFFSET :offset';
        }
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        if (!empty($filters['per_page'])) {
            $perPage = (int) $filters['per_page'];
            $page    = max(1, (int) ($filters['page'] ?? 1));
            $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** Total categories matching the (search) filters. */
    public function count(array $filters = []): int
    {
        [$where, $params] = $this->buildWhere($filters);
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM categories' . $where);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** @return array{0:string,1:array} [whereClause, params] */
    private function buildWhere(array $filters): array
    {
        $where = '';
        $params = [];
        if (!empty($filters['search'])) {
            $like = '%' . $filters['search'] . '%';
            $where = ' WHERE (name LIKE :s1 OR slug LIKE :s2 OR description LIKE :s3)';
            $params = [':s1' => $like, ':s2' => $like, ':s3' => $like];
        }
        return [$where, $params];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, slug, description, status)
             VALUES (:name, :slug, :description, :status)'
        );
        $stmt->execute([
            'name'        => $d['name'],
            'slug'        => $d['slug'],
            'description' => $d['description'] ?? null,
            'status'      => $d['status'] ?? 'active',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        $fields = [];
        $params = ['id' => $id];
        foreach (['name', 'slug', 'description', 'status'] as $col) {
            if (array_key_exists($col, $d)) {
                $fields[] = "$col = :$col";
                $params[$col] = $d[$col];
            }
        }
        if (!$fields) {
            return false;
        }
        $sql = 'UPDATE categories SET ' . implode(', ', $fields) . ' WHERE id = :id';
        return $this->db->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        return $this->db->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
    }
}
