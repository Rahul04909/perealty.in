<?php
/**
 * Prime Edge Realty - About Us Page
 */

// Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

// Define SEO metadata for this page
$meta_title = 'About Prime Edge Realty | Elite Real Estate Advisors & Developers';
$meta_desc = 'Discover Prime Edge Realty, a leading real estate advisory and development firm with over 20 years of expertise in luxury villas, smart home automation, and high-yield properties.';
$meta_keywords = 'about prime edge realty, real estate developers faridabad, luxury housing consultants, anil mehra';

// Custom JSON-LD Schema for the Organization
$about_schema = [
    '@context' => 'https://schema.org',
    '@type' => 'RealEstateAgent',
    'name' => env('APP_NAME', 'Prime Edge Realty'),
    'image' => 'assets/logo/logo.png',
    'description' => $meta_desc,
    'telephone' => env('CONTACT_PHONE_RAW', '+919310104249'),
    'email' => env('CONTACT_EMAIL', 'invest@peprealty.com'),
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => env('CONTACT_ADDRESS', '198 SCO 1st Floor, Omaxe World Street, Sector 79'),
        'addressLocality' => 'Faridabad',
        'addressRegion' => 'Haryana',
        'addressCountry' => 'IN'
    ],
    'priceRange' => '₹₹₹₹'
];
$schema_json = json_encode($about_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// Load header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom Premium Styling for About Us Page -->
<style>
:root {
    --bg-dark-gradient: linear-gradient(135deg, #0a0f12 0%, #121d22 100%);
    --card-bg-glass: rgba(255, 255, 255, 0.03);
    --border-gold-soft: rgba(229, 186, 115, 0.12);
}

.about-page-layout {
    background-color: #0b1114;
    color: #e0e0e0;
}

/* Hero Section */
.about-hero {
    padding: 140px 0 80px;
    background: var(--bg-dark-gradient);
    text-align: center;
    border-bottom: 1px solid var(--border-gold-soft);
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: 'EXCELLENCE';
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 10vw;
    font-weight: 900;
    color: rgba(255, 255, 255, 0.015);
    letter-spacing: 15px;
    pointer-events: none;
    user-select: none;
}
.about-hero .tagline {
    color: var(--color-accent);
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 15px;
    display: inline-block;
}
.about-hero h1 {
    font-size: clamp(2.4rem, 6vw, 4rem);
    color: #ffffff;
    font-family: var(--font-primary);
    font-weight: 800;
    margin-bottom: 25px;
    line-height: 1.15;
}
.about-hero p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.15rem;
    max-width: 750px;
    margin: 0 auto;
    line-height: 1.65;
}

/* Intro Grid */
.about-intro-section {
    padding: 80px 0;
    border-bottom: 1px solid var(--border-gold-soft);
}
.about-intro-grid {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 60px;
    align-items: center;
}
@media (max-width: 991px) {
    .about-intro-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
}
.intro-text-pane h2 {
    font-size: clamp(1.8rem, 3.5vw, 2.5rem);
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 25px;
    line-height: 1.3;
}
.intro-text-pane p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.05rem;
    line-height: 1.75;
    margin-bottom: 20px;
}
.intro-highlight-box {
    border-left: 3px solid var(--color-accent);
    padding-left: 20px;
    margin: 30px 0;
}
.intro-highlight-box h3 {
    font-size: 1.25rem;
    color: #ffffff;
    font-weight: 600;
    line-height: 1.5;
    margin-bottom: 10px;
}
.intro-highlight-box p {
    margin-bottom: 0;
    font-size: 0.95rem;
}

