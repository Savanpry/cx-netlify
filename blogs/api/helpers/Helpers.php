<?php
/**
 * Assorted small helpers: slugs, JSON body parsing, validation, logging.
 */

declare(strict_types=1);

class Helpers
{
    /** Build a URL-safe slug from arbitrary text. */
    public static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text) ?? '';
        $text = trim($text, '-');
        $text = function_exists('iconv')
            ? (iconv('utf-8', 'us-ascii//TRANSLIT', $text) ?: $text)
            : $text;
        $text = strtolower(preg_replace('~[^-\w]+~', '', $text) ?? '');
        return $text !== '' ? $text : substr(md5((string) microtime(true)), 0, 8);
    }

    /** Decode the JSON request body into an array (empty array on failure). */
    public static function jsonBody(): array
    {
        $raw = file_get_contents('php://input') ?: '';
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    /** Estimate reading time (minutes) from HTML/text content. */
    public static function readingTime(string $content): int
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int) ceil($words / 200));
    }

    /** Client IP, best-effort. */
    public static function clientIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Read list query params: ['search' => string, 'page' => int|null,
     * 'per_page' => int|null]. page/per_page are null when the caller did not
     * request pagination (so list endpoints stay backward-compatible).
     */
    public static function listParams(int $maxPerPage = 100): array
    {
        $search   = trim((string) ($_GET['search'] ?? ''));
        $paginate = isset($_GET['page']) || isset($_GET['per_page']);
        if (!$paginate) {
            return ['search' => $search, 'page' => null, 'per_page' => null];
        }
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) ($_GET['per_page'] ?? 10);
        $perPage = max(1, min($maxPerPage, $perPage));
        return ['search' => $search, 'page' => $page, 'per_page' => $perPage];
    }

    /** Build a pagination meta block for the response envelope. */
    public static function paginationMeta(int $total, int $page, int $perPage): array
    {
        return [
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'total_pages' => max(1, (int) ceil($total / $perPage)),
        ];
    }

    /**
     * Validate $data against simple rules: ['field' => 'required|email|max:255'].
     * Returns array of error messages keyed by field (empty when valid).
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $ruleStr) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $ruleStr) as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                switch ($name) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $errors[$field][] = "$field is required";
                        }
                        break;
                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "$field must be a valid email";
                        }
                        break;
                    case 'min':
                        if ($value !== null && strlen((string) $value) < (int) $param) {
                            $errors[$field][] = "$field must be at least $param characters";
                        }
                        break;
                    case 'max':
                        if ($value !== null && strlen((string) $value) > (int) $param) {
                            $errors[$field][] = "$field must not exceed $param characters";
                        }
                        break;
                    case 'in':
                        if ($value !== null && !in_array($value, explode(',', (string) $param), true)) {
                            $errors[$field][] = "$field is invalid";
                        }
                        break;
                }
            }
        }
        return $errors;
    }
}
