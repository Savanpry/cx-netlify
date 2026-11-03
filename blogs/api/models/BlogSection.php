<?php

declare(strict_types=1);

/**
 * Structured body sections for a blog (heading + content).
 * These drive the on-page Table of Contents.
 */
class BlogSection
{
    public function __construct(private PDO $db) {}

    /** Sections for a blog, ordered. */
    public function forBlog(int $blogId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, heading, content, sort_order
             FROM blog_sections WHERE blog_id = ? ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([$blogId]);
        return $stmt->fetchAll();
    }

    /**
     * Replace all sections for a blog from an array of
     * ['heading' => ?, 'content' => ?] rows (order = array order).
     */
    public function syncForBlog(int $blogId, array $sections): void
    {
        $this->db->prepare('DELETE FROM blog_sections WHERE blog_id = ?')->execute([$blogId]);

        $stmt = $this->db->prepare(
            'INSERT INTO blog_sections (blog_id, heading, content, sort_order)
             VALUES (?, ?, ?, ?)'
        );
        $order = 0;
        foreach ($sections as $s) {
            $heading = trim((string) ($s['heading'] ?? ''));
            $content = (string) ($s['content'] ?? '');
            if ($heading === '' && trim($content) === '') {
                continue; // skip empty rows
            }
            $stmt->execute([$blogId, $heading, $content, $order++]);
        }
    }
}
