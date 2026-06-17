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
:root {
    --bg-dark-gradient: linear-gradient(135deg, #0a0f12 0%, #121d22 100%);
    --card-bg-dark: #121e21;
    --border-gold-soft: rgba(229, 186, 115, 0.12);
    --border-accent: #e5ba73;
}

.properties-page {
    background-color: #0b1114;
    color: #e0e0e0;
    padding-bottom: 80px;
}

/* Page Header */
.listings-hero {
    padding: 140px 0 50px;
    background: var(--bg-dark-gradient);
    text-align: center;
    border-bottom: 1px solid var(--border-gold-soft);
}
.listings-hero h1 {
    font-size: clamp(2.2rem, 5vw, 3.2rem);
    color: #ffffff;
    font-weight: 800;
    margin-bottom: 15px;
    font-family: var(--font-primary);
}
.listings-hero p {
    color: var(--color-text-muted);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

/* Filter Bar */
.filter-bar-container {
    background-color: var(--card-bg-dark);
    border: 1px solid var(--border-gold-soft);
    padding: 25px;
    border-radius: 12px;
    margin: -30px auto 50px;
    max-width: 1100px;
    position: relative;
    z-index: 10;
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
}
.filter-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 20px;
    align-items: end;
}
@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    .filter-bar-container {
        margin: -20px 15px 40px;
    }
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.filter-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--color-text-muted);
    letter-spacing: 1.5px;
}
.filter-input, .filter-select {
    width: 100%;
    padding: 12px 15px;
    background-color: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--color-border-dark);
    border-radius: 6px;
    color: #ffffff;
    font-size: 0.9rem;
    transition: var(--transition-normal);
}
.filter-input:focus, .filter-select:focus {
    border-color: var(--border-accent);
    box-shadow: 0 0 8px rgba(229, 186, 115, 0.15);
    outline: none;
    background-color: rgba(255, 255, 255, 0.05);
}
.filter-select option {
    background-color: var(--card-bg-dark);
    color: #ffffff;
}

/* Properties Grid */
.properties-listings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}
@media (max-width: 480px) {
    .properties-listings-grid {
        grid-template-columns: 1fr;
    }
}

/* Listing Card */
.listing-item-card {
    background-color: var(--card-bg-dark);
    border: 1px solid var(--border-gold-soft);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}
.listing-item-card:hover {
    transform: translateY(-5px);
    border-color: rgba(229, 186, 115, 0.3);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3);
}
.listing-img-box {
    position: relative;
    height: 240px;
    overflow: hidden;
    background: #0a0f12;
}
.listing-img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.listing-item-card:hover .listing-img-box img {
    transform: scale(1.05);
}
.listing-tag {
    position: absolute;
    top: 15px;
    left: 15px;
    background-color: rgba(18, 30, 33, 0.85);
    color: var(--border-accent);
    border: 1px solid var(--border-gold-soft);
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    backdrop-filter: blur(4px);
    z-index: 2;
}
.listing-details-box {
    padding: 25px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.listing-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 10px;
    font-family: var(--font-primary);
}
.listing-location {
    font-size: 0.85rem;
    color: var(--color-text-muted);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    line-height: 1.4;
}
.listing-location svg {
    color: var(--border-accent);
    flex-shrink: 0;
}

/* Spec Row */
.listing-specs-row {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid rgba(229, 186, 115, 0.08);
    border-bottom: 1px solid rgba(229, 186, 115, 0.08);
    padding: 15px 0;
    margin-bottom: 20px;
}
.listing-spec-tile {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--color-text-muted);
}
.listing-spec-tile svg {
    color: var(--border-accent);
}

/* Card Bottom Footer */
.listing-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
}
.listing-price-box {
    display: flex;
    flex-direction: column;
}
.price-tag {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--border-accent);
}
.price-lbl {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: var(--color-text-muted);
    letter-spacing: 1px;
}
.listing-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid var(--border-gold-soft);
    color: #ffffff;
    transition: all 0.3s ease;
}
.listing-item-card:hover .listing-action-btn {
    background-color: var(--border-accent);
    color: #0b1114;
    border-color: var(--border-accent);
}

/* Empty search state */
.empty-listings-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px 0;
    display: none;
}
.empty-listings-state i, .empty-listings-state svg {
    color: var(--border-accent);
    margin-bottom: 15px;
    width: 48px;
    height: 48px;
}
</style>

