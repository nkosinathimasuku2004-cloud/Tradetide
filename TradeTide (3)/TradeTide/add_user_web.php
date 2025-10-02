<?php
/**
 * Web-based User Addition for TradeTide
 * Access this through your browser to add users
 */

require_once 'config/database.php';
require_once 'config/classes/User.php';

$message = '';
$message_type = '';

// Process form submission
if ($_POST && isset($_POST['add_user'])) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $area = $_POST['area'] ?? '';
    $bio = trim($_POST['bio'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $message = 'Please fill in all required fields.';
        $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $message_type = 'error';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $message_type = 'error';
    } else {
        try {
            $user = new User();
            
            // Check if email already exists
            if ($user->emailExists($email)) {
                $message = "User with email '$email' already exists!";
                $message_type = 'error';
            } else {
                // Set user data
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->email = $email;
                $user->password = $password;
                $user->phone = $phone;
                $user->area = $area;
                $user->bio = $bio;
                $user->skills = $skills;
                
                // Create the user
                if ($user->create()) {
                    $message = "User '$first_name' has been added successfully!";
                    $message_type = 'success';
                } else {
                    $message = 'Failed to create user. Please check database connection.';
                    $message_type = 'error';
                }
            }
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

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
    <title>Add User - TradeTide Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container" style="padding: 2rem 0;">
        <div class="row">
            <div class="col-8" style="margin: 0 auto;">
                <div class="card">
                    <div class="card-header">
                        <h2>Add New User to TradeTide</h2>
                        <p class="text-secondary">Add a new user to the TradeTide platform</p>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo $message_type; ?>">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['first_name'] ?? 'Nathi'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control"
                                               value="<?php echo htmlspecialchars($_POST['last_name'] ?? 'User'); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? 'Nathi@2004.co.za'); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" id="password" name="password" class="form-control"
                                       value="<?php echo htmlspecialchars($_POST['password'] ?? '12345678'); ?>" required>
                                <small class="text-secondary">Minimum 6 characters</small>
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
                                                        <?php echo (($_POST['area'] ?? 'Pretoria CBD') === $area) ? 'selected' : ''; ?>>
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
                                       value="<?php echo htmlspecialchars($_POST['skills'] ?? 'General Services'); ?>"
                                       placeholder="e.g., Web Development, Cooking, Plumbing">
                                <small class="text-secondary">Separate multiple skills with commas</small>
                            </div>

                            <div class="form-group">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea id="bio" name="bio" class="form-control" rows="3"
                                          placeholder="Tell us about yourself..."><?php echo htmlspecialchars($_POST['bio'] ?? 'New user on TradeTide platform'); ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                                <a href="Website pages/pages/index.php" class="btn btn-secondary">Back to Site</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Add Button for Nathi -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3>Quick Add: Nathi</h3>
                    </div>
                    <div class="card-body">
                        <p>Click the button below to quickly add the user "Nathi" with the specified details:</p>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="first_name" value="Nathi">
                            <input type="hidden" name="last_name" value="User">
                            <input type="hidden" name="email" value="Nathi@2004.co.za">
                            <input type="hidden" name="password" value="12345678">
                            <input type="hidden" name="phone" value="">
                            <input type="hidden" name="area" value="Pretoria CBD">
                            <input type="hidden" name="bio" value="New user on TradeTide platform">
                            <input type="hidden" name="skills" value="General Services">
                            <button type="submit" name="add_user" class="btn btn-success">Quick Add Nathi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

