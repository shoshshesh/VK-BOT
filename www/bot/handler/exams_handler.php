<?php

/**
 * @throws Exception
 */
function add_exams($user_id, $args) {
    if (!$args) {
        error($user_id, ERROR_EXAM_EMPTY_ARGS);
        return;
    }
    $exams = mb_split(COLON_SEPARATOR, $args);
    $failed = array();
    foreach ($exams as $exam_str) {
        $exam_str = trim($exam_str);
        $data = mb_split(COMMA_SEPARATOR, $exam_str);
        if (count($data) != EXAM_DATA_ARGS_COUNT) {
            $failed[] = "$exam_str\n" . ERROR_EXAM_DATA_ARGS_WRONG_COUNT;
            continue;
        }
        try {
            $exam = new Exam($user_id, $data[0], $data[1], $data[2]);
            $exam->insertExam($user_id);
        } catch (Exception $e) {
            $failed[] = "$exam_str\n" . $e->getMessage();
        }
    }
    if (count($failed) == 0) {
        $message = "Добавил экзамены в твоё расписание.";
    } else {
        $message = "Возникли проблемы с добавлением следующих экзаменов:\n";
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
function show_exams($user_id) {
    try {
        $time_table = Exam::getExams($user_id);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, $time_table);
}

/**
 * @throws Exception
 */
function show_nearest_exam($user_id) {
    try {
        $exam = Exam::getNearestExam($user_id);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, $exam);
}

/**
 * @throws Exception
 */
function remove_exams($user_id, $exam_ids) {
    try {
        $exam_ids = trim($exam_ids);
        validate_ids($exam_ids);
        Exam::removeExams($user_id, $exam_ids);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, "Удаление прошло успешно.");
}

/**
 * @throws Exception
 */
function remove_all_exams($user_id) {
    try {
        Exam::removeAllExams($user_id);
    } catch (Exception $e) {
        error($user_id, $e->getMessage());
        return;
    }
    bot_sendMessage($user_id, "Удалил всё расписание твоих экзаменов.");
}