<?php
/*
 *  Jyraphe Upload Page (LDAP Protected)
 *  Only accessible after Active Directory authentication
 */

define('JYRAPHE_ROOT', dirname(__FILE__) . '/../');

// Preparing the configuration.
$cfg = parse_ini_file(JYRAPHE_ROOT . 'config.php', true);
$cfg = $cfg['Interface'];

// Preparing the language settings.
setlocale(LC_ALL, $cfg['lang']);
bindtextdomain($cfg['jyraphe_package'], JYRAPHE_ROOT . 'lib/locale');
textdomain($cfg['jyraphe_package']);

require(JYRAPHE_ROOT . 'libjyraphe/hConfig.php');
require(JYRAPHE_ROOT . 'libjyraphe/hUpload.php');

hConfig::create(JYRAPHE_ROOT . 'config.php');

require(JYRAPHE_ROOT . 'template/header.php');

// Show authenticated user info
if (isset($_SERVER['REMOTE_USER'])) {
  echo '<div class="message info">';
  echo '<p>' . _('Authenticated as') . ': <strong>' . htmlspecialchars($_SERVER['REMOTE_USER']) . '</strong></p>';
  echo '</div>';
}

try {
  $uploader = new hUpload();

  // Bail out if low on space!
  if(disk_free_space(hConfig::getVar('var_root')) > hSystem::getMaxUploadSize()) {
    // Displays the upload form.
    $uploader->printUploadForm('../upload.php', $cfg);
  } else {
    throw new hException(_('The server is low on disk space, please try again later.'));
  }
}

catch(hException $e) {
  echo '<p class="error">';
  echo '<img src="../media/images/error.png" />&nbsp;';
  echo $e->getMessage();
  echo '</p>';
}

require(JYRAPHE_ROOT . 'template/footer.php');

?>