<main class="properties-page">
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
                    <input type="text" id="filter-search" class="filter-input" placeholder="e.g. Omaxe World Street, Solar Villa...">
                </div>

                <!-- Beds Filter -->
                <div class="filter-group">
                    <label class="filter-label">Min Bedrooms</label>
                    <select id="filter-beds" class="filter-select">
                        <option value="">Any Beds</option>
                        <option value="4">4+ Beds</option>
                        <option value="5">5+ Beds</option>
                        <option value="6">6+ Beds</option>
                    </select>
                </div>

                <!-- Price Sorting -->
                <div class="filter-group">
                    <label class="filter-label">Sort By Price</label>
                    <select id="filter-sort" class="filter-select">
                        <option value="default">Default Sort</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                    </select>
                </div>
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
                <p class="text-muted mt-2">We couldn't find any properties matching your search criteria. Please try different filters.</p>
            </div>

            <?php foreach ($projectsList as $item): ?>
                <?php 
                $resolvedImg = strpos($item['image'], 'assets/') === false ? $item['image'] : $item['image']; 
                ?>
                <!-- Property Card -->
                <div class="listing-item-card" data-title="<?php echo htmlspecialchars(strtolower($item['title'])); ?>" data-location="<?php echo htmlspecialchars(strtolower($item['location'])); ?>" data-beds="<?php echo (int)$item['beds']; ?>" data-price="<?php echo (int)$item['raw_price']; ?>">
                    <div class="listing-img-box">
                        <span class="listing-tag"><?php echo htmlspecialchars($item['tag']); ?></span>
                        <img src="<?php echo htmlspecialchars($resolvedImg); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    </div>
                    
                    <div class="listing-details-box">
                        <h3 class="listing-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="listing-location">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?php echo htmlspecialchars($item['location']); ?>
                        </p>
                        
                        <div class="listing-specs-row">
                            <div class="listing-spec-tile" title="Bedrooms">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/><path d="M18 14H6M18 10H6"/></svg>
                                <span><?php echo htmlspecialchars($item['beds']); ?> Beds</span>
                            </div>
                            <div class="listing-spec-tile" title="Bathrooms">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bath"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.3 3 3 4.3 3 5.5v13a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5V14"/><path d="M3 12h18"/><path d="M21 9.5a2.5 2.5 0 0 0-5 0v3h5v-3Z"/></svg>
                                <span><?php echo htmlspecialchars($item['baths']); ?> Baths</span>
                            </div>
                            <div class="listing-spec-tile" title="Area size">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-maximize-2"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                                <span><?php echo htmlspecialchars($item['sqft']); ?> Sqft</span>
                            </div>
                        </div>
                        
                        <div class="listing-card-footer">
                            <div class="listing-price-box">
                                <span class="price-lbl">Investment Value</span>
                                <span class="price-tag"><?php echo htmlspecialchars($item['price']); ?></span>
                            </div>
                            <a href="property-details.php?property=<?php echo htmlspecialchars($item['slug']); ?>" class="listing-action-btn" aria-label="View Project Details">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
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
    
    // Live client-side search and bed filter
    function applyFilters() {
        var keyword = $('#filter-search').val().toLowerCase().trim();
        var minBeds = parseInt($('#filter-beds').val()) || 0;
        
        var visibleCount = 0;
        
        $cards.each(function() {
            var $card = $(this);
            var title = $card.data('title');
            var location = $card.data('location');
            var beds = parseInt($card.data('beds')) || 0;
            
            var matchesKeyword = title.indexOf(keyword) !== -1 || location.indexOf(keyword) !== -1;
            var matchesBeds = beds >= minBeds;
            
            if (matchesKeyword && matchesBeds) {
                $card.show();
                visibleCount++;
            } else {
                $card.hide();
            }
        });
        
        if (visibleCount === 0) {
            $('#empty-state').show();
        } else {
            $('#empty-state').hide();
        }
    }
    
    // Price sorting
    function applySorting() {
        var sortValue = $('#filter-sort').val();
        var $grid = $('#properties-grid-container');
        var $visibleCards = $('.listing-item-card:visible');
        
        if (sortValue === 'price-asc') {
            $visibleCards.sort(function(a, b) {
                return ($(a).data('price') - $(b).data('price'));
            });
        } else if (sortValue === 'price-desc') {
            $visibleCards.sort(function(a, b) {
                return ($(b).data('price') - $(a).data('price'));
            });
        }
        
        // Re-append sorted cards to the DOM container
        if (sortValue !== 'default') {
            $visibleCards.detach().appendTo($grid);
        }
    }
    
    // Bind change/input events
    $('#filter-search, #filter-beds').on('input change', function() {
        applyFilters();
        applySorting();
    });
    
    $('#filter-sort').on('change', function() {
        applySorting();
    });
});
</script>

<?php
// Load footer include
require_once __DIR__ . '/includes/footer.php';
?>
