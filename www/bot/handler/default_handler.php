<?php

/**
 * @throws Exception
 */
function handle_default($user_id) {
    $message = "Извини, я не знаю такую команду.\nЕсли забыл, что я умею, -- напиши \"Помощь\".";
    bot_sendMessage($user_id, $message);
}