/* Image overlap frame */
.intro-image-frame {
    position: relative;
    height: 450px;
}
.intro-image-frame .frame-img {
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid var(--border-gold-soft);
    position: absolute;
    box-shadow: 0 15px 35px rgba(0,0,0,0.4);
}
.intro-image-frame .img-main {
    width: 80%;
    height: 80%;
    top: 0;
    left: 0;
    z-index: 2;
}
.intro-image-frame .img-sub {
    width: 65%;
    height: 65%;
    bottom: 0;
    right: 0;
    z-index: 1;
    border-color: rgba(229, 186, 115, 0.25);
}
@media (max-width: 575px) {
    .intro-image-frame {
        height: 320px;
    }
}

/* Core Values */
.values-section {
    padding: 80px 0;
    background: #090e10;
    border-bottom: 1px solid var(--border-gold-soft);
}
.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 50px;
}
.value-card {
    background: var(--card-bg-glass);
    border: 1px solid var(--border-gold-soft);
    border-radius: 12px;
    padding: 40px 30px;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
.value-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--color-accent), transparent);
    transform: translateX(-100%);
    transition: transform 0.5s ease;
}
.value-card:hover {
    transform: translateY(-5px);
    border-color: rgba(229, 186, 115, 0.3);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.value-card:hover::before {
    transform: translateX(100%);
}
.value-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(229, 186, 115, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-accent);
    margin-bottom: 25px;
}
.value-card h3 {
    font-size: 1.3rem;
    color: #ffffff;
    font-weight: 700;
    margin-bottom: 15px;
}
.value-card p {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.6;
    margin-bottom: 0;
}

/* Executive Portrait Spread */
.executive-spread {
    padding: 100px 0;
    border-bottom: 1px solid var(--border-gold-soft);
}
.executive-grid {
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    gap: 60px;
    align-items: center;
}
@media (max-width: 991px) {
    .executive-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
}
.exec-image-wrapper {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(229, 186, 115, 0.2);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}
.exec-image-wrapper img {
    width: 100%;
    height: auto;
    display: block;
    filter: grayscale(10%) contrast(105%);
    transition: transform 0.4s ease;
}
.exec-image-wrapper:hover img {
    transform: scale(1.03);
}
.exec-details {
    padding-left: 20px;
}
.exec-tag {
    color: var(--color-accent);
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 10px;
    display: block;
}
.exec-name {
    font-size: 2.2rem;
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 5px;
}
.exec-role {
    font-size: 1.05rem;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 30px;
    display: block;
}
.exec-quote {
    font-size: 1.2rem;
    font-style: italic;
    color: #ffffff;
    border-left: 3px solid var(--color-accent);
    padding-left: 20px;
    margin-bottom: 30px;
    line-height: 1.6;
}
.exec-bio p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.05rem;
    line-height: 1.75;
    margin-bottom: 20px;
}

