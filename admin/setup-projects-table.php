<?php
/**
 * Standalone Projects Table Installer & Seeder
 * Prime Edge Realiity
 */

require_once dirname(__DIR__) . '/config.php';

// Access check: Only logged in admins can run this setup if admins exist. 
// If database is empty, allow setup directly.
try {
    $db = db();
    $stmtCount = $db->query("SELECT COUNT(*) FROM `admins`");
    $hasAdmins = $stmtCount->fetchColumn() > 0;
} catch (\Exception $e) {
    // If database connection fails, let it show
    die("Database Connection Error: " . htmlspecialchars($e->getMessage()));
}

if ($hasAdmins && php_sapi_name() !== 'cli') {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        die("Access Denied: Please log in as an administrator to run this setup script.");
    }
}

try {
    // 1. Create Projects Table
    $db->exec("CREATE TABLE IF NOT EXISTS `projects` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `slug` VARCHAR(100) NOT NULL UNIQUE,
        `title` VARCHAR(255) NOT NULL,
        `location` VARCHAR(255) NOT NULL,
        `price` VARCHAR(100) NOT NULL,
        `raw_price` BIGINT NOT NULL,
        `beds` INT NOT NULL,
        `baths` INT NOT NULL,
        `sqft` VARCHAR(50) NOT NULL,
        `garages` INT NOT NULL,
        `year` INT NOT NULL,
        `image` VARCHAR(255) NOT NULL,
        `gallery` TEXT NOT NULL,
        `desc` TEXT NOT NULL,
        `tag` VARCHAR(100) NOT NULL,
        `google_map` TEXT DEFAULT NULL,
        `proximity_distances` TEXT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    echo "<h3>Projects table created or verified successfully!</h3>";

    // 2. Check if table is empty
    $stmtCountProj = $db->query("SELECT COUNT(*) FROM `projects`");
    if ($stmtCountProj->fetchColumn() == 0) {
        // Mock properties definitions
        $mockProjects = [
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
                'gallery' => json_encode([
                    'assets/images/about_house_one.png',
                    'assets/images/about_house_two.png',
                    'assets/images/hero_house.png'
                ]),
                'desc' => '<p>Experience the absolute pinnacle of sustainable luxury living. The <strong>Eco-Solar Villa</strong> is designed for forward-thinking homeowners who value modern aesthetics and green engineering.</p><p>Featuring advanced rooftop solar arrays, automated climate control, greywater filtration, and floor-to-ceiling double-glazed glass panels, this residence delivers structural integrity while generating its own power. Designed with a custom open-floor layout, the living quarters merge seamlessly with the outdoor deck and heated infinity pool.</p>',
                'tag' => 'Featured / Eco-Friendly',
                'google_map' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3513.829037748805!2d77.34863377615967!3d28.334185797380126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cdc171d3bb161%3A0xe54e38c92a6c8e37!2sOmaxe%20World%20Street!5e0!3m2!1sen!2sin!4v1718617596000!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'proximity_distances' => json_encode([
                    ['name' => 'Delhi Public School', 'distance' => '1.2 km', 'icon' => 'school'],
                    ['name' => 'Omaxe World Street Mall', 'distance' => '0.5 km', 'icon' => 'mall'],
                    ['name' => 'Fortis Hospital', 'distance' => '3.4 km', 'icon' => 'hospital'],
                    ['name' => 'IGI Airport', 'distance' => '42 km', 'icon' => 'airport']
                ])
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
                'gallery' => json_encode([
                    'assets/images/hero_house.png',
                    'assets/images/about_house_one.png',
                    'assets/images/promo_apartment.png'
                ]),
                'desc' => '<p>An architectural marvel showcasing cubic geometries and premium concrete constructs. The <strong>Cubic Glass Manor</strong> offers expansive panoramic vistas through high-durability floor-to-ceiling glass screens.</p><p>Fully automated smart-home systems control illumination, security, climate, and sound. Includes private spa facilities, a dedicated gym room, automated double garage, and beautifully manicured Zen gardens.</p>',
                'tag' => 'Featured / Architectural',
                'google_map' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3513.829037748805!2d77.34863377615967!3d28.334185797380126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cdc171d3bb161%3A0xe54e38c92a6c8e37!2sOmaxe%20World%20Street!5e0!3m2!1sen!2sin!4v1718617596000!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'proximity_distances' => json_encode([
                    ['name' => 'Delhi Public School', 'distance' => '1.5 km', 'icon' => 'school'],
                    ['name' => 'Omaxe World Street Mall', 'distance' => '0.2 km', 'icon' => 'mall'],
                    ['name' => 'Fortis Hospital', 'distance' => '3.1 km', 'icon' => 'hospital'],
                    ['name' => 'IGI Airport', 'distance' => '41.8 km', 'icon' => 'airport']
                ])
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
                'gallery' => json_encode([
                    'assets/images/promo_apartment.png',
                    'assets/images/about_house_two.png',
                    'assets/images/project_two.png'
                ]),
                'desc' => '<p>Indulge in unmatched grandeur. The <strong>Contemporary Mansion</strong> offers a massive residential footprint featuring custom marble flooring, double-height grand salons, a master wing with a walk-in wardrobe, and commercial-grade chef kitchens.</p><p>Outdoor recreation spaces include an olympic-sized swimming pool, custom fire pit lounges, and state-of-the-art security networks.</p>',
                'tag' => 'Premium Elite',
                'google_map' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3513.829037748805!2d77.34863377615967!3d28.334185797380126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cdc171d3bb161%3A0xe54e38c92a6c8e37!2sOmaxe%20World%20Street!5e0!3m2!1sen!2sin!4v1718617596000!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'proximity_distances' => json_encode([
                    ['name' => 'Delhi Public School', 'distance' => '2.0 km', 'icon' => 'school'],
                    ['name' => 'Omaxe World Street Mall', 'distance' => '0.8 km', 'icon' => 'mall'],
                    ['name' => 'Fortis Hospital', 'distance' => '2.5 km', 'icon' => 'hospital'],
                    ['name' => 'IGI Airport', 'distance' => '42.5 km', 'icon' => 'airport']
                ])
            ]
        ];

        $insert = $db->prepare("INSERT INTO `projects` 
            (`slug`, `title`, `location`, `price`, `raw_price`, `beds`, `baths`, `sqft`, `garages`, `year`, `image`, `gallery`, `desc`, `tag`, `google_map`, `proximity_distances`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        foreach ($mockProjects as $proj) {
            $insert->execute([
                $proj['slug'],
                $proj['title'],
                $proj['location'],
                $proj['price'],
                $proj['raw_price'],
                $proj['beds'],
                $proj['baths'],
                $proj['sqft'],
                $proj['garages'],
                $proj['year'],
                $proj['image'],
                $proj['gallery'],
                $proj['desc'],
                $proj['tag'],
                $proj['google_map'],
                $proj['proximity_distances']
            ]);
        }
        echo "<h3>Default projects seeded successfully!</h3>";
    } else {
        echo "<h3>Projects table already contains records. Seeding skipped.</h3>";
    }
    
    echo "<p><a href='./projects.php'>Go to Projects Management Panel</a></p>";
} catch (\PDOException $e) {
    die("Database SQL Error: " . htmlspecialchars($e->getMessage()));
}
