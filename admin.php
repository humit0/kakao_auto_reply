<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */
include_once 'lib.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== TRUE) {
    show_error(403, "Login plz. <a href=\"" . BASE_URL . "login.php\">Login</a>");
}

$action = isset($_GET['action']) ? $_GET['action'] : 'default';
?>
<html>
<head>
    <title>관리자 페이지</title>
    <meta charset="utf-8">
</head>
<body>
<div align="right">
    <a href="<?= $_SERVER['PHP_SELF'] ?>?action=logout">로그아웃</a>
</div>
<?php
switch ($action) {
    default:
    case "default":
    ?>
    <h3>관리자 페이지</h3>
    <ul>
        <li><a href="<?= $_SERVER['PHP_SELF'] ?>?action=add">버튼 추가하기</a></li>
        <li><a href="<?= $_SERVER['PHP_SELF'] ?>?action=keyboard">Home Keyboard 보기</a></li>
        <li><a href="<?= $_SERVER['PHP_SELF'] ?>?action=find">파일명 찾기</a></li>
        <li><a href="<?= BASE_URL ?>kakao_test.php">테스트 하기</a></li>
    </ul>
<?php
        break;
    case "add":
        if (!isset($_GET['post']) || $_GET['post'] !== "1"):
?>
    <style>
    </style>
    <form action="<?= $_SERVER['PHP_SELF'] ?>?action=add&amp;post=1" method="post" enctype="multipart/form-data">
        <h3>텍스트</h3>
        <label for="content">명령어</label>
        <input type="text" name="content" required><br>
        <label for="message">표시할 메시지 (최대 1000자)</label><br>
        <textarea name="message" cols="30" rows="10" required></textarea><br>

        <h3>이미지</h3>
        <input type="checkbox" id="use_img_ok" onclick="use_image(this.checked);">이미지를 사용하겠습니다.
        <input type="checkbox" id="use_img_url" class="img_form" onclick="use_image_url(this.checked);" disabled>URL로
        사용하겠습니다.<br>
        <label for="img_file2">파일 업로드</label>
        <input type="file" name="img_file" id="img_file2" class="img_form" disabled>
        <label for="img_file1">이미지 url</label>
        <input type="url" name="img_file" id="img_file1" class="img_form" disabled>
        <br>
        <label for="img_width">이미지 width</label>
        <input type="number" name="img_width" class="img_form" value="0" disabled>
        <label for="img_height">이미지 height</label>
        <input type="number" name="img_height" class="img_form" value="0" disabled><br>

        <h3>링크</h3>
        <input type="checkbox" id="use_url_ok" onclick="use_link(this.checked);">링크를 사용하겠습니다.<br>
        <label for="url_path">이동할 링크</label>
        <input type="url" name="url_path" class="url_form" disabled><br>
        <label for="url_msg">표시할 내용</label>
        <input type="text" name="url_msg" class="url_form" disabled><br>

        <h3>Keyboard</h3>
        <input type="checkbox" id="use_keyboard_ok" name="use_default_keyboard" onclick="use_keyboard(this.checked);"
               value="1"> Default keyboard를 사용합니다.<br>
        <label for="keyboard">표시할 Keyboard (Enter로 구분. 아무 내용이 없으면 미디어 업로드를 의미.)</label><br>
        <textarea name="keyboard" cols="60" rows="15" class="keyboard_form"></textarea>
        <input type="submit" value="제출">
    </form>
    <script>
        var img_form = document.getElementsByClassName('img_form');
        var img_file = [document.getElementById('img_file1'), document.getElementById('img_file2')];
        var url_form = document.getElementsByClassName('url_form');
        var keyboard_form = document.getElementsByClassName('keyboard_form');

        use_image(document.getElementById('use_img_ok').checked);
        use_link(document.getElementById('use_url_ok').checked);
        use_keyboard(document.getElementById('use_keyboard_ok').checked);

        function use_image(is_used) {
            for (var i = 0; i < img_form.length; ++i) {
                img_form[i].disabled = !is_used;
            }
            if (is_used) {
                use_image_url(document.getElementById('use_img_url').checked);
            }
        }
        function use_image_url(is_used) {
            is_used += 0;
            img_file[is_used].disabled = true;
            img_file[(is_used + 1) % 2].disabled = false;
        }

        function use_link(is_used) {
            for (var i = 0; i < url_form.length; ++i) {
                url_form[i].disabled = !is_used;
            }
        }
        function use_keyboard(is_used) {
            for (var i = 0; i < keyboard_form.length; ++i) {
                keyboard_form[i].disabled = is_used;
            }
        }
    </script>
<?php
        else:
            $result = add_button();
?>
    /resource/msg/<?= $result ?>에 성공적으로 쓰여졌습니다. <a href="<?= $_SERVER['PHP_SELF'] ?>">돌아가기</a>
<?php
        endif;
        break;
    case "keyboard":
?>
    <h3>Home Keyboard</h3>
    <ul>
        <?php foreach ($DEFAULT_KEYBOARD as $button): ?>
            <li><?= $button ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="javascript:history.back();">뒤로가기</a>
    <?php
        break;
    case "find":
        ?>
        <h3>버튼에 해당하는 파일명 찾기</h3>
        <form method="post">
            <label for="button_name">버튼 이름</label>
            <input type="text" name="button_name" maxlength="30">
            <input type="submit" value="찾기">
        </form>
        <?php if(isset($_POST['button_name'])):
            $filename = get_message_filename($_POST['button_name']);
            $filename_with_path = get_message_file($_POST['button_name']);
            if(file_exists($filename_with_path)):
        ?>
            파일 이름 : <span style="font-weight: bold"><?=$filename?></span><br>
            파일 위치 : <span style="font-weight: bold"><?=$filename_with_path?></span><br>
            <a href="<?=BASE_URL?>download.php?filename=<?=$filename?>">다운로드 하기</a><br><br>
            파일 내용을 수정하시려면 위쪽의 <b>다운로드 하기</b>를 누르시고 내용만 수정하신 후에 FTP로 접속하여<br>
            <b><?=$filename_with_path?></b>를 덮어쓰기 하시면 됩니다.<br>
        <?php
            else:
        ?>
                해당하는 버튼이 아직 생성되지 않았습니다.
        <?php
            endif;
        endif;
        ?>
<?php
        break;
    case "logout":
        unset($_SESSION['is_admin']);
        echo '<script>location.href="' . $_SERVER['PHP_SELF'] . '";</script>';
        break;
}
?>

</body>
</html>
