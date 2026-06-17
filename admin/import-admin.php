<?php
/**
 * Admin Import/Creation Utility
 * Prime Edge Realiity
 */

require_once dirname(__DIR__) . '/config.php';

// Determine if database has admin accounts
try {
    $db = db();
    $stmtCount = $db->query("SELECT COUNT(*) FROM `admins`");
    $hasAdmins = $stmtCount->fetchColumn() > 0;
} catch (\Exception $e) {
    die("Database Initialization Error: " . htmlspecialchars($e->getMessage()));
}

// Access Protection Check
$isSetupMode = !$hasAdmins;
if (!$isSetupMode) {
    // If admins exist, user must be logged in as admin to access this tool
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

// Generate CSRF Token
if (empty($_SESSION['import_csrf_token'])) {
    $_SESSION['import_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['import_csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid security token validation.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validation checks
        if (empty($name) || empty($username) || empty($email) || empty($password)) {
            $error = 'Please fill out all required fields (Name, Username, Email, Password).';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            try {
                // Check if username already exists
                $stmt = $db->prepare("SELECT COUNT(*) FROM `admins` WHERE `username` = ?");
                $stmt->execute([$username]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'The username is already taken.';
                }
                
                // Check if email already exists
                if (empty($error)) {
                    $stmt = $db->prepare("SELECT COUNT(*) FROM `admins` WHERE `email` = ?");
                    $stmt->execute([$email]);
                    if ($stmt->fetchColumn() > 0) {
                        $error = 'The email address is already registered.';
                    }
                }
                
                // Process File Upload for Profile Picture
                $profilePicName = 'user-avtar.png'; // default fallback
                if (empty($error) && !empty($_FILES['profile_pic']['name'])) {
                    $file = $_FILES['profile_pic'];
                    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $fileInfo = pathinfo($file['name']);
                    $ext = strtolower($fileInfo['extension'] ?? '');
                    
                    if (!in_array($ext, $allowedExts)) {
                        $error = 'Invalid image type. Allowed types: JPG, JPEG, PNG, GIF, WEBP.';
                    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                        $error = 'Error occurred during image upload.';
                    } else {
                        // Generate a safe unique filename to prevent path injection
                        $profilePicName = 'avatar_' . uniqid('', true) . '.' . $ext;
                        $uploadDir = __DIR__ . '/src/images/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        
                        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $profilePicName)) {
                            $error = 'Failed to save the uploaded profile picture.';
                        }
                    }
                }
                
                // Insert Admin into database
                if (empty($error)) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                    $insert = $db->prepare("INSERT INTO `admins` (`name`, `username`, `password`, `email`, `profile_pic`, `mobile`) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert->execute([$name, $username, $hashedPassword, $email, $profilePicName, $mobile]);
                    
                    $success = 'Admin account successfully created!';
                    
                    // Reset inputs for form if in dashboard mode
                    if (!$isSetupMode) {
                        $name = $username = $email = $mobile = '';
                    } else {
                        // If it was setup mode, prompt redirect
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $db->lastInsertId();
                        $_SESSION['admin_name'] = $name;
                        $_SESSION['admin_username'] = $username;
                        $_SESSION['admin_email'] = $email;
                        $_SESSION['admin_profile_pic'] = $profilePicName;
                        $_SESSION['admin_mobile'] = $mobile;
                    }
                }
            } catch (\Exception $e) {
                $error = 'Database Error: ' . $e->getMessage();
            }
        }
    }
}

