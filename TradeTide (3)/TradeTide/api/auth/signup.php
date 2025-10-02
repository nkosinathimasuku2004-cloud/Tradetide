<?php
require_once '../includes/auth.php';
require_once '../config/classes/User.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$success = '';

// Process form submission
if ($_POST) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $area = $_POST['area'] ?? '';
    $bio = trim($_POST['bio'] ?? '');
    $skills = trim($_POST['skills'] ?? '');

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Check if email already exists
        $user = new User();
        if ($user->emailExists($email)) {
            $error = 'An account with this email already exists.';
        } else {
            // Create new user
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->email = $email;
            $user->password = $password;
            $user->phone = $phone;
            $user->area = $area;
            $user->bio = $bio;
            $user->skills = $skills;

            if ($user->create()) {
                $success = 'Account created successfully! You can now log in.';
            } else {
                $error = 'There was an error creating your account. Please try again.';
            }
        }
    }
}

// Pretoria areas for dropdown
$pretoria_areas = [
    'Arcadia', 'Brooklyn', 'Centurion', 'Garsfontein', 'Hatfield', 'Menlyn',
    'Moot', 'Muckleneuk', 'Pretoria CBD', 'Pretoria East', 'Pretoria North',
    'Pretoria West', 'Sunnyside', 'Waterkloof', 'Wonderboom', 'Lynnwood',
    'Faerie Glen', 'Monument Park', 'Colbyn', 'Erasmuskloof'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="../index.php" class="logo">TradeTide</a>
            <div class="nav-buttons">
                <a href="login.php" class="btn btn-secondary">Already have an account?</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container" style="padding: 2rem 0; min-height: calc(100vh - 70px);">
        <div class="row">
            <div class="col-6" style="margin: 0 auto;">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Join TradeTide</h2>
                        <p class="text-center text-secondary">Start trading skills and services in your community</p>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <form method="POST" data-validate>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="password" class="form-label">Password *</label>
                                        <input type="password" id="password" name="password" class="form-control" required>
                                        <small class="text-secondary">Minimum 6 characters</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm Password *</label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                               placeholder="e.g., 082 123 4567">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="area" class="form-label">Area in Pretoria</label>
                                        <select id="area" name="area" class="form-control form-select">
                                            <option value="">Select your area</option>
                                            <?php foreach ($pretoria_areas as $area): ?>
                                                <option value="<?php echo $area; ?>"
                                                        <?php echo (($_POST['area'] ?? '') === $area) ? 'selected' : ''; ?>>
                                                    <?php echo $area; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="skills" class="form-label">Skills & Services You Offer</label>
                                <input type="text" id="skills" name="skills" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['skills'] ?? ''); ?>"
                                       placeholder="e.g., Web Development, Cooking, Plumbing, Language Tutoring">
                                <small class="text-secondary">Separate multiple skills with commas</small>
                            </div>

                            <div class="form-group">
                                <label for="bio" class="form-label">Tell us about yourself</label>
                                <textarea id="bio" name="bio" class="form-control" rows="3"
                                          placeholder="Brief description of your background and what you're looking to trade..."><?php echo htmlspecialchars($_POST['bio'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    Create Account
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="text-secondary">Already have an account?
                                   <a href="login.php" class="text-primary">Sign in here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
