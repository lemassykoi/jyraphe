Jyraphe, your web file repository
=================================

How to : 
========

Debian 12 fresh install, with apache2 and PHP8:

- enable PHP extension `gettext` in php.ini (`sudo nano /etc/php/8.2/apache2/php.ini` then CTRL+W for search, type gettext and enter. Uncomment the line (remove pound at line start )

- git clone :

```bash
cd /var/www
sudo mv html html.old
sudo git clone https://github.com/lemassykoi/jyraphe.git
sudo mv jyraphe html
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 775 /var/www/html
sudo mkdir -p /data/jyraphe/var-sc6Qu8Lpx4dV1ss
```

- Edit config files:

  - config.php
	  var_root to define : `/data/jyraphe/var-sc6Qu8Lpx4dV1ss`
	  web_root to define : http://YOUR_DEBIAN_INTERNAL_IP_ADDRESS ==> `http://192.168.0.2`
	  lang to define if not french
	  email part (`from_email`, `smtp_host` `smtp_auth` `smtp_port` `smtp_username` and `smtp_password`)

  - libjyraphe/hConfig.php
	  within `private_function`, `var_root` and `jyraphe_root` (same values as in config.php)

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

