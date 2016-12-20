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

$title = array('default' => '관리자 페이지', 'add' => '버튼 추가하기', 'keyboard' => 'Home Keyboard 보기 / 수정', 'find' => '파일명 찾기', 'test' => '테스트 하기', 'logout' => '로그아웃');
$script_lists = array('add' => 'add_button.min.js', 'keyboard' => 'keyboard_update.min.js', 'test' => 'kakao_test.min.js');

if (!isset($_GET['post']) || $_GET['post'] !== "1"):
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>관리자 페이지</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    .chips .input{margin-bottom:0!important}div.msg_box{overflow:auto;height:500px;margin-right:20px}a.keyboard_button{margin-right:5px}div.one_msg{display:inline-block}
  </style>
</head>
<body>
  <div class="row">
    <div class="col l9 offset-l3 s12">
      <h4><?php echo isset($title[$action]) ? $title[$action] : 'Error'; ?></h4>
    </div>
    <div class="col l3 s12">
      <div class="collection">
        <a class="collection-item" href="<?= $_SERVER['PHP_SELF'] ?>?action=add">버튼 추가하기</a>
        <a class="collection-item" href="<?= $_SERVER['PHP_SELF'] ?>?action=keyboard">Home Keyboard 보기 / 수정</a>
        <a class="collection-item" href="<?= $_SERVER['PHP_SELF'] ?>?action=find">파일명 찾기</a>
        <a class="collection-item" href="<?= $_SERVER['PHP_SELF'] ?>?action=test">테스트 하기</a>
        <a class="collection-item" href="<?= $_SERVER['PHP_SELF'] ?>?action=logout">로그아웃</a>
      </div>
    </div>
    <div class="col l9 s12">
<?php
endif;

