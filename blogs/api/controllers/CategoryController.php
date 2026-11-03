<?php

declare(strict_types=1);

/** Categories CRUD. All authenticated users may create/update/delete. */
class CategoryController
{
    public function __construct(
        private Category $categories,
        private ActivityLog $logs
    ) {}

    public function index(): void
    {
        $p = Helpers::listParams();
        $filters = ['search' => $p['search']];
        if ($p['per_page'] !== null) {
            $filters['page']     = $p['page'];
            $filters['per_page'] = $p['per_page'];
            $total = $this->categories->count($filters);
            Response::success(
                $this->categories->all($filters), 'OK', 200,
                Helpers::paginationMeta($total, $p['page'], $p['per_page'])
            );
        }
        Response::success($this->categories->all($filters));
    }

    public function show(int $id): void
    {
        $cat = $this->categories->find($id);
        if (!$cat) {
            Response::error('Category not found', 404);
        }
        Response::success($cat);
    }

    public function store(array $authUser): void
    {
        $body = Helpers::jsonBody();
        $errors = Helpers::validate($body, ['name' => 'required|max:150']);
        if ($errors) {
            Response::error('Validation failed', 422, $errors);
        }
        $body['slug'] = Helpers::slugify($body['slug'] ?? $body['name']);
        $id = $this->categories->create($body);
        $this->logs->record($authUser['id'], 'create', 'category', $id);
        Response::success(['id' => $id], 'Category created', 201);
    }

    public function update(int $id, array $authUser): void
    {
        if (!$this->categories->find($id)) {
            Response::error('Category not found', 404);
        }
        $body = Helpers::jsonBody();
        if (!empty($body['slug'])) {
            $body['slug'] = Helpers::slugify($body['slug']);
        }
        $this->categories->update($id, $body);
        $this->logs->record($authUser['id'], 'update', 'category', $id);
        Response::success(null, 'Category updated');
    }

    public function destroy(int $id, array $authUser): void
    {
        if (!$this->categories->find($id)) {
            Response::error('Category not found', 404);
        }
        $this->categories->delete($id);
        $this->logs->record($authUser['id'], 'delete', 'category', $id);
        Response::success(null, 'Category deleted');
    }
}
