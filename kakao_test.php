<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

include_once "lib.php";

if (!is_session_start()) {
    session_start();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== TRUE) {
    show_error(403, "Login plz. <a href=\"" . BASE_URL . "login.php\">Login</a>");
}
?>
<html>
<head>
    <meta charset="utf-8">
    <title>카카오 자동 응답 테스트</title>
    <style>
        #msg_show {
            overflow: auto;
            width: 50%;
            height: 80%;
            float: left;
            padding: 10px;
            margin: 5px;
            border: 1px black solid;
            background: skyblue;
        }

        #keyboards {
            width: 40%;
            float: left;
            padding: 10px;
        }

        .msg_elem {
            background: white;
            width: auto;
            display: inline-block;
            margin: 5px;
        }

        .keyboard_button {
            margin: 3px;
        }

        button.msg_link {
            width: 100%;
        }

        pre.msg_text {
            margin: 1em;
        }
    </style>
</head>
<body>
<div align="right">
    <a href="<?= BASE_URL ?>admin.php?action=logout">로그아웃</a>
</div>
<div id="msg_show">

</div>
<div id="keyboards">

</div>
<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<script>
    var parent_keyboard = $('#keyboards');
    var parent_msg = $('#msg_show');
    var base_url = '<?=BASE_URL?>';
    function keyboard_update(keyboards) {
        parent_keyboard.empty();
        $.each(keyboards, function (index, value) {
            parent_keyboard.append($('<button />', {
                text: value,
                click: function () {
                    keyboard_click(value)
                },
                class: 'keyboard_button'
            }));
        });
    }
    function keyboard_click(value) {
        $.ajax({
            url: base_url + "message",
            type: "POST",
            data: JSON.stringify({'user_key': 'dummy', 'content': value, 'type': 'text'}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (data) {
                var $elem = $('<div>', {
                    class: 'msg_elem'
                });
                $elem.append($('<pre>', {
                    text: data['message']['text'],
                    class: 'msg_text'
                }));
                if (data['message']['photo'] != null) {
                    $elem.append($('<img>', {
                        src: data['message']['photo']['url'],
                        width: data['message']['photo']['width'],
                        height: data['message']['photo']['height'],
                        class: 'msg_img'
                    })).append('<br>');
                }
                if (data['message']['message_button'] != null) {
                    $elem.append($('<button>', {
                        text: data['message']['message_button']['label'],
                        click: function () {
                            alert('"' + data['message']['message_button']['url'] + '"로 이동');
                        },
                        class: 'msg_link'
                    })).append('<br>');
                }
                parent_msg.append($elem).append('<br>');
                parent_msg.scrollTop(parent_msg[0].scrollHeight);

                if (data['keyboard'] != null) {
                    keyboard_update(data['keyboard']['buttons']);
                } else {
                    keyboard_update([]);
                }
                console.log(data['message']['text']);
            }
        });
    }
    $(document).ready(function () {
        $.get(base_url + "keyboard", function () {
        }).done(function (data) {
            keyboard_update(data['buttons']);
        }).fail(function () {
            alert("Failed to load Home keyboard!");
        });
    });
</script>
</body>
</html>
