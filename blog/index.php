<?php
/**
 * Public blog listing.
 * - Lists published blogs, 6 per page, with pagination.
 * - Filter by category via ?category=<slug>.
 * Direct PDO connection (no API).
 */
declare(strict_types=1);
require_once __DIR__ . '/partials/config.php';
 
/* ------------------------------- helpers --------------------------------- */
function e(?string $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/* Base URL of this blog folder, e.g. "/blog" or "/cx_blog/blog".
   Lets clean links work regardless of trailing slash. */
$BASE = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

/* ------------------------------- inputs ---------------------------------- */
$perPage = 6;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$category = trim((string) ($_GET['category'] ?? ''));   // category slug
$q = trim((string) ($_GET['q'] ?? ''));                 // free-text search

/* --------------------------- search + filter ----------------------------- */
$where = ["b.status = 'published'"];
$params = [];

if ($category !== '') {
    $where[] = 'c.slug = :cat';
    $params[':cat'] = $category;
}
if ($q !== '') {
    $where[] = '(b.title LIKE :q1 OR b.short_description LIKE :q2 OR b.meta_keywords LIKE :q3 OR c.name LIKE :q4)';
    $like = '%' . $q . '%';
    $params[':q1'] = $like;
    $params[':q2'] = $like;
    $params[':q3'] = $like;
    $params[':q4'] = $like;
}
$whereSql = implode(' AND ', $where);

/* ------------------------------ total count ------------------------------ */
$countSql = "SELECT COUNT(*)
             FROM blogs b
             LEFT JOIN categories c ON c.id = b.category_id
             WHERE {$whereSql}";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / $perPage));

/* -------------------------------- blogs ---------------------------------- */
$listSql = "SELECT b.id, b.title, b.slug, b.short_description, b.featured_image, b.featured_thumb,
                   b.reading_time, b.author_name, b.published_at,
                   c.name AS category_name, c.slug AS category_slug
            FROM blogs b
            LEFT JOIN categories c ON c.id = b.category_id
            WHERE {$whereSql}
            ORDER BY COALESCE(b.published_at, b.created_at) DESC
            LIMIT :limit OFFSET :offset";
$listStmt = $pdo->prepare($listSql);
foreach ($params as $k => $v) {
    $listStmt->bindValue($k, $v, PDO::PARAM_STR);
}
$listStmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$listStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStmt->execute();
$blogs = $listStmt->fetchAll();

/* --------------------- categories (for filter bar) ----------------------- */
$categories = $pdo->query(
    "SELECT name, slug FROM categories WHERE status = 'active' ORDER BY name"
)->fetchAll();

/** Build a listing URL keeping the current search + category but changing the page. */
function pageUrl(int $p, string $category, string $base, string $search = ''): string
{
    $qs = [];
    if ($p > 1) {
        $qs['page'] = $p;
    }
    if ($category !== '') {
        $qs['category'] = $category;
    }
    if ($search !== '') {
        $qs['q'] = $search;
    }
    return $base . '/' . ($qs ? '?' . http_build_query($qs) : '');
}
/* Human-readable category label for the current filter (falls back to its slug). */
$categoryLabel = '';
if ($category !== '') {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $category) {
            $categoryLabel = $cat['name'];
            break;
        }
    }
    $categoryLabel = $categoryLabel !== '' ? $categoryLabel : $category;
}

//$pageTitle = 'Blog' . ($categoryLabel !== '' ? ' — ' . $categoryLabel : '') . ' | Cypherox';
$pageTitle = 'Blog | AI Development, Software Engineering & Industry Insights
';
//$metaDescription = $categoryLabel !== ''
//    ? 'Latest ' . $categoryLabel . ' articles, insights and tech trends from Cypherox.'
//    : 'Latest articles, insights and tech trends from Cypherox.';

$metaDescription = "Explore insights on AI agent development, software architecture, MLOps, and engineering best practices written by Cypherox technical leaders.";
/* ----------------------- social / SEO meta (listing) --------------------- */
$ogType     = 'website';
$ogUrl      = 'https://www.cypherox.com' . pageUrl($page, $category, '/blog', $q);
$canonical  = $ogUrl;
$ogImageAlt = $pageTitle;

