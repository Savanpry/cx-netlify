<?php

declare(strict_types=1);

class ActivityLog
{
    public function __construct(private PDO $db) {}

    public function record(?int $userId, string $action, ?string $entityType = null, ?int $entityId = null): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO activity_logs (user_id, action, entity_type, entity_id, ip_address)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $action, $entityType, $entityId, Helpers::clientIp()]);
    }

    public function recent(int $limit = 20): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, u.name AS user_name
             FROM activity_logs a
             LEFT JOIN users u ON u.id = a.user_id
             ORDER BY a.created_at DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
