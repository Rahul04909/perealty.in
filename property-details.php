<?php
/**
 * Prime Edge Realiity - Property Details Page
 * Dynamic Detail View
 */

// Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

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

$property = null;

// Query from database first
try {
    $db = db();
    $stmt = $db->prepare("SELECT * FROM `projects` WHERE `slug` = ?");
    $stmt->execute([$selectedSlug]);
    $dbProperty = $stmt->fetch();
} catch (\Exception $e) {
    $dbProperty = null;
}

if ($dbProperty) {
    $property = [
        'id' => $dbProperty['id'],
        'title' => $dbProperty['title'],
        'location' => $dbProperty['location'],
        'price' => $dbProperty['price'],
        'beds' => $dbProperty['beds'],
        'baths' => $dbProperty['baths'],
        'sqft' => $dbProperty['sqft'],
        'garages' => $dbProperty['garages'],
        'year' => $dbProperty['year'],
        'image' => $dbProperty['image'],
        'gallery' => json_decode($dbProperty['gallery'] ?? '[]', true),
        'desc' => $dbProperty['desc'],
        'tag' => $dbProperty['tag'],
        'raw_price' => $dbProperty['raw_price'],
        'seo_title' => $dbProperty['seo_title'],
        'seo_desc' => $dbProperty['seo_desc'],
        'seo_keywords' => $dbProperty['seo_keywords'],
        'floor_plans' => json_decode($dbProperty['floor_plans'] ?? '[]', true),
        'google_map' => $dbProperty['google_map'],
        'proximity' => json_decode($dbProperty['proximity_distances'] ?? '[]', true),
        'is_db' => true
    ];
} else {
    // Fallback to hardcoded mock array
    if (!array_key_exists($selectedSlug, $properties)) {
        $selectedSlug = 'eco-solar';
    }
    $mockProp = $properties[$selectedSlug];
    
    // Seed default floor plans for static mock objects
    $mockFloorPlans = [];
    if ($selectedSlug === 'eco-solar') {
        $mockFloorPlans = [
            [
                'title' => 'Ground Level Floor Plan',
                'desc' => 'Large open-floor plan combining double grand salon lounge, state-of-the-art kitchen, secondary bedroom with attached bath, and garden deck sliders.',
                'image' => 'assets/images/about_house_one.png'
            ],
            [
                'title' => 'First Level Floor Plan',
                'desc' => 'Hosts the premium master bedroom wing featuring massive walk-in closets, luxury master bath with custom spa tubs, and balconies.',
                'image' => 'assets/images/about_house_two.png'
            ]
        ];
    } elseif ($selectedSlug === 'cubic-glass') {
        $mockFloorPlans = [
            [
                'title' => 'Cubic Ground Floor Plan',
                'desc' => 'Geometrical concrete layout with master dining rooms, guest lounges, and integrated smart control utilities.',
                'image' => 'assets/images/about_house_one.png'
            ],
            [
                'title' => 'Cubic First Level Plan',
                'desc' => 'Premium spa decks, individual suite chambers, and terrace access channels.',
                'image' => 'assets/images/hero_house.png'
            ]
        ];
    } else {
        $mockFloorPlans = [
            [
                'title' => 'Mansion Ground Level Layout',
                'desc' => 'Double-height grand salon chambers, professional Italian chef kitchen facilities, and internal elevator portals.',
                'image' => 'assets/images/promo_apartment.png'
            ],
            [
                'title' => 'Mansion Master Level Layout',
                'desc' => 'Full master suite wings with dual walk-in dressing halls, private studies, and security monitor nodes.',
                'image' => 'assets/images/about_house_two.png'
            ]
        ];
    }

    $property = [
        'id' => 0,
        'title' => $mockProp['title'],
        'location' => $mockProp['location'],
        'price' => $mockProp['price'],
        'beds' => $mockProp['beds'],
        'baths' => $mockProp['baths'],
        'sqft' => $mockProp['sqft'],
        'garages' => $mockProp['garages'],
        'year' => $mockProp['year'],
        'image' => $mockProp['image'],
        'gallery' => $mockProp['gallery'],
        'desc' => $mockProp['desc'],
        'tag' => $mockProp['tag'],
        'raw_price' => $mockProp['raw_price'],
        'seo_title' => $mockProp['title'] . ' | Luxury Real Estate Faridabad',
        'seo_desc' => strip_tags(html_entity_decode($mockProp['desc'])),
        'seo_keywords' => 'luxury villa, real estate faridabad, prime edge residence',
        'floor_plans' => $mockFloorPlans,
        'google_map' => '',
        'proximity' => [
            ['name' => 'Delhi Public School', 'distance' => '1.2 km', 'icon' => 'school'],
            ['name' => 'Omaxe World Street Mall', 'distance' => '0.5 km', 'icon' => 'mall'],
            ['name' => 'Fortis Hospital', 'distance' => '3.4 km', 'icon' => 'hospital'],
            ['name' => 'IGI Airport', 'distance' => '42 km', 'icon' => 'plane']
        ],
        'is_db' => false
    ];
}

