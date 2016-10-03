<?php
$ADMIN_INFO = NULL;
$DEFAULT_KEYBOARD = NULL;
// Update your base url.
define("BASE_URL", "http://kakao.humit.tk/");
// Only access kakao server or not.
define("IP_CHECK", TRUE);
// Show all warnings or not.
define("DEBUG", FALSE);

include_once __DIR__ . '/keyboard.config.php';
include_once __DIR__ . '/admin.config.php';
?>