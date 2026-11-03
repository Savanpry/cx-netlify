<?php

declare(strict_types=1);

/** GET /api/dashboard — aggregate counters for the admin dashboard. */
class DashboardController
{
    public function __construct(
        private Blog $blogs,
        private PDO $db,
        private ActivityLog $logs
    ) {}

    public function index(array $authUser): void
    {
        $blogStats = $this->blogs->stats();

        $userCount = (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();

        // Blogs created per month for the current year.
        $monthly = $this->db->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
             FROM blogs
             WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
             GROUP BY month ORDER BY month"
        )->fetchAll();

        Response::success([
            'total_blogs'     => $blogStats['total'] ?? 0,
            'published_blogs' => $blogStats['published'] ?? 0,
            'draft_blogs'     => $blogStats['draft'] ?? 0,
            'total_views'     => $blogStats['views'] ?? 0,
            'total_users'     => $userCount,
            'monthly'         => $monthly,
            'recent_activity' => $this->logs->recent(10),
        ]);
    }
}
