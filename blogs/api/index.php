<?php
/**
 * API front controller / router.
 *
 * All requests are rewritten here by .htaccess. Routes are matched against
 * the path segments that follow the "api" segment in the URL, e.g.
 *   /cx_blog/api/blogs/12/publish  ->  ['blogs', '12', 'publish']
 */

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

// Reduce the URL to the segments after the "api" segment.
$segments = array_values(array_filter(explode('/', $path), fn($s) => $s !== ''));
$apiIndex = array_search('api', $segments, true);
$route = $apiIndex === false ? $segments : array_slice($segments, $apiIndex + 1);

$db = Database::connection();

// -- service container --------------------------------------------------
$logs       = new ActivityLog($db);
$users      = new User($db);
$blogs      = new Blog($db);
$sections   = new BlogSection($db);
$faqs       = new BlogFaq($db);
$categories = new Category($db);

$resource = $route[0] ?? '';
$id       = isset($route[1]) && ctype_digit($route[1]) ? (int) $route[1] : null;
$action   = $route[2] ?? null;

// ======================================================================
//  Routing
// ======================================================================
switch ($resource) {

    // ---- Auth --------------------------------------------------------
    case 'auth':
        $auth = new AuthController($users, $logs);
        if ($method === 'POST' && ($route[1] ?? '') === 'login') {
            $auth->login();
        }
        if ($method === 'GET' && ($route[1] ?? '') === 'me') {
            $auth->me(AuthMiddleware::authenticate());
        }
        Response::error('Not found', 404);

    // ---- Dashboard ---------------------------------------------------
    case 'dashboard':
        $authUser = AuthMiddleware::authenticate();
        (new DashboardController($blogs, $db, $logs))->index($authUser);

    // ---- Users (admin only) -----------------------------------------
    case 'users':
        $authUser = AuthMiddleware::authenticate();
        AuthMiddleware::requireRole($authUser, ['admin']);
        $c = new UserController($users, $logs);
        match (true) {
            $method === 'GET'    && $id === null => $c->index(),
            $method === 'GET'    && $id !== null => $c->show($id),
            $method === 'POST'                   => $c->store($authUser),
            $method === 'PUT'    && $id !== null => $c->update($id, $authUser),
            $method === 'DELETE' && $id !== null => $c->destroy($id, $authUser),
            default                              => Response::error('Method not allowed', 405),
        };
        break;

    // ---- Blogs -------------------------------------------------------
    case 'blogs':
        $authUser = AuthMiddleware::authenticate();
        $c = new BlogController($blogs, $sections, $faqs, $logs);

        if ($method === 'POST' && $id !== null && $action === 'publish') {
            AuthMiddleware::requireRole($authUser, ['admin']);
            $c->publish($id, $authUser);
        }

        match (true) {
            $method === 'GET'    && $id === null => $c->index($authUser),
            $method === 'GET'    && $id !== null => $c->show($id, $authUser),
            $method === 'POST'                   => $c->store($authUser),
            $method === 'PUT'    && $id !== null => $c->update($id, $authUser),
            $method === 'DELETE' && $id !== null => $c->destroy($id, $authUser),
            default                              => Response::error('Method not allowed', 405),
        };
        break;

    // ---- Categories --------------------------------------------------
    case 'categories':
        $authUser = AuthMiddleware::authenticate();
        $c = new CategoryController($categories, $logs);
        $writes = in_array($method, ['POST', 'PUT', 'DELETE'], true);
        if ($writes) {
            AuthMiddleware::requireRole($authUser, ['admin']);
        }
        match (true) {
            $method === 'GET'    && $id === null => $c->index(),
            $method === 'GET'    && $id !== null => $c->show($id),
            $method === 'POST'                   => $c->store($authUser),
            $method === 'PUT'    && $id !== null => $c->update($id, $authUser),
            $method === 'DELETE' && $id !== null => $c->destroy($id, $authUser),
            default                              => Response::error('Method not allowed', 405),
        };
        break;

    // ---- Upload ------------------------------------------------------
    case 'upload':
        $authUser = AuthMiddleware::authenticate();
        if ($method !== 'POST') {
            Response::error('Method not allowed', 405);
        }
        (new UploadController())->store($authUser);

    // ---- Fallback ----------------------------------------------------
    default:
        Response::success([
            'name'    => 'CX Blog API',
            'version' => '1.0.0',
            'docs'    => 'See README.md for endpoints',
        ], 'API is running');
}
