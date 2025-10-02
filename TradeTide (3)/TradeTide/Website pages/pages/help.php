<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Contact - TradeTide</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="browse.php">Browse</a></li>
                    <li><a href="help.php" class="active">Help</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="../../api/auth/login.php" class="btn btn-secondary">Sign In</a>
                <a href="../../api/auth/signup.php" class="btn btn-primary">Join Now</a>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 2rem 0;">
        <div class="row">
            <div class="col-8" style="margin: 0 auto;">
                <h1>Help & Support</h1>
                <p class="text-secondary">Get answers to common questions and learn how to make the most of TradeTide.</p>

                <!-- FAQ Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>Frequently Asked Questions</h2>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 2rem;">
                            <h4>How does TradeTide work?</h4>
                            <p>TradeTide is a bartering platform where you can exchange skills, services, and items without using money. Simply create an account, post what you can offer, browse what others are offering, and connect to arrange trades.</p>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4>Is TradeTide free to use?</h4>
                            <p>Yes! TradeTide is completely free to use. We believe in building community connections without barriers.</p>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4>How do I post a barter?</h4>
                            <p>After creating an account, click "Post Barter" in the navigation menu. Fill out the form with details about what you're offering, including title, description, category, and type.</p>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4>How do I contact other users?</h4>
                            <p>You can send messages to other users through our messaging system. Click "Make Offer" on any barter listing to start a conversation.</p>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4>What areas does TradeTide cover?</h4>
                            <p>Currently, TradeTide focuses on the Pretoria area, including suburbs like Garsfontein, Menlyn, Hatfield, Brooklyn, and many others.</p>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <h4>Is it safe to trade with strangers?</h4>
                            <p>We encourage users to meet in public places, verify identities, and trust their instincts. Our rating system helps build trust within the community.</p>
                        </div>
                    </div>
                </div>

                <!-- Getting Started Guide -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h2>Getting Started Guide</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h4>For Beginners</h4>
                                <ol>
                                    <li>Create your account</li>
                                    <li>Complete your profile</li>
                                    <li>Browse existing barters</li>
                                    <li>Post your first offer</li>
                                    <li>Start connecting with others</li>
                                </ol>
                            </div>
                            <div class="col-6">
                                <h4>Safety Tips</h4>
                                <ul>
                                    <li>Meet in public places</li>
                                    <li>Bring a friend if possible</li>
                                    <li>Trust your instincts</li>
                                    <li>Verify skills before trading</li>
                                    <li>Keep records of agreements</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="card">
                    <div class="card-header">
                        <h2>Contact Us</h2>
                        <p class="text-secondary">Can't find what you're looking for? Send us a message.</p>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" id="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject" class="form-label">Subject</label>
                                <select id="subject" class="form-control form-select">
                                    <option>General Question</option>
                                    <option>Technical Issue</option>
                                    <option>Account Problem</option>
                                    <option>Safety Concern</option>
                                    <option>Feature Request</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Message</label>
                                <textarea id="message" class="form-control" rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/main.js"></script>
</body>
</html>
