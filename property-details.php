<?php
/**
 * Prime Edge Realiity - Property Details Page
 * Dynamic Detail View
 */

// Define mock database of properties
$properties = [
    'eco-solar' => [
        'title' => 'Eco-Solar Villa',
        'location' => '198 SCO 1st Floor, Omaxe World Street, Sector 79 Faridabad 121002',
        'price' => '₹4,85,00,000',
        'beds' => 4,
        'baths' => 5,
        'sqft' => '4,200',
        'garages' => 2,
        'year' => 2025,
        'image' => 'assets/images/project_one.png',
        'gallery' => [
            'assets/images/about_house_one.png',
            'assets/images/about_house_two.png',
            'assets/images/hero_house.png'
        ],
        'desc' => 'Experience the absolute pinnacle of sustainable luxury living. The Eco-Solar Villa is designed for forward-thinking homeowners who value modern aesthetics and green engineering. Featuring advanced rooftop solar arrays, automated climate control, greywater filtration, and floor-to-ceiling double-glazed glass panels, this residence delivers structural integrity while generating its own power. Designed with a custom open-floor layout, the living quarters merge seamlessly with the outdoor deck and heated infinity pool.',
        'tag' => 'Featured / Eco-Friendly',
        'raw_price' => 48500000
    ],
    'cubic-glass' => [
        'title' => 'Cubic Glass Manor',
        'location' => 'Omaxe World Street Phase II, Sector 79 Faridabad 121002',
        'price' => '₹6,50,00,000',
        'beds' => 5,
        'baths' => 6,
        'sqft' => '5,800',
        'garages' => 3,
        'year' => 2025,
        'image' => 'assets/images/project_two.png',
        'gallery' => [
            'assets/images/hero_house.png',
            'assets/images/about_house_one.png',
            'assets/images/promo_apartment.png'
        ],
        'desc' => 'An architectural marvel showcasing cubic geometries and premium concrete constructs. The Cubic Glass Manor offers expansive panoramic vistas through high-durability floor-to-ceiling glass screens. Fully automated smart-home systems control illumination, security, climate, and sound. Includes private spa facilities, a dedicated gym room, automated double garage, and beautifully manicured Zen gardens.',
        'tag' => 'Featured / Architectural',
        'raw_price' => 65000000
    ],
    'contemporary-mansion' => [
        'title' => 'Contemporary Mansion',
        'location' => 'Sector 79 Faridabad, Haryana 121002',
        'price' => '₹8,90,00,000',
        'beds' => 6,
        'baths' => 8,
        'sqft' => '8,200',
        'garages' => 4,
        'year' => 2026,
        'image' => 'assets/images/hero_house.png',
        'gallery' => [
            'assets/images/promo_apartment.png',
            'assets/images/about_house_two.png',
            'assets/images/project_two.png'
        ],
        'desc' => 'Indulge in unmatched grandeur. The Contemporary Mansion offers a massive residential footprint featuring custom marble flooring, double-height grand salons, a master wing with a walk-in wardrobe, and commercial-grade chef kitchens. Outdoor recreation spaces include an olympic-sized swimming pool, custom fire pit lounges, and state-of-the-art security networks.',
        'tag' => 'Premium Elite',
        'raw_price' => 89000000
    ]
];

// Determine selected property
$selectedSlug = isset($_GET['property']) ? trim($_GET['property']) : 'eco-solar';
if (!array_key_exists($selectedSlug, $properties)) {
    $selectedSlug = 'eco-solar';
}

$property = $properties[$selectedSlug];

// Load header include
require_once __DIR__ . '/includes/header.php';
?>

<!-- Add direct link to CSS in case it wasn't added dynamically -->
<link rel="stylesheet" href="assets/css/property-details.css">

