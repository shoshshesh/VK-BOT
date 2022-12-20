<?php

/**
 * @throws Exception
 */
function start($user_id) {
    $user = new User($user_id);
    $connection = get_connection($user_id);
    try {
        $user->insertUser($connection);
    } catch (Exception $e) {
        bot_sendMessage($user_id, $e->getMessage());
        return;
    }
    $user_info = vkApi_userGet($user_id);
    $message = "Привет, {$user_info['first_name']}, приятно познакомиться!\nЯ помощник для студентов, который может показывать тебе " .
        "расписание твоих занятий и экзаменов.\n Напиши команду \"Помощь\", чтобы узнать, что я умею.";
    bot_sendMessage($user_id, $message);
}