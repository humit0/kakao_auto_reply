<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */
include_once "lib.php";
if (!is_installed()) {
    exit('Need to install! <a href="' . BASE_URL . 'install.php">Install</a>');
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === TRUE) {
    echo '<script>location.href="' . BASE_URL . 'admin.php"</script>';
}
$is_ok = isset($_GET['login']) ? intval($_GET['login']) : 0;
?>
<html>
<head>
    <title>로그인</title>
    <meta charset="utf-8">
</head>
<body>
<?php
switch ($is_ok) {
    case 0:
    ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>?login=1" method="post" onsubmit="return validation(this);">
        <label for="id">아이디 </label>
        <input type="text" name="id" required><br>
        <label for="pw">패스워드 </label>
        <input type="password" name="pw" required><br>
        <input type="submit" value="제출">
    </form>
    <script>
        function validation(form) {
            form.id.value = form.id.value.trim();
            if (form.id.value.length == 0) {
                alert("아이디를 입력하세요.");
                form.id.focus();
                return false;
            }
            if (form.pw.value.length == 0) {
                alert("패스워드를 입력하세요.");
                form.pw.focus();
                return false;
            }
        }
    </script>
<?php
        break;
    case 1:
        if (check_login($_POST['id'], $_POST['pw'])):
            $_SESSION['is_admin'] = TRUE;
?>
    <script>alert("login success!");
        location.href = "<?=BASE_URL?>admin.php"</script>
<?php
        else:
?>
    <script>alert("login failed!");
        history.back();</script>
    <?php
        endif;
        break;
}
?>

</body>
</html>