<main class="property-details-page">
    <div class="container">
        <!-- Breadcrumb & Back Link -->
        <div class="details-nav-bar">
            <a href="index.php#projects" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Back to Properties
            </a>
            <div class="breadcrumb-trail">
                <a href="index.php">Home</a> / <a href="index.php#projects">Properties</a> / <span><?php echo htmlspecialchars($property['title']); ?></span>
            </div>
        </div>

        <!-- Property Title & Price Header -->
        <div class="property-header-area">
            <div class="property-title-box">
                <span class="detail-tag"><?php echo htmlspecialchars($property['tag']); ?></span>
                <h1 class="detail-title"><?php echo htmlspecialchars($property['title']); ?></h1>
                <p class="detail-location">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo htmlspecialchars($property['location']); ?>
                </p>
            </div>
            <div class="property-price-box">
                <span class="price-label">Price List</span>
                <h2 class="detail-price"><?php echo htmlspecialchars($property['price']); ?></h2>
            </div>
        </div>

        <!-- Gallery Grid (Premium Overlap design) -->
        <div class="details-gallery-grid">
            <div class="gallery-main-box custom-border-img">
                <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Main Exterior" class="gallery-large-img">
            </div>
            <div class="gallery-side-box side-top custom-border-img">
                <img src="<?php echo htmlspecialchars($property['gallery'][0]); ?>" alt="Interior Room view">
            </div>
            <div class="gallery-side-box side-bottom custom-border-img">
                <img src="<?php echo htmlspecialchars($property['gallery'][1]); ?>" alt="Pool and Terrace view">
            </div>
        </div>

        <!-- Key Specifications Bar -->
        <div class="details-specs-bar">
            <div class="spec-tile">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/><path d="M18 14H6M18 10H6"/></svg>
                <div class="spec-info">
                    <span class="spec-val"><?php echo htmlspecialchars($property['beds']); ?> Beds</span>
                    <span class="spec-label">Bedrooms</span>
                </div>
            </div>
            <div class="spec-tile">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bath"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.3 3 3 4.3 3 5.5v13a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5V14"/><path d="M3 12h18"/><path d="M21 9.5a2.5 2.5 0 0 0-5 0v3h5v-3Z"/></svg>
                <div class="spec-info">
                    <span class="spec-val"><?php echo htmlspecialchars($property['baths']); ?> Baths</span>
                    <span class="spec-label">Bathrooms</span>
                </div>
            </div>
            <div class="spec-tile">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-maximize-2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                <div class="spec-info">
                    <span class="spec-val"><?php echo htmlspecialchars($property['sqft']); ?> sqft</span>
                    <span class="spec-label">Built Area</span>
                </div>
            </div>
            <div class="spec-tile">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M9 17h6"/></svg>
                <div class="spec-info">
                    <span class="spec-val"><?php echo htmlspecialchars($property['garages']); ?> Garages</span>
                    <span class="spec-label">Parking Spot</span>
                </div>
            </div>
            <div class="spec-tile">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                <div class="spec-info">
                    <span class="spec-val"><?php echo htmlspecialchars($property['year']); ?></span>
                    <span class="spec-label">Year Built</span>
                </div>
            </div>
        </div>

        <!-- Split Main Content & Sidebar Grid -->
        <div class="details-split-grid">
            <!-- Left Pane: Info, Features, Plans -->
            <div class="details-main-content">
                <!-- Description -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Property Description</h3>
                    <p class="detail-desc-paragraph">
                        <?php echo nl2br(htmlspecialchars($property['desc'])); ?>
                    </p>
                </div>

                <!-- Amenities List -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Premium Amenities</h3>
                    <div class="amenities-check-grid">
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Solar System Integration</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Smart Home Automation</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Heated Infinity Pool</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Private Gym Facility</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Walk-In Closets</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Landscaped Zen Garden</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>Security CCTV Grid</span>
                        </div>
                        <div class="amenity-check-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check check-gold"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>High-End Italian Kitchen</span>
                        </div>
                    </div>
                </div>

                <!-- Interactive Floor Plans -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Floor Plans</h3>
                    <div class="floor-plans-container">
                        <!-- Floor Level 1 -->
                        <div class="floor-accordion-item">
                            <button class="floor-accordion-header" onclick="this.parentElement.classList.toggle('active')">
                                <span>Ground Level Floor Plan</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down acc-arrow"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="floor-accordion-content">
                                <p class="floor-plan-desc">Large open-floor plan combining double grand salon lounge, state-of-the-art kitchen, secondary bedroom with attached bath, and floor-to-ceiling glass screen sliders connecting directly with the garden deck and infinity pool.</p>
                                <div class="floor-plan-img-mock custom-border-md">
                                    <div class="mock-plan-layout">
                                        <div class="mock-plan-room">Grand Salon</div>
                                        <div class="mock-plan-room">Kitchen</div>
                                        <div class="mock-plan-room">Bedroom</div>
                                        <div class="mock-plan-room">Deck / Pool</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floor Level 2 -->
                        <div class="floor-accordion-item">
                            <button class="floor-accordion-header" onclick="this.parentElement.classList.toggle('active')">
                                <span>First Level Floor Plan</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down acc-arrow"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="floor-accordion-content">
                                <p class="floor-plan-desc">Hosts the premium master bedroom wing featuring massive walk-in closets, luxury master bath with custom spa tubs, double vanity, and two secondary bedrooms with private balconies overlooking the pool grounds.</p>
                                <div class="floor-plan-img-mock custom-border-md">
                                    <div class="mock-plan-layout">
                                        <div class="mock-plan-room">Master Suite</div>
                                        <div class="mock-plan-room">Walk-in Closet</div>
                                        <div class="mock-plan-room">Bedroom 2</div>
                                        <div class="mock-plan-room">Bedroom 3</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distance Mapping -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Location & Proximity</h3>
                    <div class="proximity-map-box custom-border-md">
                        <!-- Mock Map Background -->
                        <div class="mock-map-bg">
                            <div class="map-marker-pin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin fill-gold"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span class="marker-text"><?php echo htmlspecialchars($property['title']); ?></span>
                            </div>
                        </div>
                        <div class="proximity-distances-grid">
                            <div class="proximity-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 18.8v-4L2 13v6a1 1 0 0 0 1 1h3Z"/><path d="M21.5 13v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-4.5"/></svg>
                                <span>Delhi Public School (1.2 km)</span>
                            </div>
                            <div class="proximity-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                <span>Omaxe World Street Mall (0.5 km)</span>
                            </div>
                            <div class="proximity-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-pulse"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l1.5-3.5L13 15l1.5-4.5 1.5 1.5h2.78"/></svg>
                                <span>Fortis Hospital (3.4 km)</span>
                            </div>
                            <div class="proximity-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plane"><path d="M21 16V8a2 2 0 0 0-2-2h-3.38L10 2H8v4H4.5A2.5 2.5 0 0 0 2 8.5v3A2.5 2.5 0 0 0 4.5 14H8v4h2l5.62-4H19a2 2 0 0 0 2-2Z"/><path d="M17 12h.01"/></svg>
                                <span>IGI Airport (42 km)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Pane: Inquiry Sidebar & Mortgage Calculator -->
            <div class="details-sidebar-content">
                <!-- Sticky Wrapper -->
                <div class="sidebar-sticky-box">
                    <!-- Agent Card -->
                    <div class="agent-form-card">
                        <div class="agent-profile">
                            <div class="agent-avatar">
                                <img src="assets/images/agent_portrait.png" alt="Anil Mehra">
                            </div>
                            <div class="agent-meta">
                                <h4 class="agent-name">Anil Mehra</h4>
                                <span class="agent-role">Founder & Director</span>
                                <a href="tel:+919310104249" class="agent-phone">+91 93101 04249</a>
                            </div>
                        </div>

                        <!-- Sidebar Enquiry Form -->
                        <form class="sidebar-inquiry-form" onsubmit="event.preventDefault(); alert('Your inquiry has been successfully sent to Anil Mehra. We will reach out shortly.'); this.reset();">
                            <div class="form-group">
                                <input type="text" placeholder="Your Full Name" required class="sidebar-input">
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Your Email Address" required class="sidebar-input">
                            </div>
                            <div class="form-group">
                                <input type="tel" placeholder="Your Phone Number" required class="sidebar-input">
                            </div>
                            <div class="form-group">
                                <textarea rows="3" class="sidebar-textarea" required>I am interested in <?php echo htmlspecialchars($property['title']); ?>. Please send me further details and pricing structure.</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-full form-submit-btn">Send Enquiry <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></button>
                        </form>
                    </div>

                    <!-- Mortgage Calculator -->
                    <div class="mortgage-calc-card">
                        <h4 class="calc-title">Mortgage Calculator</h4>
                        <p class="calc-subtitle">Estimate your monthly housing installments.</p>
                        
                        <div class="calc-form">
                            <!-- Loan Principal Amount -->
                            <div class="calc-group">
                                <label class="calc-label">Property Price (₹)</label>
                                <input type="number" id="calc-price" value="<?php echo $property['raw_price']; ?>" class="calc-input">
                            </div>

                            <!-- Down Payment -->
                            <div class="calc-group">
                                <label class="calc-label">Down Payment (20% default)</label>
                                <input type="number" id="calc-down-payment" value="<?php echo intval($property['raw_price'] * 0.2); ?>" class="calc-input">
                            </div>

                            <!-- Interest Rate -->
                            <div class="calc-group">
                                <label class="calc-label">Interest Rate (%)</label>
                                <input type="number" step="0.1" id="calc-interest" value="8.5" class="calc-input">
                            </div>

                            <!-- Term -->
                            <div class="calc-group">
                                <label class="calc-label">Loan Term (Years)</label>
                                <select id="calc-term" class="calc-select">
                                    <option value="15">15 Years</option>
                                    <option value="20" selected>20 Years</option>
                                    <option value="25">25 Years</option>
                                    <option value="30">30 Years</option>
                                </select>
                            </div>

                            <!-- Calculation Result -->
                            <div class="calc-result-box">
                                <span class="result-label">Estimated Monthly Installment</span>
                                <h3 class="result-amount" id="calc-result-display">₹0 /mo</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Injected script variables for calculation -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        // Simple Mortgage Calculator JavaScript
        const priceInput = document.getElementById('calc-price');
        const downPaymentInput = document.getElementById('calc-down-payment');
        const interestInput = document.getElementById('calc-interest');
        const termInput = document.getElementById('calc-term');
        const resultDisplay = document.getElementById('calc-result-display');

        const calculateMortgage = () => {
            const price = parseFloat(priceInput.value) || 0;
            const downPayment = parseFloat(downPaymentInput.value) || 0;
            const annualInterest = parseFloat(interestInput.value) || 0;
            const termYears = parseFloat(termInput.value) || 20;

            const principal = price - downPayment;
            if (principal <= 0) {
                resultDisplay.textContent = "₹0 /mo";
                return;
            }

            const monthlyInterest = (annualInterest / 100) / 12;
            const totalPayments = termYears * 12;

            let monthlyInstallment = 0;
            if (monthlyInterest === 0) {
                monthlyInstallment = principal / totalPayments;
            } else {
                monthlyInstallment = (principal * monthlyInterest * Math.pow(1 + monthlyInterest, totalPayments)) / 
                                     (Math.pow(1 + monthlyInterest, totalPayments) - 1);
            }

            // Format Indian currency style
            const formatted = new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR',
                maximumFractionDigits: 0
            }).format(monthlyInstallment);

            resultDisplay.textContent = `${formatted} /mo`;
        };

        // Event Listeners
        [priceInput, downPaymentInput, interestInput, termInput].forEach(elem => {
            if (elem) elem.addEventListener('input', calculateMortgage);
        });

        // Run initially
        calculateMortgage();
    });
</script>

<?php
// Load footer include
require_once __DIR__ . '/includes/footer.php';
?>
