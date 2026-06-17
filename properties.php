<?php
/**
 * Prime Edge Realty - Property Listings Catalog
 */

// Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

// Define SEO metadata for this page
$meta_title = 'Exclusive Property Listings | Prime Edge Realty';
$meta_desc = 'Explore the premier catalog of luxury homes, glass villas, and modern residential developments in Sector 79 Faridabad. View sizes, floor plans, and prices.';
$meta_keywords = 'properties listings, real estate faridabad, luxury villas for sale, prime edge properties';

// Fetch all projects from database
try {
    $db = db();
    $stmt = $db->query("SELECT * FROM `projects` ORDER BY `id` DESC");
    $dbProjects = $stmt->fetchAll();
} catch (\Exception $e) {
    $dbProjects = [];
}

// Fallback to hardcoded mock array if DB is empty
if (empty($dbProjects)) {
    $projectsList = [
        [
            'id' => 1,
            'slug' => 'eco-solar',
            'title' => 'Eco-Solar Villa',
            'location' => '198 SCO 1st Floor, Omaxe World Street, Sector 79 Faridabad 121002',
            'price' => '₹4,85,00,000',
            'raw_price' => 48500000,
            'beds' => 4,
            'baths' => 5,
            'sqft' => '4,200',
            'garages' => 2,
            'year' => 2025,
            'image' => 'assets/images/project_one.png',
            'tag' => 'Featured / Eco-Friendly'
        ],
        [
            'id' => 2,
            'slug' => 'cubic-glass',
            'title' => 'Cubic Glass Manor',
            'location' => 'Omaxe World Street Phase II, Sector 79 Faridabad 121002',
            'price' => '₹6,50,00,000',
            'raw_price' => 65000000,
            'beds' => 5,
            'baths' => 6,
            'sqft' => '5,800',
            'garages' => 3,
            'year' => 2025,
            'image' => 'assets/images/project_two.png',
            'tag' => 'Featured / Architectural'
        ],
        [
            'id' => 3,
            'slug' => 'contemporary-mansion',
            'title' => 'Contemporary Mansion',
            'location' => 'Sector 79 Faridabad, Haryana 121002',
            'price' => '₹8,90,00,000',
            'raw_price' => 89000000,
            'beds' => 6,
            'baths' => 8,
            'sqft' => '8,200',
            'garages' => 4,
            'year' => 2026,
            'image' => 'assets/images/hero_house.png',
            'tag' => 'Premium Elite'
        ]
    ];
} else {
    $projectsList = [];
    foreach ($dbProjects as $dbProj) {
        $projectsList[] = [
            'id' => $dbProj['id'],
            'slug' => $dbProj['slug'],
            'title' => $dbProj['title'],
            'location' => $dbProj['location'],
            'price' => $dbProj['price'],
            'raw_price' => $dbProj['raw_price'],
            'beds' => $dbProj['beds'],
            'baths' => $dbProj['baths'],
            'sqft' => $dbProj['sqft'],
            'garages' => $dbProj['garages'],
            'year' => $dbProj['year'],
            'image' => $dbProj['image'],
            'tag' => $dbProj['tag']
        ];
    }
}

// Load header include
require_once __DIR__ . '/includes/header.php';
?>

<style>
.properties-page {
    background-color: var(--color-bg-dark);
    color: var(--color-text-light);
    padding-bottom: 100px;
    position: relative;
    overflow: hidden;
}

