<?php

const CALLBACK_API_EVENT_CONFIRMATION = 'confirmation';
const CALLBACK_API_EVENT_MESSAGE_NEW = 'message_new';

require_once 'config.php';
require_once 'global.php';
require_once '../db/database.php';
require_once 'api/vk_api.php';
require_once 'bot/router.php';
require_once 'bot/validator.php';
require_once 'entity/User.php';
require_once 'entity/Lesson.php';
require_once 'entity/Exam.php';
require_once 'bot/bot.php';


if (!isset($_REQUEST)) {
    exit;
}

mb_internal_encoding('UTF-8');
callback_handleEvent();

function callback_handleEvent() {
    $event = _callback_getEvent();

    try {
        switch ($event['type']) {
            //Подтверждение сервера
            case CALLBACK_API_EVENT_CONFIRMATION:
                _callback_handleConfirmation();
                break;

            //Получение нового сообщения
            case CALLBACK_API_EVENT_MESSAGE_NEW:
                _callback_handleMessageNew($event['object']);
                break;

            default:
                _callback_response('Unsupported event: ' . $event['type']);
                break;
        }
    } catch (Exception $e) {
        log_error($e);
    }

    _callback_okResponse();
}

function _callback_getEvent() {
    return json_decode(file_get_contents('php://input'), true);
}

function _callback_handleConfirmation() {
    _callback_response(CALLBACK_API_CONFIRMATION_TOKEN);
}

/**
 * @throws Exception
 */
function _callback_handleMessageNew($data) {
    route($data);
    _callback_okResponse();
}

function _callback_okResponse() {
    _callback_response('ok');
}

function _callback_response($data) {
    echo $data;
    exit();
}

