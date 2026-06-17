<?php
/**
 * Admin Profile Management Page
 * Prime Edge Realiity
 */

// Load Header (which verifies authentication and configures session)
include './header.php';

// Fetch the most up-to-date admin data from database
try {
    $db = db();
    $stmt = $db->prepare("SELECT * FROM `admins` WHERE `id` = ? LIMIT 1");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch();
    
    if (!$admin) {
        // Safety: If admin account was deleted, log out immediately
        header('Location: logout.php');
        exit;
    }
} catch (\Exception $e) {
    die("Database Error: " . htmlspecialchars($e->getMessage()));
}

// Generate CSRF Token
if (empty($_SESSION['profile_csrf_token'])) {
    $_SESSION['profile_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // CSRF check
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['profile_csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid security token validation.';
    } else {
        if ($action === 'update_details') {
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mobile = trim($_POST['mobile'] ?? '');
            
            if (empty($name) || empty($username) || empty($email)) {
                $error = 'Please fill out all required fields (Name, Username, Email).';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } else {
                try {
                    // Check if username or email is already taken by another user
                    $stmt = $db->prepare("SELECT `username`, `email` FROM `admins` WHERE `id` != ? AND (`username` = ? OR `email` = ?)");
                    $stmt->execute([$_SESSION['admin_id'], $username, $email]);
                    $existing = $stmt->fetch();
                    
                    if ($existing) {
                        if ($existing['username'] === $username) {
                            $error = 'The username is already taken by another administrator.';
                        } else {
                            $error = 'The email address is already registered by another administrator.';
                        }
                    } else {
                        // Process profile picture file upload if a new file is provided
                        $profilePic = $admin['profile_pic'];
                        if (!empty($_FILES['profile_pic']['name'])) {
                            $file = $_FILES['profile_pic'];
                            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION) ?? '');
                            
                            if (!in_array($ext, $allowedExts)) {
                                $error = 'Invalid image type. Allowed: JPG, JPEG, PNG, GIF, WEBP.';
                            } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                                $error = 'Error occurred during image upload.';
                            } else {
                                $newPicName = 'avatar_' . uniqid('', true) . '.' . $ext;
                                $uploadDir = __DIR__ . '/src/images/';
                                if (!is_dir($uploadDir)) {
                                    mkdir($uploadDir, 0755, true);
                                }
                                
                                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newPicName)) {
                                    // Delete old custom profile picture from server if it is not default
                                    if ($profilePic && $profilePic !== 'user-avtar.png') {
                                        $oldPath = $uploadDir . $profilePic;
                                        if (file_exists($oldPath)) {
                                            @unlink($oldPath);
                                        }
                                    }
                                    $profilePic = $newPicName;
                                } else {
                                    $error = 'Failed to save the uploaded profile picture.';
                                }
                            }
                        }
                        
                        // Perform Database Update
                        if (empty($error)) {
                            $update = $db->prepare("UPDATE `admins` SET `name` = ?, `username` = ?, `email` = ?, `mobile` = ?, `profile_pic` = ? WHERE `id` = ?");
                            $update->execute([$name, $username, $email, $mobile, $profilePic, $_SESSION['admin_id']]);
                            
                            $success = 'Profile details successfully updated!';
                            
                            // Refresh local $admin variable
                            $admin['name'] = $name;
                            $admin['username'] = $username;
                            $admin['email'] = $email;
                            $admin['mobile'] = $mobile;
                            $admin['profile_pic'] = $profilePic;
                            
                            // Update dynamic session credentials
                            $_SESSION['admin_name'] = $name;
                            $_SESSION['admin_username'] = $username;
                            $_SESSION['admin_email'] = $email;
                            $_SESSION['admin_profile_pic'] = $profilePic;
                            $_SESSION['admin_mobile'] = $mobile;
                        }
                    }
                } catch (\Exception $e) {
                    $error = 'Database Error: ' . $e->getMessage();
                }
            }
        } elseif ($action === 'change_password') {
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
                $error = 'All password fields are required.';
            } elseif ($newPass !== $confirmPass) {
                $error = 'New password and confirmation password do not match.';
            } elseif (strlen($newPass) < 6) {
                $error = 'New password must be at least 6 characters long.';
            } else {
                try {
                    // Validate current password hash
                    if (password_verify($currentPass, $admin['password'])) {
                        $newHashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
                        
                        $update = $db->prepare("UPDATE `admins` SET `password` = ? WHERE `id` = ?");
                        $update->execute([$newHashedPassword, $_SESSION['admin_id']]);
                        
                        $success = 'Password successfully updated!';
                    } else {
                        $error = 'Incorrect current password.';
                    }
                } catch (\Exception $e) {
                    $error = 'Database Error: ' . $e->getMessage();
                }
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Left Column: User Summary Card -->
        <div class="col-lg-4">
            <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-body box-profile text-dark text-center">
                    <div class="text-center mb-3">
                        <?php 
                        $avatarFile = !empty($admin['profile_pic']) ? $admin['profile_pic'] : 'user-avtar.png';
                        $avatarPath = "./src/images/" . $avatarFile;
                        ?>
                        <img class="profile-user-img img-fluid img-circle bg-white"
                             src="<?php echo htmlspecialchars($avatarPath); ?>"
                             alt="User profile picture"
                             style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #ced4da;">
                    </div>

                    <h3 class="profile-username text-center font-weight-bold" style="font-size: 1.3rem;"><?php echo htmlspecialchars($admin['name']); ?></h3>
                    <p class="text-muted text-center" style="font-size: 0.85rem; margin-top: -5px;"><i class="fas fa-shield-alt text-success me-1"></i> Administrator</p>

                    <ul class="list-group list-group-unbordered mb-3 mt-4 text-start" style="font-size: 0.9rem;">
                        <li class="list-group-item bg-transparent" style="border-left: 0; border-right: 0; padding: 10px 0;">
                            <b class="text-muted"><i class="fas fa-user me-2" style="width: 20px;"></i> Username</b> 
                            <span class="float-right font-weight-bold text-dark"><?php echo htmlspecialchars($admin['username']); ?></span>
                        </li>
                        <li class="list-group-item bg-transparent" style="border-left: 0; border-right: 0; padding: 10px 0;">
                            <b class="text-muted"><i class="fas fa-envelope me-2" style="width: 20px;"></i> Email</b> 
                            <span class="float-right text-dark"><?php echo htmlspecialchars($admin['email']); ?></span>
                        </li>
                        <li class="list-group-item bg-transparent" style="border-left: 0; border-right: 0; border-bottom: 0; padding: 10px 0;">
                            <b class="text-muted"><i class="fas fa-phone me-2" style="width: 20px;"></i> Mobile</b> 
                            <span class="float-right text-dark"><?php echo htmlspecialchars($admin['mobile'] ? $admin['mobile'] : 'Not Set'); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings & Password Cards -->
        <div class="col-lg-8">
            <!-- Alert Messages -->
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

            <!-- Tab Content Cards -->
            <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <div class="card-header bg-white" style="border-bottom: 1px solid #f0f0f1;">
                    <h3 class="card-title text-dark font-weight-bold" style="margin-top: 5px;"><i class="fas fa-cog text-success me-2" style="margin-right: 5px;"></i> Profile Settings</h3>
                </div>
                
                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['profile_csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="action" value="update_details">
                    
                    <div class="card-body text-dark">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="John Doe" value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                            </div>
                            
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="admin@example.com" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                            </div>
                            
                            <!-- Mobile -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="+91 99999 99999" value="<?php echo htmlspecialchars($admin['mobile']); ?>">
                            </div>
                        </div>
                        
                        <!-- Profile Pic -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted">Update Profile Picture (PNG, JPG, WEBP)</label>
                            <input type="file" name="profile_pic" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-end pb-4 px-4">
                        <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; height: 42px;">
                            Save Details <i class="fas fa-save ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Card -->
            <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-header bg-white" style="border-bottom: 1px solid #f0f0f1;">
                    <h3 class="card-title text-dark font-weight-bold" style="margin-top: 5px;"><i class="fas fa-lock text-success me-2" style="margin-right: 5px;"></i> Change Password</h3>
                </div>
                
                <form action="profile.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['profile_csrf_token'] ?? ''); ?>">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="card-body text-dark">
                        <!-- Current Password -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-muted">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Enter current password" required>
                        </div>
                        
                        <div class="row">
                            <!-- New Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">New Password <span class="text-danger">*</span></label>
                                <input type="password" name="new_password" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="At least 6 characters" required>
                            </div>
                            
                            <!-- Confirm New Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Confirm New Password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Confirm new password" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-end pb-4 px-4">
                        <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; height: 42px;">
                            Change Password <i class="fas fa-key ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// Load Footer
include './footer.php'; 
?>