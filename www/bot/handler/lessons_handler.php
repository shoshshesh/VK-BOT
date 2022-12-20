<?php

/**
 * @throws Exception
 */
function add_lessons($user_id, $args) {
    if (!$args) {
        error($user_id, ERROR_LESSON_EMPTY_ARGS);
        return;
    }
    $lessons = mb_split(COLON_SEPARATOR, $args);
    $failed = array();
    foreach ($lessons as $lesson_str) {
        $lesson_str = trim($lesson_str);
        $data = mb_split(COMMA_SEPARATOR, $lesson_str);
        $lesson_data_count = count($data);
        if ($lesson_data_count < LESSON_DATA_ARGS_MIN_COUNT) {
            $failed[] = "$lesson_str\n" . ERROR_LESSON_DATA_ARGS_WRONG_COUNT;
            continue;
        }
        try {
            $lesson = new Lesson($user_id, $data[0], $data[1], $data[2], $data[3], $data[4]);
            $lesson->insertLesson($user_id);
        } catch (Exception $e) {
            $failed[] = "$lesson_str\n" . $e->getMessage();
        }
    }
    if (count($failed) == 0) {
        $message = "Добавил занятия в твоё расписание.";
    } else {
        $message = "Возникли проблемы с добавлением следующих занятий:\n";
        $count = 1;
        foreach ($failed as $failure) {
            $message .= "$count) " . $failure . "\n";
            $count += 1;
        }
    }
    bot_sendMessage($user_id, $message);
}

/**
 * @throws Exception
 */
function show_lessons($user_id) {
    try {
        $time_table = Lesson::getLessons($user_id);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, $time_table);
}

/**
 * @throws Exception
 */
function remove_lessons($user_id, $lesson_ids) {
    try {
        $lesson_ids = trim($lesson_ids);
        validate_ids($lesson_ids);
        Lesson::removeLesson($user_id, $lesson_ids);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, "Удаление прошло успешно.");
}

function remove_all_lessons($user_id) {
    try {
        Lesson::removeAllLessons($user_id);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, "Удалил всё расписание твоих занятий.");
}