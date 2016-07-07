<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */
include_once 'lib.php';

if (!is_installed())
    exit('Need to install! <a href="' . BASE_URL . 'install.php">Install</a>');
include_once 'router.php';