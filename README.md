# Jyraphe - Secure File Sharing

A lightweight, secure file sharing application with LDAP authentication and email notifications. Based on the original Jyraphe project with modern security enhancements.

## Features

- 🔒 **LDAP/Active Directory Authentication** - Restrict uploads to authorized users
- 📧 **Email Notifications** - Notify recipients when files are uploaded
- 🔐 **Password Protection** - Optional password protection for downloads
- ⏰ **Automatic Expiration** - Files auto-delete after configurable time periods
- 🎨 **Customizable Branding** - Add your logo and custom server name
- 🔑 **Secure by Design** - Cryptographically secure random hashes, timing-safe password comparison
- 📱 **AJAX Upload** - Modern drag-and-drop file upload with progress tracking

## Requirements

- **PHP 8.0+** with extensions:
  - `gettext` (i18n)
  - `mbstring` (string handling)
  - `openssl` (secure random generation, TLS encryption)
- **Apache 2.4+** with modules:
  - `mod_rewrite` (clean URLs)
  - `mod_authnz_ldap` and `mod_ldap` (if using LDAP authentication)
  - `mod_ssl` (HTTPS)
- **Debian 12** or similar Linux distribution

## Installation

### 1. Clone Repository

```bash
cd /var/www
sudo git clone https://github.com/lemassykoi/jyraphe.git
sudo chown -R www-data:www-data /var/www/jyraphe
sudo chmod -R 755 /var/www/jyraphe
```

### 2. Create Data Directory

The data directory must be **outside the document root** for security:

```bash
sudo mkdir -p /data/jyraphe/var-sc6Qu8Lpx4dV1ss/files
sudo mkdir -p /data/jyraphe/var-sc6Qu8Lpx4dV1ss/links
sudo chown -R www-data:www-data /data/jyraphe/var-sc6Qu8Lpx4dV1ss
sudo chmod -R 755 /data/jyraphe/var-sc6Qu8Lpx4dV1ss
```

### 3. Enable PHP Extensions

```bash
# Enable gettext extension
sudo sed -i -e 's/;extension=gettext/extension=gettext/g' /etc/php/8.2/apache2/php.ini
```

### 4. Enable Apache Modules

```bash
sudo a2enmod rewrite ssl

# If using LDAP authentication:
sudo a2enmod authnz_ldap ldap
```

### 5. Configure Apache VirtualHost

Create `/etc/apache2/sites-available/jyraphe.conf`:

```apache
<IfModule mod_ssl.c>
    <VirtualHost *:443>
        ServerName jyraphe.example.com
        ServerAdmin admin@example.com
        DocumentRoot /var/www/jyraphe
        
        <Directory /var/www/jyraphe>
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/jyraphe_error.log
        CustomLog ${APACHE_LOG_DIR}/jyraphe_access.log combined

        SSLEngine on
        SSLCertificateFile      /etc/letsencrypt/live/jyraphe.example.com/fullchain.pem
        SSLCertificateKeyFile   /etc/letsencrypt/live/jyraphe.example.com/privkey.pem
        Include /etc/letsencrypt/options-ssl-apache.conf
    </VirtualHost>
</IfModule>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName jyraphe.example.com
    Redirect permanent / https://jyraphe.example.com/
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite jyraphe.conf
sudo systemctl reload apache2
```

### 6. Obtain SSL Certificate (Let's Encrypt)

Install certbot and obtain a free SSL certificate:

```bash
# Install certbot
sudo apt update
sudo apt install certbot python3-certbot-apache

# Obtain certificate (interactive)
sudo certbot --apache -d jyraphe.example.com

# Certbot will automatically:
# - Verify domain ownership
# - Issue certificate
# - Configure Apache SSL settings
# - Set up auto-renewal

# Test auto-renewal
sudo certbot renew --dry-run
```

**Note:** Ensure your domain points to your server's public IP before running certbot.