/* Listings Hero */
.listings-hero {
    padding: 160px 0 80px;
    background: linear-gradient(180deg, rgba(18, 30, 33, 0.4) 0%, rgba(18, 30, 33, 0.95) 100%);
    text-align: center;
    position: relative;
    z-index: 1;
}
.listings-hero h1 {
    font-size: clamp(2.5rem, 6vw, 3.8rem);
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 15px;
    font-family: var(--font-heading);
    letter-spacing: -0.5px;
    text-shadow: 0 4px 12px rgba(0,0,0,0.5);
}
.listings-hero p {
    color: var(--color-text-muted);
    font-size: 1.15rem;
    max-width: 650px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Page Background Outline Text */
.listings-bg-text {
    top: 90px;
    left: 5%;
    font-size: 10rem;
    opacity: 0.4;
}
@media (max-width: 768px) {
    .listings-bg-text {
        font-size: 6rem;
        top: 110px;
        left: 2%;
    }
}

/* Glassmorphism Floating Filter Bar */
.filter-bar-container {
    background: rgba(27, 43, 46, 0.45);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(229, 186, 115, 0.2);
    padding: 30px;
    border-radius: var(--border-radius-custom-md);
    margin: -40px auto 40px;
    max-width: 1100px;
    position: relative;
    z-index: 10;
    box-shadow: 0 25px 50px rgba(0,0,0,0.4), 0 0 40px rgba(229, 186, 115, 0.02);
}
.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1.2fr 1.2fr 1.6fr;
    gap: 20px;
    align-items: end;
}
@media (max-width: 992px) {
    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 600px) {
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    .filter-bar-container {
        margin: -20px 15px 30px;
        padding: 20px;
    }
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    position: relative;
}
.filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-accent);
    letter-spacing: 2px;
}
.filter-input-wrapper {
    position: relative;
    width: 100%;
}
.filter-input-wrapper svg,
.filter-input-wrapper i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-accent);
    opacity: 0.8;
    pointer-events: none;
    width: 16px;
    height: 16px;
}
.filter-input, .filter-select {
    width: 100%;
    padding: 13px 15px 13px 45px;
    background-color: rgba(18, 30, 33, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: var(--border-radius-rect);
    color: #ffffff;
    font-size: 0.9rem;
    transition: var(--transition-normal);
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}
.filter-input::placeholder {
    color: rgba(255,255,255,0.4);
}
.filter-select {
    cursor: pointer;
}
.filter-select-wrapper {
    position: relative;
}
.filter-select-wrapper::after {
    content: '';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid var(--color-accent);
    pointer-events: none;
}
.filter-input:focus, .filter-select:focus {
    border-color: var(--color-accent);
    box-shadow: 0 0 10px rgba(229, 186, 115, 0.2);
    outline: none;
    background-color: rgba(18, 30, 33, 0.85);
}
.filter-select option {
    background-color: var(--color-bg-dark-card);
    color: #ffffff;
}

/* Results Metadata Bar */
.results-meta-bar {
    max-width: 1100px;
    margin: 0 auto 30px;
    padding: 0 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.95rem;
    color: var(--color-text-muted);
}
.results-count-text span {
    font-weight: 700;
}
.results-clear-btn {
    color: var(--color-accent);
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition-fast);
}
.results-clear-btn:hover {
    color: #ffffff;
}

/* Properties Grid */
.properties-listings-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 35px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--container-padding);
}
@media (max-width: 1024px) {
    .properties-listings-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }
}
@media (max-width: 768px) {
    .properties-listings-grid {
        grid-template-columns: 1fr;
        gap: 25px;
        padding: 0 1.5rem;
    }
}

