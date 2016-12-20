<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

include_once 'lib.php';

$no = isset($_GET['no']) ? intval($_GET['no']) : 1;
$current_url = $_SERVER['PHP_SELF'];
if (!is_session_start()) {
    session_start();
}

if ($no == 1 && is_installed()) {
    show_error(400, "이미 설치되었습니다.<a href='" . BASE_URL . "login.php'>로그인</a>");
}

$script = array(1 => 'install_admin_id.min.js', 2 => 'keyboard_config.min.js');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>설치하기</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>.chips .input{margin-bottom:0!important}</style>
</head>
<body>
<div class="container">
<?php
switch ($no) {
    case 1:
        if (!isset($_GET['send']) || $_GET['send'] !== "1"):
    ?>
        
    <form action="<?= $current_url ?>?no=<?= $no ?>&amp;send=1" method="post" onsubmit="return validation(this);">
        <div class="row">
            <h5>관리자 계정 설정</h5>
            <div class="input-field col s8">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required><br>
            </div>
            <div class="input-field col s8">
                <label for="password1">Password</label>
                <input type="password" id="password1" name="password1" required><br>
            </div>
            <div class="input-field col s8">
                <label for="password2">Re-password</label>
                <input type="password" id="password2" name="password2" required><br>
            </div>
            <div class="input-field col s8">
                <input type="submit" class="btn waves-effect waves-light col s12" value="제출">
            </div>
        </div>
    </form>
<?php
        else:
            $is_user_add = user_add($_POST['username'], $_POST['password1']);
            $_SESSION['username'] = $_POST['username'];
            if ($is_user_add === TRUE):
?>
    <script>alert("계정 생성 완료");
        location.href = "<?=$current_url?>?no=<?=$no+1?>"</script>
<?php
            else:
?>
    <script>alert("계정 생성 실패(<?=$is_user_add['reason']?>)");
        history.back();</script>
<?php
            endif;
        endif;
        break;
    case 2:
        if (!isset($_GET['send']) || $_GET['send'] !== "1"):
?>
        <div class="row">
            <h5>초기 Keyboard 설정하기</h5>
            <div class="chips" id="keyboard_chips"></div>
            <a class="waves-effect waves-light btn" onclick="update_home_keyboard()">설정하기</a>
            <form id="update_keyboard" action="<?= $current_url ?>?no=<?= $no ?>&amp;send=1" method="post">
            </form>
        </div>
<?php
        else:
            $result = set_default_buttons(explode("\r\n", $_POST['default_buttons']));
            if ($result):
?>
    <script>alert("초기 Keyboard 설정 완료");
        location.href = "<?=$current_url?>?no=<?=$no+1?>";</script>
<?php
            else:
?>
    <script>alert("초기 Keyboard 설정 실패");
        history.back();</script>
<?php
            endif;
        endif;
        break;
    case 3:
?>
    <div class="row">
        <div class="col s12">
            <h5>설정 확인하기</h5>
            <p>User : <?= $_SESSION['username'] ?></p>
            <p>Default Keyboard : [<?= implode(", ", $DEFAULT_KEYBOARD); ?>]</p>
            <p><a href="<?= BASE_URL . "login.php" ?>">완료</a></p>
        </div>
    </div>
    <?php
        break;
}
?>
</div>
<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
<?php
if (!isset($_GET['send']) || $_GET['send'] !== "1")
    echo '<script src="'.BASE_URL.'static/js/'.$script[$no].'"></script>';
?>
</body>
</html>