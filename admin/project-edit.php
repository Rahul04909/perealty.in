<?php
/**
 * Admin Edit Project Page
 * Prime Edge Realiity
 */

// Load Header (which verifies authentication)
include './header.php';

// Generate CSRF Token
if (empty($_SESSION['project_edit_csrf_token'])) {
    $_SESSION['project_edit_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';
$proj = null;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: projects.php');
    exit;
}

try {
    $db = db();
    $stmt = $db->prepare("SELECT * FROM `projects` WHERE `id` = ?");
    $stmt->execute([$id]);
    $proj = $stmt->fetch();
    if (!$proj) {
        $_SESSION['projects_error'] = 'Project not found.';
        header('Location: projects.php');
        exit;
    }
} catch (\Exception $e) {
    $error = 'Failed to load project: ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $proj) {
    // Validate CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['project_edit_csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid security token validation.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $raw_price = (int)($_POST['raw_price'] ?? 0);
        $beds = (int)($_POST['beds'] ?? 0);
        $baths = (int)($_POST['baths'] ?? 0);
        $sqft = trim($_POST['sqft'] ?? '');
        $garages = (int)($_POST['garages'] ?? 0);
        $year = (int)($_POST['year'] ?? date('Y'));
        $tag = trim($_POST['tag'] ?? '');
        $desc = $_POST['desc'] ?? '';
        $google_map = $_POST['google_map'] ?? '';
        
        // SEO Fields
        $seo_title = trim($_POST['seo_title'] ?? '');
        $seo_desc = trim($_POST['seo_desc'] ?? '');
        $seo_keywords = trim($_POST['seo_keywords'] ?? '');
        
        // Validation
        if (empty($title) || empty($slug) || empty($location) || empty($price) || empty($raw_price) || empty($desc)) {
            $error = 'Please fill out all required fields (Title, Slug, Location, Price, Raw Price, Description).';
        } else {
            try {
                // Check if slug is unique (excluding current project)
                $stmtSlug = $db->prepare("SELECT COUNT(*) FROM `projects` WHERE `slug` = ? AND `id` != ?");
                $stmtSlug->execute([$slug, $id]);
                if ($stmtSlug->fetchColumn() > 0) {
                    $error = 'The project URL slug is already taken by another project. Please choose another title/slug.';
                }
                
                // Handle Main Image Update
                $imagePath = $proj['image'];
                if (empty($error) && !empty($_FILES['image']['name'])) {
                    $file = $_FILES['image'];
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION) ?? '');
                    
                    if (!in_array($ext, $allowedExts)) {
                        $error = 'Invalid main image format. Allowed: JPG, JPEG, PNG, GIF, WEBP.';
                    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                        $error = 'Error occurred during main image upload.';
                    } else {
                        $mainPicName = 'proj_' . uniqid('', true) . '.' . $ext;
                        $uploadDir = dirname(__DIR__) . '/uploads/projects/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $mainPicName)) {
                            // Unlink old custom main image if exists
                            if ($proj['image'] && strpos($proj['image'], 'assets/images/') === false && file_exists(dirname(__DIR__) . '/' . $proj['image'])) {
                                @unlink(dirname(__DIR__) . '/' . $proj['image']);
                            }
                            $imagePath = 'uploads/projects/' . $mainPicName;
                        } else {
                            $error = 'Failed to save the main project photo.';
                        }
                    }
                }
                
                // Handle Gallery Updates
                $gallery = json_decode($proj['gallery'] ?? '[]', true);
                if (!is_array($gallery)) {
                    $gallery = [];
                }
                
                // Process deleted gallery items
                if (empty($error) && isset($_POST['delete_gallery_images']) && is_array($_POST['delete_gallery_images'])) {
                    foreach ($_POST['delete_gallery_images'] as $delImg) {
                        $index = array_search($delImg, $gallery);
                        if ($index !== false) {
                            unset($gallery[$index]);
                            // Unlink if it is a custom uploaded image
                            if ($delImg && strpos($delImg, 'assets/images/') === false && file_exists(dirname(__DIR__) . '/' . $delImg)) {
                                @unlink(dirname(__DIR__) . '/' . $delImg);
                            }
                        }
                    }
                    // Reindex gallery array
                    $gallery = array_values($gallery);
                }
                
                // Process newly uploaded gallery items
                if (empty($error) && !empty($_FILES['gallery']['name'][0])) {
                    $files = $_FILES['gallery'];
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $uploadDir = dirname(__DIR__) . '/uploads/projects/';
                    
                    for ($i = 0; $i < count($files['name']); $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION) ?? '');
                            if (in_array($ext, $allowedExts)) {
                                $galName = 'gallery_' . uniqid('', true) . '_' . $i . '.' . $ext;
                                if (move_uploaded_file($files['tmp_name'][$i], $uploadDir . $galName)) {
                                    $gallery[] = 'uploads/projects/' . $galName;
                                }
                            }
                        }
                    }
                }
                
                // Handle Floor Plans Updates
                $floorPlans = [];
                if (empty($error) && !empty($_POST['floor_plan_title'])) {
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $uploadDir = dirname(__DIR__) . '/uploads/projects/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    for ($k = 0; $k < count($_POST['floor_plan_title']); $k++) {
                        $pTitle = trim($_POST['floor_plan_title'][$k]);
                        $pDesc = trim($_POST['floor_plan_desc'][$k]);
                        $existingImg = $_POST['existing_floor_plan_image'][$k] ?? '';
                        
                        $fpImagePath = $existingImg;
                        
                        // Check if a new file is uploaded for this plan
                        if (!empty($_FILES['floor_plan_image']['name'][$k])) {
                            if ($_FILES['floor_plan_image']['error'][$k] === UPLOAD_ERR_OK) {
                                $ext = strtolower(pathinfo($_FILES['floor_plan_image']['name'][$k], PATHINFO_EXTENSION) ?? '');
                                if (in_array($ext, $allowedExts)) {
                                    $fpName = 'floor_plan_' . uniqid('', true) . '_' . $k . '.' . $ext;
                                    if (move_uploaded_file($_FILES['floor_plan_image']['tmp_name'][$k], $uploadDir . $fpName)) {
                                        // Unlink old floor plan image if custom
                                        if ($existingImg && strpos($existingImg, 'assets/') === false && file_exists(dirname(__DIR__) . '/' . $existingImg)) {
                                            @unlink(dirname(__DIR__) . '/' . $existingImg);
                                        }
                                        $fpImagePath = 'uploads/projects/' . $fpName;
                                    }
                                }
                            }
                        }
                        
                        if (!empty($pTitle)) {
                            $floorPlans[] = [
                                'title' => $pTitle,
                                'desc' => $pDesc,
                                'image' => $fpImagePath
                            ];
                        }
                    }
                }
                
                // Housekeeping for deleted floor plans images
                $oldFloorPlans = json_decode($proj['floor_plans'] ?? '[]', true);
                if (is_array($oldFloorPlans)) {
                    foreach ($oldFloorPlans as $oldPlan) {
                        $oldImg = $oldPlan['image'] ?? '';
                        if ($oldImg && strpos($oldImg, 'assets/') === false) {
                            // Check if this image is still in the new floor plans list
                            $stillExists = false;
                            foreach ($floorPlans as $newPlan) {
                                if ($newPlan['image'] === $oldImg) {
                                    $stillExists = true;
                                    break;
                                }
                            }
                            if (!$stillExists && file_exists(dirname(__DIR__) . '/' . $oldImg)) {
                                @unlink(dirname(__DIR__) . '/' . $oldImg);
                            }
                        }
                    }
                }
                
                // Process Proximity Distances
                $proximity = [];
                if (empty($error) && !empty($_POST['proximity_name'])) {
                    for ($j = 0; $j < count($_POST['proximity_name']); $j++) {
                        $pName = trim($_POST['proximity_name'][$j]);
                        $pDist = trim($_POST['proximity_distance'][$j]);
                        $pIcon = trim($_POST['proximity_icon'][$j]);
                        
                        if (!empty($pName) && !empty($pDist)) {
                            $proximity[] = [
                                'name' => $pName,
                                'distance' => $pDist,
                                'icon' => $pIcon
                            ];
                        }
                    }
                }
                
                // Process Amenities
                $amenities = [];
                if (empty($error) && !empty($_POST['amenities'])) {
                    foreach ($_POST['amenities'] as $am) {
                        $trimmed = trim($am);
                        if ($trimmed !== '') {
                            $amenities[] = $trimmed;
                        }
                    }
                }
                
                // Update project in DB
                if (empty($error)) {
                    $update = $db->prepare("UPDATE `projects` SET 
                        `slug` = ?, 
                        `title` = ?, 
                        `location` = ?, 
                        `price` = ?, 
                        `raw_price` = ?, 
                        `beds` = ?, 
                        `baths` = ?, 
                        `sqft` = ?, 
                        `garages` = ?, 
                        `year` = ?, 
                        `image` = ?, 
                        `gallery` = ?, 
                        `desc` = ?, 
                        `tag` = ?, 
                        `seo_title` = ?, 
                        `seo_desc` = ?, 
                        `seo_keywords` = ?, 
                        `floor_plans` = ?, 
                        `amenities` = ?, 
                        `google_map` = ?, 
                        `proximity_distances` = ? 
                        WHERE `id` = ?");
                    
                    $update->execute([
                        $slug,
                        $title,
                        $location,
                        $price,
                        $raw_price,
                        $beds,
                        $baths,
                        $sqft,
                        $garages,
                        $year,
                        $imagePath,
                        json_encode($gallery),
                        $desc,
                        $tag,
                        $seo_title,
                        $seo_desc,
                        $seo_keywords,
                        json_encode($floorPlans),
                        json_encode($amenities),
                        $google_map,
                        json_encode($proximity),
                        $id
                    ]);
                    
                    $success = 'Project updated successfully!';
                    
                    // Refresh project details from DB
                    $stmt = $db->prepare("SELECT * FROM `projects` WHERE `id` = ?");
                    $stmt->execute([$id]);
                    $proj = $stmt->fetch();
                }
            } catch (\Exception $e) {
                $error = 'Database SQL Error: ' . $e->getMessage();
            }
        }
    }
}

