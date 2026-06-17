<?php
/**
 * Admin Projects Management List
 * Prime Edge Realiity
 */

// Load Header (authentication & database initialization checked here)
include './header.php';

// Generate CSRF Token for deletes
if (empty($_SESSION['projects_csrf_token'])) {
    $_SESSION['projects_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['projects_csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid security token validation.';
    } else {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $db = db();
            // Fetch project to delete its files from server
            $stmt = $db->prepare("SELECT `image`, `gallery` FROM `projects` WHERE `id` = ?");
            $stmt->execute([$id]);
            $proj = $stmt->fetch();
            
            if ($proj) {
                // Delete main image file if custom
                if ($proj['image'] && strpos($proj['image'], 'assets/images/') === false && file_exists(dirname(__DIR__) . '/' . $proj['image'])) {
                    @unlink(dirname(__DIR__) . '/' . $proj['image']);
                }
                
                // Delete gallery images if custom
                $gallery = json_decode($proj['gallery'] ?? '[]', true);
                if (is_array($gallery)) {
                    foreach ($gallery as $galFile) {
                        if ($galFile && strpos($galFile, 'assets/images/') === false && file_exists(dirname(__DIR__) . '/' . $galFile)) {
                            @unlink(dirname(__DIR__) . '/' . $galFile);
                        }
                    }
                }
                
                // Delete record
                $stmtDel = $db->prepare("DELETE FROM `projects` WHERE `id` = ?");
                $stmtDel->execute([$id]);
                
                $success = 'Project deleted successfully.';
            } else {
                $error = 'Project not found.';
            }
        } catch (\Exception $e) {
            $error = 'Error occurred: ' . $e->getMessage();
        }
    }
}

// Fetch all projects
try {
    $db = db();
    $stmt = $db->query("SELECT * FROM `projects` ORDER BY `id` DESC");
    $projectsList = $stmt->fetchAll();
} catch (\Exception $e) {
    $projectsList = [];
    $error = 'Failed to load projects: ' . $e->getMessage();
}

$page_title = "Manage Projects";
?>

<div class="container-fluid">
    <!-- Action Alerts -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 px-3 mb-4 d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2" style="margin-right: 10px;"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success py-2 px-3 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-2" style="margin-right: 10px;"></i>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="h3 text-dark font-weight-bold">Properties & Projects</h1>
            <a href="project-add.php" class="btn btn-success" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important;">
                <i class="fas fa-plus me-1"></i> Add New Project
            </a>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <div class="card-body p-0 text-dark">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px; padding: 12px 15px;">Image</th>
                            <th style="padding: 12px 15px;">Project Details</th>
                            <th style="padding: 12px 15px;">Location</th>
                            <th style="padding: 12px 15px; width: 150px;">Price</th>
                            <th style="padding: 12px 15px; width: 200px;">Specifications</th>
                            <th style="padding: 12px 15px; width: 150px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projectsList)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No projects found. Click "Add New Project" to create one.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($projectsList as $proj): ?>
                                <?php 
                                // Resolve main image path
                                $imgSrc = $proj['image'];
                                if (strpos($imgSrc, 'assets/') === false) {
                                    $imgSrc = '../uploads/projects/' . $imgSrc;
                                } else {
                                    $imgSrc = '../' . $imgSrc;
                                }
                                ?>
                                <tr>
                                    <td style="padding: 12px 15px;">
                                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Project Photo" class="img-thumbnail" style="width: 65px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span class="font-weight-bold d-block text-dark" style="font-size: 0.95rem;"><?php echo htmlspecialchars($proj['title']); ?></span>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="fas fa-link me-1"></i> Slug: <?php echo htmlspecialchars($proj['slug']); ?></small>
                                        <span class="badge bg-secondary py-1 px-2 mt-1" style="font-size: 0.7rem; font-weight: 600;"><?php echo htmlspecialchars($proj['tag']); ?></span>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span class="text-secondary" style="font-size: 0.85rem;"><?php echo htmlspecialchars($proj['location']); ?></span>
                                    </td>
                                    <td style="padding: 12px 15px;">
                                        <span class="font-weight-bold text-success" style="font-size: 0.95rem;"><?php echo htmlspecialchars($proj['price']); ?></span>
                                    </td>
                                    <td style="padding: 12px 15px; font-size: 0.8rem; line-height: 1.6;">
                                        <div class="row row-cols-2 g-1">
                                            <div class="col"><i class="fas fa-bed text-muted me-1" style="width: 14px;"></i> <?php echo $proj['beds']; ?> Beds</div>
                                            <div class="col"><i class="fas fa-bath text-muted me-1" style="width: 14px;"></i> <?php echo $proj['baths']; ?> Baths</div>
                                            <div class="col"><i class="fas fa-expand-arrows-alt text-muted me-1" style="width: 14px;"></i> <?php echo htmlspecialchars($proj['sqft']); ?> Sqft</div>
                                            <div class="col"><i class="fas fa-car text-muted me-1" style="width: 14px;"></i> <?php echo $proj['garages']; ?> Garages</div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px 15px; text-align: center;">
                                        <div class="btn-group btn-group-sm">
                                            <a href="project-edit.php?id=<?php echo $proj['id']; ?>" class="btn btn-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Delete" onclick="confirmDelete(<?php echo $proj['id']; ?>, '<?php echo htmlspecialchars(addslashes($proj['title'])); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="delete-form" action="projects.php" method="post" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['projects_csrf_token'] ?? ''); ?>">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="delete-id" value="">
</form>

<script>
function confirmDelete(id, title) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete "' + title + '"? This will remove all project details, images, and maps associated with it permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-id').value = id;
            document.getElementById('delete-form').submit();
        }
    });
}
</script>

<?php 
// Load Footer
include './footer.php'; 
?>
