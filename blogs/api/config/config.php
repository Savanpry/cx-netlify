<?php
/**
 * Global API configuration.
 * Adjust values per-environment. Do NOT commit real secrets in production.
 */

declare(strict_types=1);

// ---- Error reporting (dev) ------------------------------------------- 
date_default_timezone_set('UTC');

define('APP_ENV', 'production');           // development | production
define('APP_MODE', 'production');           // development | production

if( APP_MODE == 'development' ) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    define('API_BASE_URL', 'http://localhost/cx_blog/api');
    
    // ---- CORS ------------------------------------------------------------
    define('CORS_ALLOWED_ORIGINS', ['http://localhost', 'http://localhost:80']);

    define("DB_NAME","cx_blog");
    define("DB_USERNAME","root");
    define("DB_PASSWORD","");
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);

    define('API_BASE_URL', 'https://www.cypherox.com/blogs/api');
    
    // ---- CORS ------------------------------------------------------------
    define('CORS_ALLOWED_ORIGINS', ['https://www.cypherox.com', 'https://cypherox.com','https://cxblogs.flowcodes.in',  'https://www.cxblogs.flowcodes.in', 'https://cxblogs.flowcodes.in', 'https://www.cypherox.com/blogs', 'https://cypherox.com/blogs']);

    define("DB_NAME","cypherox_cxblogs");
    define("DB_USERNAME","cypherox_cxblogs");
    define("DB_PASSWORD","CxBlogs@2026");
}
// ---- Application ----------------------------------------------------- 
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('UPLOAD_URL', API_BASE_URL . '/uploads');
define('MAX_UPLOAD_BYTES', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

// Featured-image dimensions. A "featured" upload is normalised to the full size
// and a thumbnail copy is generated alongside it (cover-cropped, centred).
define('FEATURED_IMAGE_WIDTH', 1920);
define('FEATURED_IMAGE_HEIGHT', 1080);
define('FEATURED_THUMB_WIDTH', 380);
define('FEATURED_THUMB_HEIGHT', 220);
define('AUTHOR_AVATAR_WIDTH', 100);
define('AUTHOR_AVATAR_HEIGHT', 100);

// ---- JWT -------------------------------------------------------------
define('JWT_SECRET', 'change-this-super-secret-key-in-production');
define('JWT_ISSUER', 'cx_blog_api');
define('JWT_TTL', 60 * 60 * 8); // 8 hours (seconds)