// Generate SEO Meta, OpenGraph & Schema variables
$meta_title = !empty($property['seo_title']) ? $property['seo_title'] : $property['title'] . ' | ' . env('APP_NAME', 'Prime Edge Realiity');
$meta_desc = !empty($property['seo_desc']) ? $property['seo_desc'] : strip_tags(html_entity_decode($property['desc']));
if (strlen($meta_desc) > 160) {
    $meta_desc = substr($meta_desc, 0, 157) . '...';
}
$meta_keywords = !empty($property['seo_keywords']) ? $property['seo_keywords'] : $property['title'] . ', real estate, Faridabad, property';

// Determine absolute URLs
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$currentUrl = $protocol . '://' . $host . $_SERVER['REQUEST_URI'];
$absoluteImage = strpos($property['image'], 'assets/') === false ? $protocol . '://' . $host . '/' . $property['image'] : $protocol . '://' . $host . '/peprealty.com/' . $property['image'];

// Normalize absolute URLs (avoid double-slashes)
$absoluteImage = preg_replace('/([^:])(\/{2,})/', '$1/', $absoluteImage);
$currentUrl = preg_replace('/([^:])(\/{2,})/', '$1/', $currentUrl);

// Dynamic OpenGraph Meta Tags
$og_tags = [
    'og:title' => $meta_title,
    'og:description' => $meta_desc,
    'og:image' => $absoluteImage,
    'og:url' => $currentUrl,
    'og:type' => 'website',
    'og:site_name' => env('APP_NAME', 'Prime Edge Realiity')
];

