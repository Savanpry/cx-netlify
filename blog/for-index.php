<?php
/**
 * Latest 6 published blogs for the home-page swiper.
 *
 * TEST PAGE — must never be indexed or crawled.
 * When opened directly it emits a full noindex/nofollow document
 * (meta robots + X-Robots-Tag header). When included by another page
 * it outputs only the swiper markup, so the host page is untouched.
 */
declare(strict_types=1);

require_once __DIR__ . '/partials/config.php';

if (!function_exists('e')) {
    function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
    }
}

/* Direct hit on this file, or included from another script? */
$IS_STANDALONE = isset($_SERVER['SCRIPT_FILENAME'])
    && realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__);

if ($IS_STANDALONE && !headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet', true);
}

$latestBlogs = [];
try {
    $stmt = $pdo->query(
        "SELECT b.title, b.slug, b.featured_image, b.featured_thumb,
                c.name AS category_name
         FROM blogs b
         LEFT JOIN categories c ON c.id = b.category_id
         WHERE b.status = 'published'
         ORDER BY COALESCE(b.published_at, b.created_at) DESC
         LIMIT 6"
    );
    $latestBlogs = $stmt->fetchAll();
} catch (Throwable $ex) {
    $latestBlogs = [];
}
?>
<?php if ($IS_STANDALONE): ?>
<html lang=en>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="robots" content="noindex,nofollow,noarchive,nosnippet">
<meta name="googlebot" content="noindex, nofollow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="charset" content="UTF-8">
<title>Blog slider test</title>
</head>
<body class="homepage before_load">
<?php endif; ?>

<div class=swiper-wrapper id="blogWrapper">

    <?php foreach ($latestBlogs as $b): ?>
        <?php
        $url = '/blog/' . rawurlencode((string) $b['slug']);
        $thumb = !empty($b['featured_thumb']) ? $b['featured_thumb'] : $b['featured_image'];
        ?>
        <div class=swiper-slide aria-label="Go to blog page">
            <div class=blog-post-slide>
                <a href="<?= e($url) ?>" aria-label="<?= e($b['title']) ?>" >
                    <div class=blog-img>
                        <img src="<?= e($thumb) ?>"
                            alt="<?= e($b['title']) ?>">
                    </div>
                    <div class=bottom-text>
                        <p><?= e($b['category_name']) ?></p>
                        <h3><?= e($b['title']) ?></h3>
                    </div>
                </a>
            </div>
            <a href="<?= e($url) ?>" aria-label="<?= e($b['title']) ?>" >
                <div class=blog-swiper-bottom-arrow>
                    <span>
                        <img src=../../../assets/img/post-arrow.svg alt=arrow-icon>
                    </span>
                </div>
                <span class=btn-text></span>
            </a>
        </div>
    <?php endforeach; ?>

</div>
<?php if ($IS_STANDALONE): ?>
</body>
</html>
<?php endif; ?>
