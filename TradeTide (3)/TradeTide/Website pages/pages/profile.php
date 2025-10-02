<?php
require_once '../../includes/auth.php';
require_once '../../config/classes/User.php';
require_once '../../config/classes/Barter.php';

requireAuth();

$current_user = getCurrentUser();
$error = '';
$success = '';

// Process form submission
if ($_POST) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $area = $_POST['area'] ?? '';
    $bio = trim($_POST['bio'] ?? '');
    $skills = trim($_POST['skills'] ?? '');

    if (empty($first_name) || empty($last_name)) {
        $error = 'First name and last name are required.';
    } else {
        $current_user->first_name = $first_name;
        $current_user->last_name = $last_name;
        $current_user->phone = $phone;
        $current_user->area = $area;
        $current_user->bio = $bio;
        $current_user->skills = $skills;

        if ($current_user->update()) {
            $success = 'Profile updated successfully!';
            // Update session data
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
        } else {
            $error = 'Error updating profile. Please try again.';
        }
    }
}

// Get user's barters for display
$barter = new Barter();
$user_barters = $barter->readByUser($current_user->id);

// Pretoria areas
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
    <title>My Profile - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="browse.php">Browse</a></li>
                    <li><a href="my-barters.php">My Barters</a></li>
                    <li><a href="messages.php">Messages</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="post-barter.php" class="btn btn-primary">Post Barter</a>
                <a href="../../api/auth/logout.php" class="btn btn-secondary">Sign Out</a>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 2rem 0;">
        <div class="row">
            <!-- Profile Information -->
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h2>My Profile</h2>
                        <p class="text-secondary">Manage your account information and showcase your skills</p>
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
                                               value="<?php echo htmlspecialchars($current_user->first_name); ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control"
                                               value="<?php echo htmlspecialchars($current_user->last_name); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" class="form-control"
                                       value="<?php echo htmlspecialchars($current_user->email); ?>" disabled>
                                <small class="text-secondary">Email cannot be changed. Contact support if needed.</small>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                               value="<?php echo htmlspecialchars($current_user->phone); ?>"
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
                                                        <?php echo ($current_user->area === $area) ? 'selected' : ''; ?>>
                                                    <?php echo $area; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="skills" class="form-label">Skills & Services</label>
                                <input type="text" id="skills" name="skills" class="form-control"
                                       value="<?php echo htmlspecialchars($current_user->skills); ?>"
                                       placeholder="e.g., Web Development, Cooking, Plumbing, Language Tutoring">
                                <small class="text-secondary">Separate multiple skills with commas</small>
                            </div>

                            <div class="form-group">
                                <label for="bio" class="form-label">About Me</label>
                                <textarea id="bio" name="bio" class="form-control" rows="4"
                                          placeholder="Tell others about yourself, your experience, and what you're passionate about..."><?php echo htmlspecialchars($current_user->bio); ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Summary & Stats -->
            <div class="col-4">
                <!-- Profile Card -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="user-avatar" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto 1rem;">
                            <?php echo strtoupper(substr($current_user->first_name, 0, 1)); ?>
                        </div>
                        <h3><?php echo htmlspecialchars($current_user->first_name . ' ' . $current_user->last_name); ?></h3>
                        <?php if ($current_user->area): ?>
                            <p class="text-secondary">📍 <?php echo htmlspecialchars($current_user->area); ?></p>
                        <?php endif; ?>
                        <div style="margin: 1rem 0;">
                            <span style="color: var(--warning-color); font-size: 1.25rem;">⭐</span>
                            <span style="font-weight: bold;"><?php echo number_format($current_user->rating, 1); ?></span>
                            <small class="text-secondary">(<?php echo $current_user->total_trades; ?> trades)</small>
                        </div>
                        <p class="text-secondary"><?php echo date('Member since M Y', strtotime($current_user->created_at)); ?></p>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span>Active Barters:</span>
                            <strong><?php echo $user_barters->rowCount(); ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span>Completed Trades:</span>
                            <strong><?php echo $current_user->total_trades; ?></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <span>Rating:</span>
                            <strong><?php echo number_format($current_user->rating, 1); ?>/5.0</strong>
                        </div>
                    </div>
                </div>

                <!-- Skills Card -->
                <?php if ($current_user->skills): ?>
                <div class="card">
                    <div class="card-header">
                        <h4>My Skills</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $skills = explode(',', $current_user->skills);
                        foreach ($skills as $skill):
                            $skill = trim($skill);
                            if ($skill):
                        ?>
                            <span style="display: inline-block; background: var(--primary-color); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; margin: 2px;"><?php echo htmlspecialchars($skill); ?></span>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
