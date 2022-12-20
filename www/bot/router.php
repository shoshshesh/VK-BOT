<?php

require_once 'handler/help_handler.php';
require_once 'handler/start_handler.php';
require_once 'handler/hello_handler.php';
require_once 'handler/default_handler.php';
require_once 'handler/success_session_handler.php';
require_once 'handler/error_handler.php';
require_once 'handler/exams_handler.php';
require_once 'handler/lessons_handler.php';

/**
 * @throws Exception
 */
function route($data) {
    $message = $data['message'];
    $user_id = $message['from_id'];
    $row_command = trim($message['text']);
    $command = mb_strtolower($row_command);
    if (_is_hello($command)) {
        hello($user_id);
    } elseif (_is_start($command)) {
        start($user_id);
    } elseif (_is_success_session($command)) {
        success_session($user_id);
    } elseif (_is_help($command)) {
        help($user_id);
    } elseif (_is_add_lessons($command)) {
        add_lessons($user_id, mb_substr($row_command, mb_strlen(ACTION_ADD_LESSONS)));
    } elseif (_is_show_lessons($command)) {
        show_lessons($user_id);
    } elseif (_is_remove_lessons($command)) {
        remove_lessons($user_id, mb_substr($row_command, mb_strlen(ACTION_REMOVE_LESSONS)));
    } elseif (_is_remove_all_lessons($command)) {
        remove_all_lessons($user_id);
    } elseif (_is_add_exams($command)) {
        add_exams($user_id, mb_substr($row_command, mb_strlen(ACTION_ADD_EXAMS)));
    } elseif (_is_show_exams($command)) {
        show_exams($user_id);
    } elseif (_is_remove_exams($command)) {
        remove_exams($user_id, mb_substr($row_command, mb_strlen(ACTION_REMOVE_EXAMS)));
    } elseif (_is_remove_all_exams($command)) {
        remove_all_exams($user_id);
    } elseif (_is_show_nearest_exam($command)) {
        show_nearest_exam($user_id);
    } else {
        handle_default($user_id);
    }
}

function _is_hello($command): bool {
    return strcmp($command, ACTION_HELLO) == 0;
}

function _is_start($command): bool {
    return strcmp($command, ACTION_START) == 0;
}

function _is_help($command): bool {
    return strcmp($command, ACTION_HELP) == 0;
}

function _is_success_session($command): bool {
    return strcmp($command, ACTION_SUCCESS_SESSION) == 0;
}

function _is_add_lessons($command): bool {
    return _starts_with($command, ACTION_ADD_LESSONS);
}

function _starts_with($command, $prefix): bool {
    $length = mb_strlen($prefix);
    $substr = mb_substr($command, 0, $length);
    if (strcmp($substr, $prefix) == 0) {
        return true;
    } else {
        return false;
    }
}

function _is_add_exams($command): bool {
    return _starts_with($command, ACTION_ADD_EXAMS);
}

function _is_remove_lessons($command): bool {
    return _starts_with($command, ACTION_REMOVE_LESSONS);
}

function _is_remove_exams($command): bool {
    return _starts_with($command, ACTION_REMOVE_EXAMS);
}

function _is_remove_all_lessons($command): bool {
    return strcmp($command, ACTION_REMOVE_ALL_LESSONS) == 0;
}

function _is_remove_all_exams($command): bool {
    return strcmp($command, ACTION_REMOVE_ALL_EXAMS) == 0;
}

function _is_show_lessons($command): bool {
    return strcmp($command, ACTION_SHOW_LESSONS) == 0;
}

function _is_show_exams($command): bool {
    return strcmp($command, ACTION_SHOW_EXAMS) == 0;
}

function _is_show_nearest_exam($command): bool {
    return strcmp($command, ACTION_SHOW_NEAREST_EXAM) == 0;
}