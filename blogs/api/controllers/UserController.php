<?php

declare(strict_types=1);

/**
 * Users CRUD. Admin-only — role enforcement happens in the router.
 */
class UserController
{
    public function __construct(
        private User $users,
        private ActivityLog $logs
    ) {}

    /** GET /api/users */
    public function index(): void
    {
        $p = Helpers::listParams();
        $filters = ['search' => $p['search']];
        if ($p['per_page'] !== null) {
            $filters['page']     = $p['page'];
            $filters['per_page'] = $p['per_page'];
            $total = $this->users->count($filters);
            Response::success(
                $this->users->all($filters), 'OK', 200,
                Helpers::paginationMeta($total, $p['page'], $p['per_page'])
            );
        }
        Response::success($this->users->all($filters));
    }

    /** GET /api/users/{id} */
    public function show(int $id): void
    {
        $user = $this->users->find($id);
        if (!$user) {
            Response::error('User not found', 404);
        }
        Response::success($user);
    }

    /** POST /api/users */
    public function store(array $authUser): void
    {
        $body = Helpers::jsonBody();
        $errors = Helpers::validate($body, [
            'name'     => 'required|max:150',
            'email'    => 'required|email|max:190',
            'password' => 'required|min:6',
            'role'     => 'in:admin,user',
        ]);
        if ($errors) {
            Response::error('Validation failed', 422, $errors);
        }
        if ($this->users->findByEmail($body['email'])) {
            Response::error('Email already in use', 409);
        }

        $id = $this->users->create($body);
        $this->logs->record($authUser['id'], 'create', 'user', $id);
        Response::success(['id' => $id], 'User created', 201);
    }

    /** PUT /api/users/{id} */
    public function update(int $id, array $authUser): void
    {
        if (!$this->users->find($id)) {
            Response::error('User not found', 404);
        }
        $body = Helpers::jsonBody();
        $this->users->update($id, $body);
        $this->logs->record($authUser['id'], 'update', 'user', $id);
        Response::success(null, 'User updated');
    }

    /** DELETE /api/users/{id} */
    public function destroy(int $id, array $authUser): void
    {
        $target = $this->users->find($id);
        if (!$target) {
            Response::error('User not found', 404);
        }
        if ($id === $authUser['id']) {
            Response::error('You cannot delete your own account', 400);
        }
        $this->users->delete($id);
        $this->logs->record($authUser['id'], 'delete', 'user', $id);
        Response::success(null, 'User deleted');
    }
}
