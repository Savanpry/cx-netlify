<?php

declare(strict_types=1);

class Blog
{
    public function __construct(private PDO $db) {}

    /** List blogs with optional filters (status, category_id, user_id, search, page, per_page). */
    public function all(array $filters = []): array
    {
        [$where, $params] = $this->buildWhere($filters);

        $sql = 'SELECT b.*, c.name AS category_name, u.name AS user_name
                FROM blogs b
                LEFT JOIN categories c ON c.id = b.category_id
                LEFT JOIN users u ON u.id = b.user_id'
             . $where . ' ORDER BY b.created_at DESC';

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

    /** Total blogs matching the filters (ignores paging). */
    public function count(array $filters = []): int
    {
        [$where, $params] = $this->buildWhere($filters);
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM blogs b' . $where);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /** @return array{0:string,1:array} [whereClause, params] */
    private function buildWhere(array $filters): array
    {
        $where = [];
        $params = [];
        if (!empty($filters['status'])) {
            $where[] = 'b.status = :status';
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'b.category_id = :category_id';
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['user_id'])) {
            $where[] = 'b.user_id = :user_id';
            $params[':user_id'] = $filters['user_id'];
        }
        if (!empty($filters['search'])) {
            $like = '%' . $filters['search'] . '%';
            $where[] = '(b.title LIKE :search1 OR b.short_description LIKE :search2)';
            $params[':search1'] = $like;
            $params[':search2'] = $like;
        }
        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, c.name AS category_name, u.name AS user_name
             FROM blogs b
             LEFT JOIN categories c ON c.id = b.category_id
             LEFT JOIN users u ON u.id = b.user_id
             WHERE b.id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO blogs
              (category_id, user_id, title, slug, short_description, quick_summary, content, conclusion,
               featured_image, featured_thumb, meta_title, meta_description, meta_keywords,
               canonical_url, schema_json, reading_time, author_name, author_role, author_bio, author_linkedin, author_image, show_author,
               cta_position, cta_text, cta_button_text, cta_button_link, status, `schema`)
             VALUES
              (:category_id, :user_id, :title, :slug, :short_description, :quick_summary, :content, :conclusion,
               :featured_image, :featured_thumb, :meta_title, :meta_description, :meta_keywords,
               :canonical_url, :schema_json, :reading_time, :author_name, :author_role, :author_bio, :author_linkedin, :author_image, :show_author,
               :cta_position, :cta_text, :cta_button_text, :cta_button_link, :status, :schema)'
        );
        $stmt->execute([
            'category_id'       => $d['category_id'] ?? null,
            'user_id'           => $d['user_id'],
            'title'             => $d['title'],
            'slug'              => $d['slug'],
            'short_description' => $d['short_description'] ?? null,
            'quick_summary'     => $d['quick_summary'] ?? null,
            'content'           => $d['content'] ?? null,
            'conclusion'        => $d['conclusion'] ?? null,
            'featured_image'    => $d['featured_image'] ?? null,
            'featured_thumb'    => $d['featured_thumb'] ?? null,
            'meta_title'        => $d['meta_title'] ?? null,
            'meta_description'  => $d['meta_description'] ?? null,
            'meta_keywords'     => $d['meta_keywords'] ?? null,
            'canonical_url'     => $d['canonical_url'] ?? null,
            'schema_json'       => $d['schema_json'] ?? null,
            'reading_time'      => $d['reading_time'] ?? 0,
            'author_name'       => $d['author_name'] ?? null,
            'author_role'       => $d['author_role'] ?? null,
            'author_bio'        => $d['author_bio'] ?? null,
            'author_linkedin'   => $d['author_linkedin'] ?? null,
            'author_image'      => $d['author_image'] ?? null,
            'show_author'       => !empty($d['show_author']) ? 1 : 0,
            'cta_position'      => $d['cta_position'] ?? 'none',
            'cta_text'          => $d['cta_text'] ?? null,
            'cta_button_text'   => $d['cta_button_text'] ?? null,
            'cta_button_link'   => $d['cta_button_link'] ?? null,
            'status'            => $d['status'] ?? 'draft',
            'schema'            => $d['schema'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        $allowed = [
            'category_id', 'title', 'slug', 'short_description', 'quick_summary', 'content', 'conclusion',
            'featured_image', 'featured_thumb', 'meta_title', 'meta_description', 'meta_keywords',
            'canonical_url', 'schema_json', 'reading_time', 'author_name', 'author_role', 'author_bio', 'author_linkedin', 'author_image', 'show_author',
            'cta_position', 'cta_text', 'cta_button_text', 'cta_button_link', 'status', 'schema',
        ];
        $fields = [];
        $params = ['id' => $id];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $d)) {
                $fields[] = "`$col` = :$col";   // backtick: `schema` is a reserved word
                $params[$col] = $d[$col];
            }
        }
        if (!$fields) {
            return false;
        }
        $sql = 'UPDATE blogs SET ' . implode(', ', $fields) . ' WHERE id = :id';
        return $this->db->prepare($sql)->execute($params);
    }

    public function publish(int $id, bool $publish = true): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE blogs
             SET status = :status, published_at = :published_at
             WHERE id = :id'
        );
        return $stmt->execute([
            'status'       => $publish ? 'published' : 'draft',
            'published_at' => $publish ? date('Y-m-d H:i:s') : null,
            'id'           => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM blogs WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function incrementViews(int $id): void
    {
        $this->db->prepare('UPDATE blogs SET views = views + 1 WHERE id = ?')->execute([$id]);
    }

    /** Dashboard counters. */
    public function stats(): array
    {
        $row = $this->db->query(
            "SELECT
                COUNT(*) AS total,
                SUM(status = 'published') AS published,
                SUM(status = 'draft') AS draft,
                COALESCE(SUM(views), 0) AS views
             FROM blogs"
        )->fetch();
        return array_map('intval', $row ?: []);
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $sql = 'SELECT 1 FROM blogs WHERE slug = ?';
        $params = [$slug];
        if ($exceptId !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $exceptId;
        }
        $stmt = $this->db->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }
}
