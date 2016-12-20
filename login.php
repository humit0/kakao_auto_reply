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

if (!is_session_start()) {
    session_start();
}

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === TRUE) {
    echo '<script>location.href="' . BASE_URL . 'admin.php"</script>';
}
$is_ok = isset($_GET['login']) ? intval($_GET['login']) : 0;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>로그인</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="cyan">
<?php
switch ($is_ok) {
    case 0:
    ?>
    <div id="login-page" class="row">
      <div class="col l4 offset-l4 m6 offset-m3 s8 offset-s2 z-depth-4 card-panel">
        <form class="login-form" action="<?= $_SERVER['PHP_SELF'] ?>?login=1" method="post" onsubmit="return validation(this);">
          <div class="row">
            <div class="input-field col s12 center">
              <p class="center">관리자 로그인</p>
            </div>
          </div>
          <div class="row" style="margin:0 !important">
            <div class="input-field col s12">
              <i class="medium material-icons prefix">perm_identity</i>
              <input id="username" name="username" type="text" required>
              <label for="username">아이디</label>
            </div>
          </div>
          <div class="row" style="margin:0 !important">
            <div class="input-field col s12">
              <i class="medium material-icons prefix">lock_outline</i>
              <input id="password" name="password" type="password" required>
              <label for="password">비밀번호</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <button type="submit" class="btn waves-effect waves-light col s12">로그인</button>
            </div>
          </div>
        </form>
      </div>
    </div>
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
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
<?php
        break;
    case 1:
        if (check_login($_POST['username'], $_POST['password'])):
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