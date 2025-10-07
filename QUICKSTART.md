# Quick Start Guide - Git Setup & Database Configuration

## 🚀 For New Developers

### Step 1: Clone the Repository

```bash
git clone https://github.com/hariharasuthan-hb/xerte.git
cd xerte
```

---

## 🗄️ Database Configuration (2 Methods)

### Method A: Automatic Setup (Recommended) ⭐

**1. Start your web server and database:**
```bash
# For XAMPP users:
# - Start Apache and MySQL from XAMPP Control Panel

# For other systems:
sudo systemctl start apache2
sudo systemctl start mysql
```

**2. Open the setup wizard in your browser:**
```
http://localhost/xerte/setup/
```

**3. Follow the wizard steps:**

- **Page 1: System Check**
  - Wizard checks PHP version, extensions, and permissions
  - Click "Next"

- **Page 2: Database Connection**
  - Database Type: `mysqli` (recommended) or `pdo_mysql`
  - Database Host: `localhost` (or your DB server IP)
  - Database Username: Your MySQL username (e.g., `root`)
  - Database Password: Your MySQL password
  - Database Name: Choose a name (e.g., `xerte_db`)
  - Table Prefix: Leave empty (or use a prefix like `xot_`)
  - Click "Create Database"

- **Page 3: Admin Account**
  - Create your admin username and password
  - This will be your login credentials

- **Page 4: Site Configuration**
  - Site URL: `http://localhost/xerte/` (or your domain)
  - Site Name: Your site name
  - Configure other options as needed
  - Click "Finish"

**4. The wizard automatically creates:**
- ✅ `database.php` (with your DB credentials)
- ✅ Database tables and structure
- ✅ Admin user account

**5. Security - Delete/Restrict setup folder:**
```bash
# Option 1: Delete the setup folder
rm -rf setup/

# Option 2: Restrict access (Linux/Apache)
echo "Deny from all" > setup/.htaccess
```

**✅ Done! Visit:** `http://localhost/xerte/`

---

### Method B: Manual Database Configuration

If you prefer manual setup or setup wizard doesn't work:

**1. Create MySQL database:**
```sql
-- Log into MySQL
mysql -u root -p

-- Create database
CREATE DATABASE xerte_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user (optional)
CREATE USER 'xerte_user'@'localhost' IDENTIFIED BY 'your_secure_password';

-- Grant privileges
GRANT ALL PRIVILEGES ON xerte_db.* TO 'xerte_user'@'localhost';
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;
```

**2. Create `database.php` file:**

Create a new file in the root directory: `database.php`

```php
<?php 
/**
 * Database connection configuration
 */

/*
 * Database type (mysqli or pdo_mysql)
 */
$xerte_toolkits_site->database_type = 'mysqli';

/*
 * Database host (usually 'localhost')
 */
$xerte_toolkits_site->database_host = 'localhost';

/*
 * Database username
 */
$xerte_toolkits_site->database_username = 'xerte_user';

/*
 * Database password
 */
$xerte_toolkits_site->database_password = 'your_secure_password';

/*
 * Database name
 */
$xerte_toolkits_site->database_name = 'xerte_db';

/*
 * Database table prefix (leave empty or use prefix like 'xot_')
 */
$xerte_toolkits_site->database_table_prefix = '';
?>
```

**3. Import database structure:**
```bash
# Navigate to setup folder
cd setup/

# Import the SQL file
mysql -u xerte_user -p xerte_db < basic.sql

# Go back to root
cd ..
```

**4. Configure site settings:**

Edit `config.php` if needed:
- Set `$development = false;` for production
- Configure paths and site-specific settings

**✅ Done!** Visit: `http://localhost/xerte/`

---

## 🔧 Optional Configuration Files

These are **optional** and only needed for specific features:

### Authentication Configuration (LDAP/Custom Auth)
```bash
cp auth_config.php.dist auth_config.php
nano auth_config.php  # Edit with your settings
```

### API Keys (External Services)
```bash
cp api_keys_dist.php api_keys.php
nano api_keys.php  # Add your API keys
```

### LRS Configuration (xAPI/Learning Record Store)
```bash
cp lrsdb_config.php.dist lrsdb_config.php
nano lrsdb_config.php  # Configure LRS database
```

