<?php
/**
 * Authentication + authorization middleware.
 * Extracts the Bearer token, validates the JWT, and exposes the auth user.
 */

declare(strict_types=1);

class AuthMiddleware
{
    /**
     * Require a valid JWT. Returns the decoded claims, or sends 401 and exits.
     *
     * @return array{id:int,role:string,email:string,name:string}
     */
    public static function authenticate(): array
    {
        $token = self::bearerToken();
        if ($token === null) {
            Response::error('Authorization token missing', 401);
        }

        $claims = Jwt::decode($token);
        if ($claims === null) {
            Response::error('Invalid or expired token', 401);
        }

        return [
            'id'    => (int) ($claims['sub'] ?? 0),
            'role'  => (string) ($claims['role'] ?? 'user'),
            'email' => (string) ($claims['email'] ?? ''),
            'name'  => (string) ($claims['name'] ?? ''),
        ];
    }

    /** Require the authenticated user to hold one of $roles. */
    public static function requireRole(array $user, array $roles): void
    {
        if (!in_array($user['role'], $roles, true)) {
            Response::error('Forbidden: insufficient permissions', 403);
        }
    }

    /** Pull the Bearer token from the Authorization header. */
    private static function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if ($header === '' && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }

        if (preg_match('/Bearer\s+(.+)$/i', $header, $m)) {
            return trim($m[1]);
        }

        return null;
    }
}
