<?php
/**
 * Site header copied from https://www.cypherox.com/blog/ (nav + chrome).
 * Asset URLs are absolute to cypherox.com so CSS/JS/images load from the live CDN.
 * $pageTitle, $metaDescription, $canonical, $schema, $extraHead may be set before include.
 */
$pageTitle       = $pageTitle       ?? 'Cypherox Blog';
$metaDescription = $metaDescription ?? '';
$canonical       = $canonical       ?? '';
$schema          = $schema          ?? '';
$extraHead       = $extraHead       ?? '';

/* ----------------------- social / SEO meta (dynamic) --------------------- */
/* Each page may set these before include; sensible defaults are used otherwise. */
$DEFAULT_OG_IMAGE = 'https://www.cypherox.com/assets/img/cypherox-blog.png';
$ogTitle       = $ogTitle       ?? $pageTitle;
$ogDescription = $ogDescription ?? $metaDescription;
$ogType        = $ogType        ?? 'website';
$ogUrl         = $ogUrl         ?? $canonical;
$ogImage       = $ogImage       ?? $DEFAULT_OG_IMAGE;
$ogImageAlt    = $ogImageAlt    ?? $pageTitle;
$metaRobots    = $metaRobots    ?? 'noindex,nofollow';
$metaAuthor    = $metaAuthor    ?? 'Cypherox';
$twitterData1  = $twitterData1  ?? 'cypherox';

