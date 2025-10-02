<?php
require_once '../includes/auth.php';
require_once '../config/classes/User.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';

// Process form submission
if ($_POST) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $user = new User();
        if ($user->findByEmail($email)) {
            if ($user->verifyPassword($password)) {
                // Login successful
                loginUser($user->id, $user->email, $user->first_name, $user->last_name);

                // Redirect to dashboard or intended page
                $redirect = $_GET['redirect'] ?? '../dashboard/index.php';
                header('Location: ' . $redirect);
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="../index.php" class="logo">TradeTide</a>
            <div class="nav-buttons">
                <a href="signup.php" class="btn btn-secondary">Need an account?</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="padding: 4rem 0; min-height: calc(100vh - 70px);">
        <div class="row">
            <div class="col-4" style="margin: 0 auto;">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Welcome Back</h2>
                        <p class="text-center text-secondary">Sign in to your TradeTide account</p>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" data-validate>
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    Sign In
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="text-secondary">Don't have an account?
                                   <a href="signup.php" class="text-primary">Sign up here</a>
                                </p>
                            </div>
                        </form>

                        <!-- Demo Accounts -->
                        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
                            <h4 class="text-center mb-3">Demo Accounts</h4>
                            <div class="row">
                                <div class="col-6">
                                    <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                                        <p style="margin: 0; font-size: 0.875rem;"><strong>Sipho Mthembu</strong></p>
                                        <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary);">sipho@example.com</p>
                                        <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary);">password</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div style="background: var(--background-color); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                                        <p style="margin: 0; font-size: 0.875rem;"><strong>Nomsa Van Der Merwe</strong></p>
                                        <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary);">nomsa@example.com</p>
                                        <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary);">password</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
