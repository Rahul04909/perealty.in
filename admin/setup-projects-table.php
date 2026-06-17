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
    // 1. Create Projects Table (with SEO and Floor Plan fields)
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
        `seo_title` VARCHAR(255) DEFAULT NULL,
        `seo_desc` TEXT DEFAULT NULL,
        `seo_keywords` VARCHAR(255) DEFAULT NULL,
        `floor_plans` TEXT DEFAULT NULL,
        `google_map` TEXT DEFAULT NULL,
        `proximity_distances` TEXT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    echo "<h3>Projects table created or verified successfully!</h3>";

    // Check and add missing columns dynamically (for pre-existing databases)
    $columns = $db->query("DESCRIBE `projects`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('seo_title', $columns)) {
        $db->exec("ALTER TABLE `projects` ADD `seo_title` VARCHAR(255) DEFAULT NULL AFTER `tag`");
    }
    if (!in_array('seo_desc', $columns)) {
        $db->exec("ALTER TABLE `projects` ADD `seo_desc` TEXT DEFAULT NULL AFTER `seo_title`");
    }
    if (!in_array('seo_keywords', $columns)) {
        $db->exec("ALTER TABLE `projects` ADD `seo_keywords` VARCHAR(255) DEFAULT NULL AFTER `seo_desc`");
    }
    if (!in_array('floor_plans', $columns)) {
        $db->exec("ALTER TABLE `projects` ADD `floor_plans` TEXT DEFAULT NULL AFTER `seo_keywords`");
    }
    echo "<h3>Projects table columns verified/migrated!</h3>";

    // 2. Create Enquiries Table
    $db->exec("CREATE TABLE IF NOT EXISTS `enquiries` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `project_id` INT DEFAULT NULL,
        `project_title` VARCHAR(255) DEFAULT NULL,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `phone` VARCHAR(50) NOT NULL,
        `message` TEXT NOT NULL,
        `status` VARCHAR(50) DEFAULT 'New',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "<h3>Enquiries table created or verified successfully!</h3>";

    // 3. Setup mock projects data structure
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
            'seo_title' => 'Eco-Solar Villa | Luxury Sustainable Living Faridabad',
            'seo_desc' => 'Discover Eco-Solar Villa in Sector 79 Faridabad. Features advanced rooftop solar arrays, automated green climate controls, heated pools, and open layouts.',
            'seo_keywords' => 'eco solar villa, eco friendly home, luxury green villa faridabad',
            'floor_plans' => json_encode([
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
            ]),
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
            'seo_title' => 'Cubic Glass Manor | Contemporary Glass Villa Faridabad',
            'seo_desc' => 'Explore the geometric Cubic Glass Manor. Custom glass screens, private spa, automated smart systems, and gym at Omaxe World Street Phase II.',
            'seo_keywords' => 'cubic glass manor, glass residence, smart home villa faridabad',
            'floor_plans' => json_encode([
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
            ]),
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
            'seo_title' => 'Contemporary Mansion | Elite Luxury Estate Faridabad',
            'seo_desc' => 'Live in peak luxury at the Contemporary Mansion. Double-height salons, grand master wings, custom marble, and olympic swimming pools.',
            'seo_keywords' => 'luxury mansion faridabad, elite estate, contemporary villa',
            'floor_plans' => json_encode([
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
            ]),
            'google_map' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3513.829037748805!2d77.34863377615967!3d28.334185797380126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cdc171d3bb161%3A0xe54e38c92a6c8e37!2sOmaxe%20World%20Street!5e0!3m2!1sen!2sin!4v1718617596000!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'proximity_distances' => json_encode([
                ['name' => 'Delhi Public School', 'distance' => '2.0 km', 'icon' => 'school'],
                ['name' => 'Omaxe World Street Mall', 'distance' => '0.8 km', 'icon' => 'mall'],
                ['name' => 'Fortis Hospital', 'distance' => '2.5 km', 'icon' => 'hospital'],
                ['name' => 'IGI Airport', 'distance' => '42.5 km', 'icon' => 'airport']
            ])
        ]
    ];

    // Check if table is empty
    $stmtCountProj = $db->query("SELECT COUNT(*) FROM `projects`");
    if ($stmtCountProj->fetchColumn() == 0) {
        $insert = $db->prepare("INSERT INTO `projects` 
            (`slug`, `title`, `location`, `price`, `raw_price`, `beds`, `baths`, `sqft`, `garages`, `year`, `image`, `gallery`, `desc`, `tag`, `seo_title`, `seo_desc`, `seo_keywords`, `floor_plans`, `google_map`, `proximity_distances`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
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
                $proj['seo_title'],
                $proj['seo_desc'],
                $proj['seo_keywords'],
                $proj['floor_plans'],
                $proj['google_map'],
                $proj['proximity_distances']
            ]);
        }
        echo "<h3>Default projects seeded successfully!</h3>";
    } else {
        // Automatically update existing mock rows with the new SEO and floor plan data
        $update = $db->prepare("UPDATE `projects` SET 
            `seo_title` = ?, 
            `seo_desc` = ?, 
            `seo_keywords` = ?, 
            `floor_plans` = ? 
            WHERE `slug` = ?");
            
        foreach ($mockProjects as $proj) {
            $update->execute([
                $proj['seo_title'],
                $proj['seo_desc'],
                $proj['seo_keywords'],
                $proj['floor_plans'],
                $proj['slug']
            ]);
        }
        echo "<h3>Existing projects updated with SEO and Floor Plan defaults!</h3>";
    }
    
    echo "<p><a href='./projects.php'>Go to Projects Management Panel</a></p>";
} catch (\PDOException $e) {
    die("Database SQL Error: " . htmlspecialchars($e->getMessage()));
}
