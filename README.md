Jyraphe, your web file repository
=================================

How to : 
========

Debian 12 fresh install, with apache2 and PHP8:

- enable PHP extension `gettext` in php.ini (`sudo nano /etc/php/8.2/apache2/php.ini` then CTRL+W for search, type gettext and enter. Uncomment the line (remove comment symbol at line start) or use this bash command:

```bash
sed -i -e 's/;extension=gettext/extension=gettext/g' /etc/php/8.2/apache2/php.ini
```

- enable rewrite url: `a2enmod rewrite`

- git clone :

```bash
cd /var/www
sudo git clone https://github.com/lemassykoi/jyraphe.git
sudo chown -R www-data:www-data /var/www/jyraphe
sudo chmod -R 775 /var/www/jyraphe
sudo mkdir -p /data/jyraphe/var-sc6Qu8Lpx4dV1ss/files
sudo mkdir -p /data/jyraphe/var-sc6Qu8Lpx4dV1ss/links
sudo chown -R www-data:www-data /data/jyraphe/var-sc6Qu8Lpx4dV1ss
sudo chmod -R 775 /data/jyraphe/var-sc6Qu8Lpx4dV1ss
sudo tee /var/www/jyraphe/.htaccess > /dev/null << 'EOF'
RewriteEngine On
RewriteBase /

# Rewrite file-{hash} to index.php?h={hash}
RewriteRule ^file-([a-zA-Z0-9]+)$ index.php?h=$1 [L,QSA]

# Prevent access to sensitive files
<FilesMatch "^(config\.php|\.git)">
    Require all denied
</FilesMatch>
EOF
```

- create apache configuration file
```
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
```

- Edit config files:

  - Copy `config.php.example` to `config.php` and edit:
	  - `var_root` to define : `/data/jyraphe/var-sc6Qu8Lpx4dV1ss/`

	  - `web_root` to define : `https://jyraphe.example.com/` (your domain)

	  - `lang` to define if not french

	  - email part (`from_email`, `smtp_host` `smtp_auth` `smtp_port` `smtp_username` and `smtp_password`)

  - libjyraphe/hConfig.php
	  within `private_function`:
      - `var_root` (same value as in config.php)

      - `jyraphe_root` (same value as in config.php)

- Restart Apache2
  `sudo systemctl restart apache2.service`

You are done!

![image](https://github.com/user-attachments/assets/aad2a10c-4e1f-451f-ab00-35e644f4a953)

Jyraphe is a web application of file repository, easy to install and easy to
use. Jyraphe is an entirely free application, it is distributed under the
terms of the GNU Affero General Public License, version 3 or later. See the
COPYING file in this directory.

For more information, see:
http://home.gna.org/jyraphe/

Jyraphe 0.7 (04 octobre 2013)
-----------------------------

  - FEATURE: Add JQuery 1.10
  - FEATURE: Add JQuery File Upload
  - FEATURE: Add Email notification on upload
  - FEATURE: Ajax rendering
  - FEATURE: Render raw text if called with Curl
  - BUG: CSS fixes