/* Premium Property Card */
.listing-item-card {
    background-color: var(--color-bg-dark-card);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: var(--border-radius-custom-md);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: var(--transition-normal);
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
.listing-item-card:hover {
    transform: translateY(-8px);
    border-color: rgba(229, 186, 115, 0.25);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 25px rgba(229, 186, 115, 0.05);
}
.listing-img-box {
    position: relative;
    height: 260px;
    overflow: hidden;
    background: #0d1618;
    border-radius: 20px 0 20px 0;
}
.listing-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s cubic-bezier(0.25, 1, 0.5, 1);
}
.listing-item-card:hover .listing-img-box img {
    transform: scale(1.08);
}
.listing-tag {
    position: absolute;
    top: 15px;
    left: 15px;
    background-color: rgba(18, 30, 33, 0.85);
    color: var(--color-accent);
    border: 1px solid rgba(229, 186, 115, 0.25);
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 2;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.listing-year-badge {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background-color: rgba(229, 186, 115, 0.95);
    color: #121e21;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 4px;
    z-index: 2;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}
.listing-details-box {
    padding: 28px 25px 25px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.listing-title {
    font-size: 1.45rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
    font-family: var(--font-heading);
    transition: var(--transition-fast);
}
.listing-item-card:hover .listing-title {
    color: var(--color-accent);
}
.listing-location {
    font-size: 0.88rem;
    color: var(--color-text-muted);
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.4;
}
.listing-location svg {
    color: var(--color-accent);
    flex-shrink: 0;
    width: 15px;
    height: 15px;
}

/* Specs layout */
.listing-specs-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    padding: 16px 0;
    margin-bottom: 24px;
    gap: 10px;
}
.listing-spec-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.78rem;
    color: var(--color-text-muted);
    text-align: center;
}
.listing-spec-tile svg {
    color: var(--color-accent);
    width: 16px;
    height: 16px;
    margin-bottom: 2px;
}
.listing-spec-val {
    font-weight: 700;
    color: #ffffff;
    font-size: 0.85rem;
}

/* Card bottom */
.listing-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    border-top: 1px solid rgba(255, 255, 255, 0.03);
    padding-top: 18px;
}
.listing-price-box {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.price-tag {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--color-accent);
    font-family: var(--font-heading);
    letter-spacing: -0.5px;
}
.price-lbl {
    font-size: 0.68rem;
    text-transform: uppercase;
    color: var(--color-text-muted);
    letter-spacing: 1.5px;
    font-weight: 600;
}
.listing-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1px solid rgba(229, 186, 115, 0.3);
    color: #ffffff;
    background: rgba(229, 186, 115, 0.03);
    transition: var(--transition-normal);
}
.listing-item-card:hover .listing-action-btn {
    background-color: var(--color-accent);
    color: var(--color-text-dark);
    border-color: var(--color-accent);
    box-shadow: 0 0 15px rgba(229, 186, 115, 0.4);
    transform: rotate(45deg);
}

