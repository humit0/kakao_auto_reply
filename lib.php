<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/lib.add.php';

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

define("MESSAGE_PATH", __DIR__ . '/resource/msg');

/**
 * Return if it is installed or not.
 *
 * @return bool
 */
function is_installed()
{
    global $DEFAULT_KEYBOARD, $ADMIN_INFO;
    if (is_null($DEFAULT_KEYBOARD) || is_null($ADMIN_INFO)) {
        return FALSE;
    } else {
        return TRUE;
    }
}

/**
 * Return the requested ip is valid or not.
 *
 * @return bool
 */
function ip_check()
{
    if (!IP_CHECK) {
        return TRUE;
    }
    // check it https://github.com/plusfriend/auto_reply#71-proxy-server-information
    $allowed_ips = array("110.76.143.234", "110.76.143.235", "110.76.143.236");
    $ip = $_SERVER['REMOTE_ADDR'];
    if (in_array($ip, $allowed_ips)) {
        return TRUE;
    }
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']);
}

/**
 * Display error message with error code.
 *
 * @param int $err_no error code
 * @param string $message error message
 */
function show_error($err_no, $message)
{
    switch ($err_no) {
        case 400:
            header("HTTP/1.1 400 Bad request");
            break;
        case 401:
            header("HTTP/1.1 401 Unauthorized");
            break;
        case 403:
            header("HTTP/1.1 403 Forbidden");
            break;
        default:
            header("HTTP/1.1 200 OK");
            break;
    }
    exit($message);
}

/**
 * Return file name matched by button.
 *
 * @param string $content button
 * @return string file name
 */
function get_message_filename($content)
{
    return 'msg_' . md5('k@k@o_@ut0r2p1y' . $content) . '.php';
}

/**
 * Return full path of the file matched by button.
 *
 * @param string $content button
 * @return string file name
 */
function get_message_file($content)
{
    return MESSAGE_PATH . '/' . get_message_filename($content);
}

/**
 * Return the end line with tab size.
 *
 * @param int $tab_size tab size
 * @return string end line
 */
function end_line($tab_size = 0)
{
    return "\n" . str_repeat("\t", $tab_size);
}

/**
 * Write message into matched file.
 *
 * @param string $content button
 * @param Msg $msg showed message
 */
function write_msg_file($content, \kakao\Msg $msg)
{
    $f = fopen(get_message_file($content), "w");
    fwrite($f, "<?php\n// content : {$content}\nuse \\kakao\\Msg;\nuse \\kakao\\Msg\\Message;\nuse \\kakao\\Keyboard;");
    fwrite($f, "\n\necho ");
    fwrite($f, $msg->get_class());
    fwrite($f, ";");
    fclose($f);
}

/**
 * Set Home Keyboard and return success or not.
 *
 * @param array $buttons button
 * @return bool
 */
function set_default_buttons($buttons)
{
    $f = fopen(__DIR__ . '/keyboard.config.php', "w");
    fwrite($f, "<?php\n$" . "DEFAULT_KEYBOARD = array(");
    foreach ($buttons as $key => &$val) {
        $val = trim($val);
        if ($val === "")
            unset($buttons[$key]);
    }
    fwrite($f, '"' . implode('","', array_map('addslashes', $buttons)) . '"');
    fwrite($f, ");");
    fclose($f);
    return TRUE;
}

/**
 * Add user and return TRUE if success, array include reason if failed.
 *
 * @param string $user_name user name
 * @param string $password password
 * @return array|bool
 */
function user_add($user_name, $password)
{
    $user_name = addslashes($user_name);
    $password = addslashes($password);
    if ($user_name == "" || !isset($user_name) || $password == "" || !isset($password)) {
        return array("reason" => "빈 칸이 있습니다.");
    }
    $f = fopen(__DIR__ . '/admin.config.php', "w");
    fwrite($f, "<?php\n$" . "ADMIN_INFO = array(");
    fwrite($f, '"user_name"=>"' . $user_name . '",');
    fwrite($f, '"password"=>"' . $password . '"');
    fwrite($f, ");");
    fclose($f);
    return TRUE;
}

/**
 * Return valid user or not
 *
 * @param string $user_name user name
 * @param string $password password
 * @return bool
 */
function check_login($user_name, $password)
{
    global $ADMIN_INFO;
    return $user_name === $ADMIN_INFO['user_name'] && $password === $ADMIN_INFO['password'];
}

use kakao\Keyboard;
use kakao\Msg;
use kakao\Msg\Message;

/**
 * Add button with POST data.
 *
 * @return string message file name
 */
function add_button()
{

    include_once __DIR__ . '/class/Keyboard.php';
    include_once __DIR__ . '/class/Message.php';

    $keyboard = TRUE;
    if (!isset($_POST['use_default_keyboard'])) {
        $keyboard = preg_replace('/^\s+/m', '', trim($_POST['keyboard']));
        if ($keyboard !== '')
            $keyboard = new Keyboard(explode("\r\n", $keyboard));
        else
            $keyboard = new Keyboard(NULL);
    }

    $photo = NULL;
    if (isset($_POST['img_file'])) {
        $photo = array($_POST['img_file'], $_POST['img_width'], $_POST['img_height']);
    } else if (isset($_FILES['img_file'])) {
        $save_dir = "resource/img/";
        $origin_file = $_FILES['img_file']['name'];
        $target = $save_dir . date("YmdHis") . "." . strtolower(substr($origin_file, strrpos($origin_file, '.') + 1));
        if (!move_uploaded_file($_FILES['img_file']['tmp_name'], $target))
            show_error(400, "Failed to upload img file...");
        $photo = array(BASE_URL . $target, $_POST['img_width'], $_POST['img_height']);
    }

    $msg_button = NULL;
    if (isset($_POST['url_path'])) {
        $msg_button = array($_POST['url_msg'], $_POST['url_path']);
    }
    $msg = new Msg(new Message($_POST['message'], $photo, $msg_button), $keyboard);

    if (!$msg->is_valid())
        show_error(400, $msg->get_invalid_msg());
    // write file.
    write_msg_file($_POST['content'], $msg);

    return get_message_filename($_POST['content']);
}

/**
 * File download & return download is success or not.
 *
 * @param string $filename file name
 * @param string $path file path
 * @return bool
 */
function file_download($filename, $path = MESSAGE_PATH)
{
    // checking file existence.
    if (!file_exists($path . "/" . $filename))
        return FALSE;
    // checking valid input or not.
    if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]+$/', $filename)) {
        return FALSE;
    }

    // download file.
    $file = $path . "/" . $filename;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    return TRUE;
}

/**
 * Check session started or not.
 *
 * @return bool
 */
function is_session_start()
{
    if (php_sapi_name() !== 'cli') {
        if (version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}