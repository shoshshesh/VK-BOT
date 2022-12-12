<?php

/**
 * @throws Exception
 */
function help($user_id) {
    $message = "Извини, я не знаю такую команду.\nВот список моих возможостей:\n"
        ."1) Привет:\nПоздороваюсь с тобой.\n"
        ."2) Помощь:\nПодскажу свои команды.";
    bot_sendMessage($user_id, $message);
}