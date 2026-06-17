<?php
/**
 * Prime Edge Realty - Privacy Policy Page
 */

// Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

// Define SEO metadata for this page
$meta_title = 'Privacy Policy | Prime Edge Realty';
$meta_desc = 'Read the privacy policy of Prime Edge Realty. Learn how we collect, store, protect, and use your personal information and contact enquiries securely.';
$meta_keywords = 'privacy policy, prime edge realty, real estate data security, user confidentiality';

// Load header
require_once __DIR__ . '/includes/header.php';
?>

<style>
.legal-hero {
    padding: 120px 0 50px;
    background: linear-gradient(135deg, #0e161a 0%, #152228 100%);
    text-align: center;
    border-bottom: 1px solid rgba(229, 186, 115, 0.1);
}
.legal-hero h1 {
    font-size: clamp(2rem, 5vw, 3rem);
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 15px;
}
.legal-hero p {
    color: var(--color-accent);
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.legal-content-section {
    padding: 70px 0;
    background-color: #0b1114;
    color: #e0e0e0;
}
.legal-container {
    max-width: 800px;
    margin: 0 auto;
}
.legal-text-block {
    margin-bottom: 40px;
}
.legal-text-block h2 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    color: #ffffff;
    font-weight: 700;
    margin-bottom: 20px;
    border-left: 3px solid var(--color-accent);
    padding-left: 15px;
}
.legal-text-block p {
    font-size: 1.05rem;
    line-height: 1.8;
    margin-bottom: 15px;
    color: rgba(255, 255, 255, 0.75);
}
.legal-text-block ul {
    margin: 20px 0;
    padding-left: 20px;
}
.legal-text-block li {
    font-size: 1.02rem;
    line-height: 1.7;
    margin-bottom: 10px;
    color: rgba(255, 255, 255, 0.7);
    list-style-type: square;
}
</style>

<main class="legal-page-layout">
    <!-- Hero Header -->
    <div class="legal-hero">
        <div class="container">
            <p>LEGAL INFORMATION</p>
            <h1>Privacy Policy</h1>
        </div>
    </div>

    <!-- Content Block -->
    <section class="legal-content-section" id="privacy-content">
        <div class="container">
            <div class="legal-container">
                <div class="legal-text-block">
                    <h2>1. Overview</h2>
                    <p>At Prime Edge Realty, we are committed to respecting your privacy and protecting the personal data you share with us. This Privacy Policy details how we collect, store, process, and safeguard your personal information when you use our website, submit property enquiries, or engage with our investment consultants.</p>
                </div>

                <div class="legal-text-block">
                    <h2>2. Information We Collect</h2>
                    <p>When you visit our site or use our forms, we may collect two types of information:</p>
                    <ul>
                        <li><strong>Personal Identity Information:</strong> This includes your full name, email address, and 10-digit Indian phone number when you submit an enquiry on property details or a general connect form.</li>
                        <li><strong>Inquiry Metadata:</strong> Details about specific projects or listings you show interest in, message texts, budgets, and submission timestamps.</li>
                    </ul>
                </div>

                <div class="legal-text-block">
                    <h2>3. How We Use Your Data</h2>
                    <p>We use the data we collect solely for business purposes, specifically to:</p>
                    <ul>
                        <li>Verify your 10-digit Indian mobile number to establish contact and provide details.</li>
                        <li>Deliver customized real estate brochures, site floor plans, and financial sheets.</li>
                        <li>Manage and follow up on client requests within our admin panel.</li>
                        <li>Optimize web design metrics and compile analytics data.</li>
                    </ul>
                </div>

                <div class="legal-text-block">
                    <h2>4. Data Storage & Security</h2>
                    <p>We implement professional, industry-standard security measures to guard your data. Passwords for admin panels are salted and hashed using secure algorithms (BCRYPT). Administrative dashboards are guarded with session validations and CSRF verification tokens. We do not sell, trade, or leak your private information to third-party databases.</p>
                </div>

                <div class="legal-text-block">
                    <h2>5. Your Rights</h2>
                    <p>Under local regulations, you have the right to request access to the information we hold about you, request corrections to your contact number or email address, or request complete removal of your submitted enquiry logs from our records.</p>
                </div>

                <div class="legal-text-block">
                    <h2>6. Contact Us</h2>
                    <p>For any questions regarding this policy or data safety, you can connect directly with our director:</p>
                    <ul>
                        <li><strong>Officer in Charge:</strong> Anil Mehra (Founder & Director)</li>
                        <li><strong>Email:</strong> invest@peprealty.com</li>
                        <li><strong>Phone:</strong> +91 93101 04249</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Load footer
require_once __DIR__ . '/includes/footer.php';
?>
