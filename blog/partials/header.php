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
            <header class="cust-header-v3" style="position:relative">
                <div class="cursor"></div>
                <div class="header-container">
                <div class="header-inner">
                    <div class="brandLogo">
                    <a href="https://www.cypherox.com/" aria-label="Go to home page">
                        <img src="<?php echo ASSETS_URL ?>/img/logo-main.svg" alt="logo-main">
                    </a>
                    </div>
                    <nav class="navbar">
                    <div class="mega-menu menu-v3">
                        <ul>
                        <li class="ai-btn">
                            <a href="https://www.cypherox.com/ai-agent-development-services" class="primary-btn" aria-label="Go to ai agent development page">
                                <span class="glow"></span>
                                <span class="stock"></span>
                                <span class="btn-text">AI Agent</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.cypherox.com/services" aria-label="Go to services page">Services </a>
                            <div class="v3-sub-menu-level-2">
                            <div class="cust-row row">
                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/generative-ai-platforms-solution" aria-label="Go to Generative AI Solutions"
                                    class="v3-level-3-parent-link">Generative AI Solutions</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/ai-chatbot-app-solutions" aria-label="Go to AI Chatbot Development page">AI
                                        Chatbot Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/virtual-assistant-services"
                                        aria-label="Go to Virtual Assistant Services page">Virtual
                                        Assistant Services</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/voice-user-interface-vui-solutions"
                                        aria-label="Go to Voice User Interface (VUI) page">Voice
                                        User Interface (VUI)</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/language-translator-app-solutions"
                                        aria-label="Go to AI Language Translator page">AI
                                        Language Translator</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/ai-content-generator-automated-writing-solutions"
                                        aria-label="Go to AI Content Generator page">AI Content
                                        Generator</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/virtual-research-assistant-solutions"
                                        aria-label="Go to Virtual Research Assistant page">Virtual
                                        Research Assistant</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/cloud-and-devops-services" aria-label="Go to Cloud and DevOps Services page"
                                    class="v3-level-3-parent-link">Cloud and DevOps Services</a>
                                <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/cloud-strategy-consulting-services"
                                        aria-label="Go to Cloud Strategy Consulting page">Cloud
                                        Strategy Consulting</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/cloud-architecture-and-design-services"
                                        aria-label="Go to Cloud Architecture And Design page">Cloud
                                        Architecture And Design</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/cloud-migration-services" aria-label="Go to Cloud Migration page">Cloud
                                        Migration</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/cloud-optimization-services"
                                        aria-label="Go to Cloud Optimization page">Cloud
                                        Optimization</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/cloud-security-services" aria-label="Go to Cloud Security page">Cloud
                                        Security</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/devops-automation-services"
                                        aria-label="Go to DevOps Automation page">DevOps
                                        Automation</a>
                                    </li>

                                </ul>
                                </div>

                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/data-analytics-solutions" aria-label="Go to Data Analytics Solutions page"
                                    class="v3-level-3-parent-link">Data Analytics Solutions</a>
                                <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/data-warehousing-and-business-intelligence-solutions"
                                        aria-label="Go to Data Warehousing and Business Intelligence page">Data
                                        Warehousing and Business Intelligence</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/data-analysis-and-modeling-solutions"
                                        aria-label="Go to Data Analysis and Modeling page">Data
                                        Analysis and Modeling</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/ai-and-machine-learning-solutions"
                                        aria-label="Go to AI and Machine Learning Data page">AI
                                        and Machine Learning Data</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/data-visualization-services"
                                        aria-label="Go to Data Visualization page">Data
                                        Visualization</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/data-security-and-privacy-services"
                                        aria-label="Go to Data Security and Privacy page">Data
                                        Security and Privacy</a>
                                    </li>
                                </ul>
                                </div>
                                

                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/artificial-intelligence-and-machine-learning-services"
                                    aria-label="Go to AI & ML Development Services page" class="v3-level-3-parent-link">AI & ML
                                    Development Services</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/predictive-maintenance-services"
                                        aria-label="Go to Predictive Maintenance Solutions page">Predictive
                                        Maintenance Solutions</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/fraud-detection-solutions"
                                        aria-label="Go to Fraud Detection Solutions page">Fraud
                                        Detection Solutions</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/automated-machine-learning-services"
                                        aria-label="Go to Automated Machine Learning (AutoML) page">Automated
                                        Machine Learning (AutoML)</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/blockchain-development-services" aria-label="Go to blockchain development"
                                    class="v3-level-3-parent-link">Blockchain Development
                                    Services</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/smart-contract-development-services"
                                        aria-label="Go to smart contract development page">
                                        Smart Contract Development</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/blockchain-security-services"
                                        aria-label="Go to Blockchain Security Services page">
                                        Blockchain Security Services</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/blockchain-strategy-consulting-services"
                                        aria-label="Go to blockchain strategy consulting">Blockchain
                                        Strategy Consulting</a>
                                    </li>
                                </ul>
                                </div>

                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/vr-ar-solution-design-and-development-services"
                                    aria-label="Go to UI UX Design Services" class="v3-level-3-parent-link">VR/AR Solutions</a>
                                <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/ar-vr-app-development-services"
                                        aria-label="Go to AR/VR App Development page">AR/VR App
                                        Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/ar-vr-integration-solutions"
                                        aria-label="Go to AR/VR Integration page">AR/VR
                                        Integration</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/consulting-services" aria-label="Go to consulting services"
                                    class="v3-level-3-parent-link">Consulting Services</a>
                                <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/mobile-app-development-consulting-services"
                                        aria-label="Go to Mobile App Consulting">Mobile App
                                        Consulting</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/startup-it-consulting-services"
                                        aria-label="Go to Startup IT Consulting page">Startup IT
                                        Consulting</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/it-consulting-services" aria-label="Go to IT Consulting page">IT
                                        Consulting</a>
                                    </li>
                                </ul>
                                </div>

                                <div class="col-md-4">
                                <a href="https://www.cypherox.com/ui-ux-design-services" aria-label="Go to UI UX Design Services page"
                                    class="v3-level-3-parent-link">UI UX Design Services</a>
                                <ul class="v3-inner-menu ">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/responsive-web-design-development-services"
                                        aria-label="Go to Responsive Web Design Development page">Responsive
                                        Web Design Development</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/mobile-app-design-development-services"
                                        aria-label="Go to Mobile App Design Development page">Mobile
                                        App Design Development</a>
                                    </li>
                                </ul>
                                </div>
                            </div>

                            </div>
                        </li>

                        <li>
                            <a href="https://www.cypherox.com/technology" aria-label="Go to Technology page"> Technologies </a>
                            <div class="v3-sub-menu-level-2">
                            <div class="cust-row row">
                                <div class="col-md-6">
                                <a href="https://www.cypherox.com/application-development" aria-label="Go to Application Development page"
                                    class="v3-level-3-parent-link"> Application Development </a>
                                <ul class="v3-inner-menu three-column-list no-left-line">
                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/ios-app-development-services"
                                        aria-label="Go to iOS app development services"> iOS
                                    </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/swift-app-development-services" aria-label="Go to swift">Swift</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/objective-c-app-development-services" aria-label="Go to objective-c">
                                            Objective-C</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/android-app-development-services" aria-label="Go to android "> Android </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/kotlin-app-development-services" aria-label="Go to kotlin "> Kotlin
                                        </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/java-app-development-services" aria-label="Go to java "> Java </a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/cross-platform-app-development-services"
                                        aria-label="Go to cross-platform "> Cross-Platform </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/dart-app-development-services" aria-label="Go to dart "> Dart </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/flutter-app-development-services" aria-label="Go to flutter "> Flutter
                                        </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/react-native-app-development-services"
                                            aria-label="Go to react native "> React Native
                                        </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/xamarin-app-development-services" aria-label="Go to xamarin "> Xamarin
                                        </a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/framework-app-development-services" aria-label="Go to framework ">
                                        Framework </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/golang-app-development-services" aria-label="Go to golang "> Golang
                                        </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/ruby-on-rails-app-development-services"
                                            aria-label="Go to ruby on rails s"> Ruby on
                                            Rails </a>
                                        </li>
                                    </ul>
                                    </li>
                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/application-development" aria-label="Go to Trending App Solutions">Trending
                                        App
                                        Solutions</a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/ai-app-development-services"
                                            aria-label="Go to AI App Development page">AI
                                            App</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/b2b-mobile-app-development-services"
                                            aria-label="Go to B2B Mobile App Development page">B2B
                                            Mobile App</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/b2c-mobile-app-development-services"
                                            aria-label="Go to B2C Mobile App Development page">B2C
                                            Mobile App</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/saas-app-development-services"
                                            aria-label="Go to SAAS App Development  page">SAAS
                                            App </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/mobile-application-solutions"
                                            aria-label="Go to Mobile Application Solutions page">Mobile
                                            Solutions</a>
                                        </li>
                                    </ul>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-6">
                                <a href="https://www.cypherox.com/web-development-solutions" aria-label="Go to Web Development page"
                                    class="v3-level-3-parent-link"> Web Development </a>
                                <ul class="v3-inner-menu three-column-list no-left-line">

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/front-end-development-services"
                                        aria-label="Go to Front End Web Development"> Front End
                                    </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/angularjs-development-services" aria-label="Go to angularjs">
                                            AngularJS</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/reactjs-development-services" aria-label="Go to reactjs"> ReactJS </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/vuejs-development-services" aria-label="Go to vuejs"> VueJS </a>
                                        </li>
                                    </ul>
                                    </li>
                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/back-end-development-services" aria-label="Go to back end"> Back End </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/nodejs-development-services" aria-label="Go to nodejs"> NodeJS</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/express-js-development-services" aria-label="Go to expressjs">
                                            ExpressJS </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/php-development-services" aria-label="Go to php"> PHP </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/python-web-development-services" aria-label="Go to python"> Python </a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/full-stack-development-services" aria-label="Go to full stack"> Full
                                        Stack</a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/mean-stack-development-services" aria-label="Go to MEAN">MEAN</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/mern-stack-development-services" aria-label="Go to MERN">MERN</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/e-commerce-development-services" aria-label="Go to e-commerce">
                                        E-Commerce</a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/shopify-development-services"
                                            aria-label="Go to shopify web development">
                                            Shopify</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/magento-development-services"
                                            aria-label="Go to magento web development">
                                            Magento</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/woocommerce-development-services"
                                            aria-label="Go to woo commerce web development">
                                            WooCommerce</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/bigcommerce-development-services"
                                            aria-label="Go to big commerce web development">
                                            BigCommerce </a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/cms-development-company"
                                        aria-label="Go to content management system web development services">
                                        CMS</a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/wordpress-development-services"
                                            aria-label="Go to wordpress web development">
                                            WordPress </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/joomla-development-services" aria-label="Go to joomla web development">
                                            Joomla </a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/drupal-development-services" aria-label="Go to drupal web development">
                                            Drupal </a>
                                        </li>

                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/modern-web-development-services"
                                        aria-label="Go to Go to modern web-development approaches">
                                        Modern </a>
                                    <ul class="tech-inner-menu">
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/headless-development-services"
                                            aria-label="Go to headless web development">
                                            Headless</a>
                                        </li>
                                        <li class="tech-inner-menu-item">
                                        <a href="https://www.cypherox.com/webflow-development-services"
                                            aria-label="Go to webflow web development">
                                            Webflow</a>
                                        </li>
                                    </ul>
                                    </li>

                                    <li class="v3-inner-menu-item tech-menu-wrapper">
                                    <a href="https://www.cypherox.com/erp-and-crm-development-services"
                                        aria-label="Go to crm and erp development" class="">ERP
                                        and CRM </a>
                                    </li>
                                </ul>
                                </div>
                            </div>
                            </div>
                        </li>

                        <li class="clone-solutions">
                            <a href="https://www.cypherox.com/clone-solutions" aria-label="Go to clone solutions page"> Clone Solutions </a>
                            <div class="v3-sub-menu-level-2">
                                <div class="cust-row row">
                                    <div class="col-md-12">
                                        <ul class="v3-inner-menu five-column-list">
                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/ada-health-clone" aria-label="Go to Ada Health Clone page"> Ada Health Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/ai-influencer-generator-clone" aria-label="Go to influencer generator clone page"> AI Influencer Generator Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/candy-ai-clone" aria-label="Go to candy ai clone page"> Candy AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/character-ai-clone" aria-label="Go to character ai clone page"> Character AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/glean-ai-clone" aria-label="Go to glean ai clone page"> Glean AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/multipass-ai-clone" aria-label="Go to multipass ai clone page"> Multipass AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/opus-clip-clone" aria-label="Go to opus clip clone page"> Opus Clip Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/polybuzz-ai-clone" aria-label="Go to polybuzz ai clone page"> Polybuzz AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/replika-ai-clone" aria-label="Go to replika ai clone page"> Replika AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/rizz-ai-clone" aria-label="Go to rizz ai clone page"> Rizz AI Clone </a>
                                            </li>

                                            <li class="v3-inner-menu-item">
                                                <a href="https://www.cypherox.com/talkie-ai-clone" aria-label="Go to talkie ai clone page"> Talkie AI Clone </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li>
                            <a href="https://www.cypherox.com/service-industry" aria-label="Go to Industry page"> Industries </a>
                            <div class="v3-sub-menu-level-2">
                            <div class="cust-row row">
                                <div class="col-md-12">
                                <a href="https://www.cypherox.com/service-industry" class="v3-level-3-parent-link">
                                    Industries we served
                                </a>
                                <ul class="v3-inner-menu five-column-list">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/healthcare-industry-services" aria-label="Go to healthcare page">
                                        Healthcare </a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/finance-industry-services" aria-label="Go to finance page"> Finance </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/retail-industry-services" aria-label="Go to retail page"> Retail </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/manufacturing-industry-services" aria-label="Go to manufacturing page">
                                        Manufacturing
                                    </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/transportation-industry-services" aria-label="Go to transportation page">
                                        Transportation
                                    </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/education-industry-services" aria-label="Go to education pages"> Education
                                    </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/energy-industry-services" aria-label=" Go to energy page"> Energy </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/agriculture-industry-services" aria-label="Go to agriculture page">
                                        Agriculture </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/media-and-entertainment-industry-services"
                                        aria-label="Go to media-and-entertainment"> Media and
                                        entertainment </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/social-networking-industry-services" aria-label="Go to social networking">
                                        Social Networking
                                    </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/on-demand-delivery-industry-services"
                                        aria-label=" Go to on demand delivery"> On-Demand
                                        Delivery </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/entertainment-industry-services" aria-label="Go to entertainment">
                                        Entertainment </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/health-and-fitness-industry-services"
                                        aria-label="Go to health and fitness"> Health &amp;
                                        Fitness </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/food-and-drink-industry-services" aria-label="Go to food and drink"> Food
                                        &amp; Drink </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/logistics-industry-services"
                                        aria-label="Go to transportation and logistics">
                                        Transportation &amp; Logistics
                                    </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/e-learning-industry-services" aria-label="Go to education and e-learning">
                                        Education
                                        &amp; E-learning </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/ecommerce-industry-services" aria-label="Go to ecommerce and shopping">
                                        Ecommerce
                                        &amp; Shopping </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/banking-industry-services" aria-label="Go to banking and finance"> Banking
                                        &amp;
                                        Finance </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/travel-and-tourism-industry-services"
                                        aria-label="Go to travel and tourism"> Travel &amp;
                                        Tourism </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/real-estate-industry-services" aria-label="Go to real estate"> Real-estate
                                    </a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/photo-and-video-industry-services" aria-label="Go to photo and video">
                                        Photo &amp; Video
                                    </a>
                                    </li>
                                </ul>
                                </div>
                            </div>
                            </div>
                        </li>

                        <li>
                            <a href="https://www.cypherox.com/hire-dedicated-developers" aria-label="Go to hire developer">Hire Developers </a>
                            <div class="v3-sub-menu-level-2 cust-hire-col">
                            <div class="cust-row row justify-content-between">
                                <div class="col-md-3 w-auto">
                                <a href="https://www.cypherox.com/hire-mobile-app-developers" aria-label="Go to hire mobile app developer"
                                    class="v3-level-3-parent-link">Mobile App Developers</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-ios-developer" aria-label="Go to hire developer ios" class="">iOS
                                        Developer</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-android-developer" aria-label="Go to hire developer android"
                                        class="">Android Developer</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-swift-developers" aria-label="Go to hire swift developer"
                                        class="">Swift
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-kotlin-developers" aria-label="Go to hire kotlin developers"
                                        class="">Kotlin Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-flutter-developers" aria-label="Go to hire-flutter-developers"
                                        class="">Flutter Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-react-native-developers"
                                        aria-label="Go to hire react native developers" class="">React Native Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-golang-developers" aria-label="Go to hire golang developers"
                                        class="">Golang Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-java-developers" aria-label="Go to hire java developers" class="">Java
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-dart-developers" aria-label="Go to hire dart developers" class="">Dart
                                        Developers</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-3 w-auto">
                                <a href="https://www.cypherox.com/hire-a-front-end-web-developer"
                                    aria-label="Go to hire a front end web developer" class="v3-level-3-parent-link">Front-End
                                    Web Developer</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-angularjs-developers" aria-label="Goangularjs developers"
                                        class="">AngularJS
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-reactjs-developers" aria-label="Go to hire reactjs developers"
                                        class="">ReactJS Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-vuejs-developers" aria-label="Go to hire vuejs developers"
                                        class="">VueJS
                                        Developers</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-3 w-auto">
                                <a href="https://www.cypherox.com/hire-a-back-end-web-developer" aria-label="Go to hire a back end web developer"
                                    class="v3-level-3-parent-link">Back-End Web Developer</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-nodejs-developers" aria-label="Go to hire NodeJS developers"
                                        class="">NodeJS Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-laravel-developers" aria-label="Go to hire laravel developer"
                                        class="">Laravel Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-python-developers" aria-label="Go to hire python developers"
                                        class="">Python Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-php-developers" aria-label="Go to hire php developer" class="">PHP
                                        Developers</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-2 w-auto">
                                <a href="https://www.cypherox.com/hire-e-commerce-developers" aria-label="Go to hire e-commerce developers"
                                    class="v3-level-3-parent-link">E-Commerce Developers</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-wordpress-developers" aria-label="Go to hire wordpress developer"
                                        class="">WordPress Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-shopify-developers" aria-label="Go to hire shopify developer"
                                        class="">Shopify Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-magento-developers" aria-label="Go to hire magento developers"
                                        class="">Magento Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-bigcommerce-developers" aria-label="Go to hire bigcommerce developers"
                                        class="">BigCommerce Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-woocommerce-developers" aria-label="Go to hire woocommerce developers"
                                        class="">WooCommerce Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-chatbot-developers" aria-label="Go to hire chatbot developer"
                                        class="">Chatbot Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-graphic-designers" aria-label="Go to hire graphic designer"
                                        class="">Graphic Designers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-ui-ux-designers" aria-label="Go to hire ui ux designers"
                                        class="">UI/UX
                                        Designers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-digital-marketers" aria-label="Go to hire digital marketers developer"
                                        class="">Digital Marketers</a>
                                    </li>
                                </ul>
                                </div>
                                <div class="col-md-2 w-auto">
                                <a href="https://www.cypherox.com/hire-dedicated-developers" aria-label="Go to hire e-commerce developers"
                                    class="v3-level-3-parent-link">Trending Developer</a>
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-devops-developers" aria-label="Go to hire developer ios"
                                        class="">DevOps
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-aws-developers" aria-label="Go to hire aws developers" class="">AWS
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-ai-developers" aria-label="Go to hire ai developers" class="">AI
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-ml-developers" aria-label="Go to hire ml developers" class="">ML
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-blockchain-developers" aria-label="Go to hire blockchain developers"
                                        class="">Blockchain Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-ar-developers" aria-label="Go to hire ar developers" class="">AR
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-vr-developers" aria-label="Go to hire vr developers" class="">VR
                                        Developers</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-data-analytics" aria-label="Go to hire data analytics" class="">Data
                                        Analytics</a>
                                    </li>
                                    
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/hire-full-stack-developer" aria-label="Go to hire full stackdeveloper"
                                        class="">Full Stack Developer</a>
                                    </li>
                                </ul>
                                </div>
                            </div>
                            </div>
                        </li>
                        <li class="position-relative  company-multi-dropdown">
                            <a href="https://www.cypherox.com/about-us" aria-label="Go to Company pages"> Company </a>
                            <div class="v3-sub-menu-level-2 sub-menu-small">
                            <div class="cust-row row no-border" data-lenis-prevent="true">
                                <div class="col-md-12">
                                <ul class="v3-inner-menu">
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/about-us" aria-label="Go to about page" class="v3-inner-item-link">About us
                                    </a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/life-at-cypherox" aria-label="Go to cypherox page"
                                        class="v3-inner-item-link">Life@Cypherox</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/our-work/" aria-label="Go to  our work page" class="v3-inner-item-link">our
                                        work</a>
                                    </li>

                                    <li class="v3-inner-menu-item official-partners">
                                        <a href="javascript:void(0)" aria-label="Go to Company pages"> Official Partners
                                            <span class="submenu-toggle">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
                                                </svg>
                                            </span>
                                        </a>
                                        <div class="company-multi-dropdown-main">
                                            <div class="company-multi-dropdown-main-inner">
                                                <div class="col-md-12">
                                                <ul class="company-multi-menu">
                                                    <li class="company-multi-menu-item">
                                                    <a href="https://www.cypherox.com/jibble-partnership-free-time-tracking-software"
                                                        aria-label="Go to about page" class="company-multi-item-link"> Jibble </a>
                                                    </li>
                                                    <li class="company-multi-menu-item">
                                                    <a href="https://www.cypherox.com/shopify-partnership" aria-label="Go to shopify page"
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
                                    <a href="https://www.cypherox.com/our-engagement-models" aria-label="Go to  Engagement Model page"
                                        class="v3-inner-item-link">Engagement Model</a>
                                    </li>
                                    
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/blog/" aria-label="Go to blog page" class="v3-inner-item-link">Blogs</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/news/" aria-label="Go to news page" class="v3-inner-item-link">News</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                    <a href="https://www.cypherox.com/get-in-touch" aria-label="Go to contact page"
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
                            <a href="https://www.cypherox.com/career" aria-label="Go to contact page"> Careers </a>
                        </li>
                        </ul>
                    </div>
                    </nav>
                    <div class="mobile-menu" data-lenis-prevent="true">
                    <nav class="mobile-navbar">
                        <div class="mega-menu" data-lenis-prevent="true">
                        <ul class="menu-level-1">
                            <li class="ai-agent">
                                <a href="https://www.cypherox.com/ai-agent-development-services" class="primary-btn" aria-label="Go to ai agent development page">
                                    <span class="glow"></span>
                                    <span class="stock"></span>
                                    <span class="btn-text">AI Agent</span>
                                </a>
                            </li>
                            
                            <li class="list-item-1 has-inner-menu">
                            <a href="https://www.cypherox.com/services" aria-label="Go to services page" class="link-item-level-1"> <span
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
                                    <a href="https://www.cypherox.com/generative-ai-platforms-solution" aria-label="Go to Generative AI Solutions"
                                    class="">
                                    <span class="inner-text"> Generative AI Solutions</span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/ai-chatbot-app-solutions"
                                        aria-label="Go to AI Chatbot Development page">AI
                                        Chatbot Development</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/virtual-assistant-services"
                                        aria-label="Go to Virtual Assistant Services page">Virtual
                                        Assistant
                                        Services</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/voice-user-interface-vui-solutions"
                                        aria-label="Go to Voice User Interface (VUI) page">Voice
                                        User Interface
                                        (VUI)</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/language-translator-app-solutions"
                                        aria-label="Go to AI Language Translator page">AI
                                        Language Translator</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/ai-content-generator-automated-writing-solutions"
                                        aria-label="Go to AI Content Generator page">AI
                                        Content Generator</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/virtual-research-assistant-solutions"
                                        aria-label="Go to Virtual Research Assistant page">Virtual
                                        Research
                                        Assistant</a>
                                    </li>
                                    </ul>
                                </li>
                                

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/vr-ar-solution-design-and-development-services"
                                    aria-label="Go to VR/AR Solutions">
                                    <span class="inner-text">VR/AR Solutions</span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/ar-vr-app-development-services"
                                        aria-label="Go to AR/VR App Development page">AR/VR
                                        App Development</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/ar-vr-integration-solutions"
                                        aria-label="Go to AR/VR Integration page">AR/VR
                                        Integration</a>
                                    </li>

                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/cloud-and-devops-services" aria-label="Go to Cloud and DevOps Services">
                                    <span class="inner-text"> Cloud and DevOps Services</span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/cloud-strategy-consulting-services"
                                        aria-label="Go to Cloud Strategy Consulting page">Cloud
                                        Strategy
                                        Consulting</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/cloud-architecture-and-design-services"
                                        aria-label="Go to Cloud Architecture And Design page">Cloud
                                        Architecture And
                                        Design</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/cloud-migration-services" aria-label="Go to Cloud Migration page">Cloud
                                        Migration</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/cloud-optimization-services"
                                        aria-label="Go to Cloud Optimization page">Cloud
                                        Optimization</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/cloud-security-services" aria-label="Go to Cloud Security page">Cloud
                                        Security</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/devops-automation-services"
                                        aria-label="Go to DevOps Automation page">DevOps
                                        Automation</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/artificial-intelligence-and-machine-learning-services"
                                    aria-label="Go to AI & ML Development Services">
                                    <span class="inner-text"> AI & ML Development Services
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
                                        <a href="https://www.cypherox.com/predictive-maintenance-services"
                                        aria-label="Go to Predictive Maintenance Solutions page">Predictive
                                        Maintenance
                                        Solutions</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/fraud-detection-solutions"
                                        aria-label="Go to Fraud Detection Solutions page">Fraud
                                        Detection
                                        Solutions</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/automated-machine-learning-services"
                                        aria-label="Go to Automated Machine Learning (AutoML) page">Automated
                                        Machine Learning
                                        (AutoML)</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/consulting-services" aria-label="Go to consulting services">
                                    <span class="inner-text">
                                        Consulting Services
                                    </span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="v3-inner-menu-item">
                                        <a href="https://www.cypherox.com/mobile-app-development-consulting-services"
                                        aria-label="Go to Mobile App Consulting">Mobile App
                                        Consulting</a>
                                    </li>

                                    <li class="v3-inner-menu-item">
                                        <a href="https://www.cypherox.com/startup-it-consulting-services"
                                        aria-label="Go to Startup IT Consulting page">Startup
                                        IT Consulting</a>
                                    </li>
                                    <li class="v3-inner-menu-item">
                                        <a href="https://www.cypherox.com/it-consulting-services" aria-label="Go to IT Consulting page">IT
                                        Consulting</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/data-analytics-solutions" aria-label="Go to Data Analytics Solutions">
                                    <span class="inner-text"> Data Analytics Solutions</span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/data-warehousing-and-business-intelligence-solutions"
                                        aria-label="Go to Data Warehousing and Business Intelligence page">Data
                                        Warehousing and
                                        Business Intelligence</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/data-analysis-and-modeling-solutions"
                                        aria-label="Go to Data Analysis and Modeling page">Data
                                        Analysis and
                                        Modeling</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/ai-and-machine-learning-solutions"
                                        aria-label="Go to AI and Machine Learning Data page">AI
                                        and Machine Learning
                                        Data</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/data-visualization-services"
                                        aria-label="Go to Data Visualization page">Data
                                        Visualization</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/data-security-and-privacy-services"
                                        aria-label="Go to Data Security and Privacy page">Data
                                        Security and
                                        Privacy</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">

                                    <a href="https://www.cypherox.com/blockchain-development-services" aria-label="Go to blockchain development">
                                    <span class="inner-text">
                                        Blockchain Development Services
                                    </span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/smart-contract-development-services"
                                        aria-label="Go to smart contract development page">
                                        Smart Contract
                                        Development</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/blockchain-security-services"
                                        aria-label="Go to Blockchain Security Services page">
                                        Blockchain Security
                                        Services</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/blockchain-strategy-consulting-services"
                                        aria-label="Go to blockchain strategy consulting">Blockchain
                                        Strategy
                                        Consulting</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/ui-ux-design-services" aria-label="Go to UI UX Design Services">
                                    <span class="inner-text"> UI UX Design Services</span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/responsive-web-design-development-services"
                                        aria-label="Go to Responsive Web Design Development page">Responsive
                                        Web
                                        Design
                                        Development</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/mobile-app-design-development-services"
                                        aria-label="Go to Mobile App Design Development page">Mobile
                                        App Design
                                        Development</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/clone-solutions" aria-label="Go to Clone Solutions">
                                    <span class="inner-text">Clone Solutions</span>
                                    <span class="submenu-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#f26e65">
                                        <path fill-rule="evenodd"
                                            d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                    </a>
                                    <ul class="third-sub-menu">
                                    <li class="">
                                        <a href="https://www.cypherox.com/ada-health-clone"
                                        aria-label="Go to Ada Health Clone page">Ada Health Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/ai-influencer-generator-clone"
                                        aria-label="Go to AI Influencer Generator Clone page">AI Influencer Generator Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/candy-ai-clone"
                                        aria-label="Go to Candy AI Clone page">Candy AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/character-ai-clone"
                                        aria-label="Go to Character AI Clone page">Character AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/glean-ai-clone"
                                        aria-label="Go to Glean AI Clone page">Glean AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/multipass-ai-clone"
                                        aria-label="Go to Multipass AI Clone page">Multipass AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/opus-clip-clone"
                                        aria-label="Go to Opus Clip Clone page">Opus Clip Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/polybuzz-ai-clone"
                                        aria-label="Go to Polybuzz AI Clone page">Polybuzz AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/replika-ai-clone"
                                        aria-label="Go to Replika AI Clone page">Replika AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/rizz-ai-clone"
                                        aria-label="Go to Rizz AI Clone page">Rizz AI Clone</a>
                                    </li>

                                    <li class="">
                                        <a href="https://www.cypherox.com/talkie-ai-clone"
                                        aria-label="Go to Talkie AI Clone page">Talkie AI Clone</a>
                                    </li>

                                    </ul>
                                </li>

                                </ul>
                            </div>

                            </li>
                            
                            <li class="list-item-1 has-inner-menu">
                            <a href="https://www.cypherox.com/technology" aria-label="Go to Technology page" class="link-item-level-1">
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
                                    <a href="https://www.cypherox.com/application-development" aria-label="Go to Application Development page"
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
                                        <a href="https://www.cypherox.com/ios-app-development-services"
                                        aria-label="Go to iOS app development services">
                                        iOS</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/swift-app-development-services" aria-label="Go to swift">Swift</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/objective-c-app-development-services"
                                        aria-label="Go to objective-c">Objective-C</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/android-app-development-services" aria-label="Go to android ">Android</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/kotlin-app-development-services" aria-label="Go to kotlin ">Kotlin </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/java-app-development-services" aria-label="Go to java ">Java</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/cross-platform-app-development-services"
                                        aria-label="Go to cross-platform ">
                                        Cross-Platform</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/dart-app-development-services" aria-label="Go to dart "> Dart </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/flutter-app-development-services" aria-label="Go to flutter "> Flutter
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/react-native-app-development-services" aria-label="Go to react native ">
                                        React Native </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/xamarin-app-development-services" aria-label="Go to xamarin "> Xamarin
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/framework-app-development-services" aria-label="Go to framework ">
                                        Framework</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/golang-app-development-services" aria-label="Go to golang "> Golang </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/ruby-on-rails-app-development-services"
                                        aria-label="Go to ruby on rails s"> Ruby on Rails
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/application-development" aria-label="Go to framework ">Trending App
                                        Solutions</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/ai-app-development-services"
                                        aria-label="Go to AI App Development page">AI App
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/b2b-mobile-app-development-services"
                                        aria-label="Go to B2B Mobile App Development page">B2B
                                        Mobile
                                        App </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/b2c-mobile-app-development-services"
                                        aria-label="Go to B2C Mobile App Development page">B2C
                                        Mobile
                                        App </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/saas-app-development-services"
                                        aria-label="Go to SAAS App Development  page">SAAS
                                        App
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/mobile-application-solutions"
                                        aria-label="Go to Mobile Application Solutions page">Mobile
                                        Solutions</a>
                                    </li>
                                    </ul>
                                </li>
                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/web-development-solutions" aria-label="Go to Web Development page"
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
                                        <a href="https://www.cypherox.com/front-end-development-services"
                                        aria-label="Go to Front End Web Development"> Front
                                        End</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/angularjs-development-services" aria-label="Go to angularjs">
                                        AngularJS</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/reactjs-development-services" aria-label="Go to reactjs"> ReactJS </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/vuejs-development-services" aria-label="Go to vuejs"> VueJS </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/back-end-development-services" aria-label="Go to back end"> Back End</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/nodejs-development-services" aria-label="Go to nodejs"> NodeJS</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/express-js-development-services" aria-label="Go to expressjs"> ExpressJS
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/php-development-services" aria-label="Go to php"> PHP </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/python-web-development-services" aria-label="Go to python"> Python </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/full-stack-development-services" aria-label="Go to full stack"> Full
                                        Stack</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/mean-stack-development-services" aria-label="Go to MEAN">MEAN</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/mern-stack-development-services" aria-label="Go to MERN">MERN</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/e-commerce-development-services" aria-label="Go to e-commerce">
                                        E-Commerce </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/shopify-development-services" aria-label="Go to shopify web development">
                                        Shopify</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/magento-development-services" aria-label="Go to magento web development">
                                        Magento</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/woocommerce-development-services"
                                        aria-label="Go to woo commerce web development">
                                        WooCommerce</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/bigcommerce-development-services"
                                        aria-label="Go to big commerce web development">
                                        BigCommerce </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/cms-development-company"
                                        aria-label="Go to content management system web development services">
                                        CMS</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/wordpress-development-services"
                                        aria-label="Go to wordpress web development">
                                        WordPress </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/joomla-development-services" aria-label="Go to joomla web development">
                                        Joomla
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/drupal-development-services" aria-label="Go to drupal web development">
                                        Drupal
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/modern-web-development-services"
                                        aria-label="Go to Go to modern web-development approaches">
                                        Modern</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/headless-development-services"
                                        aria-label="Go to headless web development">
                                        Headless</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/webflow-development-services" aria-label="Go to webflow web development">
                                        Webflow</a>
                                    </li>
                                    <li class="">
                                        <a href="https://www.cypherox.com/erp-and-crm-development-services"
                                        aria-label="Go to crm and erp development" class="">ERP and CRM </a>
                                    </li>
                                    </ul>
                                </li>
                                </ul>
                            </div>
                            </li>

                            <li class="list-item-1 has-inner-menu">
                            <a href="https://www.cypherox.com/service-industry" class="link-item-level-1">
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
                                    <a href="https://www.cypherox.com/healthcare-industry-services" aria-label="Go to healthcare page"> Healthcare
                                    </a>
                                </li>
                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/finance-industry-services" aria-label="Go to finance page"> Finance </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/retail-industry-services" aria-label="Go to retail page"> Retail </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/manufacturing-industry-services" aria-label="Go to manufacturing page">
                                    Manufacturing </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/transportation-industry-services" aria-label="Go to transportation page">
                                    Transportation </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/education-industry-services" aria-label="Go to education pages"> Education
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/energy-industry-services" aria-label=" Go to energy page"> Energy </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/agriculture-industry-services" aria-label="Go to agriculture page">
                                    Agriculture </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/media-and-entertainment-industry-services"
                                    aria-label="Go to media-and-entertainment"> Media and
                                    entertainment </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/social-networking-industry-services" aria-label="Go to social networking">
                                    Social Networking </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/on-demand-delivery-industry-services" aria-label=" Go to on demand delivery">
                                    On-Demand Delivery
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/entertainment-industry-services" aria-label="Go to entertainment">
                                    Entertainment </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/health-and-fitness-industry-services" aria-label="Go to health and fitness">
                                    Health &amp; Fitness
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/food-and-drink-industry-services" aria-label="Go to food and drink"> Food
                                    &amp; Drink </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/logistics-industry-services" aria-label="Go to transportation and logistics">
                                    Transportation &amp;
                                    Logistics
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/e-learning-industry-services" aria-label="Go to education and e-learning">
                                    Education &amp;
                                    E-learning
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/ecommerce-industry-services" aria-label="Go to ecommerce and shopping">
                                    Ecommerce &amp;
                                    Shopping </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/banking-industry-services" aria-label="Go to banking and finance"> Banking
                                    &amp;
                                    Finance </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/travel-and-tourism-industry-services" aria-label="Go to travel and tourism">
                                    Travel &amp; Tourism
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/real-estate-industry-services" aria-label="Go to real estate"> Real-estate
                                    </a>
                                </li>

                                <li class="second-sub-menu-li">
                                    <a href="https://www.cypherox.com/photo-and-video-industry-services" aria-label="Go to photo and video"> Photo
                                    &amp; Video </a>
                                </li>
                                </ul>
                            </div>
                            </li>

                            <li class="list-item-1 has-inner-menu clone-solutions">
                                <a href="https://www.cypherox.com/clone-solutions" class="link-item-level-1">
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
                                                <a href="https://www.cypherox.com/ada-health-clone" aria-label="Go to clone solutions page"> Ada Health Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/ai-influencer-generator-clone" aria-label="Go to influencer generator clone page"> AI Influencer Generator Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/candy-ai-clone" aria-label="Go to candy ai clone page"> Candy AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/character-ai-clone" aria-label="Go to character ai clone page"> Character AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/glean-ai-clone" aria-label="Go to glean ai clone page"> Glean AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/multipass-ai-clone" aria-label="Go to multipass ai clone page"> Multipass AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/opus-clip-clone" aria-label="Go to opus clip clone page"> Opus Clip Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/polybuzz-ai-clone" aria-label="Go to polybuzz ai clone page"> Polybuzz AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/replika-ai-clone" aria-label="Go to replika ai clone page"> Replika AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/rizz-ai-clone" aria-label="Go to rizz ai clone page"> Rizz AI Clone </a>
                                            </li>

                                            <li class="second-sub-menu-li">
                                                <a href="https://www.cypherox.com/talkie-ai-clone" aria-label="Go to talkie ai clone page"> Talkie AI Clone </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="list-item-1 has-inner-menu">
                            <a href="https://www.cypherox.com/hire-dedicated-developers" aria-label="Go to hire developer"
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

                                    <a href="https://www.cypherox.com/hire-mobile-app-developers" aria-label="Go to hire mobile app developer"
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
                                        <a href="https://www.cypherox.com/hire-ios-developer" aria-label="Go to hire developer ios" class=""> IOS
                                        Developer</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-android-developer" aria-label="Go to hire developer android"
                                        class="">
                                        Android Developer</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-swift-developers" aria-label="Go to hire swift developer" class="">
                                        Swift
                                        Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-kotlin-developers" aria-label="Go to hire kotlin developers"
                                        class="">
                                        Kotlin Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-flutter-developers" aria-label="Go to hire-flutter-developers"
                                        class="">

                                        Flutter Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-react-native-developers"
                                        aria-label="Go to hire react native developers" class=""> React
                                        Native Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-golang-developers" aria-label="Go to hire golang developers"
                                        class="">
                                        Golang Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-java-developers" aria-label="Go to hire java developers" class="">
                                        Java
                                        Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-dart-developers" aria-label="Go to hire dart developers" class="">
                                        Dart
                                        Developers</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/hire-a-front-end-web-developer"
                                    aria-label="Go to hire front end web developer" class="">
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
                                        <a href="https://www.cypherox.com/hire-angularjs-developers" aria-label="Go to hire angularjs developers"
                                        class="">
                                        AngularJS Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-reactjs-developers" aria-label="Go to hire reactjs developers"
                                        class="">
                                        ReactJS Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-vuejs-developers" aria-label="Go to hire vuejs developers" class="">
                                        VueJS
                                        Developers</a>
                                    </li>
                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/hire-a-back-end-web-developer"
                                    aria-label="Go to hire front end web developer" class="">
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
                                        <a href="https://www.cypherox.com/hire-nodejs-developers" aria-label="Go to hire nodejs developers"
                                        class="">
                                        NodeJS Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-laravel-developers" aria-label="Go to hire laravel developer"
                                        class="">
                                        Laravel Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-python-developers" aria-label="Go to hire python developers"
                                        class="">
                                        Python Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-php-developers" aria-label="Go to hire php developer" class=""> PHP
                                        Developers</a>
                                    </li>
                                    </ul>
                                </li>



                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/hire-e-commerce-developers" aria-label="Go to hire e-commerce developers"
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
                                        <a href="https://www.cypherox.com/hire-wordpress-developers" aria-label="Go to hire wordpress developer"
                                        class="">
                                        WordPress
                                        Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-shopify-developers" aria-label="Go to hire shopify developer"
                                        class="">
                                        Shopify
                                        Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-magento-developers" aria-label="Go to hire magento developers"
                                        class="">
                                        Magento
                                        Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-bigcommerce-developers"
                                        aria-label="Go to hire bigcommerce developers" class="">
                                        BigCommerce Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-woocommerce-developers"
                                        aria-label="Go to hire woocommerce developers" class="">
                                        WooCommerce Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-chatbot-developers" aria-label="Go to hire chatbot developer"
                                        class="">
                                        Chatbot
                                        Developers</a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-graphic-designers" aria-label="Go to hire graphic designer"
                                        class="">
                                        Graphic
                                        Designers </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-ui-ux-designers" aria-label="Go to hire ui ux designers" class="">
                                        UI/UX
                                        Designers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-digital-marketers"
                                        aria-label="Go to hire digital marketers developer" class="">
                                        Digital Marketers </a>
                                    </li>

                                    </ul>
                                </li>

                                <li class="second-sub-menu-li has-inner-menu">
                                    <a href="https://www.cypherox.com/hire-dedicated-developers" aria-label="Go to page" class="">
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
                                        <a href="https://www.cypherox.com/hire-devops-developers" aria-label="Go to hire hire developer ios"
                                        class="">
                                        DevOps
                                        Developers </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-aws-developers" aria-label="Go to hire aws developers" class=""> AWS
                                        Developers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-ai-developers" aria-label="Go to hire ai developers" class=""> AI
                                        Developers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-ml-developers" aria-label="Go to hire ml developers" class=""> ML
                                        Developers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-blockchain-developers" aria-label="Go to hire blockchain developers"
                                        class="">
                                        Blockchain Developers </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-ar-developers" aria-label="Go to hire ar developers" class=""> AR
                                        Developers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-vr-developers" aria-label="Go to hire vr developers" class=""> VR
                                        Developers
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.cypherox.com/hire-data-analytics" aria-label="Go to hire data analytics" class="">
                                        Data Analytics
                                        </a>
                                    </li>
                                    
                                    <li>
                                        <a href="https://www.cypherox.com/hire-full-stack-developer" aria-label="Go to hire full stackdeveloper"
                                        class="">
                                        full Stack Developer</a>
                                    </li>
                                    <ul>
                                </li>
                                </ul>
                            </div>

                            </li>

                            <li class="list-item-1 has-inner-menu">
                                <a href="https://www.cypherox.com/about-us" aria-label="Go to Company page"
                                    class="link-item-level-1">
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
                                        <a href="https://www.cypherox.com/about-us" aria-label="Go to about page" class="">About us </a>
                                    </li>
                                    <li class="second-sub-menu-li">
                                        <a href="https://www.cypherox.com/life-at-cypherox" aria-label="Go to cypherox page" class="">Life@Cypherox</a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="https://www.cypherox.com/our-work/" aria-label="Go to  our work page" class="">our work</a>
                                    </li>



                                    <li class="second-sub-menu-li has-inner-menu">
                                        <a href="https://www.cypherox.com/hire-e-commerce-developers" aria-label="Go to Official Partners page"
                                        class="">
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
                                        <a href="https://www.cypherox.com/jibble-partnership-free-time-tracking-software" aria-label="Go to Jibble page"
                                        class="">Jibble</a>
                                        </li>
                                        
                                        <li>
                                            <a href="https://www.cypherox.com/shopify-partnership" aria-label="Go to shopify page"
                                            class="">Shopify</a>
                                        </li>
                                        
                                        <li>
                                            <a href="https://codespheretech.in/" aria-label="Go to codespheretech page"
                                            class="">Odoo (codespheretech)</a>
                                        </li>
                                        
                                        </ul>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="https://www.cypherox.com/our-engagement-models" aria-label="Go to  Engagement Model page"
                                        class="">Engagement
                                        Model</a>
                                    </li>
                                    
                                    <li class="second-sub-menu-li">
                                        <a href="https://www.cypherox.com/blog/" aria-label="Go to blog page" class="">Blogs</a>
                                    </li>
                                    <li class="second-sub-menu-li">
                                        <a href="https://www.cypherox.com/news/" aria-label="Go to news page" class="">News</a>
                                    </li>

                                    <li class="second-sub-menu-li">
                                        <a href="https://www.cypherox.com/get-in-touch" aria-label="Go to contact page" class="">Contact us</a>
                                    </li>

                                    </ul>
                                </div>

                            </li> 

                            <li class="list-item-1">
                            <a href="https://www.cypherox.com/career" aria-label="Go to careers page" class="link-item-level-1"> <span
                                class="inner-text"> Careers </span>
                            </a>
                            </li>
                        </ul>
                        </div>
                    </nav>
                    </div>
                    <div class="contact-btn-sec btn-header-contact">
                        <a href="https://www.cypherox.com/get-in-touch" class="primary-btn" aria-label="Go to Contact us">
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