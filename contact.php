<?php
/**
 * Prime Edge Realty - Contact Us Page
 */

// Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

// Define SEO metadata for this page
$meta_title = 'Contact Our Real Estate Experts | Prime Edge Realty';
$meta_desc = 'Have questions about premium properties or smart real estate investments? Contact the experts at Prime Edge Realty Faridabad today for personalized assistance.';
$meta_keywords = 'contact prime edge realty, property consulting, real estate enquiries faridabad, anil mehra';

// Load header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Contact Page Styling -->
<style>
.contact-hero-banner {
    padding: 120px 0 60px;
    background: linear-gradient(135deg, #0e161a 0%, #152228 100%);
    text-align: center;
    border-bottom: 1px solid rgba(229, 186, 115, 0.1);
}
.contact-hero-banner .banner-tag {
    color: var(--color-accent);
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 15px;
    display: inline-block;
}
.contact-hero-banner h1 {
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    color: #ffffff;
    font-family: var(--font-primary);
    font-weight: 800;
    margin-bottom: 20px;
    line-height: 1.2;
}
.contact-hero-banner p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.1rem;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}
</style>

<main class="contact-page-layout">
    <!-- Hero Banner Section -->
    <div class="contact-hero-banner">
        <div class="container">
            <span class="banner-tag">Connect With Us</span>
            <h1>Let's Build Your Dream Investment</h1>
            <p>Whether you're looking to purchase a modern sustainable villa, lease commercial spaces, or inquire about property values, our team of dedicated advisors is ready to guide you.</p>
        </div>
    </div>

    <!-- Contact & Enquiry Form Component -->
    <?php require_once __DIR__ . '/components/contact.php'; ?>
</main>

<?php
// Load footer
require_once __DIR__ . '/includes/footer.php';
?>
