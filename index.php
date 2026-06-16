<?php
/**
 * Prime Edge Realiity Landing Page
 * Main Entry Point
 */

// Load Header (includes doctype, meta tags, assets styles & navigation menu)
require_once __DIR__ . '/includes/header.php';

// Load Hero Section (welcome banner, brand badge & video popup)
require_once __DIR__ . '/components/hero.php';

// Load Statistics Section (four numeric counts highlight bar)
require_once __DIR__ . '/components/stats.php';

// Load About Us Section (company details, rotating stamp & cards grid)
require_once __DIR__ . '/components/about.php';

// Load Exclusive Projects Showcase (horizontal properties slider with vertical indicator)
require_once __DIR__ . '/components/projects.php';

// Load Highlighted Modern Apartment Promo (CEO quote profile & play trigger)
require_once __DIR__ . '/components/promo.php';

// Load Testimonials Slider (customer review cards)
require_once __DIR__ . '/components/testimonials.php';

// Load Contact & Investment Inquiry Section (inquiry form & office contacts)
require_once __DIR__ . '/components/contact.php';

// Load Footer (links, socials, newsletter subscription & scripts)
require_once __DIR__ . '/includes/footer.php';
