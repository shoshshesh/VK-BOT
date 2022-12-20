<?php

/**
 * @throws Exception
 */
function validate_time($time) {
    if (preg_match(TIME_FORMAT, $time) == 0) {
        throw new Exception("Неправильный формат времени.");
    }
    $hours_minutes = mb_split(TIME_SEPARATOR, $time);
    $hours = intval($hours_minutes[0]);
    $minutes = intval($hours_minutes[1]);
    if ($hours >= 24 || $minutes >= 60) {
        throw new Exception("Неправильный формат времени.");
    }
}

/**
 * @throws Exception
 */
function validate_ids($ids) {
    if (preg_match(IDS_FORMAT, $ids) == 0) {
        throw new Exception("Неправильный формат id для удаления.");
    }
}

/**
 * @throws Exception
 */
function validate_date($date) {
    if (preg_match(DATE_FORMAT, $date) == 0) {
        throw new Exception("Неправильный формат даты.");
    }
    $day_month_year = mb_split(DATE_SEPARATOR, $date);
    $day = intval($day_month_year[0]);
    $month = intval($day_month_year[1]);
    if ($day > 31 || $month > 12) {
        throw new Exception("Неправильный формат даты.");
    }
}

/**
 * @throws Exception
 */
function validate_error($error) {
    if (preg_match(DUPLICATE_ERROR, $error)) {
        throw new Exception("Эта запись уже есть в твоём расписании.");
    } elseif (preg_match(NOT_REGISTERED_ERROR, $error)) {
        throw new Exception("Ты не зарегистрирован, напиши \"Начать\", чтобы исправить это.");
    } elseif (preg_match(WRONG_DATE_ERROR, $error)) {
        throw new Exception("Такой даты не существует 😔");
    } else { // our failure
        log_error($error);
        throw new Exception("Не получилось добавить запись. Попробуй позже.");
    }
}
/**
 * @throws Exception
 */
function validate_day_of_week($day_of_week): int {
    switch ($day_of_week) {
        case 'понедельник':
        case 'пн':
            return 1;
        case 'вторник':
        case 'вт':
            return 2;
        case 'среда':
        case 'ср':
            return 3;
        case 'четверг':
        case 'чт':
            return 4;
        case 'пятница':
        case 'пт':
            return 5;
        case 'суббота':
        case 'сб':
            return 6;
        case 'воскресенье':
        case 'вс':
            return 7;
        default:
            throw new Exception("Неправильный формат дня недели.");
    }
}