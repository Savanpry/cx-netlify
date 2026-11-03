<?php
/**
 * Dynamic blog sitemap.
 * Outputs an XML sitemap built from the database:
 *   - blog home + news (static)
 *   - one <url> per active category   -> /blog/<category-slug>/
 *   - one <url> per published blog     -> /blog/<slug>
 *
 * <lastmod> uses each row's updated_at (falls back to published_at / created_at).
 * Served as XML; query the database via the same PDO connection as the site.
 */
declare(strict_types=1);

require_once __DIR__ . '/blog/partials/config.php';

/* Public base for live URLs (no trailing slash). The clean blog URLs live at
   https://www.cypherox.com/blog/... regardless of the local dev path. */
const SITEMAP_BLOG_BASE = 'https://www.cypherox.com/blog';
const SITEMAP_NEWS_BASE = 'https://www.cypherox.com/news';

/* Escape a value for inclusion in XML text. */
function sx(?string $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

/* Format a DB timestamp as W3C date (YYYY-MM-DD); null/blank -> '' (omitted). */
function lastmod(?string $ts): string
{
    if (empty($ts) || $ts === '0000-00-00 00:00:00') {
        return '';
    }
    $t = strtotime($ts);
    return $t ? date('Y-m-d', $t) : '';
}

/* Collect URL entries, then render once. */
$urls = [];

/* Static: blog home. */
$urls[] = [
    'loc'        => SITEMAP_BLOG_BASE . '/',
    'changefreq' => 'daily',
    'priority'   => '0.9',
];

/* Dynamic: active categories. */
$catStmt = $pdo->query(
    "SELECT slug, updated_at FROM categories WHERE status = 'active' ORDER BY name"
);
foreach ($catStmt as $cat) {
    $urls[] = [
        'loc'        => SITEMAP_BLOG_BASE . '/?category=' . rawurlencode($cat['slug']) ,
        'lastmod'    => lastmod($cat['updated_at'] ?? null),
        'changefreq' => 'weekly',
        'priority'   => '0.8',
    ];
}

/* Dynamic: published blog articles (newest first). */
$blogStmt = $pdo->query(
    "SELECT slug, updated_at, published_at, created_at
     FROM blogs
     WHERE status = 'published'
     ORDER BY COALESCE(published_at, created_at) DESC"
);
foreach ($blogStmt as $b) {
    $urls[] = [
        'loc'        => SITEMAP_BLOG_BASE . '/' . rawurlencode($b['slug']),
        'lastmod'    => lastmod($b['updated_at'] ?? $b['published_at'] ?? $b['created_at'] ?? null),
        'changefreq' => 'monthly',
        'priority'   => '0.7',
    ];
}

/* Static: news section. 
$urls[] = [
    'loc'        => SITEMAP_NEWS_BASE . '/',
    'changefreq' => 'weekly',
    'priority'   => '0.8',
];
$urls[] = [
    'loc'        => SITEMAP_NEWS_BASE . '/animal-welfare-csr-street-dog-feeding-rajkot',
    'changefreq' => 'monthly',
    'priority'   => '0.7',
];*/

/* ------------------------------- render ---------------------------------- */
header('Content-Type: application/xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
 
echo '
  <!-- Blog Main & Categories -->
  <url>
    <loc>https://www.cypherox.com/blog/</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>

  <url>
    <loc>https://www.cypherox.com/blog/web-development/</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://www.cypherox.com/blog/app-development/</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://www.cypherox.com/blog/ecommerce-development/</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://www.cypherox.com/blog/digital-marketing/</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://www.cypherox.com/blog/ai-development/</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <url>
    <loc>https://www.cypherox.com/blog/trending-tech/</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>

  <!-- Blog Articles -->
  <url><loc>https://www.cypherox.com/blog/why-business-needs-a-professional-website-design</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/web-development-services-business-need</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/right-types-of-web-development-services-for-business</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/web-development-role-for-building-strong-online-presence</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/web-development-customer-engagement-retention</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/key-features-professional-website-development</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/increase-ecommerce-conversion-rate</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/challenges-in-blockchain-app-development-and-solutions</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/scalable-and-secure-online-ecommerce-store</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/beginners-guide-to-webflow-no-code-design</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/ai-app-development-services-for-startups</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/ecommerce-video-marketing-product-demos-and-ugc</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/ai-in-digital-marketing-how-brands-stay-ahead</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/what-is-answer-engine-optimization</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/how-to-build-character-ai-clone</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/how-to-build-crushon-ai-clone-app</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/how-ai-trends-and-artificial-intelligence-changing-technology</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/how-ai-agents-transforming-workflows-through-automation</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/openclaw-atrophic-ai-agent-guide-2026</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/agentic-ai-software-development-business-growth</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/pwa-development-services-pricing</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/ada-health-clone-solutions</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/multipass-ai-clone-solutions-guide</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/polybuzz-ai-clone-solutions</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/rizz-ai-clone-solutions</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/how-to-build-ai-companion-app-like-replika</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/how-to-build-ai-influencer-generator-clone</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/opus-clip-ai-clone-development-guide</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/talkie-ai-clone-development</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc>https://www.cypherox.com/blog/glean-ai-clone-development</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>


   <!-- News -->
  <url><loc>https://www.cypherox.com/news/</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc>https://www.cypherox.com/news/animal-welfare-csr-street-dog-feeding-rajkot</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
';

foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . sx($u['loc']) . "</loc>\n";
    if (!empty($u['lastmod'])) {
        echo '    <lastmod>' . sx($u['lastmod']) . "</lastmod>\n";
    }
    echo '    <changefreq>' . sx($u['changefreq']) . "</changefreq>\n";
    echo '    <priority>' . sx($u['priority']) . "</priority>\n";
    echo "  </url>\n";
}

echo "</urlset>\n";
