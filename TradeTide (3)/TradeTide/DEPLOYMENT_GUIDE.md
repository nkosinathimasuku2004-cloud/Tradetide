# TradeTide Deployment Guide

## 🚀 Deployment Options for TradeTide

Since XAMPP isn't working, here are several deployment options for your TradeTide website:

## Option 1: Free Web Hosting (Recommended for Testing)

### 1. **000WebHost (Free)**
- **Website**: https://www.000webhost.com/
- **Features**: Free PHP hosting, MySQL database
- **Steps**:
  1. Sign up for free account
  2. Create new website
  3. Upload all TradeTide files via File Manager
  4. Create MySQL database in control panel
  5. Run install.php to setup database
  6. Access your site at your assigned URL

### 2. **InfinityFree**
- **Website**: https://infinityfree.net/
- **Features**: Free PHP hosting, MySQL database
- **Steps**: Similar to 000WebHost

### 3. **Freehostia**
- **Website**: https://www.freehostia.com/
- **Features**: Free hosting with PHP and MySQL

## Option 2: Paid Hosting (Recommended for Production)

### 1. **Hostinger**
- **Cost**: ~$1-3/month
- **Features**: Fast, reliable, PHP/MySQL support
- **Steps**:
  1. Purchase hosting plan
  2. Upload files via File Manager or FTP
  3. Create MySQL database
  4. Run install.php

### 2. **Bluehost**
- **Cost**: ~$3-5/month
- **Features**: Popular, reliable hosting

### 3. **SiteGround**
- **Cost**: ~$4-7/month
- **Features**: Fast, secure hosting

## Option 3: Cloud Hosting

### 1. **Heroku** (Free tier available)
- **Website**: https://heroku.com/
- **Steps**:
  1. Create Heroku account
  2. Install Heroku CLI
  3. Create Procfile for PHP
  4. Deploy via Git

### 2. **DigitalOcean** ($5/month)
- **Website**: https://www.digitalocean.com/
- **Features**: VPS hosting, full control

## Option 4: Local Development Server (Alternative to XAMPP)

### 1. **PHP Built-in Server**
```bash
# Navigate to TradeTide folder
cd TradeTide

# Start PHP server
php -S localhost:8000
```
Then access: http://localhost:8000/Website pages/pages/index.php

### 2. **WAMP Server**
- Download from: https://www.wampserver.com/
- Alternative to XAMPP

### 3. **MAMP** (Mac)
- Download from: https://www.mamp.info/

## 📋 Pre-Deployment Checklist

### Files to Upload:
- [ ] All PHP files in `Website pages/pages/`
- [ ] `api/` folder
- [ ] `assets/` folder (CSS, JS)
- [ ] `config/` folder
- [ ] `includes/` folder
- [ ] `database_schema.sql`
- [ ] `install.php`
- [ ] `README.md`

### Database Setup:
1. Create MySQL database on hosting
2. Import `database_schema.sql`
3. Update database credentials in `config/database.php`
4. Test connection

### Security:
- [ ] Change default passwords
- [ ] Set proper file permissions
- [ ] Enable HTTPS (if available)

## 🎯 Quick Start Commands

### For PHP Built-in Server:
```bash
# Windows Command Prompt
cd "C:\Users\27763\OneDrive\Desktop\TradeTide (3)\TradeTide"
php -S localhost:8000

# Then open browser to:
# http://localhost:8000/Website pages/pages/index.php
```

### For Free Hosting:
1. Go to 000WebHost.com
2. Sign up for free account
3. Upload TradeTide folder contents
4. Create MySQL database
5. Run install.php
6. Access your live website!

## 📞 Support

If you need help with any deployment option, I can guide you through the specific steps for your chosen method.

## 🎉 Your TradeTide Website is Ready!

All bugs have been fixed and the website is production-ready. Choose your preferred deployment method and get your bartering platform online!