// Prefill values
$title = $proj['title'] ?? '';
$slug = $proj['slug'] ?? '';
$location = $proj['location'] ?? '';
$price = $proj['price'] ?? '';
$raw_price = $proj['raw_price'] ?? 0;
$beds = $proj['beds'] ?? 0;
$baths = $proj['baths'] ?? 0;
$sqft = $proj['sqft'] ?? '';
$garages = $proj['garages'] ?? 0;
$year = $proj['year'] ?? date('Y');
$tag = $proj['tag'] ?? '';
$desc = $proj['desc'] ?? '';
$google_map = $proj['google_map'] ?? '';
$image = $proj['image'] ?? '';
$gallery = json_decode($proj['gallery'] ?? '[]', true);
$proximity = json_decode($proj['proximity_distances'] ?? '[]', true);
$seo_title = $proj['seo_title'] ?? '';
$seo_desc = $proj['seo_desc'] ?? '';
$seo_keywords = $proj['seo_keywords'] ?? '';
$floor_plans = json_decode($proj['floor_plans'] ?? '[]', true);
$amenities = json_decode($proj['amenities'] ?? '[]', true);
if (empty($amenities)) {
    $amenities = [
        "Solar System Integration",
        "Smart Home Automation",
        "Heated Infinity Pool",
        "Private Gym Facility",
        "Walk-In Closets",
        "Landscaped Zen Garden",
        "Security CCTV Grid",
        "High-End Italian Kitchen"
    ];
}

