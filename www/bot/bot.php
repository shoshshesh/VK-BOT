<?php

/**
 * @throws Exception
 */
function bot_sendMessage($user_id, $message, $attachments = array()) {
    vkApi_messagesSend($user_id, $message, $attachments);
}