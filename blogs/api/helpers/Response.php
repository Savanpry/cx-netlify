<?php
/**
 * JSON response helper. Centralises status codes + envelope shape.
 */

declare(strict_types=1);

class Response
{
    /** Send a JSON response and terminate. */
    public static function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /** Success envelope. Pass $meta to include pagination info. */
    public static function success($data = null, string $message = 'OK', int $status = 200, ?array $meta = null): never
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];
        if ($meta !== null) {
            $payload['meta'] = $meta;
        }
        self::json($payload, $status);
    }

    /** Error envelope. */
    public static function error(string $message = 'Error', int $status = 400, $errors = null): never
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
