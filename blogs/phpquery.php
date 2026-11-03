<?php
/**
 * ONE-TIME importer for the 30 legacy blogs that still live on the production
 * site (cypherox.com) under the OLD theme.
 *
 * For each slug it fetches the live page, scrapes the article (intro, content
 * sections, conclusion, FAQs) and the head meta (title, description, og:image),
 * then inserts it as a published blog using the SAME slug.
 *
 *   - category_id is left NULL (uncategorised — assign later in admin).
 *   - featured_image = the page's og:image (absolute URL).
 *
 * Run from the project root:
 *     php database/import_old_blogs.php
 *
 * Safe to re-run: an existing blog with the same slug is deleted first
 * (cascading its sections + faqs) and re-imported fresh.
 */
declare(strict_types=1);

const LIVE_BASE = 'https://www.cypherox.com/blog/';

/** The 30 legacy slugs to import (order preserved). */
const SLUGS = [
    'why-business-needs-a-professional-website-design',
    'web-development-services-business-need',
    'right-types-of-web-development-services-for-business',
    'web-development-role-for-building-strong-online-presence',
    'web-development-customer-engagement-retention',
    'key-features-professional-website-development',
    'increase-ecommerce-conversion-rate',
    'challenges-in-blockchain-app-development-and-solutions',
    'scalable-and-secure-online-ecommerce-store',
    'beginners-guide-to-webflow-no-code-design',
    'ai-app-development-services-for-startups',
    'ecommerce-video-marketing-product-demos-and-ugc',
    'ai-in-digital-marketing-how-brands-stay-ahead',
    'what-is-answer-engine-optimization',
    'how-to-build-character-ai-clone',
    'how-to-build-crushon-ai-clone-app',
    'how-ai-trends-and-artificial-intelligence-changing-technology',
    'how-ai-agents-transforming-workflows-through-automation',
    'openclaw-atrophic-ai-agent-guide-2026',
    'agentic-ai-software-development-business-growth',
    'pwa-development-services-pricing',
    'ada-health-clone-solutions',
    'multipass-ai-clone-solutions-guide',
    'polybuzz-ai-clone-solutions',
    'rizz-ai-clone-solutions',
    'how-to-build-ai-companion-app-like-replika',
    'how-to-build-ai-influencer-generator-clone',
    'opus-clip-ai-clone-development-guide',
    'talkie-ai-clone-development',
    'glean-ai-clone-development',
];

/* ------------------------------- DB ------------------------------------- */
$pdo = new PDO(
    'mysql:host=localhost;port=3306;dbname=cypherox_cxblogs;charset=utf8mb4',
    'cypherox_cxblogs',
    'CxBlogs@2026',
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
);

/* Author every imported blog to the first (admin) user. */
$userId = (int) $pdo->query('SELECT id FROM users ORDER BY id LIMIT 1')->fetchColumn();
if (!$userId) {
    fwrite(STDERR, "No users in DB — run database/seed_admin.php first.\n");
    exit(1);
}

/* ----------------------------- helpers ---------------------------------- */
/** Fetch a URL's HTML (cURL with a browser UA); '' on failure. */
function fetch_html(string $url): string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; cx-blog-importer/1.0)',
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $html = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($html !== false && $code === 200) ? (string) $html : '';
}

/** Concatenated HTML of a node's children (its "innerHTML"). */
function inner_html(DOMNode $node): string
{
    $html = '';
    foreach ($node->childNodes as $child) {
        $html .= $node->ownerDocument->saveHTML($child);
    }
    return trim($html);
}

/** Make root-relative URLs (src="/x", href="/x") absolute to cypherox.com. */
function absolutize(string $html): string
{
    return preg_replace(
        '/(src|href)="\/(?!\/)/i',
        '$1="https://www.cypherox.com/',
        $html
    ) ?? $html;
}

function attr(DOMXPath $xp, string $query): ?string
{
    $n = $xp->query($query)->item(0);
    return $n ? trim($n->nodeValue) : null;
}

/* ------------------------------ statements ------------------------------ */
$insertBlog = $pdo->prepare(
    'INSERT INTO blogs
       (category_id, user_id, title, slug, short_description, content, conclusion,
        featured_image, meta_title, meta_description, meta_keywords, canonical_url,
        reading_time, published_at, status, `schema`)
     VALUES
       (NULL, :user_id, :title, :slug, :short_description, :content, :conclusion,
        :featured_image, :meta_title, :meta_description, :meta_keywords, :canonical_url,
        :reading_time, :published_at, :status, :schema)'
);
$insertFaq = $pdo->prepare(
    'INSERT INTO blog_faqs (blog_id, question, answer, sort_order) VALUES (?, ?, ?, ?)'
);
$insertSection = $pdo->prepare(
    'INSERT INTO blog_sections (blog_id, heading, content, sort_order) VALUES (?, ?, ?, ?)'
);
$deleteBySlug = $pdo->prepare('DELETE FROM blogs WHERE slug = ?');

/* ------------------------------- import --------------------------------- */
$imported = 0;
$skipped  = 0;

