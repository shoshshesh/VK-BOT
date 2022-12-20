<?php

/**
 * @throws Exception
 */
function hello($user_id) {
    $user = vkApi_userGet($user_id);
    $message = "Привет, {$user['first_name']}!\n Удачи на занятиях 😉";
    bot_sendMessage($user_id, $message);
}