/* tiny escaper for attribute values */
$mh = static fn(?string $v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$ogImageExt  = strtolower((string) pathinfo((string) $ogImage, PATHINFO_EXTENSION));
$ogImageType = 'image/' . ($ogImageExt === 'jpg' ? 'jpeg' : ($ogImageExt ?: 'png'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    <!-- ================ Page Title & Description ================= -->
    <meta name="robots" content="<?= $mh($metaRobots) ?>">
    <meta name="googlebot" content="noindex, nofollow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <title><?= $mh($pageTitle) ?></title>
    <meta name="title" content="<?= $mh($pageTitle) ?>">
    <?php if ($metaDescription !== ''): ?><meta name="description" content="<?= $mh($metaDescription) ?>"><?php endif; ?>
    <meta name="author" content="<?= $mh($metaAuthor) ?>"/>
    <meta name="copyright" content="© <?= date('Y') ?> Cypherox"/>
    <?php if ($canonical !== ''): ?><link rel="canonical" href="<?= $mh($canonical) ?>"><?php endif; ?>
    <!-- ================ End Page Title & Description ================= -->

    <!-- ================ Open Graph / Facebook Meta Tags ================= -->
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="<?= $mh($ogType) ?>"/>
    <meta property="og:site_name" content="Cypherox"/>
    <meta property="og:title" content="<?= $mh($ogTitle) ?>"/>
    <?php if ($ogDescription !== ''): ?><meta property="og:description" content="<?= $mh($ogDescription) ?>"/><?php endif; ?>
    <?php if ($ogUrl !== ''): ?><meta property="og:url" content="<?= $mh($ogUrl) ?>"/><?php endif; ?>
    <meta property="og:image" content="<?= $mh($ogImage) ?>"/>
    <meta property="og:image:secure_url" content="<?= $mh($ogImage) ?>"/>
    <meta property="og:image:type" content="<?= $mh($ogImageType) ?>"/>
    <meta property="og:image:alt" content="<?= $mh($ogImageAlt) ?>"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <!-- ================ End Open Graph / Facebook Meta Tags ================= -->

    <!-- ================ Twitter Card Meta Tags ================= -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@cypherox"/>
    <meta name="twitter:creator" content="@cypherox"/>
    <meta name="twitter:title" content="<?= $mh($ogTitle) ?>"/>
    <?php if ($ogDescription !== ''): ?><meta name="twitter:description" content="<?= $mh($ogDescription) ?>"/><?php endif; ?>
    <meta name="twitter:image" content="<?= $mh($ogImage) ?>"/>
    <meta name="twitter:image:alt" content="<?= $mh($ogImageAlt) ?>"/>
    <meta name="twitter:label1" content="Written by"/>
    <meta name="twitter:data1" content="<?= $mh($twitterData1) ?>"/>
    <!-- ================ End Twitter Card Meta Tags ================= -->


    <!-- Cypherox site styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ASSETS_URL ?>/css/swiper.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL ?>/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL ?>/css/styles.css?16161616">


    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo ASSETS_URL ?>/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo ASSETS_URL ?>/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo ASSETS_URL ?>/img/favicon-16x16.png">
    <link rel="shortcut icon" href="<?php echo ASSETS_URL ?>/img/favicon.ico" />
    <link rel="manifest" href="<?php echo ASSETS_URL ?>/img/site.webmanifest">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <?= $extraHead ?>
        
    <?php if ($schema !== ''): ?><?= $schema ?><?php endif; ?>
    <script>!function(key) {if (window.reb2b) return;window.reb2b = {loaded: true};var s = document.createElement("script");s.async = true;s.src = "https://ddwl4m2hdecbv.cloudfront.net/b/" + key + "/" + key + ".js.gz";document.getElementsByTagName("script")[0].parentNode.insertBefore(s, document.getElementsByTagName("script")[0]);}("GOYPYHQJ3GOX");</script>
<script>window._nQc="89781462";</script>
<script async src="https://serve.albacross.com/track.js"></script>

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1688705918077878');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1688705918077878&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
</head>
<body class="blog-page">

    <div class="site_wrapper" style="flex-direction: column;">
        <div class="desktop-header">
            <header class="cust-header-v3">
                <div class="cursor"></div>
                <div class="header-container">
                <div class="header-inner">
                    <div class="brandLogo">
                    <a href="/" aria-label="Go to home page">
                        <img src="../../assets/img/logo-main.svg" alt="logo-main">
                    </a>
                    </div>
                    
                    <nav class="navbar">
                        <div class="mega-menu menu-v3">
                        <ul>
                            <li class="ai-btn">
                            <a href="/ai-agent-development-services" class="primary-btn"
                                aria-label="Go to ai agent development page">
                                <span class="glow"></span>
                                <span class="stock"></span>
                                <span class="btn-text">AI Agent</span>
                            </a>
                            </li>
                            <li>
                            <a href="/services" aria-label="Go to services page">Services </a>
                            <div class="v3-sub-menu-level-2">
                                <div class="cust-row row">
                                <div class="col-md-4">
                                    <a href="/ai-development-services" aria-label="Go to AI Development"
                                    class="v3-level-3-parent-link">AI Development</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/generative-ai-development-services" aria-label="Go to Generative AI Development page">Generative AI Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/ai-app-development-services"
                                        aria-label="Go to AI App Development page">AI App Development</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/virtual-assistant-services"
                                        aria-label="Go to AI Chatbots & Virtual Assistants page">AI Chatbots & Virtual Assistants</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/artificial-intelligence-and-machine-learning-services"
                                        aria-label="Go to Machine Learning Development page">Machine Learning Development</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/ai-sales-agent-for-ecommerce"
                                        aria-label="Go to AI Sales Agent for Ecommerce page">AI Sales Agent for Ecommerce</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <span class="column-title-main">Product Engineering</span>
                                    <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                        <a href="/application-development"
                                        aria-label="Go to Mobile App Development page">Mobile App Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/web-development-solutions"
                                        aria-label="Go to Web Development page">Web Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/saas-app-development-services" aria-label="Go to SaaS Development page">SaaS Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/e-commerce-development-services" aria-label="Go to Ecommerce Development page">Ecommerce Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/erp-and-crm-development-services" aria-label="Go to ERP & CRM Development page">ERP & CRM Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/ui-ux-design-services" aria-label="Go to UI/UX Design page">UI/UX Design</a>
                                    </li>

                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <a href="/cloud-and-devops-services" aria-label="Go to Cloud, DevOps & Data page"
                                    class="v3-level-3-parent-link">Cloud, DevOps & Data</a>
                                    <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                        <a href="/cloud-strategy-consulting-services"
                                        aria-label="Go to Cloud Strategy Consulting page">Cloud Strategy Consulting</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/cloud-migration-services"
                                        aria-label="Go to Cloud Migration page">Cloud Migration</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/cloud-optimization-services"
                                        aria-label="Go to Cloud Optimization page">Cloud Optimization</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/devops-automation-services" aria-label="Go to DevOps Automation page">DevOps Automation</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/continuous-testing-and-monitoring-services"
                                        aria-label="Go to Continuous Testing & Monitoring page">Continuous Testing & Monitoring</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/data-analytics-solutions"
                                        aria-label="Go to Data Analytics page">Data Analytics</a>
                                    </li>
                                    </ul>
                                </div>


                                <div class="col-md-4">
                                    <a href="/consulting-services"
                                    aria-label="Go to Consulting page" class="v3-level-3-parent-link">Consulting</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/it-consulting-services"
                                        aria-label="Go to IT Consulting page">IT Consulting</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/mobile-app-development-consulting-services"
                                        aria-label="Go to Mobile App Consulting page">Mobile App Consulting</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <span class="column-title-main">Emerging Technologies</span>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/blockchain-development-services"
                                        aria-label="Go to Blockchain Development page">
                                        Blockchain Development</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/virtual-reality-and-augmented-reality-solutions"
                                        aria-label="Go to AR/VR Development page">
                                        AR/VR Development</a>
                                    </li>
                                    </ul>
                                </div>
                                </div>

                            </div>
                            </li>

                            <li>
                            <a href="/technology" aria-label="Go to Technology page"> Technologies </a>
                            <div class="v3-sub-menu-level-2">
                                <div class="cust-row row">
                                <div class="col-md-6">
                                    <a href="/application-development" aria-label="Go to Application Development page"
                                    class="v3-level-3-parent-link"> Application Development </a>
                                    <ul class="v3-inner-menu three-column-list no-left-line">
                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/ios-app-development-services" aria-label="Go to iOS app development services">
                                        iOS
                                        </a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/swift-app-development-services" aria-label="Go to swift">Swift</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/objective-c-app-development-services" aria-label="Go to objective-c">
                                            Objective-C</a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/android-app-development-services" aria-label="Go to android "> Android </a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/kotlin-app-development-services" aria-label="Go to kotlin "> Kotlin
                                            </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/java-app-development-services" aria-label="Go to java "> Java </a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/cross-platform-app-development-services" aria-label="Go to cross-platform ">
                                        Cross-Platform </a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/dart-app-development-services" aria-label="Go to dart "> Dart </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/flutter-app-development-services" aria-label="Go to flutter "> Flutter
                                            </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/react-native-app-development-services" aria-label="Go to react native ">
                                            React Native
                                            </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/xamarin-app-development-services" aria-label="Go to xamarin "> Xamarin
                                            </a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/framework-app-development-services" aria-label="Go to framework ">
                                        Framework </a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/golang-app-development-services" aria-label="Go to golang "> Golang
                                            </a>
                                        </li>
                                        </ul>
                                    </li>
                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/application-development" aria-label="Go to Trending App Solutions">Trending
                                        App
                                        Solutions</a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/ai-app-development-services" aria-label="Go to AI App Development page">AI
                                            App</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/b2b-mobile-app-development-services"
                                            aria-label="Go to B2B Mobile App Development page">B2B
                                            Mobile App</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/b2c-mobile-app-development-services"
                                            aria-label="Go to B2C Mobile App Development page">B2C
                                            Mobile App</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/saas-app-development-services"
                                            aria-label="Go to SAAS App Development  page">SAAS
                                            App </a>
                                        </li>
                                        </ul>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <a href="/web-development-solutions" aria-label="Go to Web Development page"
                                    class="v3-level-3-parent-link"> Web Development </a>
                                    <ul class="v3-inner-menu three-column-list no-left-line">

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/front-end-development-services" aria-label="Go to Front End Web Development">
                                        Front End
                                        </a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/angularjs-development-services" aria-label="Go to angularjs">
                                            AngularJS</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/reactjs-development-services" aria-label="Go to reactjs"> ReactJS </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/vuejs-development-services" aria-label="Go to vuejs"> VueJS </a>
                                        </li>
                                        </ul>
                                    </li>
                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/back-end-development-services" aria-label="Go to back end"> Back End </a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/nodejs-development-services" aria-label="Go to nodejs"> NodeJS</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/express-js-development-services" aria-label="Go to expressjs">
                                            ExpressJS </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/php-development-services" aria-label="Go to php"> PHP </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/python-web-development-services" aria-label="Go to python"> Python </a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/full-stack-development-services" aria-label="Go to full stack"> Full
                                        Stack</a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/mean-stack-development-services" aria-label="Go to MEAN">MEAN</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/mern-stack-development-services" aria-label="Go to MERN">MERN</a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/e-commerce-development-services" aria-label="Go to e-commerce">
                                        E-Commerce</a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/shopify-development-services" aria-label="Go to shopify web development">
                                            Shopify</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/magento-development-services" aria-label="Go to magento web development">
                                            Magento</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/woocommerce-development-services"
                                            aria-label="Go to woo commerce web development">
                                            WooCommerce</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/bigcommerce-development-services"
                                            aria-label="Go to big commerce web development">
                                            BigCommerce </a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/cms-development-company"
                                        aria-label="Go to content management system web development services">
                                        CMS</a>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/wordpress-development-services"
                                            aria-label="Go to wordpress web development">
                                            WordPress </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/joomla-development-services" aria-label="Go to joomla web development">
                                            Joomla </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/drupal-development-services" aria-label="Go to drupal web development">
                                            Drupal </a>
                                        </li>

                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <span class="sub-title-main">Modern</span>
                                        <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                            <a href="/headless-development-services"
                                            aria-label="Go to headless web development">
                                            Headless</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                            <a href="/webflow-development-services" aria-label="Go to webflow web development">
                                            Webflow</a>
                                        </li>
                                        </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                        <a href="/erp-and-crm-development-services" aria-label="Go to crm and erp development"
                                        class="">ERP
                                        and CRM </a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </li>

                            <li class="clone-solutions">
                            <a href="/clone-solutions" aria-label="Go to clone solutions page"> Clone Solutions </a>
                            <div class="v3-sub-menu-level-2">
                                <div class="cust-row row">
                                    <span class="column-title-main" style="padding: 0; margin-bottom: -15px;">Clone Development</span>
                                <div class="col-md-12">
                                    <ul class="v3-inner-menu five-column-list">
                                    <li class="v3-inner-menu-item">
                                        <a href="/ada-health-clone" aria-label="Go to Ada Health Clone page"> Ada Health Clone
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/candy-ai-clone" aria-label="Go to candy ai clone page"> Candy AI Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/character-ai-clone" aria-label="Go to character ai clone page"> Character AI
                                        Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/glean-ai-clone" aria-label="Go to glean ai clone page"> Glean AI Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/multipass-ai-clone" aria-label="Go to multipass ai clone page"> Multipass AI
                                        Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/opus-clip-clone" aria-label="Go to opus clip clone page"> Opus Clip Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/polybuzz-ai-clone" aria-label="Go to polybuzz ai clone page"> Polybuzz AI
                                        Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/replika-ai-clone" aria-label="Go to replika ai clone page"> Replika AI Clone
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/rizz-ai-clone" aria-label="Go to rizz ai clone page"> Rizz AI Clone </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/talkie-ai-clone" aria-label="Go to talkie ai clone page"> Talkie AI Clone </a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </li>

                            <li>
                            <a href="/service-industry" aria-label="Go to Industry page"> Industries </a>
                            <div class="v3-sub-menu-level-2">
                                <div class="cust-row row">
                                <div class="col-md-12">
                                    <a href="/service-industry" class="v3-level-3-parent-link"
                                    aria-label="Go to Industries we served">
                                    Industries we served
                                    </a>
                                    <ul class="v3-inner-menu five-column-list">
                                    <li class="v3-inner-menu-item">
                                        <a href="/healthcare-industry-services" aria-label="Go to healthcare page">
                                        Healthcare </a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/finance-industry-services" aria-label="Go to finance page"> Finance </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/retail-industry-services" aria-label="Go to retail page"> Retail </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/manufacturing-industry-services" aria-label="Go to manufacturing page">
                                        Manufacturing
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/transportation-industry-services" aria-label="Go to transportation page">
                                        Transportation
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/education-industry-services" aria-label="Go to education pages"> Education
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/energy-industry-services" aria-label=" Go to energy page"> Energy </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/agriculture-industry-services" aria-label="Go to agriculture page">
                                        Agriculture </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/media-and-entertainment-industry-services"
                                        aria-label="Go to media-and-entertainment"> Media and
                                        entertainment </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/social-networking-industry-services" aria-label="Go to social networking">
                                        Social Networking
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/on-demand-delivery-industry-services" aria-label=" Go to on demand delivery">
                                        On-Demand
                                        Delivery </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/entertainment-industry-services" aria-label="Go to entertainment">
                                        Entertainment </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/health-and-fitness-industry-services" aria-label="Go to health and fitness">
                                        Health &amp;
                                        Fitness </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/food-and-drink-industry-services" aria-label="Go to food and drink"> Food
                                        &amp; Drink </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/logistics-industry-services" aria-label="Go to transportation and logistics">
                                        Transportation &amp; Logistics
                                        </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/e-learning-industry-services" aria-label="Go to education and e-learning">
                                        Education
                                        &amp; E-learning </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/ecommerce-industry-services" aria-label="Go to ecommerce and shopping">
                                        Ecommerce
                                        &amp; Shopping </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/banking-industry-services" aria-label="Go to banking and finance"> Banking
                                        &amp;
                                        Finance </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/travel-and-tourism-industry-services" aria-label="Go to travel and tourism">
                                        Travel &amp;
                                        Tourism </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/real-estate-industry-services" aria-label="Go to real estate"> Real-estate
                                        </a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </li>

                            <li>
                            <a href="/hire-dedicated-developers" aria-label="Go to hire developer">Hire Developers </a>
                            <div class="v3-sub-menu-level-2 cust-hire-col">
                                <div class="cust-row row justify-content-between">
                                <div class="col-md-3 w-auto">
                                    <a href="/hire-mobile-app-developers" aria-label="Go to hire mobile app developer"
                                    class="v3-level-3-parent-link">Mobile App Developers</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-ios-developer" aria-label="Go to hire developer ios" class="">iOS
                                        Developer</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-android-developer" aria-label="Go to hire developer android"
                                        class="">Android Developer</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-swift-developers" aria-label="Go to hire swift developer" class="">Swift
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-kotlin-developers" aria-label="Go to hire kotlin developers"
                                        class="">Kotlin Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-flutter-developers" aria-label="Go to hire-flutter-developers"
                                        class="">Flutter Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-react-native-developers" aria-label="Go to hire react native developers"
                                        class="">React Native Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-golang-developers" aria-label="Go to hire golang developers"
                                        class="">Golang Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-java-developers" aria-label="Go to hire java developers" class="">Java
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-dart-developers" aria-label="Go to hire dart developers" class="">Dart
                                        Developers</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-3 w-auto">
                                    <a href="/hire-a-front-end-web-developer" aria-label="Go to hire a front end web developer"
                                    class="v3-level-3-parent-link">Front-End
                                    Web Developer</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-angularjs-developers" aria-label="Goangularjs developers"
                                        class="">AngularJS
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-reactjs-developers" aria-label="Go to hire reactjs developers"
                                        class="">ReactJS Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-vuejs-developers" aria-label="Go to hire vuejs developers" class="">VueJS
                                        Developers</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-3 w-auto">
                                    <a href="/hire-a-back-end-web-developer" aria-label="Go to hire a back end web developer"
                                    class="v3-level-3-parent-link">Back-End Web Developer</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-nodejs-developers" aria-label="Go to hire NodeJS developers"
                                        class="">NodeJS Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-laravel-developers" aria-label="Go to hire laravel developer"
                                        class="">Laravel Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-python-developers" aria-label="Go to hire python developers"
                                        class="">Python Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-php-developers" aria-label="Go to hire php developer" class="">PHP
                                        Developers</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-2 w-auto">
                                    <a href="/hire-e-commerce-developers" aria-label="Go to hire e-commerce developers"
                                    class="v3-level-3-parent-link">E-Commerce Developers</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-wordpress-developers" aria-label="Go to hire wordpress developer"
                                        class="">WordPress Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-shopify-developers" aria-label="Go to hire shopify developer"
                                        class="">Shopify Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-magento-developers" aria-label="Go to hire magento developers"
                                        class="">Magento Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-bigcommerce-developers" aria-label="Go to hire bigcommerce developers"
                                        class="">BigCommerce Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-woocommerce-developers" aria-label="Go to hire woocommerce developers"
                                        class="">WooCommerce Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-chatbot-developers" aria-label="Go to hire chatbot developer"
                                        class="">Chatbot Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-graphic-designers" aria-label="Go to hire graphic designer"
                                        class="">Graphic Designers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-ui-ux-designers" aria-label="Go to hire ui ux designers" class="">UI/UX
                                        Designers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-digital-marketers" aria-label="Go to hire digital marketers developer"
                                        class="">Digital Marketers</a>
                                    </li>
                                    </ul>
                                </div>
                                <div class="col-md-2 w-auto">
                                    <a href="/hire-dedicated-developers" aria-label="Go to hire Trending Developer"
                                    class="v3-level-3-parent-link">Trending Developer</a>
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-devops-developers" aria-label="Go to hire DevOps Developers"
                                        class="">DevOps
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-aws-developers" aria-label="Go to hire aws developers" class="">AWS
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-ai-developers" aria-label="Go to hire ai developers" class="">AI
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-ml-developers" aria-label="Go to hire ml developers" class="">ML
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-blockchain-developers" aria-label="Go to hire blockchain developers"
                                        class="">Blockchain Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-ar-developers" aria-label="Go to hire ar developers" class="">AR
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-vr-developers" aria-label="Go to hire vr developers" class="">VR
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-data-analytics" aria-label="Go to hire data analytics" class="">Data
                                        Analytics</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/hire-full-stack-developer" aria-label="Go to hire full stackdeveloper"
                                        class="">Full Stack Developer</a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </li>
                            <li class="position-relative  company-multi-dropdown">
                            <a href="/about-us" aria-label="Go to Company pages"> Company </a>
                            <div class="v3-sub-menu-level-2 sub-menu-small">
                                <div class="cust-row row no-border" data-lenis-prevent="true">
                                <div class="col-md-12">
                                    <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="/about-us" aria-label="Go to about page" class="v3-inner-item-link">About us
                                        </a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/life-at-cypherox" aria-label="Go to cypherox page"
                                        class="v3-inner-item-link">Life@Cypherox</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/corporate-social-responsibility" aria-label="Go to CSR Activity page"
                                        class="v3-inner-item-link">CSR Activity</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/our-work/" aria-label="Go to our work page" class="v3-inner-item-link">our
                                        work</a>
                                    </li>

                                    <li class="v3-inner-menu-item official-partners">
                                        <a href="#official-partners" class="official-partners-a"
                                        aria-label="Go to Official Partners pages"> Official Partners
                                        <span class="submenu-toggle">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                                d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z">
                                            </path>
                                            </svg>
                                        </span>
                                        </a>
                                        <div class="company-multi-dropdown-main">
                                        <div class="company-multi-dropdown-main-inner">
                                            <div class="col-md-12">
                                            <ul class="company-multi-menu">
                                                <li class="company-multi-menu-item">
                                                <a href="/jibble-partnership-free-time-tracking-software"
                                                    aria-label="Go to Jibble page" class="company-multi-item-link"> Jibble </a>
                                                </li>
                                                <li class="company-multi-menu-item">
                                                <a href="/shopify-partnership" aria-label="Go to shopify page"
                                                    class="company-multi-item-link"> Shopify </a>
                                                </li>
                                                <li class="company-multi-menu-item">
                                                <a href="https://codespheretech.in/" target="_blank"
                                                    aria-label="Go to codespheretech page" class="company-multi-item-link">Odoo
                                                    (codespheretech)</a>
                                                </li>
                                            </ul>
                                            </div>
                                        </div>
                                        </div>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/our-engagement-models" aria-label="Go to  Engagement Model page"
                                        class="v3-inner-item-link">Engagement Model</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="/blog/" aria-label="Go to blog page" class="v3-inner-item-link">Blogs</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/news/" aria-label="Go to news page" class="v3-inner-item-link">News</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="/get-in-touch" aria-label="Go to contact us page"
                                        class="v3-inner-item-link">Contact us</a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            </li>
                            <li class="position-relative">


                            </li>
                            <li>
                            <a href="/career" aria-label="Go to contact page"> Careers </a>
                            </li>
                        </ul>
                        </div>
                    </nav>
                        
                    <div class="mobile-menu" data-lenis-prevent="true">
                        <nav class="mobile-navbar">
                        <div class="mega-menu" data-lenis-prevent="true">
                            <ul class="menu-level-1">
                            <li class="ai-agent">
                                <a href="/ai-agent-development-services" class="primary-btn"
                                aria-label="Go to ai agent development page">
                                <span class="glow"></span>
                                <span class="stock"></span>
                                <span class="btn-text">AI Agent</span>
                                </a>
                            </li>

                            <li class="list-item-1 has-inner-menu">
                                <a href="/services" aria-label="Go to services page" class="link-item-level-1"> <span
                                    class="inner-text">
                                    Services </span>
                                <span class="submenu-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                                </a>
                                <div class="second-sub-menu">
                                <ul class="second-sub-menu-ul">
                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/ai-development-services" aria-label="Go to AI Development"
                                        class="">
                                        <span class="inner-text">AI Development</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li class="">
                                        <a href="/generative-ai-development-services" aria-label="Go to Generative AI Development page">Generative AI Development</a>
                                        </li>

                                        <li class="">
                                        <a href="/ai-app-development-services"
                                            aria-label="Go to AI App Development page">AI App Development</a>
                                        </li>
                                        <li class="">
                                        <a href="/virtual-assistant-services"
                                            aria-label="Go to AI Chatbots & Virtual Assistants page">AI Chatbots & Virtual Assistants</a>
                                        </li>

                                        <li class="">
                                        <a href="/artificial-intelligence-and-machine-learning-services"
                                            aria-label="Go to Machine Learning Development page">Machine Learning Development</a>
                                        </li>
                                        <li class="">
                                        <a href="/ai-sales-agent-for-ecommerce"
                                            aria-label="Go to AI Sales Agent for Ecommerce page">AI Sales Agent for Ecommerce</a>
                                        </li>
                                    </ul>
                                    </li>


                                    <li class="second-sub-menu-li has-inner-menu">
                                    <span class="mobile-menu-title">
                                        <span class="inner-text">Product Engineering</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </span>
                                    <ul class="third-sub-menu">
                                        <li class="">
                                        <a href="/application-development"
                                            aria-label="Go to Mobile App Development page">Mobile App Development</a>
                                        </li>

                                        <li class="">
                                        <a href="/web-development-solutions" aria-label="Go to Web Development page">Web Development</a>
                                        </li>

                                        <li class="">
                                        <a href="/saas-app-development-services" aria-label="Go to SaaS Development page">SaaS Development</a>
                                        </li>

                                        <li class="">
                                        <a href="/e-commerce-development-services" aria-label="Go to Ecommerce Development page">Ecommerce Development</a>
                                        </li>

                                        <li class="">
                                        <a href="/erp-and-crm-development-services" aria-label="Go to ERP & CRM Development page">ERP & CRM Development</a>
                                        </li>

                                        <li class="">
                                        <a href="/ui-ux-design-services" aria-label="Go to UI/UX Design page">UI/UX Design</a>
                                        </li>

                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/cloud-and-devops-services" aria-label="Go to Cloud, DevOps & Data">
                                        <span class="inner-text"> Cloud, DevOps & Data</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li class="">
                                        <a href="/cloud-strategy-consulting-services"
                                            aria-label="Go to Cloud Strategy Consulting page">Cloud Strategy Consulting</a>
                                        </li>

                                        <li class="">
                                        <a href="/cloud-migration-services"
                                            aria-label="Go to Cloud Migration page">Cloud Migration</a>
                                        </li>

                                        <li class="">
                                        <a href="/cloud-optimization-services" aria-label="Go to Cloud Optimization page">Cloud Optimization</a>
                                        </li>

                                        <li class="">
                                        <a href="/devops-automation-services"
                                            aria-label="Go to DevOps Automation page">DevOps Automation</a>
                                        </li>

                                        <li class="">
                                        <a href="/continuous-testing-and-monitoring-services" aria-label="Go to Continuous Testing & Monitoring page">Continuous Testing & Monitoring</a>
                                        </li>

                                        <li class="">
                                        <a href="/data-analytics-solutions" aria-label="Go to Data Analytics page">Data Analytics</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/consulting-services"
                                        aria-label="Go to Consulting">
                                        <span class="inner-text">Consulting
                                        </span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/it-consulting-services"
                                            aria-label="Go to IT Consulting page">IT Consulting</a>
                                        </li>
                                        <li>
                                        <a href="/mobile-app-development-consulting-services"
                                            aria-label="Go to Mobile App Consulting page">Mobile App Consulting</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li has-inner-menu">
                                    <span class="mobile-menu-title">
                                        <span class="inner-text">
                                        Emerging Technologies
                                        </span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </span>
                                    <ul class="third-sub-menu">
                                        <li class="v3-inner-menu-item">
                                        <a href="/blockchain-development-services"
                                            aria-label="Go to Blockchain Development">Blockchain Development</a>
                                        </li>

                                        <li class="v3-inner-menu-item">
                                        <a href="/virtual-reality-and-augmented-reality-solutions"
                                            aria-label="Go to AR/VR Development page">AR/VR Development</a>
                                        </li>
                                    </ul>
                                    </li>

                                </ul>
                                </div>

                            </li>

                            <li class="list-item-1 has-inner-menu">
                                <a href="/technology" aria-label="Go to Technology page" class="link-item-level-1">
                                <span class="inner-text">Technologies</span>
                                <span class="submenu-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                                </a>
                                <div class="second-sub-menu">
                                <ul class="second-sub-menu-ul">
                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/application-development" aria-label="Go to Application Development page"
                                        class="">
                                        <span class="inner-text">Application Development</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/ios-app-development-services"
                                            aria-label="Go to iOS app development services">iOS</a>
                                        </li>
                                        <li class="">
                                        <a href="/swift-app-development-services" aria-label="Go to swift">Swift</a>
                                        </li>
                                        <li class="">
                                        <a href="/objective-c-app-development-services"
                                            aria-label="Go to objective-c">Objective-C</a>
                                        </li>
                                        <li>
                                        <a href="/android-app-development-services" aria-label="Go to android ">Android</a>
                                        </li>
                                        <li class="">
                                        <a href="/kotlin-app-development-services" aria-label="Go to kotlin ">Kotlin </a>
                                        </li>
                                        <li class="">
                                        <a href="/java-app-development-services" aria-label="Go to java ">Java</a>
                                        </li>
                                        <li>
                                        <a href="/cross-platform-app-development-services" aria-label="Go to cross-platform ">
                                            Cross-Platform</a>
                                        </li>
                                        <li class="">
                                        <a href="/dart-app-development-services" aria-label="Go to dart "> Dart </a>
                                        </li>
                                        <li class="">
                                        <a href="/flutter-app-development-services" aria-label="Go to flutter "> Flutter
                                        </a>
                                        </li>
                                        <li class="">
                                        <a href="/react-native-app-development-services" aria-label="Go to react native ">
                                            React Native </a>
                                        </li>
                                        <li class="">
                                        <a href="/xamarin-app-development-services" aria-label="Go to xamarin "> Xamarin
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/framework-app-development-services" aria-label="Go to framework ">
                                            Framework</a>
                                        </li>
                                        <li class="">
                                        <a href="/golang-app-development-services" aria-label="Go to golang "> Golang </a>
                                        </li>
                                        <li>
                                        <a href="/application-development" aria-label="Go to Trending App Solutions ">Trending
                                            App
                                            Solutions</a>
                                        </li>
                                        <li class="">
                                        <a href="/ai-app-development-services" aria-label="Go to AI App Development page">AI
                                            App
                                        </a>
                                        </li>
                                        <li class="">
                                        <a href="/b2b-mobile-app-development-services"
                                            aria-label="Go to B2B Mobile App Development page">B2B
                                            Mobile
                                            App </a>
                                        </li>
                                        <li class="">
                                        <a href="/b2c-mobile-app-development-services"
                                            aria-label="Go to B2C Mobile App Development page">B2C
                                            Mobile
                                            App </a>
                                        </li>
                                        <li class="">
                                        <a href="/saas-app-development-services"
                                            aria-label="Go to SAAS App Development  page">SAAS
                                            App
                                        </a>
                                        </li>
                                    </ul>
                                    </li>
                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/web-development-solutions" aria-label="Go to Web Development page"
                                        class="link-item-level-1">
                                        <span class="inner-text"> Web Development </span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li class="">
                                        <a href="/front-end-development-services"
                                            aria-label="Go to Front End Web Development"> Front
                                            End</a>
                                        </li>
                                        <li class="">
                                        <a href="/angularjs-development-services" aria-label="Go to angularjs">
                                            AngularJS</a>
                                        </li>
                                        <li class="">
                                        <a href="/reactjs-development-services" aria-label="Go to reactjs"> ReactJS </a>
                                        </li>
                                        <li class="">
                                        <a href="/vuejs-development-services" aria-label="Go to vuejs"> VueJS </a>
                                        </li>
                                        <li class="">
                                        <a href="/back-end-development-services" aria-label="Go to back end"> Back End</a>
                                        </li>
                                        <li class="">
                                        <a href="/nodejs-development-services" aria-label="Go to nodejs"> NodeJS</a>
                                        </li>
                                        <li class="">
                                        <a href="/express-js-development-services" aria-label="Go to expressjs"> ExpressJS
                                        </a>
                                        </li>
                                        <li class="">
                                        <a href="/php-development-services" aria-label="Go to php"> PHP </a>
                                        </li>
                                        <li class="">
                                        <a href="/python-web-development-services" aria-label="Go to python"> Python </a>
                                        </li>
                                        <li class="">
                                        <a href="/full-stack-development-services" aria-label="Go to full stack"> Full
                                            Stack</a>
                                        </li>
                                        <li class="">
                                        <a href="/mean-stack-development-services" aria-label="Go to MEAN">MEAN</a>
                                        </li>
                                        <li class="">
                                        <a href="/mern-stack-development-services" aria-label="Go to MERN">MERN</a>
                                        </li>
                                        <li class="">
                                        <a href="/e-commerce-development-services" aria-label="Go to e-commerce">
                                            E-Commerce </a>
                                        </li>
                                        <li class="">
                                        <a href="/shopify-development-services" aria-label="Go to shopify web development">
                                            Shopify</a>
                                        </li>
                                        <li class="">
                                        <a href="/magento-development-services" aria-label="Go to magento web development">
                                            Magento</a>
                                        </li>
                                        <li class="">
                                        <a href="/woocommerce-development-services"
                                            aria-label="Go to woo commerce web development">
                                            WooCommerce</a>
                                        </li>
                                        <li class="">
                                        <a href="/bigcommerce-development-services"
                                            aria-label="Go to big commerce web development">
                                            BigCommerce </a>
                                        </li>
                                        <li class="">
                                        <a href="/cms-development-company"
                                            aria-label="Go to content management system web development services">
                                            CMS</a>
                                        </li>
                                        <li class="">
                                        <a href="/wordpress-development-services"
                                            aria-label="Go to wordpress web development">
                                            WordPress </a>
                                        </li>
                                        <li class="">
                                        <a href="/joomla-development-services" aria-label="Go to joomla web development">
                                            Joomla
                                        </a>
                                        </li>
                                        <li class="">
                                        <a href="/drupal-development-services" aria-label="Go to drupal web development">
                                            Drupal
                                        </a>
                                        </li>
                                        <li class="">
                                        <a href="javascript:;"
                                            aria-label="Go to Go to modern web-development approaches">
                                            Modern</a>
                                        </li>
                                        <li class="">
                                        <a href="/headless-development-services" aria-label="Go to headless web development">
                                            Headless</a>
                                        </li>
                                        <li class="">
                                        <a href="/webflow-development-services" aria-label="Go to webflow web development">
                                            Webflow</a>
                                        </li>
                                        <li class="">
                                        <a href="/erp-and-crm-development-services" aria-label="Go to crm and erp development"
                                            class="">ERP and CRM </a>
                                        </li>
                                    </ul>
                                    </li>
                                </ul>
                                </div>
                            </li>

                            <li class="list-item-1 has-inner-menu">
                                <a href="/service-industry" class="link-item-level-1" aria-label="Go to Industries we served">
                                <span class="inner-text">Industries</span>
                                <span class="submenu-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                                </a>
                                <div class="second-sub-menu">
                                <ul class="second-sub-menu-ul">
                                    <li class="second-sub-menu-li">
                                    <a href="/healthcare-industry-services" aria-label="Go to healthcare page"> Healthcare
                                    </a>
                                    </li>
                                    <li class="second-sub-menu-li">
                                    <a href="/finance-industry-services" aria-label="Go to finance page"> Finance </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/retail-industry-services" aria-label="Go to retail page"> Retail </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/manufacturing-industry-services" aria-label="Go to manufacturing page">
                                        Manufacturing </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/transportation-industry-services" aria-label="Go to transportation page">
                                        Transportation </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/education-industry-services" aria-label="Go to education pages"> Education
                                    </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/energy-industry-services" aria-label=" Go to energy page"> Energy </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/agriculture-industry-services" aria-label="Go to agriculture page">
                                        Agriculture </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/media-and-entertainment-industry-services"
                                        aria-label="Go to media-and-entertainment"> Media and
                                        entertainment </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/social-networking-industry-services" aria-label="Go to social networking">
                                        Social Networking </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/on-demand-delivery-industry-services" aria-label=" Go to on demand delivery">
                                        On-Demand Delivery
                                    </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/entertainment-industry-services" aria-label="Go to entertainment">
                                        Entertainment </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/health-and-fitness-industry-services" aria-label="Go to health and fitness">
                                        Health &amp; Fitness
                                    </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/food-and-drink-industry-services" aria-label="Go to food and drink"> Food
                                        &amp; Drink </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/logistics-industry-services" aria-label="Go to transportation and logistics">
                                        Transportation &amp;
                                        Logistics
                                    </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/e-learning-industry-services" aria-label="Go to education and e-learning">
                                        Education &amp;
                                        E-learning
                                    </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/ecommerce-industry-services" aria-label="Go to ecommerce and shopping">
                                        Ecommerce &amp;
                                        Shopping </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/banking-industry-services" aria-label="Go to banking and finance"> Banking
                                        &amp;
                                        Finance </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/travel-and-tourism-industry-services" aria-label="Go to travel and tourism">
                                        Travel &amp; Tourism
                                    </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/real-estate-industry-services" aria-label="Go to real estate"> Real-estate
                                    </a>
                                    </li>
                                </ul>
                                </div>
                            </li>

                            <li class="list-item-1 has-inner-menu clone-solutions">
                                <a href="/clone-solutions" class="link-item-level-1" aria-label="Clone Solutions">
                                <span class="inner-text">Clone Solutions</span>
                                <span class="submenu-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                                </a>
                                <div class="second-sub-menu">
                                <div class="cust-row row">
                                    <ul class="second-sub-menu-ul">
                                    <li class="second-sub-menu-li">
                                        <a href="/ada-health-clone" aria-label="Go to Ada Health Clone page"> Ada Health Clone
                                        </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/candy-ai-clone" aria-label="Go to candy ai clone page"> Candy AI Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/character-ai-clone" aria-label="Go to character ai clone page"> Character AI
                                        Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/glean-ai-clone" aria-label="Go to glean ai clone page"> Glean AI Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/multipass-ai-clone" aria-label="Go to multipass ai clone page"> Multipass AI
                                        Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/opus-clip-clone" aria-label="Go to opus clip clone page"> Opus Clip Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/polybuzz-ai-clone" aria-label="Go to polybuzz ai clone page"> Polybuzz AI
                                        Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/replika-ai-clone" aria-label="Go to replika ai clone page"> Replika AI Clone
                                        </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/rizz-ai-clone" aria-label="Go to rizz ai clone page"> Rizz AI Clone </a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="/talkie-ai-clone" aria-label="Go to talkie ai clone page"> Talkie AI Clone </a>
                                    </li>
                                    </ul>
                                </div>
                                </div>
                            </li>

                            <li class="list-item-1 has-inner-menu">
                                <a href="/hire-dedicated-developers" aria-label="Go to hire developer"
                                class="link-item-level-1">
                                <span class="inner-text"> Hire Developers </span>
                                <span class="submenu-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                                </a>
                                <div class="second-sub-menu">
                                <ul class="second-sub-menu-ul">
                                    <li class="second-sub-menu-li has-inner-menu">

                                    <a href="/hire-mobile-app-developers" aria-label="Go to hire mobile app developer"
                                        class="">
                                        <span class="inner-text">Mobile App Developers</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/hire-ios-developer" aria-label="Go to hire developer ios" class=""> IOS
                                            Developer</a>
                                        </li>
                                        <li>
                                        <a href="/hire-android-developer" aria-label="Go to hire developer android" class="">
                                            Android Developer</a>
                                        </li>
                                        <li>
                                        <a href="/hire-swift-developers" aria-label="Go to hire swift developer" class="">
                                            Swift
                                            Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-kotlin-developers" aria-label="Go to hire kotlin developers" class="">
                                            Kotlin Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-flutter-developers" aria-label="Go to hire-flutter-developers"
                                            class="">

                                            Flutter Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-react-native-developers"
                                            aria-label="Go to hire react native developers" class=""> React
                                            Native Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-golang-developers" aria-label="Go to hire golang developers" class="">
                                            Golang Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-java-developers" aria-label="Go to hire java developers" class="">
                                            Java
                                            Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-dart-developers" aria-label="Go to hire dart developers" class="">
                                            Dart
                                            Developers</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/hire-a-front-end-web-developer" aria-label="Go to hire front end web developer"
                                        class="">
                                        <span class="inner-text">Front-End Web Developer</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/hire-angularjs-developers" aria-label="Go to hire angularjs developers"
                                            class="">
                                            AngularJS Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-reactjs-developers" aria-label="Go to hire reactjs developers"
                                            class="">
                                            ReactJS Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-vuejs-developers" aria-label="Go to hire vuejs developers" class="">
                                            VueJS
                                            Developers</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/hire-a-back-end-web-developer" aria-label="Go to hire Back-End Web Developer"
                                        class="">
                                        <span class="inner-text">Back-End Web Developer</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/hire-nodejs-developers" aria-label="Go to hire nodejs developers" class="">
                                            NodeJS Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-laravel-developers" aria-label="Go to hire laravel developer" class="">
                                            Laravel Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-python-developers" aria-label="Go to hire python developers" class="">
                                            Python Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-php-developers" aria-label="Go to hire php developer" class=""> PHP
                                            Developers</a>
                                        </li>
                                    </ul>
                                    </li>



                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/hire-e-commerce-developers" aria-label="Go to hire e-commerce developers"
                                        class="">
                                        <span class="inner-text">E-Commerce Developers</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/hire-wordpress-developers" aria-label="Go to hire wordpress developer"
                                            class="">
                                            WordPress
                                            Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-shopify-developers" aria-label="Go to hire shopify developer" class="">
                                            Shopify
                                            Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-magento-developers" aria-label="Go to hire magento developers"
                                            class="">
                                            Magento
                                            Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-bigcommerce-developers" aria-label="Go to hire bigcommerce developers"
                                            class="">
                                            BigCommerce Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-woocommerce-developers" aria-label="Go to hire woocommerce developers"
                                            class="">
                                            WooCommerce Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-chatbot-developers" aria-label="Go to hire chatbot developer" class="">
                                            Chatbot
                                            Developers</a>
                                        </li>
                                        <li>
                                        <a href="/hire-graphic-designers" aria-label="Go to hire graphic designer" class="">
                                            Graphic
                                            Designers </a>
                                        </li>
                                        <li>
                                        <a href="/hire-ui-ux-designers" aria-label="Go to hire ui ux designers" class="">
                                            UI/UX
                                            Designers
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/hire-digital-marketers" aria-label="Go to hire digital marketers developer"
                                            class="">
                                            Digital Marketers </a>
                                        </li>

                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/hire-dedicated-developers" aria-label="Go to page" class="">
                                        <span class="inner-text"> Trending Developers</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                        <li>
                                        <a href="/hire-devops-developers" aria-label="Go to hire hire developer ios" class="">
                                            DevOps
                                            Developers </a>
                                        </li>
                                        <li>
                                        <a href="/hire-aws-developers" aria-label="Go to hire aws developers" class=""> AWS
                                            Developers
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/hire-ai-developers" aria-label="Go to hire ai developers" class=""> AI
                                            Developers
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/hire-ml-developers" aria-label="Go to hire ml developers" class=""> ML
                                            Developers
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/hire-blockchain-developers" aria-label="Go to hire blockchain developers"
                                            class="">
                                            Blockchain Developers </a>
                                        </li>
                                        <li>
                                        <a href="/hire-ar-developers" aria-label="Go to hire ar developers" class=""> AR
                                            Developers
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/hire-vr-developers" aria-label="Go to hire vr developers" class=""> VR
                                            Developers
                                        </a>
                                        </li>
                                        <li>
                                        <a href="/hire-data-analytics" aria-label="Go to hire data analytics" class="">
                                            Data Analytics
                                        </a>
                                        </li>

                                        <li>
                                        <a href="/hire-full-stack-developer" aria-label="Go to hire full stackdeveloper"
                                            class="">
                                            full Stack Developer</a>
                                        </li>
                                        <ul>
                                    </li>
                                </ul>
                                </div>

                            </li>

                            <li class="list-item-1 has-inner-menu">
                                <a href="/about-us" aria-label="Go to Company page" class="link-item-level-1">
                                <span class="inner-text"> Company </span>
                                <span class="submenu-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                    <path fill-rule="evenodd"
                                        d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                    </svg>
                                </span>
                                </a>
                                <div class="second-sub-menu">
                                <ul class="second-sub-menu-ul">
                                    <li class="second-sub-menu-li">
                                    <a href="/about-us" aria-label="Go to about page" class="">About us </a>
                                    </li>
                                    <li class="second-sub-menu-li">
                                    <a href="/life-at-cypherox" aria-label="Go to cypherox page" class="">Life@Cypherox</a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/corporate-social-responsibility" aria-label="Go to CSR Activity page"
                                        class="">CSR Activity</a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/our-work/" aria-label="Go to our work page" class="">our work</a>
                                    </li>



                                    <li class="second-sub-menu-li has-inner-menu">
                                    <a href="/hire-e-commerce-developers" aria-label="Go to Official Partners page" class="">
                                        <span class="inner-text">Official Partners</span>
                                        <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                            <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                        </span>
                                    </a>
                                    <ul class="third-sub-menu">

                                        <li>
                                        <a href="/jibble-partnership-free-time-tracking-software"
                                            aria-label="Go to Jibble page" class="">Jibble</a>
                                        </li>

                                        <li>
                                        <a href="/shopify-partnership" aria-label="Go to shopify page" class="">Shopify</a>
                                        </li>

                                        <li>
                                        <a href="https://codespheretech.in/" aria-label="Go to codespheretech page"
                                            class="">Odoo (codespheretech)</a>
                                        </li>

                                    </ul>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/our-engagement-models" aria-label="Go to  Engagement Model page"
                                        class="">Engagement
                                        Model</a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/blog/" aria-label="Go to blog page" class="">Blogs</a>
                                    </li>
                                    <li class="second-sub-menu-li">
                                    <a href="/news/" aria-label="Go to news page" class="">News</a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                    <a href="/get-in-touch" aria-label="Go to contact us page" class="">Contact us</a>
                                    </li>

                                </ul>
                                </div>

                            </li>

                            <li class="list-item-1">
                                <a href="/career" aria-label="Go to careers page" class="link-item-level-1"> <span
                                    class="inner-text"> Careers </span>
                                </a>
                            </li>
                            </ul>
                        </div>
                        </nav>
                    </div>
                    
                    <div class="contact-btn-sec btn-header-contact">
                        <a href="/get-in-touch" class="primary-btn" aria-label="Go to Contact us">
                            <span class="glow"></span>
                            <span class="stock"></span>
                            <span class="btn-text">Contact Now</span>
                        </a>
                    </div>

                    <div class="contact-btn-sec btn-header-book-call">
                        <a href="/schedule-a-call" target="_blank" class="primary-btn" aria-label="Go to Contact us">
                            <span class="glow"></span>
                            <span class="stock"></span>
                            <span class="btn-text">Book A 15 Min Call</span>
                        </a>
                    </div>

                    <div class="menu-icon">
                    <a href="javascript:void(0)" class="menu-toggle-link">
                        <svg width="26" height="19" viewBox="0 0 26 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="12" width="14" height="3" rx="1.5" fill="#F26E65" />
                        <rect y="8" width="26" height="3" rx="1.5" fill="#F26E65" />
                        <rect y="16" width="14" height="3" rx="1.5" fill="#F26E65" />
                        </svg>
                    </a>
                    </div>

                </div>
                </div>
            </header>
        </div>
    </div>