$page_title = "Edit Project";
?>

<!-- Load Trumbowyg CSS from CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css">
<!-- Custom Style overrides for Trumbowyg in Dark Theme -->
<style>
    .trumbowyg-box, .trumbowyg-editor {
        border-color: rgba(229, 186, 115, 0.15) !important;
        background-color: #f8f9fa !important;
        color: #495057 !important;
    }
    .trumbowyg-button-pane {
        background-color: #e9ecef !important;
        border-bottom-color: rgba(229, 186, 115, 0.15) !important;
    }
</style>

<div class="container-fluid text-dark">
    <!-- Alert Feedback -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 px-3 mb-4 d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2" style="margin-right: 10px;"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success py-2 px-3 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-2" style="margin-right: 10px;"></i>
            <span><?php echo htmlspecialchars($success); ?> <a href="projects.php" class="text-success font-weight-bold ml-2" style="text-decoration:underline;">Go to list</a></span>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-header bg-white" style="border-bottom: 1px solid #f0f0f1;">
                    <h3 class="card-title text-dark font-weight-bold" style="margin-top: 5px;"><i class="fas fa-edit text-success me-2" style="margin-right: 5px;"></i> Edit Property Project</h3>
                </div>
                
                <form action="project-edit.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['project_edit_csrf_token'] ?? ''); ?>">
                    
                    <div class="card-body">
                        <!-- Section 1: Basic Information -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-1"></i> Basic Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Project Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Eco-Solar Villa" value="<?php echo htmlspecialchars($title); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. eco-solar" value="<?php echo htmlspecialchars($slug); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label font-weight-bold text-muted">Location Address <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Sector 79, Faridabad" value="<?php echo htmlspecialchars($location); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold text-muted">Tag / Label <span class="text-danger">*</span></label>
                                <input type="text" name="tag" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Featured / Eco-Friendly" value="<?php echo htmlspecialchars($tag); ?>" required>
                            </div>
                        </div>

                        <!-- Section 2: Pricing & Metrics -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-calculator me-1"></i> Sizing & Pricing</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Formatted Price <span class="text-danger">*</span></label>
                                <input type="text" name="price" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. ₹4,85,00,000" value="<?php echo htmlspecialchars($price); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Raw Numeric Price (for Calculations) <span class="text-danger">*</span></label>
                                <input type="number" name="raw_price" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. 48500000" value="<?php echo htmlspecialchars($raw_price); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Beds Count</label>
                                <input type="number" name="beds" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($beds); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Baths Count</label>
                                <input type="number" name="baths" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($baths); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Garages Count</label>
                                <input type="number" name="garages" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($garages); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Build Year</label>
                                <input type="number" name="year" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($year); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Area size (Sqft) <span class="text-danger">*</span></label>
                                <input type="text" name="sqft" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. 4,200" value="<?php echo htmlspecialchars($sqft); ?>" required>
                            </div>
                        </div>

                        <!-- Section 3: Uploads & Description -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-file-upload me-1"></i> Images & Rich Text</h5>
                        
                        <!-- Main Image display & upload -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold text-muted">Featured Image</label>
                                <input type="file" name="image" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                                <small class="text-muted d-block mt-1">Leave empty to keep current featured image.</small>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <?php if (!empty($image)): ?>
                                    <div class="mt-2">
                                        <span class="d-block font-weight-bold text-muted" style="font-size:0.8rem;">Current Featured Image:</span>
                                        <?php 
                                        $resolvedImg = strpos($image, 'assets/') === false ? '../' . $image : '../' . $image;
                                        ?>
                                        <img src="<?php echo htmlspecialchars($resolvedImg); ?>" class="img-thumbnail" style="max-height: 80px; max-width: 150px; object-fit: cover;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Gallery display & upload -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Add More Gallery Slide Images</label>
                                <input type="file" name="gallery[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" multiple>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label font-weight-bold text-muted d-block">Manage Current Gallery Images (Check to Delete)</label>
                                <?php if (empty($gallery)): ?>
                                    <p class="text-muted" style="font-size:0.85rem;">No gallery images found.</p>
                                <?php else: ?>
                                    <div class="row g-2">
                                        <?php foreach ($gallery as $idx => $galFile): ?>
                                            <?php 
                                            $resolvedGal = strpos($galFile, 'assets/') === false ? '../' . $galFile : '../' . $galFile;
                                            ?>
                                            <div class="col-md-3 col-sm-6 mb-3 text-center">
                                                <div class="border rounded p-1" style="background:#f8f9fa;">
                                                    <img src="<?php echo htmlspecialchars($resolvedGal); ?>" class="img-thumbnail mb-2" style="height: 100px; width: 100%; object-fit: cover; border:none;">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input" type="checkbox" name="delete_gallery_images[]" value="<?php echo htmlspecialchars($galFile); ?>" id="del_gal_<?php echo $idx; ?>">
                                                        <label class="form-check-label text-danger font-weight-bold" for="del_gal_<?php echo $idx; ?>" style="font-size: 0.8rem; cursor: pointer;">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted">Project Description <span class="text-danger">*</span></label>
                            <textarea name="desc" class="form-control editor" rows="6"><?php echo htmlspecialchars($desc); ?></textarea>
                        </div>

                        <!-- Section 4: Floor Plans -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-layer-group me-1"></i> Floor Plans</h5>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted d-block">Manage Property Floor Plans</label>
                            
                            <div id="floor-plans-container">
                                <?php if (empty($floor_plans)): ?>
                                    <div class="floor-plan-row border rounded p-3 mb-3 bg-light position-relative">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Level / Title</label>
                                                <input type="text" name="floor_plan_title[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Ground Level Floor Plan">
                                                <input type="hidden" name="existing_floor_plan_image[]" value="">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Schematic Image</label>
                                                <input type="file" name="floor_plan_image[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Content Description</label>
                                                <textarea name="floor_plan_desc[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" rows="2" placeholder="Describe the layout room structure, dimensions, balconies, etc."></textarea>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-floor-plan" style="position: absolute; top: 10px; right: 10px; z-index:10;"><i class="fas fa-trash-alt"></i> Remove</button>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($floor_plans as $idx => $plan): ?>
                                        <div class="floor-plan-row border rounded p-3 mb-3 bg-light position-relative">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Level / Title</label>
                                                    <input type="text" name="floor_plan_title[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Ground Level Floor Plan" value="<?php echo htmlspecialchars($plan['title'] ?? ''); ?>">
                                                    <input type="hidden" name="existing_floor_plan_image[]" value="<?php echo htmlspecialchars($plan['image'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Schematic Image</label>
                                                    <input type="file" name="floor_plan_image[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                                                    <?php if (!empty($plan['image'])): ?>
                                                        <div class="mt-2">
                                                            <span class="d-block font-weight-bold text-muted" style="font-size:0.75rem;">Current Plan Schematic:</span>
                                                            <?php 
                                                            $resolvedFPImg = strpos($plan['image'], 'assets/') === false ? '../' . $plan['image'] : '../' . $plan['image'];
                                                            ?>
                                                            <img src="<?php echo htmlspecialchars($resolvedFPImg); ?>" class="img-thumbnail" style="max-height: 60px; max-width: 120px; object-fit: cover;">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Content Description</label>
                                                    <textarea name="floor_plan_desc[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" rows="2" placeholder="Describe the layout room structure, dimensions, balconies, etc."><?php echo htmlspecialchars($plan['desc'] ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-floor-plan" style="position: absolute; top: 10px; right: 10px; z-index:10;"><i class="fas fa-trash-alt"></i> Remove</button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" id="btn-add-floor-plan">
                                <i class="fas fa-plus-circle me-1"></i> Add Floor Plan Level
                            </button>
                        </div>

                        <!-- Section: Amenities -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-list-ul me-1"></i> Premium Amenities</h5>
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted d-block">Manage Property Amenities</label>
                            <div id="amenities-container">
                                <?php foreach ($amenities as $am): ?>
                                    <div class="row mb-2 amenity-row">
                                        <div class="col-md-11 col-10">
                                            <input type="text" name="amenities[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Solar System Integration" value="<?php echo htmlspecialchars($am); ?>">
                                        </div>
                                        <div class="col-md-1 col-2">
                                            <button type="button" class="btn btn-danger btn-remove-amenity w-100" style="height: 38px;"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" id="btn-add-amenity">
                                <i class="fas fa-plus-circle me-1"></i> Add Amenity
                            </button>
                        </div>

                        <!-- Section 5: Map & Proximity -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-map-marked-alt me-1"></i> Location Mapping & Proximity</h5>
                        
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-muted">Google Maps Iframe Embed Code</label>
                            <textarea name="google_map" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" rows="3" placeholder='Copy and paste the raw <iframe src="..."></iframe> embed code from Google Maps share button'><?php echo htmlspecialchars($google_map); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted d-block">Proximity Distances (e.g. Nearby Facilities)</label>
                            
                            <div id="proximity-container">
                                <!-- Dynamic rows added here -->
                                <?php if (empty($proximity)): ?>
                                    <div class="row mb-2 proximity-row">
                                        <div class="col-md-5">
                                            <input type="text" name="proximity_name[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Facility Name, e.g. Delhi Public School">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="proximity_distance[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Distance, e.g. 1.2 km">
                                        </div>
                                        <div class="col-md-3">
                                            <select name="proximity_icon[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                                                <option value="school">School / Graduation Cap</option>
                                                <option value="mall">Shopping Mall / Bag</option>
                                                <option value="hospital">Hospital / Heart Pulse</option>
                                                <option value="plane">Airport / Plane</option>
                                                <option value="location">Default Pin Icon</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-remove-proximity w-100" style="height: 38px;"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($proximity as $item): ?>
                                        <div class="row mb-2 proximity-row">
                                            <div class="col-md-5">
                                                <input type="text" name="proximity_name[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Facility Name, e.g. Delhi Public School" value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="proximity_distance[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Distance, e.g. 1.2 km" value="<?php echo htmlspecialchars($item['distance'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <select name="proximity_icon[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                                                    <option value="school" <?php echo ($item['icon'] ?? '') === 'school' ? 'selected' : ''; ?>>School / Graduation Cap</option>
                                                    <option value="mall" <?php echo ($item['icon'] ?? '') === 'mall' ? 'selected' : ''; ?>>Shopping Mall / Bag</option>
                                                    <option value="hospital" <?php echo ($item['icon'] ?? '') === 'hospital' ? 'selected' : ''; ?>>Hospital / Heart Pulse</option>
                                                    <option value="plane" <?php echo ($item['icon'] ?? '') === 'plane' || ($item['icon'] ?? '') === 'airport' ? 'selected' : ''; ?>>Airport / Plane</option>
                                                    <option value="location" <?php echo ($item['icon'] ?? '') === 'location' ? 'selected' : ''; ?>>Default Pin Icon</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-remove-proximity w-100" style="height: 38px;"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" id="btn-add-proximity">
                                <i class="fas fa-plus-circle me-1"></i> Add Proximity Item
                            </button>
                        </div>

                        <!-- Section 6: SEO Configurations -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-search me-1"></i> SEO Configurations</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">SEO Meta Title</label>
                                <input type="text" name="seo_title" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Custom meta title for search engines" value="<?php echo htmlspecialchars($seo_title ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">SEO Meta Keywords</label>
                                <input type="text" name="seo_keywords" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Comma-separated keywords" value="<?php echo htmlspecialchars($seo_keywords ?? ''); ?>">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label font-weight-bold text-muted">SEO Meta Description</label>
                                <textarea name="seo_desc" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" rows="3" placeholder="Enter a search-engine friendly summary snippet (150-160 characters)"><?php echo htmlspecialchars($seo_desc ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between pb-4 px-4">
                        <a href="projects.php" class="btn btn-secondary font-weight-bold px-4 d-flex align-items-center" style="height: 42px;">
                            <i class="fas fa-arrow-left me-1" style="margin-right: 5px;"></i> Back to List
                        </a>
                        <button type="submit" class="btn btn-success font-weight-bold px-4 d-flex align-items-center" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; height: 42px;">
                            <i class="fas fa-save me-1" style="margin-right: 5px;"></i> Update Project Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Load Trumbowyg Editor JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Initialize WYSIWYG Editor
    $('.editor').trumbowyg({
        btns: [
            ['viewHTML'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ]
    });
    
    // 2. Auto slug generation from title input
    $('#title').on('input', function() {
        var titleText = $(this).val();
        var slugText = titleText.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // remove invalid characters
            .replace(/\s+/g, '-')        // replace spaces with hyphens
            .replace(/-+/g, '-');        // replace multiple hyphens with single
        $('#slug').val(slugText);
    });

    // 3. Proximity dynamic items addition/removal
    $('#btn-add-proximity').on('click', function() {
        var rowMarkup = `
        <div class="row mb-2 proximity-row">
            <div class="col-md-5">
                <input type="text" name="proximity_name[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Facility Name, e.g. Delhi Public School">
            </div>
            <div class="col-md-3">
                <input type="text" name="proximity_distance[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Distance, e.g. 1.2 km">
            </div>
            <div class="col-md-3">
                <select name="proximity_icon[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                    <option value="school">School / Graduation Cap</option>
                    <option value="mall">Shopping Mall / Bag</option>
                    <option value="hospital">Hospital / Heart Pulse</option>
                    <option value="plane">Airport / Plane</option>
                    <option value="location">Default Pin Icon</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-remove-proximity w-100" style="height: 38px;"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
        $('#proximity-container').append(rowMarkup);
    });

    $(document).on('click', '.btn-remove-proximity', function() {
        // Keep at least one blank row or remove if there are multiple
        if ($('.proximity-row').length > 1) {
            $(this).closest('.proximity-row').remove();
        } else {
            $(this).closest('.proximity-row').find('input').val('');
        }
    });

    // 4. Floor plans dynamic items addition/removal
    $('#btn-add-floor-plan').on('click', function() {
        var rowMarkup = `
        <div class="floor-plan-row border rounded p-3 mb-3 bg-light position-relative">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Level / Title</label>
                    <input type="text" name="floor_plan_title[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. First Level Floor Plan">
                    <input type="hidden" name="existing_floor_plan_image[]" value="">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Schematic Image</label>
                    <input type="file" name="floor_plan_image[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                </div>
                <div class="col-12">
                    <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Content Description</label>
                    <textarea name="floor_plan_desc[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" rows="2" placeholder="Describe the layout room structure, dimensions, balconies, etc."></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger btn-remove-floor-plan" style="position: absolute; top: 10px; right: 10px; z-index:10;"><i class="fas fa-trash-alt"></i> Remove</button>
        </div>`;
        $('#floor-plans-container').append(rowMarkup);
    });

    $(document).on('click', '.btn-remove-floor-plan', function() {
        if ($('.floor-plan-row').length > 1) {
            $(this).closest('.floor-plan-row').remove();
        } else {
            $(this).closest('.floor-plan-row').find('input, textarea').val('');
        }
    });

    // 5. Amenities dynamic items addition/removal
    $('#btn-add-amenity').on('click', function() {
        var rowMarkup = `
        <div class="row mb-2 amenity-row">
            <div class="col-md-11 col-10">
                <input type="text" name="amenities[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Solar System Integration">
            </div>
            <div class="col-md-1 col-2">
                <button type="button" class="btn btn-danger btn-remove-amenity w-100" style="height: 38px;"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
        $('#amenities-container').append(rowMarkup);
    });

    $(document).on('click', '.btn-remove-amenity', function() {
        $(this).closest('.amenity-row').remove();
    });
});
</script>

<?php 
// Load Footer
include './footer.php'; 
?>
