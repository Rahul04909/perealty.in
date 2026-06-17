<?php
/**
 * Admin Login Page
 * Prime Edge Realiity
 */

require_once dirname(__DIR__) . '/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Generate CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid request security token.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Please enter both username and password.';
        } else {
            try {
                $db = db();
                $stmt = $db->prepare("SELECT * FROM `admins` WHERE `username` = ? LIMIT 1");
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    // Success: Authenticate and regenerate session id to prevent fixation
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['name'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_email'] = $user['email'];
                    $_SESSION['admin_profile_pic'] = $user['profile_pic'];
                    $_SESSION['admin_mobile'] = $user['mobile'];
                    
                    // Reset CSRF token for next use
                    unset($_SESSION['csrf_token']);
                    
                    header('Location: index.php');
                    exit;
                } else {
                    // Fail: Rate-limiting sleep to deter brute forcing
                    sleep(1);
                    $error = 'Invalid username or password.';
                }
            } catch (\Exception $e) {
                $error = 'A database error occurred. Please try again later.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo htmlspecialchars(env('APP_NAME', 'Prime Edge Realiity')); ?></title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- Font Awesome & Bootstrap 5 & AdminLTE CDNs -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body.login-page {
            background-color: #121E21 !important;
            font-family: 'Source Sans Pro', sans-serif;
            color: #CAD5D6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 380px;
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
        .login-logo {
            margin-bottom: 0 !important;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
        }
        .login-logo img {
            max-height: 45px;
            width: auto;
        }
        .login-logo .logo-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.1;
        }
        .login-logo .logo-title {
            color: #FFFFFF !important;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 1.5px;
        }
        .login-logo .logo-subtitle {
            color: #E5BA73 !important;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 2px;
        }
        .card-body {
            padding: 30px !important;
        }
        .login-box-msg {
            color: #A0B2B4;
            font-size: 0.9rem;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-control {
            background-color: #121E21 !important;
            border: 1px solid rgba(229, 186, 115, 0.15) !important;
            color: #FFFFFF !important;
            border-radius: 4px !important;
            height: 45px;
        }
        .form-control:focus {
            border-color: #E5BA73 !important;
            box-shadow: 0 0 5px rgba(229, 186, 115, 0.3) !important;
            color: #FFFFFF !important;
        }
        .input-group-text {
            background-color: #121E21 !important;
            border: 1px solid rgba(229, 186, 115, 0.15) !important;
            border-left: none !important;
            color: #E5BA73 !important;
            border-radius: 0 4px 4px 0 !important;
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
        .btn-primary:hover, .btn-primary:focus {
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
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-header text-center">
            <!-- Dynamic Logo & Brand -->
            <div class="login-logo">
                <img src="../assets/logo/logo.png" alt="<?php echo htmlspecialchars(env('APP_NAME', 'Prime Edge Realiity')); ?> Logo">
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
            <p class="login-box-msg">Sign in to start your administrator session</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" autocomplete="off">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                <!-- Username -->
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text h-100">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text h-100">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-full w-100">
                            Sign In <i class="fas fa-sign-in-alt ms-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- jQuery & Bootstrap 5 & AdminLTE JS CDNs -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
