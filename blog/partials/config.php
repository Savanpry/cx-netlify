<?php 


define('APP_ENV', 'production');           // development | production

define('APP_MODE', 'production');           // development | production


if( APP_MODE == 'development' ) {
    define('BASE_URL', 'http://localhost/cx_blog');
    define('ASSETS_URL', BASE_URL . '/assets');

    error_reporting(E_ALL);
    ini_set('display_errors', '0');

} else {
    define('BASE_URL', 'https://www.cypherox.com/blog');
    define('ASSETS_URL', BASE_URL . '/assets');

    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}


/* ----------------------------- DB connection ----------------------------- */
if( APP_MODE == 'development' ) {
    
    $pdo = new PDO(
        'mysql:host=localhost;port=3306;dbname=cx_blog;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}else{
    $pdo = new PDO(
        'mysql:host=localhost;port=3306;dbname=cypherox_cxblogs;charset=utf8mb4',
        'cypherox_cxblogs',
        'CxBlogs@2026',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
}

?>