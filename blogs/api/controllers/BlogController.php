<?php

declare(strict_types=1);

/**
 * Blogs CRUD + publish. All authenticated users may view/edit/delete any
 * blog; only admins may publish/unpublish (enforced in the router).
 */
class BlogController
{
    public function __construct(
        private Blog $blogs,
        private BlogSection $sections,
        private BlogFaq $faqs,
        private ActivityLog $logs
    ) {}

    /** GET /api/blogs — all authenticated users see every blog (with filters). */
    public function index(array $authUser): void
    {
        $p = Helpers::listParams();
        $filters = array_filter([
            'status'      => $_GET['status'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'search'      => $p['search'],
        ]);

        if ($p['per_page'] !== null) {
            $total = $this->blogs->count($filters);
            $filters['page']     = $p['page'];
            $filters['per_page'] = $p['per_page'];
            Response::success(
                $this->blogs->all($filters), 'OK', 200,
                Helpers::paginationMeta($total, $p['page'], $p['per_page'])
            );
        }
        Response::success($this->blogs->all($filters));
    }

    /** GET /api/blogs/{id} */
    public function show(int $id, array $authUser): void
    {
        $blog = $this->mustFindOwned($id, $authUser);
        $blog['sections'] = $this->sections->forBlog($id);
        $blog['faqs']     = $this->faqs->forBlog($id);
        Response::success($blog);
    }

    /** POST /api/blogs */
    public function store(array $authUser): void
    {
        $body = Helpers::jsonBody();
        $errors = Helpers::validate($body, [
            'title'  => 'required|max:255',
            'status' => 'in:draft,published,archived',
        ]);
        if ($errors) {
            Response::error('Validation failed', 422, $errors);
        }

        // Slug is set at creation: use the user-entered slug if given, otherwise
        // derive it from the title. It is frozen afterwards (never changes on edit).
        $slugInput = trim((string) ($body['slug'] ?? ''));
        $slug = Helpers::slugify($slugInput !== '' ? $slugInput : $body['title']);
        if ($this->blogs->slugExists($slug)) {
            Response::error('A blog with this slug already exists — choose a different one', 409, [
                'slug' => [$slug],
            ]);
        }

        $body['user_id'] = $authUser['id'];
        $body['slug'] = $slug;
        $body['reading_time'] = Helpers::readingTime($body['content'] ?? '');
        $body['author_name'] = $body['author_name'] ?? $authUser['name'];

        $id = $this->blogs->create($body);

        if (isset($body['sections']) && is_array($body['sections'])) {
            $this->sections->syncForBlog($id, $body['sections']);
        }
        if (isset($body['faqs']) && is_array($body['faqs'])) {
            $this->faqs->syncForBlog($id, $body['faqs']);
        }

        $this->logs->record($authUser['id'], 'create', 'blog', $id);
        Response::success(['id' => $id], 'Blog created', 201);
    }

    /** PUT /api/blogs/{id} */
    public function update(int $id, array $authUser): void
    {
        $this->mustFindOwned($id, $authUser);
        $body = Helpers::jsonBody();

        // The slug is set at creation and frozen afterwards — editing never
        // changes it, even if the title changes.
        unset($body['slug']);
        if (isset($body['content'])) {
            $body['reading_time'] = Helpers::readingTime($body['content']);
        }

        $this->blogs->update($id, $body);

        if (isset($body['sections']) && is_array($body['sections'])) {
            $this->sections->syncForBlog($id, $body['sections']);
        }
        if (isset($body['faqs']) && is_array($body['faqs'])) {
            $this->faqs->syncForBlog($id, $body['faqs']);
        }

        $this->logs->record($authUser['id'], 'update', 'blog', $id);
        Response::success(null, 'Blog updated');
    }

    /** DELETE /api/blogs/{id} */
    public function destroy(int $id, array $authUser): void
    {
        $this->mustFindOwned($id, $authUser);
        $this->blogs->delete($id);
        $this->logs->record($authUser['id'], 'delete', 'blog', $id);
        Response::success(null, 'Blog deleted');
    }

    /** POST /api/blogs/{id}/publish — admin only (enforced in router). */
    public function publish(int $id, array $authUser): void
    {
        if (!$this->blogs->find($id)) {
            Response::error('Blog not found', 404);
        }
        $body = Helpers::jsonBody();
        $publish = ($body['publish'] ?? true) ? true : false;
        $this->blogs->publish($id, $publish);
        $this->logs->record($authUser['id'], $publish ? 'publish' : 'unpublish', 'blog', $id);
        Response::success(null, $publish ? 'Blog published' : 'Blog unpublished');
    }

    // -- helpers --------------------------------------------------------

    private function mustFindOwned(int $id, array $authUser): array
    {
        $blog = $this->blogs->find($id);
        if (!$blog) {
            Response::error('Blog not found', 404);
        }
        // All authenticated users may view/edit/delete any blog.
        return $blog;
    }
}
