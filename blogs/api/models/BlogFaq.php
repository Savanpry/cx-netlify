<?php

declare(strict_types=1);

/** Question/answer pairs shown in a blog's FAQ block. */
class BlogFaq
{
    public function __construct(private PDO $db) {}

    public function forBlog(int $blogId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, question, answer, sort_order
             FROM blog_faqs WHERE blog_id = ? ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([$blogId]);
        return $stmt->fetchAll();
    }

    /** Replace all FAQs for a blog from ['question'=>, 'answer'=>] rows. */
    public function syncForBlog(int $blogId, array $faqs): void
    {
        $this->db->prepare('DELETE FROM blog_faqs WHERE blog_id = ?')->execute([$blogId]);

        $stmt = $this->db->prepare(
            'INSERT INTO blog_faqs (blog_id, question, answer, sort_order)
             VALUES (?, ?, ?, ?)'
        );
        $order = 0;
        foreach ($faqs as $f) {
            $question = trim((string) ($f['question'] ?? ''));
            $answer   = (string) ($f['answer'] ?? '');
            if ($question === '') {
                continue;
            }
            $stmt->execute([$blogId, $question, $answer, $order++]);
        }
    }
}
