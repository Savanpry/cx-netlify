<?php
/**
 * PDO database connection (singleton).
 * Local WAMP defaults: host=localhost, user=root, no password, db=cx_blog.
 */

declare(strict_types=1);

class Database
{
    private const HOST    = 'localhost';
    private const PORT    = '3306';
    private const NAME    = DB_NAME;
    private const USER    = DB_USERNAME;
    private const PASS    = DB_PASSWORD;
    private const CHARSET = 'utf8mb4';

    private static ?PDO $instance = null;

    /** Return a shared PDO connection. */
    public static function connection(): PDO
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            self::HOST,
            self::PORT,
            self::NAME,
            self::CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$instance = new PDO($dsn, self::USER, self::PASS, $options);

        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
