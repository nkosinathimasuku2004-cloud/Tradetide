<?php
/**
 * InfinityFree Setup Helper for TradeTide
 * This script helps configure TradeTide for InfinityFree hosting
 */

echo "<h1>🚀 TradeTide InfinityFree Setup</h1>";
echo "<p>Welcome to your TradeTide deployment on InfinityFree!</p>";

echo "<h2>📋 Deployment Checklist</h2>";
echo "<div style='background: #f0f8ff; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>✅ Step 1: Upload Files</h3>";
echo "<p>Upload all TradeTide files to your <code>htdocs</code> folder</p>";
echo "</div>";

echo "<div style='background: #f0fff0; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>✅ Step 2: Create Database</h3>";
echo "<p>In InfinityFree control panel:</p>";
echo "<ul>";
echo "<li>Go to 'MySQL Databases'</li>";
echo "<li>Create database: <code>tradetide_db</code></li>";
echo "<li>Create user: <code>tradetide_user</code></li>";
echo "<li>Set password and note it down</li>";
echo "</ul>";
echo "</div>";

echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>⚠️ Step 3: Update Database Config</h3>";
echo "<p>Edit <code>config/database.php</code> with your InfinityFree database details:</p>";
echo "<pre style='background: #333; color: #fff; padding: 10px; border-radius: 5px;'>";
echo "private \$host = \"sql300.infinityfree.com\";\n";
echo "private \$db_name = \"tradetide_db\";\n";
echo "private \$username = \"tradetide_user\";\n";
echo "private \$password = \"your_password_here\";";
echo "</pre>";
echo "</div>";

echo "<div style='background: #d1ecf1; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>📊 Step 4: Import Database</h3>";
echo "<p>In phpMyAdmin:</p>";
echo "<ul>";
echo "<li>Select your database</li>";
echo "<li>Click 'Import' tab</li>";
echo "<li>Upload <code>database_schema.sql</code></li>";
echo "<li>Click 'Go' to import</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🎯 Quick Actions</h2>";
echo "<div style='display: flex; gap: 20px; margin: 20px 0;'>";
echo "<a href='install.php' style='background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px;'>Run Installation</a>";
echo "<a href='add_user_web.php' style='background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px;'>Add User Nathi</a>";
echo "<a href='Website pages/pages/index.php' style='background: #6c757d; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px;'>View Website</a>";
echo "</div>";

echo "<h2>🔧 InfinityFree Database Settings</h2>";
echo "<table style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
echo "<tr style='background: #f8f9fa;'>";
echo "<th style='border: 1px solid #ddd; padding: 12px; text-align: left;'>Setting</th>";
echo "<th style='border: 1px solid #ddd; padding: 12px; text-align: left;'>Value</th>";
echo "</tr>";
echo "<tr>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'>Host</td>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'><code>sql300.infinityfree.com</code></td>";
echo "</tr>";
echo "<tr style='background: #f8f9fa;'>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'>Database Name</td>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'><code>tradetide_db</code></td>";
echo "</tr>";
echo "<tr>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'>Username</td>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'><code>tradetide_user</code></td>";
echo "</tr>";
echo "<tr style='background: #f8f9fa;'>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'>Password</td>";
echo "<td style='border: 1px solid #ddd; padding: 12px;'><em>Your chosen password</em></td>";
echo "</tr>";
echo "</table>";

echo "<h2>🎉 After Setup</h2>";
echo "<p>Your TradeTide website will be live at:</p>";
echo "<p><strong>https://yourdomain.infinityfreeapp.com/Website pages/pages/index.php</strong></p>";

echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>🎯 Demo Accounts Ready</h3>";
echo "<ul>";
echo "<li><strong>Sipho Mthembu:</strong> sipho@example.com / password</li>";
echo "<li><strong>Nathi User:</strong> Nathi@2004.co.za / 12345678</li>";
echo "<li><strong>Nomsa Van Der Merwe:</strong> nomsa@example.com / password</li>";
echo "</ul>";
echo "</div>";

echo "<h2>📞 Need Help?</h2>";
echo "<p>If you encounter issues:</p>";
echo "<ul>";
echo "<li>Check InfinityFree control panel for error logs</li>";
echo "<li>Verify file uploads in File Manager</li>";
echo "<li>Test database connection in phpMyAdmin</li>";
echo "<li>Ensure all files are in the correct directories</li>";
echo "</ul>";

echo "<p><strong>Good luck with your TradeTide deployment on InfinityFree! 🚀</strong></p>";
?>

