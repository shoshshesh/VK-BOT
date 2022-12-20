<?php

/**
 * @throws Exception
 */
function success_session($user_id) {
    $message = "В вас кинули волшебную кружку с пивом🍺🍺🍺🍺🍺🍺🍺🍺🍺
        эта волшебная кружка поможет сдать сессию🍺🍺🍺🍺🍺🍺🍺🍻🍻🍻
        отправь это 10 друзьям и ты сдашь сессию 🤓";
    bot_sendMessage($user_id, $message);
}