<?php
/**
 * Public blog detail page.
 * - Looked up by ?slug=<slug>.
 * - Shows all blog data: featured image, content, sections (ToC) and FAQs.
 * Direct PDO connection (no API).
 */
declare(strict_types=1); 
require_once __DIR__ . '/partials/config.php';
 
/* ------------------------------- helpers --------------------------------- */
function e(?string $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/* Turn a section heading into a URL-friendly anchor slug. */
function slugify_heading(string $s): string
{
    $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';
    return trim($s, '-');
}

/* Base URL of this blog folder, e.g. "/blog" or "/cx_blog/blog". */
$BASE = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

/* Unknown / unpublished slug -> permanent redirect to the listing page. */
function redirect_to_listing(string $base): void
{
    header('Location: ' . ($base === '' ? '/' : $base . '/'), true, 301);
    exit;
}

/* -------------------------------- input ---------------------------------- */
$slug = trim((string) ($_GET['slug'] ?? ''));

if ($slug === '') {
    redirect_to_listing($BASE); 
}

/* -------------------------------- blog ----------------------------------- */
$stmt = $pdo->prepare(
    "SELECT b.*, c.name AS category_name, c.slug AS category_slug
     FROM blogs b
     LEFT JOIN categories c ON c.id = b.category_id
     WHERE b.slug = :slug AND b.status = 'published'
     LIMIT 1"
);
$stmt->execute([':slug' => $slug]);
$blog = $stmt->fetch();

if (!$blog) {
    redirect_to_listing($BASE); 
}

/* increment view counter (best effort) */
$pdo->prepare('UPDATE blogs SET views = views + 1 WHERE id = :id')
    ->execute([':id' => $blog['id']]);

/* ------------------------------ sections --------------------------------- */
$secStmt = $pdo->prepare(
    'SELECT heading, content FROM blog_sections
     WHERE blog_id = :id ORDER BY sort_order, id'
);
$secStmt->execute([':id' => $blog['id']]);
$sections = $secStmt->fetchAll();

/* Build a unique anchor slug per section (from its heading), keeping TOC links
   and section ids in sync. Falls back to section-N for blank/duplicate headings. */
$sectionSlugs = [];
$usedSlugs = [];
foreach ($sections as $i => $sec) {
    $base = slugify_heading($sec['heading'] ?? '') ?: 'section-' . ($i + 1);
    $slugAnchor = $base;
    $n = 2;
    while (isset($usedSlugs[$slugAnchor])) {
        $slugAnchor = $base . '-' . $n++;
    }
    $usedSlugs[$slugAnchor] = true;
    $sectionSlugs[$i] = $slugAnchor;
}

/* -------------------------------- faqs ----------------------------------- */
$faqStmt = $pdo->prepare(
    'SELECT question, answer FROM blog_faqs
     WHERE blog_id = :id ORDER BY sort_order, id'
);
$faqStmt->execute([':id' => $blog['id']]);
$faqs = $faqStmt->fetchAll();

/* ------------------------ previous / next blog --------------------------- */
/* Previous = the published blog immediately older than this one; next = the one
   immediately newer. Ordered by id so it works even when published_at is null. */
$prevStmt = $pdo->prepare(
    "SELECT slug, title FROM blogs
     WHERE status = 'published' AND id < :id
     ORDER BY id DESC LIMIT 1"
);
$prevStmt->execute([':id' => $blog['id']]);
$prevBlog = $prevStmt->fetch();

$nextStmt = $pdo->prepare(
    "SELECT slug, title FROM blogs
     WHERE status = 'published' AND id > :id
     ORDER BY id ASC LIMIT 1"
);
$nextStmt->execute([':id' => $blog['id']]);
$nextBlog = $nextStmt->fetch();

/* ------------------------------ CTA block -------------------------------- */
/* Position is no longer used to decide placement — the CTA always shows in the
   right rail. Each field falls back to a sensible default when left empty. */
$CTA_DEFAULT_TEXT   = "Ready to turn your idea into a dependable, production-ready product?";
$CTA_DEFAULT_BTN    = 'Book a Free Consultation';
$CTA_DEFAULT_LINK   = 'https://www.cypherox.com/get-in-touch';
$CTA_DEFAULT_IMAGE  = 'https://www.cypherox.com/assets/img/business.webp';

$ctaPos = (string) ($blog['cta_position'] ?? 'none');
$ctaText    = trim((string) ($blog['cta_text'] ?? ''))        ?: $CTA_DEFAULT_TEXT;
$ctaBtnText = trim((string) ($blog['cta_button_text'] ?? '')) ?: $CTA_DEFAULT_BTN;
$ctaBtnLink = trim((string) ($blog['cta_button_link'] ?? '')) ?: $CTA_DEFAULT_LINK;
$ctaImage   = trim((string) ($blog['cta_image'] ?? ''))       ?: $CTA_DEFAULT_IMAGE;
/* Right-rail CTA is always shown now (defaults guarantee content). */
$ctaEnabled = true;
$ctaDone = false;
$blog_cat_name = $blog['category_name'] ?? '';
$blog_cat_slug = $blog['category_slug'] ?? '';
/** Output the CTA box once. */
$renderCtaBox = function () use ($BASE, $blog_cat_name, $blog_cat_slug, $ctaText, $ctaBtnText, $ctaBtnLink, &$ctaDone) {
    $ctaDone = true;


    
    echo '<section class="work-culture hire-cta">
            <div class="work-culture-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="work-cultur-svgmain">
                            <img src="https://www.cypherox.com/assets/img/work-culture-svg.svg"
                                alt="Light Theme Gradiant Background (Right Corner)">
                        </div>
                        <div class="col-md-6 svg-draw-sec p-0 work-culture-svg">
                            <svg width="928" height="635" viewBox="0 0 928 635" fill="none"
                                xmlns="http://www.w3.org/2000/svg" style="opacity: 1;">
                                <path
                                    d="M927 634.096C927 634.096 708 473.395 680.577 560.857C678.817 564.396 677.573 568.168 676.884 572.059C675.402 579.612 669.635 597.243 648.819 588.94C637.425 584.31 628.195 575.565 622.967 564.446C618.262 554.501 616.04 543.566 616.489 532.576C615.487 539.057 614.095 545.471 612.322 551.785C612.144 552.131 611.869 552.418 611.529 552.608C611.189 552.799 610.801 552.884 610.413 552.855C610.024 552.825 609.653 552.682 609.346 552.442C609.039 552.203 608.81 551.878 608.688 551.509C606.969 541.135 602.921 518.14 599.188 508.792C599.016 508.364 598.716 507.999 598.328 507.748C597.94 507.498 597.483 507.374 597.022 507.393C596.56 507.413 596.116 507.575 595.75 507.857C595.385 508.139 595.116 508.528 594.981 508.969C593.266 514.651 591.188 520.217 588.76 525.634C588.008 527.179 586.743 528.417 585.18 529.136C583.618 529.855 581.854 530.011 580.189 529.578C568.536 526.6 538.26 518.239 524.889 508.969C521.417 506.532 518.991 502.877 518.095 498.734C517.602 496.446 517.049 493.547 516.713 490.845C516.597 489.866 516.711 488.872 517.048 487.945C517.384 487.017 517.933 486.181 518.651 485.503C519.368 484.824 520.234 484.323 521.18 484.038C522.126 483.754 523.126 483.694 524.099 483.864C541.874 486.881 587.871 493.508 607.048 485.146C607.584 484.932 608.16 484.838 608.736 484.871C609.312 484.903 609.874 485.061 610.382 485.334C610.891 485.606 611.333 485.987 611.678 486.448C612.024 486.91 612.263 487.441 612.381 488.005C615.205 505.104 623.974 557.465 627.746 572.67C627.746 572.67 555.106 536.244 518.056 510.488C512.097 506.365 507.686 500.375 505.522 493.466C503.358 486.558 503.564 479.125 506.107 472.347L506.404 471.597C506.551 471.163 506.86 470.801 507.267 470.587C507.674 470.373 508.147 470.322 508.59 470.446C509.033 470.57 509.411 470.858 509.647 471.252C509.883 471.646 509.959 472.115 509.86 472.564C508.28 482.089 502.77 495.796 484.383 497.886C478.135 498.596 471.836 498.748 465.561 498.34C434.988 496.367 420.354 459.567 441.565 437.518L442.098 436.966C438.074 448.047 433.077 458.751 427.167 468.955C426.4 470.184 425.362 471.223 424.133 471.992C422.903 472.761 421.515 473.241 420.072 473.395C418.629 473.549 417.171 473.373 415.806 472.88C414.442 472.388 413.208 471.592 412.197 470.552C405.77 463.937 400.502 456.291 396.615 447.931C396.615 447.931 406.845 464.596 409.215 453.71C409.676 451.315 410.624 449.039 412 447.024C415.357 442.291 422.961 435.191 439.649 430.833C440.261 430.689 440.822 430.383 441.275 429.949C441.728 429.515 442.057 428.967 442.226 428.363C442.395 427.759 442.399 427.121 442.238 426.515C442.077 425.909 441.756 425.357 441.308 424.916L433.013 416.357C432.216 415.533 431.182 414.975 430.054 414.759C428.926 414.544 427.759 414.682 426.713 415.154C424.562 416.158 422.32 416.957 420.018 417.541C413.005 419.098 406.423 422.182 400.742 426.573C394.304 431.799 388.892 438.702 393.06 445.447C393.06 445.447 338.866 432.391 329.623 424.916C329.623 424.916 341.829 408.212 337.523 403.223C337.523 403.223 334.916 400.62 316.786 398.016C316.786 398.016 308.076 396.044 330.729 390.7C330.729 390.7 349.037 386.519 345.364 382.357C345.364 382.357 344.317 378.176 314.001 378.413C314.001 378.413 293.797 376.835 294.824 362.754C294.824 362.754 297.945 389.576 291.783 404.544C291.438 405.393 290.828 406.109 290.043 406.584C289.259 407.06 288.341 407.27 287.428 407.184C286.514 407.097 285.653 406.719 284.971 406.105C284.29 405.491 283.825 404.674 283.646 403.775C281.288 392.137 281.241 380.15 283.508 368.493C283.508 368.493 283.745 398.568 277.583 406.615C277.156 407.146 276.599 407.558 275.966 407.81C275.332 408.062 274.644 408.146 273.969 408.055L203.936 401.724L337.346 428.861C337.346 428.861 368.254 404.505 353.442 346.208C353.442 346.208 392.309 368.671 425.074 376.5C425.074 376.5 443.106 381.371 443.718 360.23C443.718 360.23 442.79 393.165 451.479 414.799C451.759 415.398 452.221 415.893 452.799 416.214C453.378 416.535 454.043 416.666 454.699 416.587C455.356 416.508 455.972 416.224 456.457 415.776C456.943 415.327 457.275 414.737 457.404 414.089C461.493 387.623 474.429 313.155 494.929 280.259C494.929 280.259 513.869 242.335 553.586 257.875C553.586 257.875 604.007 286.531 569.168 348.121C569.168 348.121 508.062 454.617 516.18 463.433C516.18 463.433 544.007 486.664 604.066 466.568C605.834 465.977 607.365 464.833 608.432 463.306C609.499 461.778 610.045 459.949 609.991 458.088C609.655 446.669 609.991 422.136 617.239 405.491C618.799 401.941 620.34 398.371 621.624 394.703C627.865 376.756 645.126 327.867 649.668 322.897C649.668 322.897 639.399 355.063 634.856 330.273L631.716 312.524L631.202 330.273C631.202 330.273 621.446 324.357 616.094 278.287C616.094 278.287 605.646 238.607 589.846 205.436C589.846 205.436 582.401 177.372 586.588 160.885L562.355 188.692C562.355 188.692 582.796 157.493 581.393 147.869C581.393 147.869 580.702 141.597 569.544 141.597C569.544 141.597 581.393 119.904 576.93 115.387C576.93 115.387 549.557 113.297 546.594 87.1857C546.594 87.1857 544.422 84.76 536.324 89.8876C536.324 89.8876 529.274 94.5024 525.857 91.6231C525.857 91.6231 533.441 84.5825 531.525 81.4468C531.525 81.4468 530.972 79.4746 519.675 85.8052C519.675 85.8052 496.193 99.6103 491.67 88.5859C489.092 81.5774 488.022 74.1049 488.53 66.6556C488.53 66.6556 486.219 45.5931 468.385 60.2067C468.385 60.2067 473.026 45.0803 464.83 60.0489C463.518 65.4668 462.763 71.0044 462.579 76.5756C462.579 76.5756 448.458 43.1673 478.596 38.6314C478.596 38.6314 496.094 -13.5123 576.713 13.6443C576.713 13.6443 568.122 -4.46008 619.234 9.91689C619.234 9.91689 648.977 18.5155 642.934 117.36C642.934 117.36 644.79 170.706 666.633 220.838C666.633 220.838 688.24 286.255 654.092 312.011C654.092 312.011 660.017 284.874 655.021 281.975C655.021 281.975 643.171 320.392 639.339 327.098C639.339 327.098 637.818 311.774 641.077 287.063C641.077 287.063 638.865 280.575 630.037 300.987C630.037 300.987 621.505 261.031 606.515 231.271C606.515 231.271 587.397 199.026 592.967 150.985C592.967 150.985 603.197 89.7495 586.469 53.0873C586.469 53.0873 580.011 139.408 586.015 155.856C586.015 155.856 565.91 163.409 561.031 156.093C561.031 156.093 540.926 118.05 537.786 109.116C537.786 109.116 534.883 99.2553 525.818 99.3736C525.818 99.3736 495.502 113.632 518.747 139.862C518.747 139.862 536.166 136.253 527.575 100.754C527.575 100.754 523.843 92.6486 512.467 103.772L502.276 114.48C502.276 114.48 500.301 114.796 500.301 103.811C500.301 103.811 498.326 96.2773 487.306 95.1138C487.306 95.1138 479.406 94.0685 480.215 75.8656C480.215 75.8656 481.716 59.2601 475.456 63.2044C475.456 63.2044 465.581 67.622 466.963 89.1973C466.963 89.1973 466.963 104.521 463.013 112.173C463.013 112.173 457.681 120.633 468.425 140.592C468.425 140.592 480.551 159.958 482.25 172.718C482.25 172.718 482.585 180.508 498.049 178.417C498.049 178.417 524.889 173.014 528.543 203.286C528.543 203.286 526.153 210.09 536.443 206.244C536.443 206.244 552.697 200.328 548.056 206.599C548.056 206.599 527.733 212.989 543.415 213.798L569.089 211.352L512.98 230.739L516.575 208.217L583.033 189.895C587.631 197.334 589.856 205.994 589.412 214.725C589.412 214.725 524.712 226.084 511.815 239.317L443.441 324.238L339.933 302.821L366.476 306.765L345.858 345.932C345.858 345.932 321.289 337.57 320.756 332.344C320.756 332.344 330.176 308.343 336.556 299.113L321.309 287.379L322.079 272.548C322.102 272.035 321.945 271.53 321.635 271.12C321.326 270.71 320.882 270.42 320.382 270.301C319.882 270.182 319.355 270.241 318.894 270.467C318.432 270.694 318.064 271.074 317.853 271.542L311.928 284.933C311.928 284.933 280.229 281.837 263.264 277.419C263.264 277.419 245.608 273.179 236.385 293.374C229.414 295.035 222.178 295.27 215.114 294.064C215.114 294.064 169.394 285.426 157.84 280.792C157.84 280.792 137.399 276.492 127.406 246.338C127.406 246.338 118.34 214.547 118.34 199.007C118.34 199.007 138.781 241.921 126.477 260.952C126.477 260.952 121.125 264.206 108.13 260.715C108.13 260.715 82.4551 257.934 88.5973 308.737C88.5973 308.737 66.1813 284.144 68.6302 215.592C68.6302 215.592 70.6052 165.598 94.6407 168.379L98.7486 169.7C98.7486 169.7 95.0357 164.139 106.412 161.812L165.957 148.539C165.957 148.539 143.482 152.365 147.136 176.031C150.115 179.242 153.848 181.662 157.998 183.072C157.998 183.072 140.914 186.543 141.092 191.079C141.092 191.079 143.067 196.127 150.335 197.508L179.426 277.557C179.426 277.557 181.184 281.048 194.061 281.383C194.061 281.383 204.173 281.916 205.753 285.742C205.753 285.742 187.089 163.705 174.153 141.972C174.005 141.731 173.784 141.545 173.521 141.439C173.259 141.333 172.969 141.314 172.695 141.385C172.422 141.455 172.177 141.612 171.999 141.831C171.821 142.05 171.717 142.321 171.704 142.603C171.329 151.359 171.487 175.262 183.1 193.682L196.925 182.204C196.925 182.204 212.942 207.842 209.11 229.003C209.11 229.003 204.765 209.163 206.326 191.256L183.396 151.951C181.463 148.65 180.27 144.97 179.9 141.163C178.893 131.046 177.017 110.615 177.274 103.259C177.276 102.884 177.358 102.513 177.515 102.173C177.672 101.832 177.901 101.529 178.186 101.284C178.47 101.04 178.804 100.858 179.164 100.753C179.525 100.648 179.904 100.622 180.276 100.675C181.322 100.872 182.251 101.701 182.646 104.048C182.705 104.387 182.705 104.734 182.646 105.073C182.172 106.828 180.276 111.936 174.746 107.992C172.692 106.493 171.11 104.441 170.184 102.075C167.873 96.7309 164.417 85.3122 167.873 73.5582C168.215 72.3687 168.997 71.3531 170.06 70.7163C171.123 70.0795 172.388 69.8688 173.6 70.1266C180.532 71.6452 194.239 73.4398 197.715 65.0976C197.715 65.0976 202.336 59.0037 192.738 48.1174C192.738 48.1174 188.215 44.2915 196.312 41.8657C196.312 41.8657 207.214 37.9214 201.368 34.3716C201.368 34.3716 200.756 34.1743 199.907 33.7996C199.396 33.5535 198.989 33.1337 198.759 32.6154C198.53 32.0971 198.493 31.5142 198.654 30.9709C198.816 30.4276 199.166 29.9595 199.641 29.6503C200.117 29.3411 200.687 29.2111 201.25 29.2834C206.731 30.1123 212.099 31.5681 217.247 33.6221C217.247 33.6221 238.537 42.9307 248.314 37.6848C252.362 35.3944 257.074 34.5592 261.664 35.3182C267.412 36.3831 273.514 40.2486 275.331 51.3715C275.331 51.3715 276.615 65.1765 278.353 68.3122C278.353 68.3122 275.687 40.0119 280.328 33.7405C280.328 33.7405 289.275 22.3809 278.827 26.3252C278.827 26.3252 270.572 29.9145 273.356 25.8519C273.356 25.8519 281.612 16.7997 278.471 15.0642C278.471 15.0642 273.83 20.2904 265.93 13.9006C265.93 13.9006 233.857 -12.1909 186.694 9.838C186.694 9.838 178.972 14.8867 181.401 28.8101C181.401 28.8101 174.825 21.4737 169.828 33.7405C169.204 35.3403 168.715 36.9895 168.367 38.6708C166.392 47.8216 159.301 82.0581 169.729 75.4514C169.729 75.4514 173.304 70.6591 179.92 82.2356C179.92 82.2356 185.588 96.3365 189.795 97.2042C189.795 97.2042 192.422 96.159 193.982 84.6811C193.982 84.6811 194.515 73.1046 203.126 68.3122C204.675 67.0516 205.833 65.3777 206.466 63.4854C207.099 61.593 207.18 59.56 206.701 57.6232C206.701 57.6232 203.837 37.5073 216.99 38.9864C216.99 38.9864 232.929 46.3819 243.396 46.0467C243.396 46.0467 256.806 46.2242 259.591 43.6012C259.591 43.6012 268.656 39.7752 271.263 60.1278C271.263 60.1278 279.972 105.487 268.123 140.71C268.123 140.71 257.497 177.254 222.125 166.111C210.441 162.28 200.379 154.649 193.548 144.437C198.699 152.629 205.733 159.476 214.066 164.408C222.399 169.341 231.791 172.216 241.46 172.797L204.706 243.972L228.228 222.574L235.891 195.772L246.003 203.089L247.227 170.549L254.713 172.107L243.732 274.422L271.085 268.683L288.346 203.424L272.31 204.351L281.908 189.422L257.734 173.033L329.821 204.351L334.699 205.278C334.699 205.278 334.008 295.78 346.549 297.851C346.549 297.851 280.565 276.985 251.75 282.784C248.158 284.862 245.191 287.865 243.159 291.481C243.159 291.481 235.378 299.823 230.717 301.341C230.717 301.341 243.495 312.938 257.102 295.543C260.996 295.2 264.919 295.695 268.606 296.995C272.292 298.294 275.656 300.368 278.471 303.077L317.971 333.133C317.971 333.133 322.039 346.819 302.29 340.785L282.54 330.924C282.54 330.924 304.837 338.576 303.573 344.039C303.573 344.039 299.841 357.134 281.256 346.819C281.256 346.819 293.58 349.955 291.131 356.443C291.131 356.443 287.655 365.496 273.593 356.562C273.593 356.562 283.942 368.631 269.406 363.642C269.406 363.642 218.649 333.586 210.513 334.059C210.513 334.059 193.548 331.614 191.81 337.767C191.81 337.767 212.843 355.635 212.132 329.76C212.132 329.76 213.771 303.886 224.91 291.934C224.91 291.934 208.182 280.575 196.332 298.206C196.332 298.206 184.028 327.334 181.461 339.503C181.461 339.503 180.414 346.583 171.941 344.729L94.7987 320.254C94.7987 320.254 97.2872 370.998 80.4011 384.231C80.4011 384.231 82.3761 381.095 92.409 387.544C94.6387 389.016 96.7757 390.623 98.8079 392.356C99.4601 392.958 99.9223 393.736 100.138 394.596C100.353 395.456 100.313 396.361 100.022 397.198C99.7302 398.035 99.2004 398.77 98.4972 399.311C97.794 399.852 96.9479 400.177 96.0627 400.245L71.1582 401.546C71.1582 401.546 152.488 407.463 176.01 405.491C177.512 405.366 178.955 404.85 180.195 403.996C181.435 403.141 182.43 401.977 183.08 400.62L204.469 356.305C204.469 356.305 197.26 393.776 189.262 407.581C188.51 408.922 187.412 410.035 186.081 410.806C184.75 411.576 183.236 411.975 181.698 411.959L0.987305 407.029"
                                    stroke="#F26E65" stroke-miterlimit="10"
                                    style="stroke-dashoffset: 0px; stroke-dasharray: 9604.97;">
                                </path>
                            </svg>
                        </div>
                        <div class="col-md-6 content-col ">
                            <div class="heading-main-sec d-flex flex-column" bis_skin_checked="1">
                                <p class="seo-title-secondary h3 text-start"><span>';
                                    if (!empty($blog_cat_name)){  
                                        echo '<a href="'.e($BASE).'?category='.urlencode($blog_cat_slug ).'">'.e($blog_cat_name ).'</a>';
                                    }
                                echo '</span></p>
                                <h2 class="text-start seo-title"><span>' . nl2br(e($ctaText)) . '</span></h2>
                                <a href="' . e($ctaBtnLink) . '" class="primary-btn large-btn">
                                    <span class="glow"></span>
                                    <span class="stock"></span>
                                    <span class="btn-text">'. e($ctaBtnText) .'</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> ';
        
    /* <div class="cx-cta-box my-4">';
     if ($ctaText !== '') {
        echo '<div class="cx-cta-text">' . nl2br(e($ctaText)) . '</div>';
    }
    if ($ctaBtnText !== '' && $ctaBtnLink !== '') {
        echo '<a class="cx-cta-btn" href="' . e($ctaBtnLink) . '">' . e($ctaBtnText) . '</a>';
    }
    echo '</div>'; */
};
/** Render the CTA here if its position matches $key (and it hasn't shown yet). */
$ctaAt = function (string $key) use (&$ctaDone, $ctaEnabled, $ctaPos, $renderCtaBox) {
    
    if ($ctaEnabled && !$ctaDone && $ctaPos === $key) { 
        $renderCtaBox(); 
    }
};

$pageTitle = $blog['meta_title'] ?: $blog['title'];
$metaDescription = $blog['meta_description'] ?? '';
$canonical = $blog['canonical_url'] ?? '';
$schema = $blog['schema'] ?? '';

/* ----------------------- social / SEO meta (per blog) -------------------- */
$ogTitle       = $blog['meta_title'] ?: $blog['title'];
$ogDescription = $metaDescription !== '' ? $metaDescription : (string) ($blog['short_description'] ?? '');
$ogType        = 'article';
$ogUrl         =  'https://www.cypherox.com/blog/' . rawurlencode($blog['slug']);
$canonical     = $ogUrl ;
$ogImage       = $blog['featured_image'] ?: ($blog['featured_thumb'] ?? '');
$ogImageAlt    = $blog['title'];
$twitterData1  = $blog['author_name'] ?: 'cypherox';

$extraHead = '';
if (!empty($blog['meta_keywords'])) {
    $extraHead .= '<meta name="keywords" content="' . e($blog['meta_keywords']) . '">';
}
$extraHead .= '<style>
                .article-hero img { width: 100%; object-fit: cover; border-radius: .5rem; }
        .article-body img { max-width: 100%; height: auto; }
        .cx-author-bio { display: flex; gap: 1.1rem; align-items: flex-start; margin: 2.5rem 0 0; padding: 1.5rem 1.75rem; background: #fff; border: 1px solid #e6eaf2; border-radius: 12px; }
        .cx-author-avatar { width: 64px; height: 64px; border-radius: 50%; object-fit: contain; background: #f2f4f8; padding: 8px; flex: 0 0 auto; }
        .cx-author-eyebrow { text-transform: uppercase; letter-spacing: .04em; font-size: .72rem !important; color: #f26e65 !important; margin: 0 0 .15rem !important; }
        .cx-author-name { display: flex; align-items: center; gap: .55rem; font-size: 1.05rem; font-weight: 700; margin: 0; }
        .cx-author-name a, .cx-author-name { color: #1e2a3b !important; text-decoration: none; }
        .cx-author-name{margin: 0 !important}
        .cx-author-linkedin { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: #f2f4f8; padding: 3px; transition: transform .2s ease, background .2s ease; }
        .cx-author-linkedin:hover { transform: translateY(-1px); background: #e9eef5; }
        .cx-author-linkedin img { width: 100%; height: 100%; display: block; }
        .cx-author-role { color: #f26e65 !important; font-size: .85rem !important; margin: .1rem 0 .5rem !important; }
        .cx-author-text { margin: 0 !important; color: #f26e65 !important; font-size: .92rem !important; line-height: 1.55 !important; }
        .cx-cta-box { background: #fff3f2; border: 1px solid #f3c9c5; border-left: 4px solid #f26e65; border-radius: 12px; padding: 1.5rem 1.75rem; text-align: center; }
        .cx-cta-text { font-size: 1.05rem; color: #1e2a3b; margin-bottom: 1rem; }
        .cx-cta-btn { display: inline-block; background: #f26e65; color: #fff; padding: .6rem 1.4rem; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .cx-cta-btn:hover { background: #e0564d; color: #fff; }

        /* ---- 20/60/20 blog detail layout ---- */
        /* styles.css sets body{overflow-x:hidden} which makes body a scroll
           container and disables position:sticky. clip avoids that. */
        body { overflow-x: clip; }
        .cx-body-layout { display: grid; grid-template-columns: 20% 55% 19%; gap: 2rem; align-items: start; }
        .cx-col-left, .cx-col-right { position: sticky; top: 20px; align-self: start; }
        .cx-col-center { min-width: 0; }

        /* sticky TOC in the left rail */
        .cx-col-left .cx-toc { position: static; max-height: calc(100vh - 200px); overflow-y: auto; }

        /* social share */
        .cx-share { margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid rgba(255, 255, 255, .12); }
        .cx-share-title { text-transform: uppercase; letter-spacing: .04em; font-size: .72rem; color: #bf9f9f; margin: 0 0 .85rem; }
        /* share icons copied from the footer (.copyright-area ul) */
        .cx-share-list { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; padding: 0; margin: 0; list-style: none; }
        .cx-share-list li { margin: 0; list-style: none; transform: translateY(0); transition: .6s ease-in-out; }
        .cx-share-list li:hover { transform: translateY(-15px); }
        .cx-share-list li a { display: inline-block; }
        .cx-share-list li img { width: 40px; height: auto; display: block; }

        /* vertical CTA in the right rail */
        .cx-side-cta {
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, #1c1414 0%, #140f0f 55%, #0d0a0a 100%);
            border: 1px solid rgba(242, 110, 101, .28);
            border-radius: 18px;
            padding: 1.9rem 1.5rem 1.6rem;
            text-align: center;
            box-shadow: 0 18px 40px -18px rgba(242, 110, 101, .55), inset 0 1px 0 rgba(255, 255, 255, .04);
        }
        /* coral glow blob top-right */
        .cx-side-cta::before {
            content: "";
            position: absolute;
            top: -55px; right: -55px;
            width: 150px; height: 150px;
            background: radial-gradient(circle, rgba(242, 110, 101, .55), transparent 70%);
            filter: blur(8px);
            pointer-events: none;
        }
        .cx-side-cta > * { position: relative; z-index: 1; }

        /* full-bleed image banner at the top of the card (gives the rail height) */
        .cx-side-cta-media {
            position: relative;
            margin: -1.9rem -1.5rem 1.5rem;
            
            overflow: hidden;
            border-bottom: 1px solid rgba(242, 110, 101, .25);
        }
        .cx-side-cta-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        /* black transparent overlay on the image */
        .cx-side-cta-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, .35) 0%, rgba(0, 0, 0, .55) 100%);
            pointer-events: none;
        }

        .cx-side-cta-icon {
            width: 54px; height: 54px;
            margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            border-radius: 14px;
            background: linear-gradient(135deg, #f26e65, #e0564d);
            box-shadow: 0 8px 20px -6px rgba(242, 110, 101, .7);
        }
        .cx-side-cta-icon svg { width: 26px; height: 26px; color: #fff; }

        .cx-side-cta .cx-side-cta-cat {
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: .66rem;
            color: #f26e65;
            margin: 0 0 .6rem;
            font-weight: 700;
            padding: .25rem .7rem;
            border: 1px solid rgba(242, 110, 101, .35);
            border-radius: 999px;
        }
        .cx-side-cta .cx-side-cta-text {
            font-size: 1.12rem;
            line-height: 1.45;
            color: #fff;
            margin: 0 0 1.4rem;
            font-weight: 600;
        }
        .cx-side-cta .cx-side-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            width: 100%;
            background: linear-gradient(135deg, #f26e65, #e0564d);
            color: #fff;
            padding: .85rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: .01em;
            box-shadow: 0 10px 24px -10px rgba(242, 110, 101, .8);
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }
        .cx-side-cta .cx-side-cta-btn svg { width: 16px; height: 16px; transition: transform .2s ease; }
        .cx-side-cta .cx-side-cta-btn:hover {
            color: #fff;
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow: 0 16px 30px -10px rgba(242, 110, 101, .9);
        }
        .cx-side-cta .cx-side-cta-btn:hover svg { transform: translateX(4px); }
        .cx-toc{
            padding: 0.5rem 0.5rem 0.5rem !important;
        }
        .cx-toc-ol{
            margin : 0  !important;
        }
             .cx-toc-ol li a,
        .cx-share-list li a{
            border-bottom:0px;
        }
        .cx-toc-ol li,
        .cx-share-list li {
         list-style: none !important;
             margin-bottom: 0.75rem !important;
             text-decoration:none;
        }
        /* previous / next blog navigation */
        .cx-post-nav {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 2.5rem 0 0;
        }
        .cx-post-nav-link {
            display: flex;
            flex-direction: column;
            gap: .4rem;
            padding: 1rem 1.25rem;
            border: 1px solid #e6eaf2;
            border-radius: 12px;
            background: #fff;
            text-decoration: none;
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }
        a.cx-post-nav-link:hover {
            border-color: rgba(242, 110, 101, .5);
            box-shadow: 0 12px 26px -14px rgba(242, 110, 101, .55);
            transform: translateY(-2px);
        }
        .cx-post-nav-empty { border: 0; background: transparent; box-shadow: none; }
        .cx-post-nav-next { text-align: right; }
        .cx-post-nav-dir {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-size: .72rem;
            font-weight: 700;
            color: #f26e65;
        }
        .cx-post-nav-next .cx-post-nav-dir { justify-content: flex-end; }
        .cx-post-nav-dir svg { width: 15px; height: 15px; }
        .cx-post-nav-title {
            font-size: .98rem;
            font-weight: 600;
            color: #1e2a3b;
            line-height: 1.4;
        }

        @media (max-width: 992px) {
            .cx-body-layout { grid-template-columns: 1fr; }
            .cx-col-left, .cx-col-right { position: static; top: auto; }
            /* mobile order: table of contents first, then summary + details, CTA last */
            .cx-col-left { order: 1; }
            .cx-col-center { order: 2; }
            .cx-col-right { order: 3; }
            .cx-col-left .cx-toc { max-height: none; }
        }
        @media (max-width: 575px) {
            .cx-post-nav { grid-template-columns: 1fr; }
            .cx-post-nav-empty { display: none; }
            .cx-post-nav-next { text-align: left; }
            .cx-post-nav-next .cx-post-nav-dir { justify-content: flex-start; }
        }


        .cx-toc::-webkit-scrollbar {
            width: 8px;
        }
        .cx-toc::-webkit-scrollbar-thumb {
            background: #f26e65;
            border-radius: 10px;
        }
        .cx-toc::-webkit-scrollbar-track {
            border-radius: 0px;
            background-color: #141414;
        }


    </style>';
$extraHead .= '<link rel="stylesheet" href="' . ASSETS_URL . '/css/new-styles.css?v='.time().'">';
require __DIR__ . '/partials/header.php';
?>

<!-- featured image -->
<?php if (!empty($blog['featured_image'])): ?>
    <figure class="cx-featured-img">
        <img src="<?= e($blog['featured_image']) ?>" alt="<?= e($blog['title']) ?>">
    </figure>
<?php endif; ?>

<div class="cx-blog-article-wrap">
    <div class="banner-section">
        <div class="container">
            <nav class="cx-breadcrumb" aria-label="breadcrumb">
                <a href="https://www.cypherox.com/">Home</a>
                <span class="cx-bc-sep">/</span>
                <a href="<?= e($BASE) ?>/">Blog</a>
                <span class="cx-bc-sep">/</span>
                <?php if (!empty($blog['category_name'])): ?>
                    <a href="<?= e($BASE) ?>/?category=<?= urlencode($blog['category_slug']) ?>"><?= e($blog['category_name']) ?></a>
                <?php endif; ?>
                <span class="cx-bc-sep">/</span>
                <span class="cx-bc-current"><?= e($blog['title']) ?></span>
            </nav>

            <div class="banner-text">
                <div class="cx-article-header-block">
                    <div class="cx-article-meta">
                        <!-- meta -->
                        <?php if (!empty($blog['category_name'])): ?>
                            <a class="cx-cat-badge"
                                href="<?= e($BASE) ?>/?category=<?= urlencode($blog['category_slug']) ?>"
                                class="badge bg-secondary text-decoration-none mb-2"><?= e($blog['category_name']) ?></a>
                        <?php endif; ?>
                    </div>
                </div>


                <h1><?= e($blog['title']) ?></h1>

                <?php /* <div class="text-muted small mb-4">
     <?php if (!empty($blog['author_name'])): ?>
         By <?= e($blog['author_name']) ?> ·
     <?php endif; ?>
     <?php if (!empty($blog['published_at'])): ?>
         <?= e(date('F j, Y', strtotime($blog['published_at']))) ?>
     <?php endif; ?>
     <?php if (!empty($blog['reading_time'])): ?>
         · <?= (int) $blog['reading_time'] ?> min read
     <?php endif; ?>
     · <?= (int) $blog['views'] ?> views
 </div> */ ?>

                <!-- excerpt -->
                <!-- <?php if (!empty($blog['short_description'])): ?>
                    <p class="lead"><?= e($blog['short_description']) ?></p>
                <?php endif; ?> -->
            </div>
        </div>
    </div>

    <div class="cx-body">
      <div class="cx-body-layout">

        <!-- ===================== LEFT RAIL (20%) ===================== -->
        <aside class="cx-col-left">
            <!-- table of contents (sticky) -->
            <?php if ($sections || $faqs || !empty($blog['conclusion'])): ?>
                <div class="cx-toc">
                    <p class="cx-toc-title">Table of contents</p>
                    <ol class="cx-toc-ol">
                        <?php foreach ($sections as $i => $sec): ?>
                            <li><a href="#<?= e($sectionSlugs[$i]) ?>" class="text-decoration-none"><?= e($sec['heading']) ?></a>
                            </li>
                        <?php endforeach; ?>
                        <!-- static entries: FAQ then Conclusion -->
                        <?php if ($faqs): ?>
                            <li><a href="#faq" class="text-decoration-none">Frequently Asked Questions</a></li>
                        <?php endif; ?>
                        <?php if (!empty($blog['conclusion'])): ?>
                            <li><a href="#conclusion" class="text-decoration-none">Conclusion</a></li>
                        <?php endif; ?>
                    </ol>
                </div>
            <?php endif; ?>

            <!-- social share (icons + style copied from the footer; links are share intents) -->
            <?php
            $shareUrl   = rawurlencode($ogUrl);
            $shareTitle = rawurlencode($blog['title']);
            ?>
            <div class="cx-share">
                <p class="cx-share-title">Share</p>
                <ul class="cx-share-list">
                    <li>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" aria-label="Share on Facebook" target="_blank" rel="noopener"><img src="<?= ASSETS_URL ?>/img/fb.svg" alt="Facebook"></a>
                    </li>
                    <li>
                        <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" aria-label="Share on Twitter" target="_blank" rel="noopener"><img src="<?= ASSETS_URL ?>/img/twitter.svg" alt="Twitter"></a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $shareUrl ?>" aria-label="Share on LinkedIn" target="_blank" rel="noopener"><img src="<?= ASSETS_URL ?>/img/linkedin.svg" alt="Linkedin"></a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- ===================== CENTER (60%) ===================== -->
        <main class="cx-col-center">

        <!-- quick summary (rich HTML from editor) -->
        <?php if (!empty($blog['quick_summary'])): ?>
            <section class="cx-quick-summary">
                <div class="card-body article-body">
                    <h2 class="cx-qs-title">Quick Summary</h2>
                    <div class="cx-qs-text"><?= $blog['quick_summary'] /* trusted rich content authored in admin */ ?></div>
                </div>
            </section>
        <?php endif; ?>

        <!-- main content (rich HTML from editor) -->
        <div class="article-body">
            <?= $blog['content'] /* trusted rich content authored in admin */ ?>
        </div>

        <?php /* CTA is rendered in the right rail below; suppress the inline in-body CTA. */ $ctaDone = true; ?>
        <?php $ctaAt('toc_start'); ?>

        <!-- sections -->
        <?php foreach ($sections as $i => $sec): ?>
            <section id="<?= e($sectionSlugs[$i]) ?>" class="mt-4 article-body">
                <h2><?= e($sec['heading']) ?></h2>
                <div style="width:100%;" ><?= $sec['content'] ?></div>
            </section>
            <?php if ($ctaEnabled && !$ctaDone && $ctaPos === 'after_section:' . ($i + 1)) {
                $renderCtaBox();
            } ?>
        <?php endforeach; ?>

        <?php
        // "after_section:N" where N is beyond the actual section count falls back to the end.
        if ($ctaEnabled && !$ctaDone && str_starts_with($ctaPos, 'after_section:')) {
            $renderCtaBox();
        }
        $ctaAt('toc_end');
        ?>

        <!-- FAQs (shown before the Conclusion) -->
        <?php if ($faqs): ?>
            <div class="faq-section" id="faqAccordion">
                <h2 id="faq" class="mt-5 mb-3">Frequently Asked Questions</h2>
                <?php foreach ($faqs as $i => $faq): ?>
                    <div class="faq-item">
                        <div class="faq-q collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq<?= $i ?>">
                            <h3><?= e($faq['question']) ?></h3>
                        </div>
                        <div id="faq<?= $i ?>" class="accordion-collapse fade collapse" data-bs-parent="#faqAccordion">
                            <?= $faq['answer'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $ctaAt('after_faq'); ?>

        <!-- Conclusion -->
        <?php if (!empty($blog['conclusion'])): ?>
            <section id="conclusion" class="mt-5 article-body">
                <h2 class="mb-3">Conclusion</h2>
                <div><?= $blog['conclusion'] /* trusted rich content authored in admin */ ?></div>
            </section>
        <?php endif; ?>

        <?php $ctaAt('after_conclusion'); ?>

        <!-- About the Author (avatar is fixed for all blogs; shown only when enabled) -->
        <?php if (!empty($blog['show_author'])): ?>
            <?php
            $authorName = $blog['author_name'] ?: 'Cypherox Technologies';
            $authorRole = $blog['author_role'] ?: 'CEO & Founder';
            $authorBio = $blog['author_bio'] ?: 'The Cypherox Editorial Team is a group of engineers and AI specialists who take AI from pilots to dependable, governed production for mid-market companies. They write from hands-on experience shipping real systems across AI, data, cloud, and product engineering.';
            $authorImage = trim((string) ($blog['author_image'] ?? ''));
            if ($authorImage === '') {
                $authorImage = ASSETS_URL . '/img/user-avatar.svg';
            } elseif (!preg_match('#^https?://#i', $authorImage)) {
                $authorImage = rtrim(BASE_URL, '/') . '/' . ltrim($authorImage, '/');
            }
            $authorLinkedin = trim((string) ($blog['author_linkedin'] ?? ''));
            if ($authorLinkedin !== '' && !preg_match('#^https?://#i', $authorLinkedin)) {
                $authorLinkedin = 'https://' . $authorLinkedin;
            }
            ?>
            <section class="cx-author-bio" aria-label="About the author">
                <img class="cx-author-avatar" src="<?= e($authorImage) ?>"
                    alt="<?= e($authorName) ?>" width="64" height="64" loading="lazy">
                <div class="cx-author-info">
                    <p class="cx-author-eyebrow">About the Author</p>
                    <p class="cx-author-name">
                         <?= e($authorName) ?> 
                        <?php if ($authorLinkedin !== ''): ?>
                            <a class="cx-author-linkedin" href="<?= e($authorLinkedin) ?>" target="_blank" rel="noopener noreferrer" aria-label="Visit <?= e($authorName) ?> on LinkedIn">
                                <img src="<?= e(ASSETS_URL) ?>/img/linkedin.svg" alt="LinkedIn" loading="lazy">
                            </a>
                        <?php endif; ?>
                    </p>
                    <p class="cx-author-role"><?= e($authorRole) ?></p>
                    <p class="cx-author-text"><?= e($authorBio) ?></p>
                </div>
            </section>
        <?php endif; ?>

        <!-- previous / next blog navigation -->
        <?php if ($prevBlog || $nextBlog): ?>
            <nav class="cx-post-nav" aria-label="Blog navigation">
                <?php if ($prevBlog): ?>
                    <a class="cx-post-nav-link cx-post-nav-prev" href="<?= e($BASE) ?>/<?= urlencode($prevBlog['slug']) ?>" rel="prev">
                        <span class="cx-post-nav-dir">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 12H5M11 18l-6-6 6-6"/>
                            </svg>
                            Previous
                        </span>
                        <span class="cx-post-nav-title"><?= e($prevBlog['title']) ?></span>
                    </a>
                <?php else: ?>
                    <span class="cx-post-nav-link cx-post-nav-empty"></span>
                <?php endif; ?>
                <?php if ($nextBlog): ?>
                    <a class="cx-post-nav-link cx-post-nav-next" href="<?= e($BASE) ?>/<?= urlencode($nextBlog['slug']) ?>" rel="next">
                        <span class="cx-post-nav-dir">
                            Next
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </span>
                        <span class="cx-post-nav-title"><?= e($nextBlog['title']) ?></span>
                    </a>
                <?php else: ?>
                    <span class="cx-post-nav-link cx-post-nav-empty"></span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

        </main>

        <!-- ===================== RIGHT RAIL (20%) ===================== -->
        <aside class="cx-col-right">
            <?php if ($ctaEnabled): ?>
                <div class="cx-side-cta">
                    <div class="cx-side-cta-media">
                        <img src="<?= e($ctaImage) ?>" alt="<?= e($ctaBtnText) ?>" loading="lazy">
                    </div>
                    <?php if (!empty($blog_cat_name)): ?>
                        <span class="cx-side-cta-cat"><?= e($blog_cat_name) ?></span>
                    <?php endif; ?>
                    <?php if ($ctaText !== ''): ?>
                        <p class="cx-side-cta-text"><?= nl2br(e($ctaText)) ?></p>
                    <?php endif; ?>
                    <?php if ($ctaBtnText !== '' && $ctaBtnLink !== ''): ?>
                        <a class="cx-side-cta-btn" href="<?= e($ctaBtnLink) ?>">
                            <span><?= e($ctaBtnText) ?></span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </aside>

      </div>
    </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>