<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeTide - South Africa's Premier Bartering Platform</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <a href="index.php" class="logo">TradeTide</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="browse.php">Browse Barters</a></li>
                    <li><a href="#about">About</a></li>
                </ul>
            </nav>
            <div class="nav-buttons">
                <a href="../../api/auth/login.php" class="btn btn-secondary">Sign In</a>
                <a href="../../api/auth/signup.php" class="btn btn-primary">Join Now</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Trade Skills, Share Services, Barter Better</h1>
            <p>Connect with your South African community and exchange what you have for what you need. No money required - just skills, services, and good intentions.</p>
            <div style="margin-top: 2rem;">
                <a href="../../api/auth/signup.php" class="btn btn-primary btn-lg" style="margin-right: 1rem;">Start Trading</a>
                <a href="browse.php" class="btn btn-secondary btn-lg">Browse Offers</a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" style="padding: 4rem 0; background: var(--white);">
        <div class="container">
            <h2 class="text-center mb-4">How TradeTide Works</h2>
            <div class="row">
                <div class="col-4">
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; font-weight: bold;">1</div>
                        <h3>Sign Up & Create Profile</h3>
                        <p>Join our community and showcase your skills, services, or items you'd like to trade.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; font-weight: bold;">2</div>
                        <h3>Browse & Connect</h3>
                        <p>Search through available offers in your area and connect with potential trading partners.</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <div style="width: 80px; height: 80px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; font-weight: bold;">3</div>
                        <h3>Trade & Exchange</h3>
                        <p>Negotiate terms, agree on exchanges, and complete your trades safely within our platform.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" style="padding: 4rem 0; background: var(--background-color);">
        <div class="container">
            <h2 class="text-center mb-4">Popular Categories</h2>
            <div class="row">
                <div class="col-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;">💻</div>
                            <h4>Technology</h4>
                            <p>Web development, app creation, IT support, and digital services.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <div style="font-size: 3rem; color: var(--accent-color); margin-bottom: 1rem;">🍳</div>
                            <h4>Food & Catering</h4>
                            <p>Traditional cooking, braai services, baking, and meal preparation.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <div style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;">🔧</div>
                            <h4>Home & Garden</h4>
                            <p>Electrical work, plumbing, gardening, and home maintenance.</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <div style="font-size: 3rem; color: var(--warning-color); margin-bottom: 1rem;">📚</div>
                            <h4>Education</h4>
                            <p>Language tutoring, skills training, academic support, and workshops.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Barters Section -->
    <section style="padding: 4rem 0; background: var(--white);">
        <div class="container">
            <h2 class="text-center mb-4">Featured Barters</h2>
            <div class="barter-grid">
                <div class="barter-card">
                    <div class="barter-image">
                        Professional Website Development
                    </div>
                    <div class="barter-content">
                        <h3 class="barter-title">Professional Website Development</h3>
                        <p class="barter-description">I can create modern, responsive websites using latest technologies. Perfect for small businesses looking to establish online presence.</p>
                        <div class="barter-meta">
                            <span class="barter-category">Technology</span>
                            <span class="barter-type">service</span>
                        </div>
                        <div class="barter-footer">
                            <div class="user-info">
                                <div class="user-avatar">S</div>
                                <span class="user-name">Sipho M.</span>
                            </div>
                            <small style="color: var(--text-secondary);">Garsfontein</small>
                        </div>
                    </div>
                </div>

                <div class="barter-card">
                    <div class="barter-image">
                        Traditional Braai Catering
                    </div>
                    <div class="barter-content">
                        <h3 class="barter-title">Traditional Braai Catering</h3>
                        <p class="barter-description">Authentic South African braai experience for your events. Includes boerewors, lamb chops, and traditional sides.</p>
                        <div class="barter-meta">
                            <span class="barter-category">Food & Catering</span>
                            <span class="barter-type">service</span>
                        </div>
                        <div class="barter-footer">
                            <div class="user-info">
                                <div class="user-avatar">N</div>
                                <span class="user-name">Nomsa V.</span>
                            </div>
                            <small style="color: var(--text-secondary);">Menlyn</small>
                        </div>
                    </div>
                </div>

                <div class="barter-card">
                    <div class="barter-image">
                        Language Lessons
                    </div>
                    <div class="barter-content">
                        <h3 class="barter-title">Afrikaans & Zulu Language Lessons</h3>
                        <p class="barter-description">Learn South African languages with a native speaker. Individual or group sessions available.</p>
                        <div class="barter-meta">
                            <span class="barter-category">Education</span>
                            <span class="barter-type">service</span>
                        </div>
                        <div class="barter-footer">
                            <div class="user-info">
                                <div class="user-avatar">L</div>
                                <span class="user-name">Lerato S.</span>
                            </div>
                            <small style="color: var(--text-secondary);">Brooklyn</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="browse.php" class="btn btn-primary">View All Barters</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section style="padding: 4rem 0; background: var(--background-color);">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Active Traders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">1,200+</div>
                    <div class="stat-label">Successful Trades</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Pretoria Areas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" style="padding: 4rem 0; background: var(--white);">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h2>About TradeTide</h2>
                    <p>TradeTide is South Africa's premier community-driven bartering platform, designed to bring people together through the exchange of skills, services, and items. Built specifically for South African communities, we understand the unique needs and cultural dynamics of our diverse nation.</p>
                    <p>Whether you're in Garsfontein, Menlyn, Hatfield, or any other area in Pretoria, TradeTide connects you with neighbors who can help you achieve your goals without the need for traditional monetary transactions.</p>
                    <a href="../../api/auth/signup.php" class="btn btn-primary">Join Our Community</a>
                </div>
                <div class="col-6">
                    <div style="background: linear-gradient(45deg, var(--primary-color), var(--accent-color)); height: 300px; border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: bold;">
                        Community Trading Platform
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>TradeTide</h4>
                    <p>South Africa's premier bartering platform connecting communities through skills and services exchange.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <a href="browse.php">Browse Barters</a>
                    <a href="../../api/auth/signup.php">Sign Up</a>
                    <a href="../../api/auth/login.php">Login</a>
                    <a href="help.php">Help Center</a>
                </div>
                <div class="footer-section">
                    <h4>Categories</h4>
                    <a href="browse.php?category=Technology">Technology</a>
                    <a href="browse.php?category=Food%20%26%20Catering">Food & Catering</a>
                    <a href="browse.php?category=Home%20%26%20Garden">Home & Garden</a>
                    <a href="browse.php?category=Education">Education</a>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <a href="terms.php">Terms of Service</a>
                    <a href="privacy.php">Privacy Policy</a>
                    <a href="help.php">Contact Us</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 TradeTide. All rights reserved. Built for South African communities.</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