/* CTA */
.about-cta-banner {
    padding: 80px 0;
    text-align: center;
    background: linear-gradient(135deg, #10191e 0%, #080d0f 100%);
}
.about-cta-banner h2 {
    font-size: 2.2rem;
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 20px;
}
.about-cta-banner p {
    color: rgba(255, 255, 255, 0.7);
    max-width: 600px;
    margin: 0 auto 35px;
    font-size: 1.1rem;
    line-height: 1.6;
}
</style>

<main class="about-page-layout">
    <!-- Hero Banner -->
    <section class="about-hero" id="about-hero-section">
        <div class="container">
            <span class="tagline">Our Philosophy</span>
            <h1>Crafting Premium Real Estate Journeys</h1>
            <p>At Prime Edge Realty, we merge state-of-the-art sustainable technology with elite residential architecture to build structures that hold value for generations.</p>
        </div>
    </section>

    <!-- Intro Grid Section -->
    <section class="about-intro-section" id="about-intro-info">
        <div class="container">
            <div class="about-intro-grid">
                <div class="intro-text-pane">
                    <h2>Twenty Years of Architectural Integrity & Client Advisory</h2>
                    <p>Founded on the principles of transparency and premium execution, Prime Edge Realty has established itself as Faridabad's leading boutique property development and advisory group. Over the past two decades, we have guided clients through high-yield commercial leasing, architectural acquisitions, and modern residential builds.</p>
                    <p>Our focus goes beyond simply buying and selling square feet. We analyze location yields, structure proximity values, and customize layouts with eco-solar features and smart automated automation so that your investment remains ahead of the curve.</p>
                    
                    <div class="intro-highlight-box">
                        <h3>Forward-Thinking Living</h3>
                        <p>We are the region's first boutique group to standardize solar array arrays, double-glazed glass panels, and complete smart control boards inside our premium projects list.</p>
                    </div>
                </div>
                
                <div class="intro-image-frame">
                    <img src="assets/images/about_house_one.png" alt="Prime Edge House Exterior" class="frame-img img-main">
                    <img src="assets/images/about_house_two.png" alt="Contemporary Villa Deck" class="frame-img img-sub">
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="values-section" id="about-values">
        <div class="container">
            <div class="text-center">
                <span class="section-tagline">HOW WE WORK</span>
                <h2 class="section-title text-white">Our Core Values</h2>
            </div>
            
            <div class="values-grid">
                <!-- Value 1 -->
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.5 3.8 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h3>Absolute Integrity</h3>
                    <p>We believe in total transparent pricing. Every raw numeric price, mapping record, and proximity distance is fully documented without hidden clauses.</p>
                </div>
                
                <!-- Value 2 -->
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275Z"/><path d="m5 3 1 2.5L8.5 6 6 7 5 9.5 4 7 1.5 6 4 5.5Z"/><path d="m19 17 1 2.5 2.5.5-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1Z"/></svg>
                    </div>
                    <h3>Innovation in Build</h3>
                    <p>From advanced rooftop solar panel mapping to modern multi-level dynamic floor plans, we utilize modern tech to elevate standard designs.</p>
                </div>
                
                <!-- Value 3 -->
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gem"><path d="M6 3h12l4 6-10 13L2 9z"/><path d="M11 3 8 9l3 13 3-13-3-6zm-5 6h12"/></svg>
                    </div>
                    <h3>Uncompromising Quality</h3>
                    <p>We select premium coordinates, high-grade materials, and double-height architectural layouts to guarantee outstanding structures.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Executive Spread Section -->
    <section class="executive-spread" id="exec-profile">
        <div class="container">
            <div class="executive-grid">
                <div class="exec-image-wrapper">
                    <img src="assets/images/anil-mehra.jpeg" alt="Anil Mehra - Founder & Director">
                </div>
                
                <div class="exec-details">
                    <span class="exec-tag">Leadership Profile</span>
                    <h2 class="exec-name"><?php echo htmlspecialchars(env('CONTACT_AGENT_NAME', 'Anil Mehra')); ?></h2>
                    <span class="exec-role"><?php echo htmlspecialchars(env('CONTACT_AGENT_ROLE', 'Founder & Director')); ?></span>
                    
                    <div class="exec-quote">
                        "Luxury is not about excess. It is about the absolute synchronization of form, sustainable function, and structural peace of mind."
                    </div>
                    
                    <div class="exec-bio">
                        <p>With over two decades of local real estate leadership, Anil Mehra has shaped the development landscapes of Sector 79 Faridabad. His vision for Prime Edge Realty is to establish standard configurations where green energy systems and high-end structural layout are built right into the property structure, rather than added as afterthoughts.</p>
                        <p>Under his direction, the firm has successfully closed over ₹500+ Cr in premium residential sales and structured advisory networks connecting developers with high-yield retail partners.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta-banner" id="about-cta">
        <div class="container">
            <h2>Ready to Begin Your Real Estate Search?</h2>
            <p>Connect with our expert investment brokers and schedule a personal walkthrough of our active premium properties list today.</p>
            <a href="contact.php" class="btn btn-primary">
                Inquire Directly <i data-lucide="arrow-right"></i>
            </a>
        </div>
    </section>
</main>

<?php
// Load footer
require_once __DIR__ . '/includes/footer.php';
?>