foreach (SLUGS as $slug) {
    $url  = LIVE_BASE . $slug;
    $html = fetch_html($url);
    if ($html === '') {
        echo "  skip (fetch failed): $slug\n";
        $skipped++;
        continue;
    }

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();
    $xp = new DOMXPath($doc);

    /* --- head meta --- */
    $title     = attr($xp, "//meta[@property='og:title']/@content")
              ?? attr($xp, '//title')
              ?? $slug;
    $metaDesc  = attr($xp, "//meta[@name='description']/@content")
              ?? attr($xp, "//meta[@property='og:description']/@content") ?? '';
    $metaKeys  = attr($xp, "//meta[@name='keywords']/@content") ?? '';
    $canonical = attr($xp, "//link[@rel='canonical']/@href") ?? $url;
    $image     = attr($xp, "//meta[@property='og:image']/@content") ?? '';

    /* First JSON-LD block -> `schema` column. */
    $schema = '';
    foreach ($xp->query("//script[@type='application/ld+json']") as $s) {
        $schema = trim($s->textContent);
        break;
    }

    /* --- intro: paragraphs inside .summary-paragraph --- */
    $content = '';
    $introNode = $xp->query("//*[contains(@class,'summary-paragraph')]")->item(0);
    if ($introNode) {
        $content = absolutize(inner_html($introNode));
    }

    /* --- content sections + conclusion: each .cust-summary-wrapper --- */
    $sections   = [];
    $conclusion = '';
    foreach ($xp->query("//*[contains(@class,'cust-summary-wrapper')]") as $wrap) {
        /* the inner .content holds the <h2> + body; fall back to the wrapper. */
        $contentNode = $xp->query(".//*[contains(@class,'content')]", $wrap)->item(0) ?? $wrap;
        $h2 = $xp->query('.//h2', $contentNode)->item(0);
        $heading = $h2 ? trim($h2->textContent) : '';

        /* innerHTML without the leading <h2> (the template renders headings). */
        $bodyHtml = '';
        foreach ($contentNode->childNodes as $child) {
            if ($child === $h2) {
                continue;
            }
            $bodyHtml .= $doc->saveHTML($child);
        }
        $bodyHtml = absolutize(trim($bodyHtml));

        $idAttr = $wrap instanceof DOMElement ? strtolower($wrap->getAttribute('id')) : '';
        if ($idAttr === 'conclusion' || strcasecmp($heading, 'Conclusion') === 0) {
            $conclusion = $bodyHtml;
            continue;
        }
        if ($heading === '' && $bodyHtml === '') {
            continue;
        }
        $sections[] = ['heading' => $heading, 'html' => $bodyHtml];
    }

    /* --- FAQs: each .faq-area-block-wrap (h3.at-title + p.at-tab) --- */
    $faqs = [];
    foreach ($xp->query("//*[contains(@class,'faq-area-block-wrap')]") as $item) {
        $q = $xp->query(".//*[contains(@class,'at-title')]", $item)->item(0);
        $a = $xp->query(".//*[contains(@class,'at-tab')]", $item)->item(0);
        if ($q) {
            $faqs[] = [trim($q->textContent), $a ? absolutize(inner_html($a)) : ''];
        }
    }

    /* --- reading time from all text --- */
    $allText     = $content . ' ' . $conclusion . ' ' . implode(' ', array_column($sections, 'html'));
    $words       = str_word_count(strip_tags($allText));
    $readingTime = max(1, (int) ceil($words / 200));

    /* refresh + insert */
    $deleteBySlug->execute([$slug]);
    $insertBlog->execute([
        'user_id'           => $userId,
        'title'             => mb_substr(html_entity_decode($title, ENT_QUOTES, 'UTF-8'), 0, 255),
        'slug'              => $slug,
        'short_description' => mb_substr($metaDesc, 0, 500),
        'content'           => $content,
        'conclusion'        => $conclusion,
        'featured_image'    => mb_substr($image, 0, 255),
        'meta_title'        => mb_substr(html_entity_decode($title, ENT_QUOTES, 'UTF-8'), 0, 255),
        'meta_description'  => mb_substr($metaDesc, 0, 500),
        'meta_keywords'     => mb_substr($metaKeys, 0, 500),
        'canonical_url'     => mb_substr($canonical, 0, 255),
        'reading_time'      => $readingTime,
        'published_at'      => date('Y-m-d H:i:s'),
        'status'            => 'published',
        'schema'            => '<script type="application/ld+json">'.$schema.'</script>',
    ]);
    $blogId = (int) $pdo->lastInsertId();

    foreach ($faqs as $i => [$question, $answer]) {
        $insertFaq->execute([$blogId, mb_substr($question, 0, 500), $answer, $i]);
    }
    foreach ($sections as $i => $sec) {
        $insertSection->execute([$blogId, mb_substr($sec['heading'], 0, 255), $sec['html'], $i]);
    }

    echo "  imported: $slug  (#$blogId, " . count($sections) . " sections, "
        . count($faqs) . " faqs, img=" . ($image !== '' ? 'yes' : 'no') . ")\n";
    $imported++;
}

echo "\nDone. Imported: $imported, Skipped: $skipped.\n";
