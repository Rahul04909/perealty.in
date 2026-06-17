<?php
/**
 * Dynamic XML Sitemap Generator
 * Prime Edge Realty
 */

// Set content type to XML
header('Content-Type: application/xml; charset=utf-8');

// Load Central Config
require_once __DIR__ . '/config.php';

// Determine base URL dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$baseUrl = $protocol . '://' . $host . '/peprealty.com/';
// Normalize trailing slash
$baseUrl = preg_replace('/([^:])(\/{2,})/', '$1/', $baseUrl);
if (substr($baseUrl, -1) !== '/') {
    $baseUrl .= '/';
}

// Fetch all projects from database
$projects = [];
try {
    $db = db();
    $stmt = $db->query("SELECT `slug`, `updated_at` FROM `projects` ORDER BY `id` DESC");
    $projects = $stmt->fetchAll();
} catch (\Exception $e) {
    // If database connection fails, fall back to mock slugs
    $projects = [
        ['slug' => 'eco-solar', 'updated_at' => date('Y-m-d H:i:s')],
        ['slug' => 'cubic-glass', 'updated_at' => date('Y-m-d H:i:s')],
        ['slug' => 'contemporary-mansion', 'updated_at' => date('Y-m-d H:i:s')]
    ];
}

// Print XML declaration
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Homepage -->
    <url>
        <loc><?php echo htmlspecialchars($baseUrl); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- About Us Page -->
    <url>
        <loc><?php echo htmlspecialchars($baseUrl . 'about.php'); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Contact Page -->
    <url>
        <loc><?php echo htmlspecialchars($baseUrl . 'contact.php'); ?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Privacy Policy -->
    <url>
        <loc><?php echo htmlspecialchars($baseUrl . 'privacy-policy.php'); ?></loc>
        <lastmod>2026-06-17</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <!-- Terms & Conditions -->
    <url>
        <loc><?php echo htmlspecialchars($baseUrl . 'terms.php'); ?></loc>
        <lastmod>2026-06-17</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>

    <!-- Dynamic Property Pages -->
    <?php foreach ($projects as $proj): ?>
        <?php 
        $lastmod = !empty($proj['updated_at']) ? date('Y-m-d', strtotime($proj['updated_at'])) : date('Y-m-d');
        $propUrl = $baseUrl . 'property-details.php?property=' . urlencode($proj['slug']);
        ?>
        <url>
            <loc><?php echo htmlspecialchars($propUrl); ?></loc>
            <lastmod><?php echo htmlspecialchars($lastmod); ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    <?php endforeach; ?>
</urlset>
