<?php

/**
 * @throws Exception
 */
function route($data) {
    $message = $data['message'];
    $user_id = $message['from_id'];
    $command = mb_strtolower(trim($message['text']));
    if (_is_hello($command)) {
        hello($user_id);
    } elseif (_is_help($command)) {
        help($user_id);
    } else {
        handle_default($user_id);
    }
}

function _is_hello($command): bool {
    return strcmp($command, "привет");
}

function _is_help($command): bool {
    return strcmp($command, "помощь");
}

function _is_add_lessons($command): bool {
    return _starts_with($command, "добавь занятия:");
}

function _starts_with($command, $prefix): bool {
    $length = strlen($prefix);
    $substr = substr($command, 0, $length);
    if (strcmp($substr, $prefix) == 0) {
        return true;
    } else {
        return false;
    }
}

function _is_add_exams($command): bool {
    return _starts_with($command, "добавь экзамены:");
}

function _is_remove_lessons($command): bool {
    return _starts_with($command, "удали занятия:");
}

function _is_remove_exams($command): bool {
    return _starts_with($command, "удали экзамены:");
}

function _is_remove_all_lessons($command): bool {
    return strcmp($command, "очисти всё расписание занятий") == 0 or
        strcmp($command, "очисти всe расписание занятий") == 0;
}

function _is_remove_all_exams($command): bool {
    return strcmp($command, "очисти всё расписание экзаменов") == 0 or
        strcmp($command, "очисти всe расписание экзаменов") == 0;
}