<?php
/**
 * Admin Add Project Page
 * Prime Edge Realiity
 */

// Load Header (which verifies authentication)
include './header.php';

// Generate CSRF Token
if (empty($_SESSION['project_add_csrf_token'])) {
    $_SESSION['project_add_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['project_add_csrf_token'], $_POST['csrf_token'])) {
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
                $db = db();
                
                // Check if slug is unique
                $stmtSlug = $db->prepare("SELECT COUNT(*) FROM `projects` WHERE `slug` = ?");
                $stmtSlug->execute([$slug]);
                if ($stmtSlug->fetchColumn() > 0) {
                    $error = 'The project URL slug is already taken. Please choose another title/slug.';
                }
                
                // Process Main Image Upload
                $imagePath = '';
                if (empty($error)) {
                    if (empty($_FILES['image']['name'])) {
                        $error = 'A main featured project photo is required.';
                    } else {
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
                                $imagePath = 'uploads/projects/' . $mainPicName;
                            } else {
                                $error = 'Failed to save the main project photo.';
                            }
                        }
                    }
                }
                
                // Process Gallery Images Upload
                $galleryPaths = [];
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
                                    $galleryPaths[] = 'uploads/projects/' . $galName;
                                }
                            }
                        }
                    }
                }
                
                // Process Floor Plans Upload
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
                        
                        $fpImagePath = '';
                        // Check if an image is uploaded for this plan
                        if (!empty($_FILES['floor_plan_image']['name'][$k])) {
                            if ($_FILES['floor_plan_image']['error'][$k] === UPLOAD_ERR_OK) {
                                $ext = strtolower(pathinfo($_FILES['floor_plan_image']['name'][$k], PATHINFO_EXTENSION) ?? '');
                                if (in_array($ext, $allowedExts)) {
                                    $fpName = 'floor_plan_' . uniqid('', true) . '_' . $k . '.' . $ext;
                                    if (move_uploaded_file($_FILES['floor_plan_image']['tmp_name'][$k], $uploadDir . $fpName)) {
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
                
                // Insert project into DB
                if (empty($error)) {
                    $insert = $db->prepare("INSERT INTO `projects` 
                        (`slug`, `title`, `location`, `price`, `raw_price`, `beds`, `baths`, `sqft`, `garages`, `year`, `image`, `gallery`, `desc`, `tag`, `seo_title`, `seo_desc`, `seo_keywords`, `floor_plans`, `google_map`, `proximity_distances`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $insert->execute([
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
                        json_encode($galleryPaths),
                        $desc,
                        $tag,
                        $seo_title,
                        $seo_desc,
                        $seo_keywords,
                        json_encode($floorPlans),
                        $google_map,
                        json_encode($proximity)
                    ]);
                    
                    $success = 'Project created successfully!';
                    
                    // Reset fields on success
                    $title = $slug = $location = $price = $sqft = $tag = $desc = $google_map = $seo_title = $seo_desc = $seo_keywords = '';
                    $raw_price = $beds = $baths = $garages = 0;
                    $year = date('Y');
                }
            } catch (\Exception $e) {
                $error = 'Database SQL Error: ' . $e->getMessage();
            }
        }
    }
}

$page_title = "Add Project";
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
                    <h3 class="card-title text-dark font-weight-bold" style="margin-top: 5px;"><i class="fas fa-plus text-success me-2" style="margin-right: 5px;"></i> Add New Property Project</h3>
                </div>
                
                <form action="project-add.php" method="post" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['project_add_csrf_token'] ?? ''); ?>">
                    
                    <div class="card-body">
                        <!-- Section 1: Basic Information -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-1"></i> Basic Details</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Project Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Eco-Solar Villa" value="<?php echo htmlspecialchars($title ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. eco-solar" value="<?php echo htmlspecialchars($slug ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label font-weight-bold text-muted">Location Address <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Sector 79, Faridabad" value="<?php echo htmlspecialchars($location ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label font-weight-bold text-muted">Tag / Label <span class="text-danger">*</span></label>
                                <input type="text" name="tag" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Featured / Eco-Friendly" value="<?php echo htmlspecialchars($tag ?? ''); ?>" required>
                            </div>
                        </div>

                        <!-- Section 2: Pricing & Metrics -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-calculator me-1"></i> Sizing & Pricing</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Formatted Price <span class="text-danger">*</span></label>
                                <input type="text" name="price" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. ₹4,85,00,000" value="<?php echo htmlspecialchars($price ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Raw Numeric Price (for Calculations) <span class="text-danger">*</span></label>
                                <input type="number" name="raw_price" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. 48500000" value="<?php echo htmlspecialchars($raw_price ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Beds Count</label>
                                <input type="number" name="beds" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($beds ?? 0); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Baths Count</label>
                                <input type="number" name="baths" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($baths ?? 0); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Garages Count</label>
                                <input type="number" name="garages" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($garages ?? 0); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label font-weight-bold text-muted">Build Year</label>
                                <input type="number" name="year" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" value="<?php echo htmlspecialchars($year ?? date('Y')); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Area size (Sqft) <span class="text-danger">*</span></label>
                                <input type="text" name="sqft" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. 4,200" value="<?php echo htmlspecialchars($sqft ?? ''); ?>" required>
                            </div>
                        </div>

                        <!-- Section 3: Uploads & Description -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-file-upload me-1"></i> Images & Rich Text</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Featured Image <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Gallery Slide Images (Choose multiple)</label>
                                <input type="file" name="gallery[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" multiple>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted">Project Description <span class="text-danger">*</span></label>
                            <textarea name="desc" class="form-control editor" rows="6"><?php echo htmlspecialchars($desc ?? ''); ?></textarea>
                        </div>

                        <!-- Section 4: Floor Plans -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-layer-group me-1"></i> Floor Plans</h5>
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted d-block">Manage Property Floor Plans</label>
                            
                            <div id="floor-plans-container">
                                <!-- Dynamic floor plan rows added here -->
                                <div class="floor-plan-row border rounded p-3 mb-3 bg-light position-relative">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label font-weight-bold text-muted" style="font-size:0.8rem;">Plan Level / Title</label>
                                            <input type="text" name="floor_plan_title[]" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. Ground Level Floor Plan">
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
                            </div>
                            
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" id="btn-add-floor-plan">
                                <i class="fas fa-plus-circle me-1"></i> Add Floor Plan Level
                            </button>
                        </div>

                        <!-- Section 5: Map & Proximity -->
                        <h5 class="text-success font-weight-bold border-bottom pb-2 mb-3 mt-4"><i class="fas fa-map-marked-alt me-1"></i> Location Mapping & Proximity</h5>
                        
                        <div class="mb-4">
                            <label class="form-label font-weight-bold text-muted">Google Maps Iframe Embed Code</label>
                            <textarea name="google_map" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" rows="3" placeholder='Copy and paste the raw <iframe src="..."></iframe> embed code from Google Maps share button'><?php echo htmlspecialchars($google_map ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted d-block">Proximity Distances (e.g. Nearby Facilities)</label>
                            
                            <div id="proximity-container">
                                <!-- Dynamic rows added here -->
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
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-end pb-4 px-4">
                        <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; height: 42px;">
                            <i class="fas fa-save me-1"></i> Save Project Details
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
});
</script>

<?php 
// Load Footer
include './footer.php'; 
?>
