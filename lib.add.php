<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

/**
 * Execute before send message.
 *
 * @param mixed $data received data
 */
function pre_message_receive($data)
{

}

/**
 * Execute after send message.
 *
 * @param mixed $data received data
 */
function post_message_receive($data)
{

}

/**
 * Add friend by user key.
 *
 * @param string $user_key user key
 */
function add_friend($user_key)
{

}

/**
 * Delete friend by user key.
 *
 * @param string $user_key user key
 */
function delete_friend($user_key)
{

}

/**
 * Delete chat room by user key.
 *
 * @param string $user_key user key
 */
function delete_chat_room($user_key)
{

}

/**
 * Show message when media upload.
 */
function msg_media_upload()
{
    include_once __DIR__ . '/class/Keyboard.php';
    include_once __DIR__ . '/class/Message.php';

    echo new \kakao\Msg(new \kakao\Msg\Message("성공적으로 업로드 했습니다."), TRUE);
}

/**
 * Show message when undefined message called.
 *
 * @param string $content message
 */
function undefined_msg_operation($content)
{
    include_once __DIR__ . '/class/Keyboard.php';
    include_once __DIR__ . '/class/Message.php';

    echo new \kakao\Msg(new \kakao\Msg\Message("Undefined operation [{$content}].\nContract to Administrator."), TRUE);
}