// Dynamic Schema JSON-LD (SingleFamilyResidence representation)
$schema_data = [
    '@context' => 'https://schema.org',
    '@type' => 'SingleFamilyResidence',
    'name' => $property['title'],
    'description' => strip_tags(html_entity_decode($property['desc'])),
    'image' => $absoluteImage,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => $property['location'],
        'addressLocality' => 'Faridabad',
        'addressRegion' => 'Haryana',
        'addressCountry' => 'IN'
    ],
    'numberOfRooms' => (int)($property['beds'] + $property['baths']),
    'numberOfBedrooms' => (int)$property['beds'],
    'numberOfBathroomsTotal' => (int)$property['baths'],
    'floorSize' => [
        '@type' => 'QuantitativeValue',
        'value' => (float)str_replace(',', '', $property['sqft']),
        'unitCode' => 'FTK'
    ],
    'offers' => [
        '@type' => 'Offer',
        'price' => (float)$property['raw_price'],
        'priceCurrency' => 'INR',
        'availability' => 'https://schema.org/InStock',
        'url' => $currentUrl
    ]
];
$schema_json = json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// SVG icon mapper for proximity locations
if (!function_exists('getProximityIconSvg')) {
    function getProximityIconSvg($iconName) {
        switch ($iconName) {
            case 'school':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M6 18.8v-4L2 13v6a1 1 0 0 0 1 1h3Z"/><path d="M21.5 13v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-4.5"/></svg>';
            case 'mall':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>';
            case 'hospital':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-pulse"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l1.5-3.5L13 15l1.5-4.5 1.5 1.5h2.78"/></svg>';
            case 'plane':
            case 'airport':
                return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plane"><path d="M21 16V8a2 2 0 0 0-2-2h-3.38L10 2H8v4H4.5A2.5 2.5 0 0 0 2 8.5v3A2.5 2.5 0 0 0 4.5 14H8v4h2l5.62-4H19a2 2 0 0 0 2-2Z"/><path d="M17 12h.01"/></svg>';
            default:
                return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>';
        }
    }
}

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
            <?php 
            $gallery0 = !empty($property['gallery'][0]) ? $property['gallery'][0] : $property['image'];
            $gallery1 = !empty($property['gallery'][1]) ? $property['gallery'][1] : (!empty($property['gallery'][0]) ? $property['gallery'][0] : $property['image']);
            ?>
            <div class="gallery-side-box side-top custom-border-img">
                <img src="<?php echo htmlspecialchars($gallery0); ?>" alt="Interior Room view">
            </div>
            <div class="gallery-side-box side-bottom custom-border-img">
                <img src="<?php echo htmlspecialchars($gallery1); ?>" alt="Pool and Terrace view">
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
                    <div class="detail-desc-paragraph">
                        <?php 
                        if (!empty($property['is_db'])) {
                            echo $property['desc'];
                        } else {
                            echo '<p>' . nl2br(htmlspecialchars($property['desc'])) . '</p>';
                        }
                        ?>
                    </div>
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
                    <?php if (!empty($property['floor_plans'])): ?>
                        <div class="floor-plans-container">
                            <?php foreach ($property['floor_plans'] as $idx => $plan): ?>
                                <?php 
                                $planImgSrc = $plan['image'] ?? '';
                                if ($planImgSrc && strpos($planImgSrc, 'assets/') === false) {
                                    $planImgSrc = $planImgSrc;
                                }
                                ?>
                                <div class="floor-accordion-item <?php echo $idx === 0 ? 'active' : ''; ?>">
                                    <button class="floor-accordion-header" onclick="this.parentElement.classList.toggle('active')">
                                        <span><?php echo htmlspecialchars($plan['title'] ?? 'Floor Plan'); ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down acc-arrow"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <div class="floor-accordion-content">
                                        <p class="floor-plan-desc"><?php echo nl2br(htmlspecialchars($plan['desc'] ?? '')); ?></p>
                                        <?php if (!empty($planImgSrc)): ?>
                                            <div class="floor-plan-img-container custom-border-md text-center" style="width: 100%; border-radius: 12px; overflow: hidden; background: #fbfbfb; border: 1px solid rgba(229, 186, 115, 0.15); margin-top: 15px;">
                                                <img src="<?php echo htmlspecialchars($planImgSrc); ?>" alt="<?php echo htmlspecialchars($plan['title'] ?? 'Floor Layout'); ?>" style="max-height: 400px; max-width: 100%; object-fit: contain; padding: 10px; display: block; margin: 0 auto;">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted" style="font-size:0.9rem;">No floor plans configured for this property.</p>
                    <?php endif; ?>
                </div>

                <!-- Distance Mapping -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Location & Proximity</h3>
                    
                    <?php if (!empty($property['google_map'])): ?>
                        <div class="google-map-container custom-border-md mb-4" style="height: 350px; width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 20px;">
                            <?php 
                            $mapIframe = $property['google_map'];
                            // Make iframe fully responsive
                            $mapIframe = preg_replace('/width="\d+"/', 'width="100%"', $mapIframe);
                            $mapIframe = preg_replace('/height="\d+"/', 'height="100%"', $mapIframe);
                            // Set height to 100% in style as well if any
                            if (strpos($mapIframe, 'style=') !== false) {
                                $mapIframe = preg_replace('/style="([^"]*)"/', 'style="border:0; width:100%; height:100%;"', $mapIframe);
                            } else {
                                $mapIframe = str_replace('<iframe ', '<iframe style="border:0; width:100%; height:100%;" ', $mapIframe);
                            }
                            echo $mapIframe;
                            ?>
                        </div>
                    <?php else: ?>
                        <div class="proximity-map-box custom-border-md mb-4" style="margin-bottom: 20px;">
                            <!-- Mock Map Background -->
                            <div class="mock-map-bg">
                                <div class="map-marker-pin">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin fill-gold"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span class="marker-text"><?php echo htmlspecialchars($property['title']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($property['proximity'])): ?>
                        <div class="proximity-distances-grid">
                            <?php foreach ($property['proximity'] as $item): ?>
                                <div class="proximity-item">
                                    <?php echo getProximityIconSvg($item['icon'] ?? ''); ?>
                                    <span><?php echo htmlspecialchars($item['name']); ?> (<?php echo htmlspecialchars($item['distance']); ?>)</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                                <img src="assets/images/agent_portrait.png" alt="<?php echo htmlspecialchars(env('CONTACT_AGENT_NAME', 'Anil Mehra')); ?>">
                            </div>
                            <div class="agent-meta">
                                <h4 class="agent-name"><?php echo htmlspecialchars(env('CONTACT_AGENT_NAME', 'Anil Mehra')); ?></h4>
                                <span class="agent-role"><?php echo htmlspecialchars(env('CONTACT_AGENT_ROLE', 'Founder & Director')); ?></span>
                                <a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+919310104249')); ?>" class="agent-phone"><?php echo htmlspecialchars(env('CONTACT_PHONE', '+91 93101 04249')); ?></a>
                            </div>
                        </div>

                        <!-- Sidebar Enquiry Form -->
                        <form class="sidebar-inquiry-form" id="sidebar-enquiry-form">
                            <input type="hidden" name="project_id" value="<?php echo (int)$property['id']; ?>">
                            <input type="hidden" name="project_title" value="<?php echo htmlspecialchars($property['title']); ?>">
                            
                            <div class="form-group">
                                <input type="text" name="client_name" placeholder="Your Full Name" required class="sidebar-input">
                            </div>
                            <div class="form-group">
                                <input type="email" name="client_email" placeholder="Your Email Address" required class="sidebar-input">
                            </div>
                            <div class="form-group">
                                <input type="tel" name="client_phone" pattern="[6-9][0-9]{9}" minlength="10" maxlength="10" placeholder="Your 10-Digit Phone Number" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required class="sidebar-input" title="Please enter a valid 10-digit Indian phone number starting with 6, 7, 8, or 9.">
                            </div>
                            <div class="form-group">
                                <textarea name="client_message" rows="3" class="sidebar-textarea" required>I am interested in <?php echo htmlspecialchars($property['title']); ?>. Please send me further details and pricing structure.</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-full form-submit-btn">Send Enquiry <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- SweetAlert2 CDN for modern notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery and AJAX script for AJAX Enquiry Submission -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    $('#sidebar-enquiry-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var btnOriginalText = $btn.html();
        
        $btn.prop('disabled', true).html('Sending... <i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: 'submit-enquiry.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Enquiry Received!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#28a745'
                    });
                    $form[0].reset();
                } else {
                    Swal.fire({
                        title: 'Submission Error',
                        text: response.message || 'Please check your inputs and try again.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'System Error',
                    text: 'Unable to process your request at this time. Please try again later.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html(btnOriginalText);
            }
        });
    });
});
</script>

<?php
// Load footer include
require_once __DIR__ . '/includes/footer.php';
?>
