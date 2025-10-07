Xerte Online Toolkits
=====================

Latest release : v3.13 (released on October 31, 2024)

Installation Instructions (Stable release, .zip)
------------------------------------------------


Here's a quick guide to installing toolkits on your local computer:

 1. Download and install XAMPP from http://www.apachefriends.org accepting the default settings;
 2. Download Xerte Online Toolkits from http://xerte.org.uk
 3. Unzip the folder 'xertetoolkits' to c:\xampp\htdocs\, giving you c:\xampp\htdocs\xertetoolkits
 4. Start Apache and MySQL in XAMPP control panel
 5. Visit http://localhost/xertetoolkits/setup
 6. Follow the steps through the setup wizard

Installation Instructions (Mac)
--------------------------------

### Option 1: Using MAMP (Recommended for Mac users)

1. **Download and install MAMP** from https://www.mamp.info
2. **Download or clone Xerte**:
   ```bash
   git clone https://github.com/hbhariharasuthan/xerte.git
   ```
3. **Move to MAMP directory**:
   ```bash
   # Default MAMP document root
   mv xerte /Applications/MAMP/htdocs/
   ```
4. **Set permissions**:
   ```bash
   cd /Applications/MAMP/htdocs/xerte
   chmod -R 755 USER-FILES/
   chmod -R 755 error_logs/
   chmod -R 755 import/
   ```
5. **Start MAMP servers**:
   - Launch MAMP application
   - Click "Start Servers" to start Apache and MySQL
6. **Configure PHP** (if needed):
   - MAMP > Preferences > PHP
   - Ensure PHP 7.x or higher is selected
   - Enable required extensions: mysqli, xml, curl, mbstring, zip
7. **Run setup wizard**:
   - Visit http://localhost:8888/xerte/setup (default MAMP port)
   - Follow the setup wizard instructions
   - Default MySQL credentials in MAMP:
     - Host: `localhost`
     - Port: `8889` (or `3306` for MAMP PRO)
     - Username: `root`
     - Password: `root`

### Option 2: Using Homebrew (Advanced)

1. **Install Homebrew** (if not already installed):
   ```bash
   /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
   ```

2. **Install required packages**:
   ```bash
   # Install Apache
   brew install httpd
   
   # Install PHP with required extensions
   brew install php@8.1
   
   # Install MySQL
   brew install mysql
   ```

3. **Configure Apache**:
   ```bash
   # Edit Apache config
   nano /opt/homebrew/etc/httpd/httpd.conf
   
   # Add/uncomment these lines:
   # LoadModule php_module /opt/homebrew/opt/php@8.1/lib/httpd/modules/libphp.so
   # DocumentRoot "/opt/homebrew/var/www"
   # <Directory "/opt/homebrew/var/www">
   #   Options Indexes FollowSymLinks
   #   AllowOverride All
   #   Require all granted
   # </Directory>
   ```

4. **Clone repository**:
   ```bash
   cd /opt/homebrew/var/www
   git clone https://github.com/hbhariharasuthan/xerte.git
   cd xerte
   ```

5. **Set permissions**:
   ```bash
   chmod -R 755 USER-FILES/
   chmod -R 755 error_logs/
   chmod -R 755 import/
   ```

6. **Start services**:
   ```bash
   # Start Apache
   brew services start httpd
   
   # Start MySQL
   brew services start mysql
   
   # Secure MySQL installation
   mysql_secure_installation
   ```

7. **Access setup wizard**:
   - Visit http://localhost/xerte/setup
   - Follow the on-screen instructions

### Mac-specific Notes:

- **PHP Extensions**: Check enabled extensions with `php -m`
- **Apache Document Root**: 
  - MAMP: `/Applications/MAMP/htdocs/`
  - Homebrew: `/opt/homebrew/var/www/` (Apple Silicon) or `/usr/local/var/www/` (Intel)
- **File Permissions**: Use `chmod` and `chown` if you encounter permission issues
- **MySQL Socket**: If connection fails, check socket location in PHP configuration
- **Port Configuration**: 
  - MAMP uses ports 8888 (Apache) and 8889 (MySQL) by default
  - Homebrew uses ports 80 (Apache) and 3306 (MySQL) by default

Installation Instructions (unstable release, github)
--------------------------------------------------

```
cd /path/to/apache/document/root
git clone https://github.com/thexerteproject/xerteonlinetoolkits.git .
```

Requires :

 1. PHP v7.x with either mysql, xml, curl, mbstring and zip extensions available.
 2. Apache or some other web server that is setup to execute PHP.
 3. Write permission to USER-FILES

Optional additions :

 1. ClamAV - if /usr/bin/clamscan exists, uploads will be checked for viruses. Requires appropriate AV definitions are in place.
 2. XML parsing - if PHP has the 'xml' module installed, then we'll validate the Learning Object's XML before saving on the server.
 3. Transcoding support for video files - see/read cron/transcoding.php - when run it will attempt to convert .flv files to .mp4 files to improve template viewing on Adobe-flash-free devices.


For full installation instructions please see the documentation/ToolkitsInstallationGuide.pdf

# Xerte Online Toolkits - Setup Guide

## Repository Information

**Repository URL**: https://github.com/hbhariharasuthan/xerte.git

## For New Developers/Deployments

After cloning this repository, follow these steps to set up your local or production environment:

---

## 📋 Prerequisites

1. **Web Server**: Apache or Nginx with PHP support
2. **PHP**: Version 7.x or higher with extensions:
   - `mysqli` or `pdo_mysql`
   - `xml`
   - `curl`
   - `mbstring`
   - `zip`
3. **MySQL/MariaDB**: Database server
4. **Write Permissions**: The web server needs write access to `USER-FILES/` directory

---

## 🚀 Installation Steps