// ----------------------------------------------------
// VIEW RENDERING: Switch layout based on Mode
// ----------------------------------------------------
if ($isSetupMode): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Setup | <?php echo htmlspecialchars(env('APP_NAME', 'Prime Edge Realiity')); ?></title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body.setup-page {
            background-color: #121E21 !important;
            font-family: 'Source Sans Pro', sans-serif;
            color: #CAD5D6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 0;
            margin: 0;
        }
        .setup-box {
            width: 500px;
            max-width: 90%;
        }
        .card {
            background-color: #1B2B2E !important;
            border: 1px solid rgba(229, 186, 115, 0.2) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
            border-radius: 8px !important;
            overflow: hidden;
        }
        .card-header {
            border-bottom: 1px solid rgba(229, 186, 115, 0.1) !important;
            background: transparent !important;
            padding: 30px 20px 15px 20px !important;
        }
        .setup-logo {
            margin-bottom: 0 !important;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
        }
        .setup-logo img {
            max-height: 45px;
            width: auto;
        }
        .setup-logo .logo-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.1;
        }
        .setup-logo .logo-title {
            color: #FFFFFF !important;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 1.5px;
        }
        .setup-logo .logo-subtitle {
            color: #E5BA73 !important;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 2px;
        }
        .card-body {
            padding: 30px !important;
        }
        .setup-box-msg {
            color: #A0B2B4;
            font-size: 0.9rem;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-label {
            color: #CAD5D6;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        .form-control {
            background-color: #121E21 !important;
            border: 1px solid rgba(229, 186, 115, 0.15) !important;
            color: #FFFFFF !important;
            border-radius: 4px !important;
            height: 42px;
        }
        .form-control:focus {
            border-color: #E5BA73 !important;
            box-shadow: 0 0 5px rgba(229, 186, 115, 0.3) !important;
            color: #FFFFFF !important;
        }
        .form-control::file-selector-button {
            background-color: #E5BA73;
            color: #121E21;
            border: none;
            padding: 5px 12px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 3px;
        }
        .btn-primary {
            background-color: #E5BA73 !important;
            border-color: #E5BA73 !important;
            color: #121E21 !important;
            font-weight: 700;
            letter-spacing: 0.5px;
            height: 45px;
            transition: all 0.2s ease-in-out;
            border-radius: 4px !important;
        }
        .btn-primary:hover {
            background-color: #D4A962 !important;
            border-color: #D4A962 !important;
            color: #121E21 !important;
            box-shadow: 0 4px 12px rgba(229, 186, 115, 0.25) !important;
        }
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
            border: 1px solid rgba(220, 53, 69, 0.3) !important;
            color: #ea868f !important;
            font-size: 0.85rem;
            border-radius: 4px !important;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
            border: 1px solid rgba(40, 167, 69, 0.3) !important;
            color: #75db8b !important;
            font-size: 0.85rem;
            border-radius: 4px !important;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body class="hold-transition setup-page">
<div class="setup-box">
    <div class="card">
        <div class="card-header text-center">
            <div class="setup-logo">
                <img src="../assets/logo/logo.png" alt="Logo">
                <span class="logo-text">
                    <span class="logo-title"><?php 
                        $appName = env('APP_NAME', 'Prime Edge Realiity');
                        $words = explode(' ', $appName);
                        echo htmlspecialchars(strtoupper($words[0] . (isset($words[1]) ? ' ' . $words[1] : '')));
                    ?></span>
                    <span class="logo-subtitle"><?php 
                        echo htmlspecialchars(strtoupper(isset($words[2]) ? $words[2] : ''));
                    ?></span>
                </span>
            </div>
        </div>
        <div class="card-body">
            <p class="setup-box-msg">Database is empty. Please set up the primary administrator account.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-2 px-3">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($success); ?> Redirecting to Dashboard...</span>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                </script>
            <?php endif; ?>

            <?php if (empty($success)): ?>
                <form action="import-admin.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['import_csrf_token'] ?? ''); ?>">

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="admin@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" placeholder="e.g. admin" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    </div>

                    <!-- Mobile Number -->
                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" class="form-control" placeholder="+91 99999 99999" value="<?php echo htmlspecialchars($mobile ?? ''); ?>">
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter secure password" required>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mb-4">
                        <label class="form-label">Profile Picture (PNG, JPG, WEBP)</label>
                        <input type="file" name="profile_pic" class="form-control align-middle">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100">
                        Create Administrator Account <i class="fas fa-user-plus ms-1"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>

<?php else: 
// ----------------------------------------------------
// DASHBOARD MODE: Render inside Sidebar & Footer
// ----------------------------------------------------
$page_title = "Import Admin";
include './header.php';
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-outline card-success" style="border-top: 3px solid var(--primary-green); background-color: #ffffff !important; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div class="card-header bg-white" style="border-bottom: 1px solid #f0f0f1;">
                    <h3 class="card-title text-dark font-weight-bold" style="margin-top: 5px;"><i class="fas fa-user-plus text-success me-2" style="margin-right: 5px;"></i> Import New Administrator</h3>
                </div>
                
                <form action="import-admin.php" method="post" enctype="multipart/form-data">
                    <div class="card-body text-dark">
                        <!-- Alert Blocks -->
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

                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['import_csrf_token'] ?? ''); ?>">

                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. John Doe" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="admin@example.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Username -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="e.g. admin" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="+91 99999 99999" value="<?php echo htmlspecialchars($mobile ?? ''); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;" placeholder="Create secure password" required>
                            </div>

                            <!-- Profile Picture -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-weight-bold text-muted">Profile Picture (PNG, JPG, WEBP)</label>
                                <input type="file" name="profile_pic" class="form-control" style="background-color: #f8f9fa !important; color: #495057 !important;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-end pb-4 px-4">
                        <button type="submit" class="btn btn-success font-weight-bold px-4" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important; height: 42px;">
                            <i class="fas fa-user-plus me-1"></i> Import Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
include './footer.php'; 
endif; 
?>
