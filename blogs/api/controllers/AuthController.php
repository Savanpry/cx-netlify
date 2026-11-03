<?php

declare(strict_types=1);

class AuthController
{
    public function __construct(
        private User $users,
        private ActivityLog $logs
    ) {}

    /** POST /api/auth/login  { email, password } */
    public function login(): void
    {
        $body = Helpers::jsonBody();
        
        $errors = Helpers::validate($body, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if ($errors) {
            Response::error('Validation failed', 422, $errors);
        }

        $user = $this->users->findByEmail($body['email']);
        if (!$user || !password_verify($body['password'], $user['password'])) {
            Response::error('Invalid credentials', 401);
        }
        if ($user['status'] !== 'active') {
            Response::error('Account is inactive', 403);
        }

        $token = Jwt::encode([
            'sub'   => (int) $user['id'],
            'role'  => $user['role'],
            'email' => $user['email'],
            'name'  => $user['name'],
        ]);

        $this->logs->record((int) $user['id'], 'login', 'user', (int) $user['id']);

        Response::success([
            'token' => $token,
            'user'  => [
                'id'    => (int) $user['id'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'role'  => $user['role'],
            ],
        ], 'Login successful');
    }

    /** GET /api/auth/me — return the current authenticated user. */
    public function me(array $authUser): void
    {
        $user = $this->users->find($authUser['id']);
        if (!$user) {
            Response::error('User not found', 404);
        }
        Response::success($user);
    }
}
