<?php

/**
 * @throws Exception
 */
function handle_help($user_id) {
    $message = "Извини, я не знаю такую команду.\nВот список моих возможостей:\n"
        ."1) Привет:\nПоздороваюсь с тобой.";
    bot_sendMessage($user_id, $message);
}