### Reverse Proxy Configuration
```bash
cp reverse_proxy_conf.php.dist reverse_proxy_conf.php
nano reverse_proxy_conf.php  # Configure proxy settings
```

---

## 🐛 Troubleshooting

### Setup wizard shows blank page?
```bash
# Check PHP error log
tail -f /var/log/apache2/error.log  # Linux
tail -f C:\xampp\apache\logs\error.log  # XAMPP Windows

# Enable error reporting temporarily
# Edit config.php and set:
$development = true;
```

### "Cannot connect to database" error?
```bash
# Test MySQL connection
mysql -u root -p

# Check if MySQL is running
# XAMPP: Check XAMPP Control Panel
# Linux: sudo systemctl status mysql
```

### Permission denied errors?
```bash
# Set correct permissions (Linux/Mac)
sudo chown -R www-data:www-data USER-FILES/
sudo chmod -R 755 USER-FILES/

# XAMPP Windows: Run XAMPP as Administrator
```

### Database import errors?
```bash
# Check MySQL version compatibility
mysql --version

# Try importing with verbose output
mysql -u xerte_user -p xerte_db < setup/basic.sql -v
```

---

## 📋 What Gets Created?

### Configuration Files (Local, Not in Git):
```
xerte/
├── database.php              ← Created by setup (DB credentials)
├── auth_config.php          ← Optional (copy from .dist)
├── api_keys.php             ← Optional (copy from .dist)
├── lrsdb_config.php         ← Optional (copy from .dist)
└── reverse_proxy_conf.php   ← Optional (copy from .dist)
```

### These Files are Ignored by Git:
All configuration files containing credentials are in `.gitignore`:
- ✅ Your secrets stay secure
- ✅ Each environment has its own config
- ✅ No accidental commits of passwords

---

## 🔄 Git Workflow

### Initial Setup (First Time)
```bash
# Clone repository
git clone https://github.com/hariharasuthan-hb/xerte.git
cd xerte

# Check what's tracked
git status

# The .gitignore file protects your configs
cat .gitignore
```

### Daily Development Workflow
```bash
# Check current status
git status

# Pull latest changes
git pull origin main

# Create a feature branch
git checkout -b feature/your-feature-name

# Make your changes...

# Stage changes
git add .

# Commit (your config files won't be included)
git commit -m "Your commit message"

# Push to your branch
git push origin feature/your-feature-name
```

### Important Git Notes:
- ❌ **NEVER** commit `database.php` or other config files with credentials
- ✅ **DO** commit `.dist` template files
- ✅ **DO** commit code changes and documentation
- ✅ The `.gitignore` file protects you automatically

---

## ✅ Verify Your Setup

### 1. Check Files Created:
```bash
# These should exist after setup:
ls -la database.php  # Should exist
ls -la config.php    # Already in repo
ls -la .gitignore    # Already in repo
```

### 2. Test Git Status:
```bash
git status
# database.php should NOT appear (it's ignored)
# Only your code changes should appear
```

### 3. Test Application:
- Open: `http://localhost/xerte/`
- Login with your admin credentials
- Create a test project

### 4. Check Database:
```bash
mysql -u xerte_user -p xerte_db

# Inside MySQL:
SHOW TABLES;
# You should see: logindetails, sitedetails, templatedetails, etc.

EXIT;
```

---

## 🆘 Need Help?

### Configuration Issues:
1. Check `error_logs/` folder for error messages
2. Enable debug mode: Set `$development = true;` in `config.php`
3. Check PHP error log

### Database Issues:
1. Verify MySQL is running
2. Test credentials manually: `mysql -u username -p`
3. Check database exists: `SHOW DATABASES;`

### Git Issues:
1. Verify remote: `git remote -v`
2. Check branch: `git branch`
3. View ignored files: `git status --ignored`

---

## 📚 Next Steps

After successful setup:

1. **Secure Your Installation:**
   - Change default passwords
   - Remove or restrict `/setup` folder
   - Set `$development = false` in production

2. **Configure Features:**
   - Set up email notifications
   - Configure file upload limits
   - Enable/disable modules

3. **Start Creating:**
   - Log in to Xerte
   - Create your first learning object
   - Explore templates and features

4. **Join the Community:**
   - Visit: https://xerte.org.uk
   - Report issues on GitHub
   - Contribute improvements

---

**🎉 You're all set! Happy coding!**

