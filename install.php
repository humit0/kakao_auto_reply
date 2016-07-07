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
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($no == 1 && is_installed()) {
    show_error(400, "이미 설치되었습니다.<a href='" . BASE_URL . "login.php'>로그인</a>");
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>설치하기</title>
</head>
<body>
<?php
switch ($no) {
    case 1:
        if (!isset($_GET['send']) || $_GET['send'] !== "1"):
    ?>
    <form action="<?= $current_url ?>?no=<?= $no ?>&amp;send=1" method="post" onsubmit="return validation(this);">
        <label for="username">Username</label>
        <input type="text" name="username" required><br>
        <label for="password1">Password</label>
        <input type="password" name="password1" required><br>
        <label for="password2">Re-password</label>
        <input type="password" name="password2" required><br>
        <input type="submit" value="제출">
    </form>
    <script>
        function validation(form) {
            form.username.value = form.username.value.trim();
            if (form.username.value.length == 0) {
                alert("아이디가 비어있습니다.");
                form.username.focus();
                return false;
            }
            if (form.password1.value.length == 0) {
                alert("패스워드가 비어있습니다.");
                form.password1.focus();
                return false;
            }
            if (form.username.value.length > 25) {
                alert("아이디는 25자보다 작아야 합니다.");
                return false;
            }
            if (form.password1.value != form.password2.value) {
                alert("패스워드가 일치하지 않습니다.");
                return false;
            }
        }
    </script>
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
    <form action="<?= $current_url ?>?no=<?= $no ?>&amp;send=1" method="post" onsubmit="return validation(this);">
        <label for="default_buttons">초기 Keyboard (Enter로 구분합니다.)</label><br>
        <textarea id="default_buttons" name="default_buttons" cols="60" rows="30" required></textarea>
        <input type="submit" value="제출">
    </form>
    <script>
        function validation(form) {
            form.default_buttons.value = form.default_buttons.value.trim().replace("\r\n", "\n").replace(/\n{2,}/gm, '\n');
            if (form.default_buttons.value.length == 0) {
                alert("폼이 비어있습니다.");
                form.default_buttons.focus();
                return false;
            }
        }
    </script>
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
    User : <?= $_SESSION['username'] ?><br/>
    Default Keyboard : [<?= implode(", ", $DEFAULT_KEYBOARD); ?>]
    <a href="<?= BASE_URL . "login.php" ?>">완료</a>
    <?php
        break;
}
?>
</body>
</html>