<?php
/**
 * Prime Edge Realty - Terms & Conditions Page
 */

// Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

// Define SEO metadata for this page
$meta_title = 'Terms & Conditions | Prime Edge Realty';
$meta_desc = 'Review the terms and conditions of Prime Edge Realty. Read guidelines on property enquiries, details accuracy, and website usage rules.';
$meta_keywords = 'terms and conditions, prime edge realty, real estate regulations, user agreement';

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
            <h1>Terms & Conditions</h1>
        </div>
    </div>

    <!-- Content Block -->
    <section class="legal-content-section" id="terms-content">
        <div class="container">
            <div class="legal-container">
                <div class="legal-text-block">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By visiting or using our website, submitting project interest forms, or contacting our consultants, you acknowledge and agree to be bound by these Terms & Conditions. If you do not agree, please do not use our services.</p>
                </div>

                <div class="legal-text-block">
                    <h2>2. Website Information & Accuracy</h2>
                    <p>All information, price metrics, dimensions, floor plan sketches, and proximity Facility lists on this site are provided for guidance only. While we verify information inside the admin dashboard, real estate values, layouts, and facilities are subject to change by developers without notice. Prime Edge Realty is not liable for minor layout deviations or market price changes.</p>
                </div>

                <div class="legal-text-block">
                    <h2>3. User Submissions</h2>
                    <p>When you submit enquiries on our forms, you agree that:</p>
                    <ul>
                        <li>The phone number entered consists of exactly 10 digits and represents a valid Indian mobile number.</li>
                        <li>The details entered (name, email) are authentic and correct.</li>
                        <li>You will not submit spam, commercial advertisements, or malicious text fields.</li>
                    </ul>
                </div>

                <div class="legal-text-block">
                    <h2>4. Intellectual Property</h2>
                    <p>All visual designs, custom HSL styling files, text copy, logos, graphics, and custom media files belong to Prime Edge Realty. Reproduction of our site content, layout sheets, or branding materials without written consent is strictly prohibited.</p>
                </div>

                <div class="legal-text-block">
                    <h2>5. Dispute Resolution</h2>
                    <p>Any disputes arising out of the use of our services, property listings, or contact enquiries shall be governed by and construed in accordance with the laws of Haryana, India, and will be subject to the exclusive jurisdiction of the courts of Faridabad.</p>
                </div>

                <div class="legal-text-block">
                    <h2>6. Amendments</h2>
                    <p>We reserve the right to amend these Terms & Conditions at any time. Any changes will be published directly on this page, and your continued usage of the website indicates acceptance of the updated terms.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Load footer
require_once __DIR__ . '/includes/footer.php';
?>
