<?php

/**
 * @throws Exception
 */
function hello($user_id) {
    $users_get_response = vkApi_usersGet($user_id);
    $user = array_pop($users_get_response);
    $message = "Привет, {$user['first_name']}!";
    bot_sendMessage($user_id, $message);
}
