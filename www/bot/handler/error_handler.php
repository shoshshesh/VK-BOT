<?php



/**
 * @throws Exception
 */
function error($user_id, $message = "") {
    bot_sendMessage($user_id, ERROR_START_MESSAGE . $message);
}