switch ($action) {
    default:
    ?>
        <p>알 수 없는 명령어입니다.</p>
    <?php
        break;
    case "default":
    ?>
    좌측에 있는 메뉴 중에서 원하는 내용을 클릭하시기 바랍니다.
<?php
        break;
    case "add":
        if (!isset($_GET['post']) || $_GET['post'] !== "1"):
?>
    <form enctype="multipart/form-data" id="add_button_form" method="post" action="<?= $_SERVER['PHP_SELF'] ?>?action=add&amp;post=1">
        <div id="button_text" class="col s12">
          <h5>텍스트</h5>
          <div class="input-field col s10">
            <input id="content" type="text" name="content" maxlength="30" required>
            <label for="content">버튼 이름</label>
          </div>

          <div class="input-field col s10">
            <textarea name="message" id="message" class="materialize-textarea" required></textarea>
            <label for="message">표시할 메시지 (최대 1,000자)</label>
          </div>
        </div>

        <div id="button_image" class="col s12">
          <h5>이미지</h5>
          <div class="col s10">
            <input type="checkbox" name="use_img" id="use_img" value="1">
            <label for="use_img">이미지를 사용합니다.</label>
          </div>

          <div id="use_img_content">
            <div class="col s10">
              <input type="checkbox" name="use_img_link" id="use_img_link" value="1">
              <label for="use_img_link">URL로 사용합니다.</label>
            </div>

            <div class="input-field col s10" id="use_img_link_content">
              <input id="img_url_path" type="url" name="img_file">
              <label for="img_url_path">이미지 URL</label>
            </div>

            <div class="file-field input-field col s10" id="use_img_upload_content">
              <div class="btn">
                <span>이미지 File</span>
                <input type="file" name="img_file" id="img_file">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
              </div>
            </div>

            <div class="input-field col s5">
              <input id="img_width" type="number" name="img_width">
              <label for="img_width">이미지 너비</label>
            </div>

            <div class="input-field col s5">
              <input id="img_height" type="number" name="img_height">
              <label for="img_height">이미지 높이</label>
            </div>

          </div>
      
        </div>
        
        <div id="button_link" class="col s12">
          <h5>링크</h5>
          <div class="col s10">
            <input type="checkbox" name="use_link" id="use_link" value="1">
            <label for="use_link">링크를 사용합니다.</label>
          </div>

          <div id="use_link_content">
            <div class="input-field col s10">
              <input id="url_path" type="url" name="url_path">
              <label for="url_path">이동할 링크</label>
            </div>

            <div class="input-field col s10">
              <input id="url_msg" type="text" name="url_msg">
              <label for="url_msg">표시할 내용</label>
            </div>
          </div>

        </div>

        <div id="button_keyboard" class="col s12">
          <h5>Keyboard</h5>
          <div class="col s10">
            <input type="checkbox" name="use_default_keyboard" id="use_default_keyboard" value="1">
            <label for="use_default_keyboard">Default keyboard를 사용합니다.</label>
            
            <div id="use_default_keyboard_content">
              미디어 입력을 원하면 아래를 빈 칸으로 둡니다.
              <div class="chips"></div>
            </div>
          </div>
        </div>

        <a class="waves-effect waves-light btn" onclick="submit_form()">제출하기</a>
      </form>
<?php
        else:
            $result = add_button();
?>
    /resource/msg/<?= $result ?>에 성공적으로 쓰여졌습니다. <a href="<?= $_SERVER['PHP_SELF'] ?>">돌아가기</a>
<?php
        endif;
        break;
    case "keyboard":
        if (!isset($_GET['post']) || $_GET['post'] !== "1"):
?>
            <div id="keyboard_chips" class="chips chips-initial">
                
            </div>
            <a class="waves-effect waves-light btn" onclick="update_home_keyboard()">수정하기</a>
            <form id="update_keyboard" action="<?= $_SERVER['PHP_SELF'] ?>?action=keyboard&amp;post=1" method="post">

    <?php
        else:
            $result = set_default_buttons(explode("\r\n", $_POST['default_buttons']));
            if ($result):
?>
    <script>alert("Keyboard 설정 완료");
        location.href = "<?= $_SERVER['PHP_SELF'] ?>?action=keyboard";</script>
<?php
            else:
?>
    <script>alert("Keyboard 설정 실패");
        history.back();</script>
<?php
            endif;
        endif;
        break;
    case "find":
        ?>
        <div id="search_file" class="col s12">
          <form method="post">
            <div class="input-field col s10">
              <input id="button_name" type="text" name="button_name" maxlength="30">
              <label for="button_name">버튼 이름</label>
            </div>
            <div class="col s2">
              <input type="submit" class="btn waves-effect waves-light" value="찾기" style="margin-top:20px">
            </div>
          </form>
        </div>
        <br>
        <?php if(isset($_POST['button_name'])):
            $filename = get_message_filename($_POST['button_name']);
            $filename_with_path = get_message_file($_POST['button_name']);
            if(file_exists($filename_with_path)):
        ?>
            <div id="result" class="col s12">
              <p>파일 이름 : <strong><?=$filename?></strong></p>
              <p>파일 위치 : <strong><?=$filename_with_path?></strong></p>
              <p><a href="<?=BASE_URL?>download.php?filename=<?=$filename?>">다운로드 하기</a></p>
              <p>파일 내용을 수정하시려면 위쪽의 <b>다운로드 하기</b>를 누르시고 내용만 수정하신 후에 FTP로 접속하여</p>
              <p><b><?=$filename_with_path?></b>를 덮어쓰기 하시면 됩니다.</p>
            </div>
        <?php
            else:
        ?>
              <p>해당하는 버튼이 아직 생성되지 않았습니다.</p>
        <?php
            endif;
        endif;
        break;
    case "test":
        ?>
        <div class="row">
          <div class="cyan msg_box">
            <div class="col s12 m9" id="msg_show"></div>
          </div>
        </div>
        <div id="keyboards"></div>
        <?php
        break;
    case "logout":
        unset($_SESSION['is_admin']);
        header("Location: ".BASE_URL."login.php");
        break;
}

if (!isset($_GET['post']) || $_GET['post'] !== "1"):
?>

    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
  <script>base_url="<?php echo BASE_URL; ?>";</script>
  <?php
  if(isset($script_lists[$action])):
  ?>
    <script src="<?php echo BASE_URL.'static/js/'.$script_lists[$action]?>"></script>
  <?php  
  endif;
  ?>

</body>
</html>
<?php endif; ?>