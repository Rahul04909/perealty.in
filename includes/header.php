<?php
/**
 * Header Component for Prime Edge Realty
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Edge Realiity | Your Edge in Smart Investments</title>
    <meta name="description" content="Prime Edge Realiity offers premium real estate solutions, modern residential apartments, luxury houses, and smart property investment opportunities with over 20 years of expertise.">
    <meta name="keywords" content="Real Estate, Prime Edge Realiity, Luxury Homes, Smart Investments, Property Valuation, Property Management">
    <meta name="author" content="Prime Edge Realiity">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png">

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/hero.css">
    <link rel="stylesheet" href="assets/css/stats.css">
    <link rel="stylesheet" href="assets/css/about.css">
    <link rel="stylesheet" href="assets/css/projects.css">
    <link rel="stylesheet" href="assets/css/promo.css">
    <link rel="stylesheet" href="assets/css/testimonials.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/footer.css">

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- Header Navigation -->
    <header class="main-header" id="site-header">
        <div class="container header-container">
            <!-- Logo Section -->
            <a href="index.php" class="logo-area" id="logo-link">
                <img src="assets/logo/logo.png" alt="Prime Edge Realiity Logo" class="logo-img">
                <span class="logo-text">
                    <span class="logo-title">PRIME EDGE</span>
                    <span class="logo-subtitle">REALIITY</span>
                </span>
            </a>

            <!-- Navigation Links (Desktop) -->
            <nav class="desktop-nav" aria-label="Main Navigation">
                <ul class="nav-list">
                    <li><a href="#hero" class="nav-link active">Home</a></li>
                    <li><a href="#about" class="nav-link">About Us</a></li>
                    <li><a href="#projects" class="nav-link">Properties</a></li>
                    <li><a href="#promo" class="nav-link">Agencies</a></li>
                    <li><a href="#testimonials" class="nav-link">Testimonials</a></li>
                    <li><a href="#contact" class="nav-link">Contact Us</a></li>
                </ul>
            </nav>

            <!-- CTA & Burger Action Area -->
            <div class="action-area">
                <a href="#contact" class="btn btn-primary btn-header-cta" id="btn-add-listing">
                    Add Listing <i data-lucide="arrow-right"></i>
                </a>
                
                <!-- Burger Button -->
                <button class="menu-toggle" id="menu-toggle-btn" aria-label="Toggle Mobile Menu" aria-expanded="false">
                    <span class="burger-bar bar-1"></span>
                    <span class="burger-bar bar-2"></span>
                    <span class="burger-bar bar-3"></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer Navigation -->
    <div class="mobile-drawer" id="mobile-drawer" aria-hidden="true">
        <div class="drawer-header">
            <a href="index.php" class="logo-area">
                <img src="assets/logo/logo.png" alt="Prime Edge Realiity Logo" class="logo-img">
                <span class="logo-text">
                    <span class="logo-title">PRIME EDGE</span>
                    <span class="logo-subtitle">REALIITY</span>
                </span>
            </a>
            <button class="drawer-close" id="drawer-close-btn" aria-label="Close Menu">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="drawer-body">
            <nav class="mobile-nav" aria-label="Mobile Navigation">
                <ul class="mobile-nav-list">
                    <li><a href="#hero" class="mobile-nav-link">Home</a></li>
                    <li><a href="#about" class="mobile-nav-link">About Us</a></li>
                    <li><a href="#projects" class="mobile-nav-link">Properties</a></li>
                    <li><a href="#promo" class="mobile-nav-link">Agencies</a></li>
                    <li><a href="#testimonials" class="mobile-nav-link">Testimonials</a></li>
                    <li><a href="#contact" class="mobile-nav-link">Contact Us</a></li>
                </ul>
            </nav>
            <div class="drawer-footer">
                <a href="#contact" class="btn btn-primary w-full" style="width: 100%;">Add Listing <i data-lucide="arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="drawer-overlay" id="drawer-overlay"></div>
