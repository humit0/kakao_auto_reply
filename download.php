<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

include_once 'lib.php';

if (!is_session_start()) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== TRUE) {
    show_error(403, "Login plz. <a href=\"" . BASE_URL . "login.php\">Login</a>");
}

if (!isset($_GET['filename']) || !file_download($_GET['filename']))
    show_error(404, "Cannot download file.");