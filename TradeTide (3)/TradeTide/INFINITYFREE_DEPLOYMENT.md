# 🚀 TradeTide Deployment on InfinityFree

## 📋 Step-by-Step InfinityFree Deployment Guide

### **Step 1: Create InfinityFree Account**
1. Go to: https://infinityfree.net/
2. Click "Sign Up" (it's completely free)
3. Verify your email address
4. Log into your control panel

### **Step 2: Create Website**
1. In your InfinityFree control panel, click "Create Account"
2. Choose a subdomain (e.g., `tradetide.infinityfreeapp.com`)
3. Set password for your hosting account
4. Click "Create Account"

### **Step 3: Upload Files**
1. In control panel, click "File Manager"
2. Navigate to `htdocs` folder (this is your website root)
3. **Upload all TradeTide files**:
   - Select all files from your `TradeTide` folder
   - Upload them to `htdocs` directory
   - **Important**: Upload the entire folder structure

### **Step 4: Create Database**
1. In control panel, click "MySQL Databases"
2. Create new database: `tradetide_db`
3. Create database user: `tradetide_user`
4. Set password for database user
5. **Note down these credentials**:
   - Database name: `tradetide_db`
   - Username: `tradetide_user`
   - Password: (your chosen password)
   - Host: `sql300.infinityfree.com` (usually)

### **Step 5: Update Database Configuration**
1. In File Manager, edit `config/database.php`
2. Update the database settings:
```php
private $host = "sql300.infinityfree.com";
private $db_name = "tradetide_db";
private $username = "tradetide_user";
private $password = "your_password_here";
```

### **Step 6: Install Database Schema**
1. In control panel, click "phpMyAdmin"
2. Select your database `tradetide_db`
3. Click "Import" tab
4. Upload `database_schema.sql` file
5. Click "Go" to import

### **Step 7: Run Installation**
1. Open your website: `https://yourdomain.infinityfreeapp.com/install.php`
2. Follow the installation wizard
3. Test database connection

### **Step 8: Add User Nathi**
1. Go to: `https://yourdomain.infinityfreeapp.com/add_user_web.php`
2. Click "Quick Add Nathi" button
3. Verify user was added successfully

### **Step 9: Test Your Website**
1. Visit: `https://yourdomain.infinityfreeapp.com/Website pages/pages/index.php`
2. Test login with demo accounts:
   - Email: `sipho@example.com`, Password: `password`
   - Email: `nathi@2004.co.za`, Password: `12345678`

## 🔧 **InfinityFree Specific Settings**

### **File Permissions**
- Files: 644
- Folders: 755
- InfinityFree usually sets these automatically

### **PHP Version**
- InfinityFree supports PHP 7.4+ (perfect for TradeTide)
- No special configuration needed

### **Database Limits**
- Free accounts: 1 database, 50MB storage
- Perfect for TradeTide (small database)

## 📁 **Files to Upload**

Upload these folders/files to `htdocs`:
```
htdocs/
├── Website pages/
├── api/
├── assets/
├── config/
├── includes/
├── database_schema.sql
├── install.php
├── add_user_web.php
├── README.md
└── DEPLOYMENT_CHECKLIST.md
```

## 🎯 **Quick Deployment Checklist**

- [ ] Create InfinityFree account
- [ ] Create website subdomain
- [ ] Upload all TradeTide files to htdocs
- [ ] Create MySQL database
- [ ] Update database credentials in config/database.php
- [ ] Import database_schema.sql via phpMyAdmin
- [ ] Run install.php
- [ ] Add user Nathi via add_user_web.php
- [ ] Test website functionality
- [ ] Share your live website URL!

## 🎉 **Your TradeTide Website Will Be Live At:**
`https://yourdomain.infinityfreeapp.com/Website pages/pages/index.php`

## 📞 **Need Help?**

If you encounter any issues:
1. Check InfinityFree documentation
2. Verify file uploads in File Manager
3. Test database connection in phpMyAdmin
4. Check error logs in control panel

**Your TradeTide bartering platform will be live and ready to use!** 🚀