$extraHead = '<style>
        .blog-card img { object-fit: cover; }
        .blog-card .card-text { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        /* Search form: text box + category dropdown + submit */
        .cx-search-form {
            display: flex; align-items: stretch; gap: 12px; flex-wrap: wrap;
            width: 100%; margin: 0 auto 30px; max-width: 100%;
        }
        .cx-search-input-wrap { flex: 1 1 260px; min-width: 200px; }
        .cx-search-input {
            color: #000;
            width: 100%; height: 100%; padding: 12px 16px; border: 1px solid #d9d9e0;
            border-radius: 10px; background: #fff; font-size: 15px; outline: none;
            transition: border-color .15s ease, box-shadow .15s ease; 
        }
            .cx-search-input::placeholder { color: #000; }
        .cx-search-input:focus { border-color: #F26E65; box-shadow: 0 0 0 3px rgba(242,110,101,.15); }
        .cx-search-submit {
            flex: 0 0 auto; padding: 12px 28px; border: 1px solid #F26E65; border-radius: 10px;
            background: #F26E65; color: #fff; font-size: 15px; font-weight: 600; cursor: pointer;
            transition: background .15s ease, box-shadow .15s ease;
        }
        .cx-search-submit:hover { background: #e15a51; box-shadow: 0 4px 14px rgba(242,110,101,.3); }
        .cx-search-summary { text-align: center; color: #555; font-size: 14px; margin: 0 0 24px; }
        .cx-search-clear { margin-left: 10px; color: #F26E65; text-decoration: underline; }

        /* Searchable single-select category dropdown */
        .cx-cat-select { position: relative; flex: 0 0 260px; max-width: 100%; text-align: left; }
        .cx-cat-toggle {
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
            width: 100%; padding: 15px 16px; border: 1px solid #d9d9e0; border-radius: 10px;
            background: #fff; color: #222; font-size: 15px; line-height: 1.2; cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .cx-cat-toggle:hover { border-color: #F26E65; }
        .cx-cat-select.is-open .cx-cat-toggle { border-color: #F26E65; box-shadow: 0 0 0 3px rgba(242,110,101,.15); }
        .cx-cat-label { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .cx-cat-caret { flex: 0 0 auto; color: #888; transition: transform .15s ease; }
        .cx-cat-select.is-open .cx-cat-caret { transform: rotate(180deg); }
        .cx-cat-menu {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0; z-index: 40;
            background: #fff; border: 1px solid #e2e2ea; border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,.12); overflow: hidden;
        }
        .cx-cat-search-wrap { padding: 10px; border-bottom: 1px solid #eee; }
        .cx-cat-search {
            width: 100%; padding: 9px 12px; border: 1px solid #d9d9e0; border-radius: 8px;
            font-size: 14px; outline: none;
        }
        .cx-cat-search:focus { border-color: #F26E65; }
        .cx-cat-options { list-style: none; margin: 0; padding: 6px; max-height: 260px; overflow-y: auto; }
        .cx-cat-option {
            padding: 10px 12px; border-radius: 8px; font-size: 14px; color: #333; cursor: pointer;
            transition: background .12s ease;
        }
        .cx-cat-option:hover, .cx-cat-option.is-active { background: #fbeceb; color: #F26E65; }
        .cx-cat-option.is-selected { background: #F26E65; color: #fff; font-weight: 600; }
        .cx-cat-empty { padding: 12px; text-align: center; color: #999; font-size: 14px; }
    </style>';
$extraHead .= '<link rel="stylesheet" href="' . ASSETS_URL . '/css/new-styles.css?v='.time().'">';
require __DIR__ . '/partials/header.php';
?>
<section class="gloabl-banner-inner-page position-relative">
    <div class="inner-page-wrapper">
        <div class="container">


            <div class="row">
                <div class="col-md-6 content-col p-0">


                    <div class="heading-main-sec d-flex flex-column" bis_skin_checked="1">
                        <h1 class="order-1"><span>Cypherox Blog Insights</span></h1>
                        <p class="order-2">Dive into our blog for expert insights, industry trends, and
                            actionable tips designed to help you grow and innovate.</p>

                    </div>
                </div>
                <div class="col-md-6 svg-draw-sec p-0 inner-banner-svg">
                    <svg width="1200" height="754" viewBox="0 0 1029 754" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1028 532.627L953.671 536.811L942.595 540.503L642.324 558.224C638.386 558.47 634.448 555.27 633.956 551.332C633.956 551.332 630.018 547.64 631.249 540.503L662.506 338.189C662.506 338.189 663.491 329.083 686.873 328.098C686.873 328.098 763.663 326.868 774.247 323.914C774.247 323.914 778.431 319.73 723.299 321.699C723.299 321.699 696.964 322.191 697.456 301.763C697.456 301.763 697.456 277.643 705.824 270.751C705.824 270.751 708.778 271.49 707.055 279.12C706.316 283.058 705.578 287.242 705.332 291.426C704.84 302.009 702.625 320.961 693.026 320.961C693.026 320.961 671.613 319.976 667.183 350.003L652.661 445.499C652.661 445.499 601.96 456.329 573.164 455.59C573.164 455.59 520.247 452.391 486.036 468.389C486.036 468.389 459.454 481.679 444.441 501.861C444.441 501.861 429.427 522.044 457.239 510.476C457.239 510.476 497.358 482.664 523.693 479.71C523.693 479.71 484.313 486.11 455.517 515.152C455.517 515.152 456.255 524.013 492.681 508.015C492.681 508.015 470.53 520.813 498.834 516.137C498.834 516.137 538.46 506.538 548.059 510.23C548.059 510.23 593.346 523.52 617.712 540.503C619.189 541.487 618.204 543.702 616.481 543.21C604.667 540.257 580.301 532.873 559.627 520.075C554.212 516.629 556.919 508.261 563.319 508.507C563.565 508.507 563.811 508.507 564.057 508.507C564.057 508.507 584.485 510.968 609.098 503.092C611.805 502.354 614.512 501.615 617.22 501.123C622.881 500.385 631.249 499.646 630.756 503.584L623.619 551.332L958.593 529.427L1009.29 318.007L790.491 324.899C790.491 324.899 790.491 203.314 689.334 187.562C689.334 187.562 660.784 182.147 646.262 176.24C646.262 176.24 632.233 170.087 615.743 184.362C615.743 184.362 570.948 239.494 532.307 224.972C532.307 224.972 519.509 217.096 525.416 205.775C526.892 203.067 529.6 201.098 532.799 200.606C537.722 199.868 545.844 199.376 550.274 201.098C552.243 201.837 552.243 204.544 550.274 205.283C544.613 207.498 532.061 210.943 526.154 201.837C523.693 197.899 526.646 192.976 531.076 193.223C533.784 193.469 537.23 193.961 542.152 195.192C542.152 195.192 562.58 200.114 549.29 203.56C549.29 203.56 534.03 204.052 535.999 189.038C535.999 189.038 536.491 187.316 537.722 185.593C539.937 182.639 544.613 182.147 547.321 184.854C548.305 185.839 548.305 187.069 545.352 187.562C545.352 187.562 533.292 188.054 528.369 181.901C528.369 181.901 522.708 174.763 528.861 165.657C528.861 165.657 538.214 152.858 531.076 139.814C531.076 139.814 520.247 124.8 494.65 123.323C494.65 123.323 485.79 121.354 492.189 110.279L499.08 99.2033C499.08 99.2033 484.067 107.325 487.02 127.261C487.02 127.261 482.098 153.843 504.003 162.211C504.003 162.211 523.939 170.579 529.354 151.135C529.354 151.135 531.076 145.967 537.722 146.705C537.722 146.705 543.875 146.705 545.352 161.719C545.352 161.719 549.29 183.131 566.272 185.593C566.272 185.593 583.747 190.761 587.685 171.81C587.685 171.81 590.392 159.996 580.301 153.843C580.301 153.843 558.396 137.845 547.074 147.936C547.074 147.936 579.317 158.519 590.638 159.504C590.638 159.504 629.28 131.199 647.985 123.816C647.985 123.816 675.305 108.802 675.059 74.0987C675.059 74.0987 679.243 42.841 642.324 37.9186C642.324 37.9186 651.923 24.6279 623.865 9.1221C618.204 5.9225 612.051 3.70739 605.652 2.72289C589.408 0.0155325 555.443 -0.722839 520.247 27.0891C511.387 33.9806 504.003 42.3488 498.834 52.1937C491.451 65.7305 486.282 85.4204 504.741 98.9572C504.741 98.9572 538.46 117.663 599.499 94.2809C599.499 94.2809 619.681 83.4514 613.774 98.9572C613.774 98.9572 597.53 115.201 613.036 126.769C613.036 126.769 620.912 128.984 621.404 142.275C621.404 142.275 622.388 150.643 630.756 135.876C630.756 135.876 642.817 118.893 652.908 130.461C652.908 130.461 660.045 141.044 646.262 154.581C646.262 154.581 625.588 167.38 627.311 190.269C627.311 190.269 625.096 252.046 590.638 265.829C590.638 265.829 547.813 287.98 530.092 245.155C530.092 245.155 513.356 187.069 505.48 181.162C505.48 181.162 502.034 180.67 500.065 185.1C500.065 185.1 488.743 195.684 458.962 197.653C458.962 197.653 403.584 207.744 383.402 247.616C383.402 247.616 345.253 321.207 341.561 349.757C341.561 349.757 340.085 359.356 335.162 366.493C335.162 366.493 325.809 379.784 340.331 386.183C340.331 386.183 344.515 389.383 358.298 389.383C364.697 389.383 370.85 390.121 377.003 391.844C383.402 393.813 390.54 397.013 394.724 402.674C394.724 402.674 405.8 412.519 403.338 430.732C403.338 430.732 399.646 461.005 379.218 466.174C379.218 466.174 386.356 440.331 359.528 425.809C359.528 425.809 346.976 418.918 324.825 418.672C322.364 418.672 319.656 417.933 317.933 416.457C315.472 414.241 313.503 410.303 318.426 403.904C318.426 403.904 330.486 379.292 352.391 381.999C352.391 381.999 378.234 386.43 393.493 408.581C393.493 408.581 400.877 426.055 407.522 417.195C407.522 417.195 408.999 410.303 406.292 400.459C404.569 393.813 400.631 388.152 395.708 383.476C392.263 380.03 387.34 376.092 382.664 372.647C379.957 370.678 377.988 367.97 377.249 364.771C376.511 361.817 377.249 358.618 381.926 358.371C381.926 358.371 387.34 358.864 395.216 365.509C395.216 365.509 408.261 375.108 405.061 357.879L403.338 296.841C403.338 296.841 417.86 266.813 406.046 480.449L432.135 486.602C432.873 486.848 432.873 488.079 431.889 488.079L405.8 488.325L385.371 553.301L521.231 556.255L526.4 525.489C526.646 524.259 524.677 523.52 524.185 524.751L513.602 549.856L379.957 544.933L398.908 491.032C399.4 489.801 397.677 489.063 396.939 489.801L357.559 537.549L201.517 534.842L249.511 489.309C251.972 486.848 255.172 485.371 258.618 484.879C262.309 484.633 266.986 485.617 269.939 491.278C269.939 491.278 271.662 495.708 270.924 504.569C270.924 504.569 271.908 524.751 305.627 513.922C305.627 513.922 283.722 510.722 276.831 497.185C276.831 497.185 291.106 521.059 327.04 511.214C327.04 511.214 292.337 495.954 284.707 480.695C284.707 480.695 299.72 506.292 331.716 507.276C331.716 507.276 343.284 502.6 333.931 493.986C333.931 493.986 302.92 474.049 300.213 469.865C300.213 469.865 319.164 486.356 338.608 495.462C338.608 495.462 340.085 512.691 348.945 520.075C348.945 520.075 349.191 499.4 343.776 491.032L339.838 495.462L344.022 490.786L344.269 490.54C344.269 490.54 345.253 498.416 348.453 497.923C348.453 497.923 361.99 485.863 359.528 476.018C359.528 476.018 347.714 454.852 328.517 449.437L337.377 472.819C337.377 472.819 346.73 475.772 344.022 489.555C344.022 489.555 351.16 479.71 337.623 471.834C337.623 471.834 322.61 467.896 319.656 455.836C319.656 455.836 316.703 444.515 313.995 437.869L300.951 400.705C300.951 400.705 299.474 397.259 303.658 395.536C303.658 395.536 306.858 394.552 309.319 400.951L328.025 449.683C328.025 449.683 306.612 435.654 297.259 440.577C297.259 440.577 281.753 442.546 272.893 462.482C270.678 467.158 269.447 472.327 268.955 477.741L268.216 483.402C268.216 483.402 262.31 481.433 252.711 495.954C252.711 495.954 244.835 508.753 233.513 521.551C220.469 536.319 201.763 544.687 182.073 544.933L112.666 545.425L1 753.5"
                            stroke="#F26E65" stroke-miterlimit="10" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="loaderWrapper innerPage">
        <div class="leftLine">
            <svg width="114" height="1078" viewBox="0 0 114 1078" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_368_86)">
                    <path class="drawline1 neon"
                        d="M113 0V390C111.5 477.5 73.4 528.2 71 533C62.6667 546.167 45.4 573.4 43 577C24.4996 607.5 1.00035 655 1 720V865"
                        stroke="#F26E65" stroke-linecap="round"></path>
                    <path class="drawline2 neon"
                        d="M79 0C79 256 78.5 512.002 78.5 768C78.5 835.5 42.4 883.4 40 887C22.5 914.5 4 940 1 1006V1078"
                        stroke="#F26E65" stroke-linecap="round"></path>
                    <path class="moveX neon" style="opacity:0;"
                        d="M42.6333 589.165C42.1661 588.915 41.6486 588.278 41.5023 587.749L40.3611 583.624C40.2358 583.377 40.1105 583.129 39.9103 583.022C39.7101 582.915 39.4057 582.843 39.1598 582.981L35.0955 584.322C34.5744 584.494 33.7572 584.418 33.29 584.168L29.0855 581.919C28.8186 581.777 28.6559 581.6 28.7683 581.389C28.8058 581.319 28.9474 581.215 29.1559 581.146L37.8347 578.398C38.1099 578.365 38.3265 578.12 38.4389 577.91C38.5514 577.7 38.6345 577.384 38.5093 577.137L35.9762 568.393C35.9177 568.181 35.9259 568.005 35.9634 567.935C36.0383 567.795 36.2467 567.726 36.5804 567.905L40.7849 570.153C41.2521 570.403 41.7695 571.04 41.9158 571.569L43.0571 575.694C43.1449 576.011 43.3076 576.188 43.5079 576.295C43.7081 576.402 44.0125 576.475 44.2584 576.336L48.3227 574.995C48.8437 574.823 49.661 574.9 50.1282 575.15L54.3326 577.398C54.5996 577.541 54.7623 577.718 54.6499 577.928C54.6124 577.998 54.4707 578.103 54.2623 578.172L45.5834 580.92C45.2708 581.023 45.0916 581.197 44.9792 581.408C44.8668 581.618 44.7836 581.934 44.9089 582.181L47.4419 590.925C47.5005 591.137 47.4923 591.312 47.4548 591.383C47.3798 591.523 47.1714 591.591 46.8377 591.413L42.6333 589.165Z"
                        fill="#F26E65"></path>
                </g>
                <defs>
                    <clipPath id="clip0_368_86">
                        <rect width="114" height="1078" fill="white" transform="matrix(1 0 0 -1 0 1078)"></rect>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <div class="rotateGlow">
            <svg width="3056" height="1664" viewBox="0 0 3056 1664" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g opacity="0.5" filter="url(#filter0_f_24_174)">
                    <ellipse cx="1528.03" cy="831.902" rx="514.258674" ry="201.031313"
                        transform="rotate(-14.9457 1528.03 831.902)" fill="#F26E65"></ellipse>
                </g>
                <defs>
                    <filter id="filter0_f_24_174" x="0.126953" y="0.337402" width="3055.81" height="1663.13"
                        filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                        <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"></feBlend>
                        <feGaussianBlur stdDeviation="250" result="effect1_foregroundBlur_24_174"></feGaussianBlur>
                    </filter>
                </defs>
            </svg>
        </div>
    </div>
</section>

<!-- Category filter -->
<section class="Our-Blogs-sec">
    <div class="circle-bg bg-top"></div>
    <div class="container">
        <div class="Our-Blogs-gallery-outer">
            <form class="cx-search-form" method="get" action="<?= e($BASE) ?>/" role="search">
                <div class="cx-search-input-wrap">
                    <input type="text" name="q" class="cx-search-input" value="<?= e($q) ?>"
                        placeholder="Search blogs…" autocomplete="off" aria-label="Search blogs">
                </div>

                <div class="cx-cat-select" id="cxCatSelect">
                    <input type="hidden" name="category" id="cxCatValue" value="<?= e($category) ?>">
                    <button type="button" class="cx-cat-toggle" id="cxCatToggle"
                        aria-haspopup="listbox" aria-expanded="false">
                        <span class="cx-cat-label"><?= $categoryLabel !== '' ? e($categoryLabel) : 'All Categories' ?></span>
                        <svg class="cx-cat-caret" width="12" height="8" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                            <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="cx-cat-menu" id="cxCatMenu" role="listbox" hidden>
                        <div class="cx-cat-search-wrap">
                            <input type="text" class="cx-cat-search" id="cxCatSearch"
                                placeholder="Search category…" autocomplete="off" aria-label="Search category">
                        </div>
                        <ul class="cx-cat-options" id="cxCatOptions">
                            <li class="cx-cat-option <?= $category === '' ? 'is-selected' : '' ?>"
                                role="option" data-slug="" data-name="All Categories">All Categories</li>
                            <?php foreach ($categories as $cat): ?>
                                <li class="cx-cat-option <?= $category === $cat['slug'] ? 'is-selected' : '' ?>"
                                    role="option" data-slug="<?= e($cat['slug']) ?>" data-name="<?= e($cat['name']) ?>">
                                    <?= e($cat['name']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="cx-cat-empty" id="cxCatEmpty" hidden>No category found.</div>
                    </div>
                </div>

                <button type="submit" class="cx-search-submit btn-primary">Search</button>
            </form>

            <?php if ($q !== '' || $category !== ''): ?>
                <p class="cx-search-summary">
                    <?= $total ?> result<?= $total === 1 ? '' : 's' ?>
                    <?php if ($q !== ''): ?> for &ldquo;<strong><?= e($q) ?></strong>&rdquo;<?php endif; ?>
                    <?php if ($categoryLabel !== ''): ?> in <strong><?= e($categoryLabel) ?></strong><?php endif; ?>
                    <a class="cx-search-clear" href="<?= e($BASE) ?>/">Clear</a>
                </p>
            <?php endif; ?>

            <?php if (!$blogs): ?>
                <div class="alert alert-info">No blogs found.</div>
            <?php else: ?>
                <div class="Our-Blogs-gallery-outer-block">
                    <div class="row grid">
                        <?php foreach ($blogs as $b): ?>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-4 grid-item element-item">
                                <div class="Our-Blogs-gallery-outer-block-box">
                                    <a href="<?= e($BASE) ?>/<?= urlencode($b['slug']) ?>" class="blog-link" aria-label="<?= e($b['title']) ?>" >
                                        <div class="card h-100">
                                            <?php $thumb = !empty($b['featured_thumb']) ? $b['featured_thumb'] : $b['featured_image']; ?>
                                            <?php if (!empty($thumb)): ?>
                                                <div class="card-img">
                                                    <img src="<?= e($thumb) ?>" class="card-img-top" alt="<?= e($b['title']) ?>"
                                                        width="100%" loading="lazy">
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <?php if (!empty($b['category_name'])): ?>
                                                    <span
                                                        class="badge bg-secondary align-self-start mb-2"><?= e($b['category_name']) ?></span>
                                                <?php endif; ?>
                                                <h5 class="card-title">
                                                    <?= e($b['title']) ?>
                                                </h5>
                                                <p class="card-text"><?= e($b['short_description']) ?></p>
                                                <?php /* <div class="mt-auto small">
                                                    <?php if (!empty($b['author_name'])): ?>
                                                        <?= e($b['author_name']) ?> ·
                                                    <?php endif; ?>
                                                    <?php if (!empty($b['published_at'])): ?>
                                                        <?= e(date('M j, Y', strtotime($b['published_at']))) ?>
                                                    <?php endif; ?>
                                                    <?php if (!empty($b['reading_time'])): ?>
                                                        · <?= (int) $b['reading_time'] ?> min read
                                                    <?php endif; ?>
                                                </div> */ ?>
                                            </div>
                                        </div>
                                        <div class="card-readmore">
                                            <span class="card-arrow d-flex align-items-center justify-content-center">
                                                <svg width="21" height="22" viewBox="0 0 21 22">
                                                    <path d="M14.3876 7.11042L5.90234 15.5957" stroke="#F26E65"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M6.60938 6.40332L15.1006 6.40927L15.0947 14.8886" stroke="#F26E65"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
<nav class="cx-pagination" aria-label="Blog pagination">

    <!-- First -->
    <a class="cx-page-btn <?= $page <= 1 ? 'disabled' : '' ?>"
       href="<?= e(pageUrl(1, $category, $BASE, $q)) ?>">First</a>

    <!-- Previous -->
    <a class="cx-page-btn <?= $page <= 1 ? 'disabled' : '' ?>"
       href="<?= e(pageUrl(max(1, $page - 1), $category, $BASE, $q)) ?>">Previous</a>

    <?php
    $range = 1; // Pages before and after current page

    // First two pages
    for ($i = 1; $i <= min(2, $totalPages); $i++):
    ?>
        <a class="cx-page-btn <?= $i == $page ? 'active' : '' ?>"
           href="<?= e(pageUrl($i, $category, $BASE, $q)) ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page > 4): ?>
        <span class="cx-page-dots">...</span>
    <?php endif; ?>

    <?php
    $start = max(3, $page - $range);
    $end   = min($totalPages - 2, $page + $range);

    for ($i = $start; $i <= $end; $i++):
    ?>
        <a class="cx-page-btn <?= $i == $page ? 'active' : '' ?>"
           href="<?= e(pageUrl($i, $category, $BASE, $q)) ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages - 3): ?>
        <span class="cx-page-dots">...</span>
    <?php endif; ?>

    <?php
    // Last two pages
    for ($i = max(3, $totalPages - 1); $i <= $totalPages; $i++):
    ?>
        <a class="cx-page-btn <?= $i == $page ? 'active' : '' ?>"
           href="<?= e(pageUrl($i, $category, $BASE, $q)) ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <!-- Next -->
    <a class="cx-page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>"
       href="<?= e(pageUrl(min($totalPages, $page + 1), $category, $BASE, $q)) ?>">Next</a>

    <!-- Last -->
    <a class="cx-page-btn <?= $page >= $totalPages ? 'disabled' : '' ?>"
       href="<?= e(pageUrl($totalPages, $category, $BASE, $q)) ?>">Last</a>

</nav>
<?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<script>
(function () {
    var root = document.getElementById('cxCatSelect');
    if (!root) return;

    var toggle  = document.getElementById('cxCatToggle');
    var menu    = document.getElementById('cxCatMenu');
    var search  = document.getElementById('cxCatSearch');
    var options = document.getElementById('cxCatOptions');
    var empty   = document.getElementById('cxCatEmpty');
    var hidden  = document.getElementById('cxCatValue');
    var label   = root.querySelector('.cx-cat-label');
    var items   = Array.prototype.slice.call(options.querySelectorAll('.cx-cat-option'));

    function openMenu() {
        root.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        menu.hidden = false;
        search.value = '';
        filter('');
        setTimeout(function () { search.focus(); }, 0);
    }
    function closeMenu() {
        root.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        menu.hidden = true;
        clearActive();
    }
    function clearActive() {
        items.forEach(function (li) { li.classList.remove('is-active'); });
    }
    function visibleItems() {
        return items.filter(function (li) { return li.style.display !== 'none'; });
    }
    function filter(q) {
        q = q.toLowerCase().trim();
        var any = false;
        items.forEach(function (li) {
            var match = li.getAttribute('data-name').toLowerCase().indexOf(q) !== -1;
            li.style.display = match ? '' : 'none';
            if (match) any = true;
        });
        empty.hidden = any;
        clearActive();
    }
    function select(li) {
        hidden.value = li.getAttribute('data-slug');
        label.textContent = li.getAttribute('data-name');
        items.forEach(function (o) { o.classList.remove('is-selected'); });
        li.classList.add('is-selected');
        closeMenu();
        toggle.focus();
    }

    toggle.addEventListener('click', function () {
        root.classList.contains('is-open') ? closeMenu() : openMenu();
    });

    search.addEventListener('input', function () { filter(search.value); });

    options.addEventListener('click', function (e) {
        var li = e.target.closest('.cx-cat-option');
        if (li) select(li);
    });

    search.addEventListener('keydown', function (e) {
        var vis = visibleItems();
        if (!vis.length) return;
        var idx = vis.findIndex(function (li) { return li.classList.contains('is-active'); });
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            clearActive();
            vis[(idx + 1) % vis.length].classList.add('is-active');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            clearActive();
            vis[(idx - 1 + vis.length) % vis.length].classList.add('is-active');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            select(idx >= 0 ? vis[idx] : vis[0]);
        } else if (e.key === 'Escape') {
            closeMenu();
            toggle.focus();
        }
    });

    document.addEventListener('click', function (e) {
        if (!root.contains(e.target)) closeMenu();
    });
})();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>