### Option 1: Web-Based Setup (Recommended for First-Time Setup)

1. **Clone the repository**:
   ```bash
   git clone https://github.com/hbhariharasuthan/xerte.git
   cd xerte
   ```

2. **Set permissions**:
   ```bash
   # On Linux/Mac:
   chmod -R 755 USER-FILES/
   chmod -R 755 error_logs/
   
   # On Windows (XAMPP):
   # Ensure the web server user has write access to these folders
   ```

3. **Access the setup wizard**:
   - Open your browser and navigate to: `http://localhost/xerte/setup/`
   - Follow the on-screen instructions
   - The setup wizard will:
     - Check system requirements
     - Create the database
     - Generate `database.php` configuration file
     - Set up initial admin account

4. **Complete installation**:
   - After successful setup, **delete or restrict access to the `/setup` directory** for security

---

### Option 2: Manual Configuration

If you prefer to set up configuration files manually:

#### 1. Database Configuration

Copy and configure the database settings:

```bash
# The database.php file will be created by the setup wizard
# Or you can create it manually using setup/database.txt as a template
```

**Create `database.php`** in the root directory with your database credentials:

```php
<?php
$xerte_toolkits_site->database_type = 'mysqli';
$xerte_toolkits_site->database_host = 'localhost';
$xerte_toolkits_site->database_username = 'your_db_user';
$xerte_toolkits_site->database_password = 'your_db_password';
$xerte_toolkits_site->database_name = 'xerte_db';
$xerte_toolkits_site->database_table_prefix = '';
```

#### 2. Optional Configuration Files

These are **optional** and only needed for specific features:

**Authentication Configuration** (if using LDAP/custom auth):
```bash
cp auth_config.php.dist auth_config.php
# Edit auth_config.php with your settings
```

**API Keys** (if using external APIs):
```bash
cp api_keys_dist.php api_keys.php
# Edit api_keys.php with your API keys
```

**LRS Configuration** (if using xAPI/LRS):
```bash
cp lrsdb_config.php.dist lrsdb_config.php
# Edit lrsdb_config.php with your LRS settings
```

**Reverse Proxy** (if behind a proxy):
```bash
cp reverse_proxy_conf.php.dist reverse_proxy_conf.php
# Edit reverse_proxy_conf.php with your proxy settings
```

---

## 🔧 Configuration Files Overview

| File | Purpose | Created By | Required? |
|------|---------|------------|-----------|
| `database.php` | Database credentials | Setup wizard | ✅ Yes |
| `config.php` | Core application config | Already in repo | ✅ Yes (committed) |
| `auth_config.php` | Authentication settings | Copy from `.dist` | ⚠️ Optional |
| `api_keys.php` | External API keys | Copy from `.dist` | ⚠️ Optional |
| `lrsdb_config.php` | LRS/xAPI database | Copy from `.dist` | ⚠️ Optional |
| `reverse_proxy_conf.php` | Proxy configuration | Copy from `.dist` | ⚠️ Optional |

---

## 🔒 Security Notes

### Files NOT Committed to Git (`.gitignore`):
- `database.php` - Contains sensitive database credentials
- `auth_config.php` - May contain LDAP/auth secrets
- `api_keys.php` - Contains API keys
- `lrsdb_config.php` - May contain additional DB credentials
- `reverse_proxy_conf.php` - Server-specific configuration

### Files Committed to Git:
- `*.dist` files - Template files for optional configurations
- `config.php` - Core application configuration (no secrets)

**⚠️ NEVER commit files containing passwords, API keys, or secrets!**

---

## 📦 After Setup

1. **Update `config.php`** (if needed):
   - Set `$development = false;` for production
   - Configure error logging paths
   - Set site-specific variables

2. **Test the installation**:
   - Navigate to: `http://localhost/xerte/`
   - Log in with your admin credentials
   - Create a test project

3. **Set up cron jobs** (optional, for video transcoding):
   ```bash
   # Add to crontab:
   */5 * * * * php /path/to/xerte/cron/transcoder.php
   ```

---

## 🆘 Troubleshooting

### Setup wizard not working?
- Check PHP extensions are installed
- Verify database server is running
- Check write permissions on `USER-FILES/` and parent directory

### Database connection errors?
- Verify database credentials in `database.php`
- Check MySQL/MariaDB service is running
- Ensure database user has proper privileges

### Permission errors?
- Ensure web server can write to:
  - `USER-FILES/`
  - `error_logs/`
  - `import/`

---

## 🔄 For Existing Installations

If you're updating an existing Xerte installation:

1. **Backup your database**
2. **Backup configuration files**:
   - `database.php`
   - `auth_config.php` (if exists)
   - `api_keys.php` (if exists)
   - Any other custom configs
3. **Pull latest changes**:
   ```bash
   git pull origin master
   ```
4. **Restore your configuration files**
5. **Run upgrade script** (if prompted):
   ```
   http://localhost/xerte/upgrade.php
   ```

---

## 📚 Additional Documentation

See the `/documentation` folder for:
- `ToolkitsInstallationGuide.pdf` - Full installation guide
- `guide_to_upgrading_an_existing_installation.pdf` - Upgrade instructions
- `integration.txt` - LMS integration guide
- `ldap.txt` - LDAP authentication setup

---

## 🤝 Contributing

When contributing code:
1. Never commit actual configuration files (only `.dist` templates)
2. Test your changes with a fresh setup
3. Update this guide if you add new configuration requirements
4. Follow the existing code standards

---

## 📞 Support

For issues and questions:
- GitHub Repository: https://github.com/hbhariharasuthan/xerte
- GitHub Issues: https://github.com/hbhariharasuthan/xerte/issues
- Official Xerte Project: https://xerte.org.uk