**Manual certificate installation** (if not using certbot's auto-config):

After obtaining certificates, they'll be located at:
- Certificate: `/etc/letsencrypt/live/jyraphe.example.com/fullchain.pem`
- Private Key: `/etc/letsencrypt/live/jyraphe.example.com/privkey.pem`

Certificates auto-renew via cron. Verify renewal cron exists:

```bash
sudo systemctl status certbot.timer
```

### 7. Configure .htaccess Files

```bash
# Copy example files
sudo cp /var/www/jyraphe/.htaccess.example /var/www/jyraphe/.htaccess
sudo cp /var/www/jyraphe/upload/.htaccess.example /var/www/jyraphe/upload/.htaccess

# If using LDAP authentication, edit with your LDAP server details:
sudo nano /var/www/jyraphe/.htaccess
sudo nano /var/www/jyraphe/upload/.htaccess

# If NOT using LDAP, comment out the <Files "upload.php"> section in .htaccess
```

### 8. Configure Application

```bash
# Copy example configuration
sudo cp /var/www/jyraphe/config.php.example /var/www/jyraphe/config.php
sudo nano /var/www/jyraphe/config.php
```

**Required settings in `config.php`:**

```ini
[Core]
var_root = /data/jyraphe/var-sc6Qu8Lpx4dV1ss/
from_email = noreply@jyraphe.example.com
smtp_host = 127.0.0.1
smtp_port = 587
smtp_username = "your_smtp_user"
smtp_password = "your_smtp_password"

[Interface]
web_root = https://jyraphe.example.com/
lang = en_US.UTF-8
server_name = "Your Company File Sharing"
company_logo = logo.jpg
```

**Edit `libjyraphe/hConfig.php`:**

Update the `private_function()` method with matching values:

```php
private function private_function() {
    $this->settings['jyraphe_root'] = "https://jyraphe.example.com/";
    $this->settings['var_root']     = "/data/jyraphe/var-sc6Qu8Lpx4dV1ss/";
}
```

### 9. Optional: Add Custom Logo

Place your logo as `media/images/logo.jpg` (max 300px width recommended).

### 10. Restart Apache

```bash
sudo systemctl restart apache2
```

### 11. Test Your Installation

Visit `https://jyraphe.example.com/` and verify:
- Landing page displays correctly
- Upload button redirects to authentication (if LDAP enabled)
- File upload works
- Download links work

## LDAP/Active Directory Configuration

If using LDAP authentication, configure in both `.htaccess` files:

```apache
AuthType Basic
AuthName "Jyraphe Upload - Enter USERNAME only (without @domain)"
AuthBasicProvider ldap
AuthLDAPURL "ldaps://dc-server.example.lan:636/DC=EXAMPLE,DC=lan?sAMAccountName?sub"
AuthLDAPBindDN "CN=Service Account,OU=ServiceAccounts,DC=EXAMPLE,DC=lan"
AuthLDAPBindPassword "your_bind_password"
Require ldap-group CN=Jyraphe_Users,OU=Groups,DC=EXAMPLE,DC=lan
```

**Important:** Users enter their **username only** (not `user@domain`) when authenticating.

## Security Features

This fork includes several security enhancements over the original Jyraphe:

- ✅ **Cryptographically secure random hash generation** - Uses `random_bytes()` instead of `rand()`
- ✅ **Timing-safe password comparison** - Uses `hash_equals()` to prevent timing attacks
- ✅ **Extended file extension filtering** - Blocks `.php`, `.phtml`, `.phar`, `.phps` uploads
- ✅ **Secure file permissions** - Files created with `0644` instead of `0777`
- ✅ **Protected configuration files** - `.htaccess` prevents direct access to sensitive files
- ✅ **STARTTLS/TLS support** - Encrypted SMTP email notifications
- ✅ **LDAP authentication** - Restrict uploads to authorized users
- ✅ **Paste prevention** - Blocks accidental clipboard uploads in file input

## Maintenance

### Automatic Cleanup

Configure a cron job to remove expired files:

```bash
# Run cleaner every hour
0 * * * * curl -s https://jyraphe.example.com/cleaner.php
```

Or run manually:

```bash
php /var/www/jyraphe/cleaner.php
```

### Backups

Regularly backup:
- `/var/www/jyraphe/` - Application code
- `/data/jyraphe/` - Uploaded files and metadata
- `/etc/apache2/sites-available/jyraphe.conf` - Apache configuration

## Troubleshooting

### Upload fails with "can not be moved into var folder"
- Check data directory exists and is writable by `www-data`
- Verify permissions: `ls -la /data/jyraphe/var-sc6Qu8Lpx4dV1ss/`

### 404 on download links
- Check `.htaccess` exists in document root
- Verify `mod_rewrite` is enabled: `apache2ctl -M | grep rewrite`
- Check `AllowOverride All` in Apache vhost config

### LDAP authentication fails
- Verify Apache LDAP modules loaded: `apache2ctl -M | grep ldap`
- Check LDAP server connectivity and credentials
- Review Apache error log: `tail -f /var/log/apache2/jyraphe_error.log`

### SMTP emails not sending
- Verify SMTP credentials in `config.php`
- Check port 587 is open for TLS connections
- Test SMTP manually: `telnet smtp_host 587`

## License

Jyraphe is free and open-source software distributed under the **GNU Affero General Public License v3.0** or later. See the COPYING file for details.

## Credits

- **Original Jyraphe Project** - http://home.gna.org/jyraphe/ (2013)
- **This Fork** - Security enhancements, LDAP authentication, modern features (2024-2025)

## Support

For issues, questions, or contributions, please use the GitHub issue tracker:
https://github.com/lemassykoi/jyraphe/issues
