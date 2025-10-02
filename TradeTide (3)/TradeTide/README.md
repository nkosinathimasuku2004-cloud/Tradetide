# TradeTide - South Africa's Premier Bartering Platform

TradeTide is a community-driven bartering platform designed to bring people together through the exchange of skills, services, and items. Built specifically for South African communities, it connects neighbors who can help each other achieve their goals without traditional monetary transactions.

## Features

- **User Authentication**: Secure login and registration system
- **Barter Management**: Post, browse, and manage barter offers
- **Community Focus**: Location-based matching for Pretoria areas
- **Messaging System**: Direct communication between users
- **Rating System**: Trust and reputation management
- **Responsive Design**: Works on desktop and mobile devices

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Styling**: Custom CSS with CSS Variables
- **Server**: Apache/Nginx with PHP support

## Installation & Setup

### Prerequisites

1. **Web Server**: Apache or Nginx
2. **PHP**: Version 7.4 or higher
3. **MySQL**: Version 5.7 or higher
4. **PHP Extensions**: PDO, PDO_MySQL, OpenSSL

### Installation Steps

1. **Clone/Download the Project**
   ```bash
   # If using Git
   git clone [repository-url]
   
   # Or extract the ZIP file to your web server directory
   ```

2. **Database Setup**
   ```bash
   # Import the database schema
   mysql -u root -p < database_schema.sql
   
   # Or use phpMyAdmin to import the database_schema.sql file
   ```

3. **Configure Database Connection**
   - Edit `config/database.php`
   - Update database credentials if needed:
   ```php
   private $host = "localhost";
   private $db_name = "tradetide_db";
   private $username = "root";
   private $password = "";
   ```

4. **Set File Permissions**
   ```bash
   # Make sure the web server can read all files
   chmod -R 755 TradeTide/
   
   # If you have upload functionality, make upload directories writable
   chmod -R 777 uploads/  # (if you create an uploads directory)
   ```

5. **Configure Web Server**
   
   **For Apache (.htaccess):**
   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php [QSA,L]
   ```
   
   **For Nginx:**
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

6. **Access the Application**
   - Navigate to `http://localhost/TradeTide/Website pages/pages/index.php`
   - Or set up a virtual host pointing to the TradeTide directory

## Default Demo Accounts

The system comes with pre-configured demo accounts:

1. **Sipho Mthembu**
   - Email: `sipho@example.com`
   - Password: `password`
   - Area: Garsfontein
   - Skills: Web Development, PHP, JavaScript, CSS

2. **Nomsa Van Der Merwe**
   - Email: `nomsa@example.com`
   - Password: `password`
   - Area: Menlyn
   - Skills: Cooking, Catering, Braai Services

3. **Lerato Sithole**
   - Email: `lerato@example.com`
   - Password: `password`
   - Area: Brooklyn
   - Skills: Language Tutoring, Afrikaans, Zulu, English

## File Structure

```
TradeTide/
├── api/
│   ├── auth/
│   │   ├── login.php
│   │   ├── signup.php
│   │   └── logout.php
│   └── auth.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── main.js
├── config/
│   ├── classes/
│   │   ├── User.php
│   │   ├── Barter.php
│   │   └── Message.php
│   └── database.php
├── includes/
│   └── auth.php
├── Website pages/
│   └── pages/
│       ├── index.php
│       ├── dashboard.php
│       ├── browse.php
│       ├── barter-details.php
│       └── post-barter.php
├── database_schema.sql
└── README.md
```

## Key Features Explained

### Authentication System
- Secure password hashing using PHP's `password_hash()`
- Session-based authentication
- CSRF protection
- Automatic redirects for authenticated/unauthenticated users

### Database Design
- Normalized database structure
- Foreign key constraints for data integrity
- Indexes for optimal performance
- Prepared statements to prevent SQL injection

### Responsive Design
- Mobile-first approach
- CSS Grid and Flexbox for layouts
- Custom CSS variables for consistent theming
- Progressive enhancement

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MySQL service is running
   - Verify credentials in `config/database.php`
   - Ensure database `tradetide_db` exists

2. **File Not Found Errors**
   - Check file paths are correct
   - Ensure web server document root is set properly
   - Verify file permissions

3. **Session Issues**
   - Check PHP session configuration
   - Ensure session directory is writable
   - Verify session_start() is called

4. **CSS/JS Not Loading**
   - Check file paths in HTML files
   - Verify assets directory exists
   - Check web server configuration for static files

### Debug Mode

To enable debug mode, add this to the top of PHP files:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Security Considerations

- All user inputs are sanitized and validated
- SQL queries use prepared statements
- Passwords are hashed using PHP's secure hashing
- CSRF tokens protect forms
- Session security is implemented

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions:
- Check the troubleshooting section above
- Review the code comments for implementation details
- Contact the development team

## Future Enhancements

- Real-time messaging
- Mobile app development
- Advanced search and filtering
- Payment integration
- Image upload functionality
- Email notifications
- Advanced analytics and reporting

---

**TradeTide** - Connecting South African communities through skills and services exchange.