/* Empty search state */
.empty-listings-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: var(--color-bg-dark-card);
    border-radius: var(--border-radius-custom-md);
    border: 1px dashed rgba(229, 186, 115, 0.2);
    margin: 20px 0;
    display: none;
}
.empty-listings-state svg {
    color: var(--color-accent);
    margin-bottom: 20px;
    opacity: 0.8;
}
.empty-listings-state h3 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 10px;
}
.empty-listings-state p {
    color: var(--color-text-muted);
    font-size: 1rem;
    max-width: 500px;
    margin: 0 auto 25px;
    line-height: 1.6;
}
.reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--color-accent);
    color: var(--color-text-dark);
    padding: 12px 24px;
    border-radius: var(--border-radius-rect);
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition-normal);
}
.reset-btn:hover {
    background: var(--color-accent-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(229, 186, 115, 0.2);
}
</style>

<main class="properties-page">
    <!-- Huge background outline text -->
    <div class="outline-text listings-bg-text">CATALOG</div>

    <!-- Hero Banner -->
    <section class="listings-hero">
        <div class="container">
            <h1>Our Exclusive Collection</h1>
            <p>Discover premier architectural designs, smart automated layouts, and high-yield real estate investments in Sector 79 Faridabad.</p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="container" style="position:relative; z-index:10;">
        <div class="filter-bar-container">
            <div class="filter-grid">
                <!-- Search -->
                <div class="filter-group">
                    <label class="filter-label">Search Location or Name</label>
                    <div class="filter-input-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" id="filter-search" class="filter-input" placeholder="e.g. Omaxe, Villa, Mansion...">
                    </div>
                </div>

                <!-- Beds Filter -->
                <div class="filter-group">
                    <label class="filter-label">Min Bedrooms</label>
                    <div class="filter-input-wrapper filter-select-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/><path d="M18 14H6M18 10H6"/></svg>
                        <select id="filter-beds" class="filter-select">
                            <option value="">Any Beds</option>
                            <option value="4">4+ Beds</option>
                            <option value="5">5+ Beds</option>
                            <option value="6">6+ Beds</option>
                        </select>
                    </div>
                </div>

                <!-- Baths Filter -->
                <div class="filter-group">
                    <label class="filter-label">Min Bathrooms</label>
                    <div class="filter-input-wrapper filter-select-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bath"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.3 3 3 4.3 3 5.5v13a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5V14"/><path d="M3 12h18"/><path d="M21 9.5a2.5 2.5 0 0 0-5 0v3h5v-3Z"/></svg>
                        <select id="filter-baths" class="filter-select">
                            <option value="">Any Baths</option>
                            <option value="5">5+ Baths</option>
                            <option value="6">6+ Baths</option>
                            <option value="8">8+ Baths</option>
                        </select>
                    </div>
                </div>

                <!-- Price Sorting -->
                <div class="filter-group">
                    <label class="filter-label">Sort By Price</label>
                    <div class="filter-input-wrapper filter-select-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal"><line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="12" x2="12" y1="18" y2="22"/></svg>
                        <select id="filter-sort" class="filter-select">
                            <option value="default">Default Order</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results Meta Bar -->
    <section class="container">
        <div class="results-meta-bar">
            <div class="results-count-text">
                Showing <span id="visible-count" style="color: var(--color-accent);">3</span> of <span id="total-count" style="color: #ffffff;">3</span> properties
            </div>
            <div id="btn-clear-all" class="results-clear-btn" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                Reset Filters
            </div>
        </div>
    </section>

    <!-- Listings Grid -->
    <section class="container">
        <div class="properties-listings-grid" id="properties-grid-container">
            <!-- Empty state -->
            <div class="empty-listings-state" id="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-code"><path d="m13 13.5 2-2.5-2-2.5"/><path d="m21 21-4.3-4.3"/><circle cx="11" cy="11" r="8"/><path d="g8 13.5 6-2.5 6-2.5"/></svg>
                <h3>No Properties Found</h3>
                <p>We couldn't find any properties matching your current filter criteria. Please try resetting your search.</p>
                <button type="button" class="reset-btn" id="btn-reset-filters">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset Filters
                </button>
            </div>

            <?php foreach ($projectsList as $idx => $item): ?>
                <?php 
                $resolvedImg = !empty($item['image']) ? $item['image'] : 'assets/images/hero_house.png';
                if (strpos($resolvedImg, 'assets/') === false && strpos($resolvedImg, 'uploads/') === false) {
                    $resolvedImg = 'uploads/projects/' . $resolvedImg;
                }
                $itemId = isset($item['id']) ? (int)$item['id'] : $idx;
                ?>
                <!-- Property Card -->
                <div class="listing-item-card" data-id="<?php echo $itemId; ?>" data-title="<?php echo htmlspecialchars(strtolower($item['title'])); ?>" data-location="<?php echo htmlspecialchars(strtolower($item['location'])); ?>" data-beds="<?php echo (int)$item['beds']; ?>" data-baths="<?php echo (int)$item['baths']; ?>" data-price="<?php echo (int)$item['raw_price']; ?>">
                    <div class="listing-img-box">
                        <span class="listing-tag"><?php echo htmlspecialchars($item['tag']); ?></span>
                        <span class="listing-year-badge">Est. <?php echo htmlspecialchars($item['year']); ?></span>
                        <img src="<?php echo htmlspecialchars($resolvedImg); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    </div>
                    
                    <div class="listing-details-box">
                        <h3 class="listing-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="listing-location">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?php echo htmlspecialchars($item['location']); ?>
                        </p>
                        
                        <div class="listing-specs-row">
                            <div class="listing-spec-tile" title="Bedrooms">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/><path d="M18 14H6M18 10H6"/></svg>
                                <span class="listing-spec-val"><?php echo htmlspecialchars($item['beds']); ?> Beds</span>
                            </div>
                            <div class="listing-spec-tile" title="Bathrooms">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bath"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.3 3 3 4.3 3 5.5v13a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5V14"/><path d="M3 12h18"/><path d="M21 9.5a2.5 2.5 0 0 0-5 0v3h5v-3Z"/></svg>
                                <span class="listing-spec-val"><?php echo htmlspecialchars($item['baths']); ?> Baths</span>
                            </div>
                            <div class="listing-spec-tile" title="Area size">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-maximize-2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                                <span class="listing-spec-val"><?php echo htmlspecialchars($item['sqft']); ?> Sqft</span>
                            </div>
                        </div>
                        
                        <div class="listing-card-footer">
                            <div class="listing-price-box">
                                <span class="price-lbl">Investment Value</span>
                                <span class="price-tag"><?php echo htmlspecialchars($item['price']); ?></span>
                            </div>
                            <a href="property-details.php?property=<?php echo htmlspecialchars($item['slug']); ?>" class="listing-action-btn" aria-label="View Project Details">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" width="16" height="16"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    var $cards = $('.listing-item-card');
    
    function applyFilters() {
        var keyword = $('#filter-search').val().toLowerCase().trim();
        var minBeds = parseInt($('#filter-beds').val()) || 0;
        var minBaths = parseInt($('#filter-baths').val()) || 0;
        
        var visibleCount = 0;
        var totalCount = $cards.length;
        
        $cards.each(function() {
            var $card = $(this);
            var title = $card.data('title');
            var location = $card.data('location');
            var beds = parseInt($card.data('beds')) || 0;
            var baths = parseInt($card.data('baths')) || 0;
            
            var matchesKeyword = title.indexOf(keyword) !== -1 || location.indexOf(keyword) !== -1;
            var matchesBeds = beds >= minBeds;
            var matchesBaths = baths >= minBaths;
            
            if (matchesKeyword && matchesBeds && matchesBaths) {
                $card.show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });
        
        // Show/hide clear all button
        if (keyword !== '' || minBeds !== 0 || minBaths !== 0) {
            $('#btn-clear-all').fadeIn(200);
        } else {
            $('#btn-clear-all').fadeOut(200);
        }
        
        // Update counters
        $('#visible-count').text(visibleCount);
        $('#total-count').text(totalCount);
        
        if (visibleCount === 0) {
            $('#empty-state').show();
            $('#properties-grid-container').css('display', 'block');
        } else {
            $('#empty-state').hide();
            $('#properties-grid-container').css('display', 'grid');
        }
        
        // Apply sorting
        applySorting();
    }
    
    function applySorting() {
        var sortValue = $('#filter-sort').val();
        var $grid = $('#properties-grid-container');
        
        if (sortValue === 'price-asc') {
            var $sorted = $cards.filter(':visible').sort(function(a, b) {
                return ($(a).data('price') - $(b).data('price'));
            });
            $sorted.detach().appendTo($grid);
        } else if (sortValue === 'price-desc') {
            var $sorted = $cards.filter(':visible').sort(function(a, b) {
                return ($(b).data('price') - $(a).data('price'));
            });
            $sorted.detach().appendTo($grid);
        } else if (sortValue === 'default') {
            var $sorted = $cards.sort(function(a, b) {
                return ($(b).data('id') - $(a).data('id'));
            });
            $sorted.detach().appendTo($grid);
        }
    }
    
    // Bind events
    $('#filter-search').on('input', applyFilters);
    $('#filter-beds, #filter-baths, #filter-sort').on('change', applyFilters);
    
    // Reset buttons
    $('#btn-reset-filters, #btn-clear-all').on('click', function(e) {
        e.preventDefault();
        $('#filter-search').val('');
        $('#filter-beds').val('');
        $('#filter-baths').val('');
        $('#filter-sort').val('default');
        applyFilters();
    });
    
    // Initialize count
    applyFilters();
});
</script>

<?php
// Load footer include
require_once __DIR__ . '/includes/footer.php';
?>
