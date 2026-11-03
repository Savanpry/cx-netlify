<?php

declare(strict_types=1);

class User
{
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, role, name, email, status, created_at, updated_at FROM users WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** List users; supports ['search', 'page', 'per_page']. */
    public function all(array $filters = []): array
    {
        [$where, $params] = $this->buildWhere($filters);
        $sql = 'SELECT id, role, name, email, status, created_at, updated_at FROM users'
             . $where . ' ORDER BY id DESC';

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

    /** Total users matching the (search) filters. */
    public function count(array $filters = []): int
    {
        [$where, $params] = $this->buildWhere($filters);
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users' . $where);
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
            $where = ' WHERE (name LIKE :s1 OR email LIKE :s2)';
            $params = [':s1' => $like, ':s2' => $like];
        }
        return [$where, $params];
    }

    public function create(array $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (role, name, email, password, status)
             VALUES (:role, :name, :email, :password, :status)'
        );
        $stmt->execute([
            'role'     => $d['role'] ?? 'user',
            'name'     => $d['name'],
            'email'    => $d['email'],
            'password' => password_hash($d['password'], PASSWORD_DEFAULT),
            'status'   => $d['status'] ?? 'active',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        $fields = [];
        $params = ['id' => $id];
        foreach (['role', 'name', 'email', 'status'] as $col) {
            if (array_key_exists($col, $d)) {
                $fields[] = "$col = :$col";
                $params[$col] = $d[$col];
            }
        }
        if (!empty($d['password'])) {
            $fields[] = 'password = :password';
            $params['password'] = password_hash($d['password'], PASSWORD_DEFAULT);
        }
        if (!$fields) {
            return false;
        }
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        return $this->db->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
