;; -*- mode: Conf -*-
;<?php header("location: i_do_not_exist.html"); exit;?>
; .ini file.
; config.php
;
; This was automatically generated and contains the configuration for
; your jyraphe installation. Edit with care.

[Core]
; Must be located outside of docroot
var_root = /data/jyraphe/var-sc6Qu8Lpx4dV1ss/

hash_size = 32
from_email = wetransfer@ext.europrecis.eu
smtp_host = 127.0.0.1
smtp_auth = true
smtp_port = 587
smtp_username = "wetransfer@ext.europrecis.eu"
smtp_password = "a*VhR9s(#$B)PD^3"
disable_infinity = false
rewrite = true

[Cleaner]
enabled = true

; Comma-separated IP addresses list. (don't put space after comma)
allow_ips = 127.0.0.1

[Interface]
web_root = https://wetransfer.ext.europrecis.eu/
style = default
jyraphe_package = Jyraphe
lang = fr_FR.UTF-8

; Custom server name displayed in page title and header
server_name = "Europrecis WeTransfer Server"

; Company logo filename (place file in media/images/ directory as logo.jpg). Leave empty to disable.
company_logo = logo.jpg

;Default validity period. Available options: 1m, 1h, 1d, 1w, 1M, F (forever)
validity = 1d
