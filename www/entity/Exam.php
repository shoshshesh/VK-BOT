<?php

class Exam {
    private $vk_id;
    private $name;
    private $date;
    private $time;

    /**
     * @throws Exception
     */
    public function __construct($vk_id, $name, $date, $time) {
        $this->vk_id = $vk_id;
        $this->name = $name;
        $this->date = $date;
        $this->time = $time;
        $this->validate();
    }

    /**
     * @throws Exception
     */
    public function insertExam($user_id) {
        $connection = get_connection($user_id);
        $query = "INSERT INTO exam (\"user_id\", \"name\", \"date\") " .
            "VALUES (" . "$this->vk_id, " . "'$this->name', "
            . "TO_TIMESTAMP('$this->date $this->time', 'DD.MM.YYYY HH24:MI'));";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
    }

    /**
     * @throws Exception
     */
    public static function getExams($user_id) {
        $connection = get_connection($user_id);
        $query = "SELECT \"id\", \"name\", TO_CHAR(\"date\", 'DD.MM.YYYY HH24:MI'), " .
            "EXTRACT(EPOCH FROM \"date\") - EXTRACT(EPOCH FROM NOW()) " .
            "FROM exam WHERE \"user_id\"=$user_id ORDER BY \"date\";";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error($connection);
        }
        $time_table = "";
        $exams_counter = 1;
        while ($exam = pg_fetch_row($result)) {
            $time_table .= "$exams_counter) ";
            if (intval($exam[3]) < 0) {
                $time_table .= "✅ ";
            }
            $time_table .= "$exam[1]\n- дата: $exam[2]\n- ID для удаления: $exam[0]\n";
            $exams_counter += 1;
        }
        if (!$time_table) {
            $time_table = "Ты ещё не добавил ни одного экзамена.";
        }
        return $time_table;
    }

    /**
     * @throws Exception
     */
    public static function getNearestExam($user_id) {
        $connection = get_connection($user_id);
        $query = "SELECT \"name\", TO_CHAR(\"date\", 'DD.MM.YYYY HH24:MI')" .
            "FROM exam WHERE \"user_id\"=$user_id " .
            "AND EXTRACT(EPOCH FROM \"date\") - EXTRACT(EPOCH FROM NOW()) > 0 ORDER BY \"date\" LIMIT 1;";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error($connection);
        }
        if ($exam_row = pg_fetch_row($result)) {
            $message = "Ближайший экзамен:\n- $exam_row[0]\n- $exam_row[1]\nУдачи!";
        } else {
            $message = "Больше нет экзаменов!\nПоздравляю, можно отдыхать 🥳";
        }
        return $message;

    }

    /**
     * @throws Exception
     */
    public static function removeExams($user_id, $exam_ids) {
        $connection = get_connection($user_id);
        $query = "DELETE FROM exam WHERE \"user_id\"=$user_id AND \"id\" IN ($exam_ids);";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
    }

    /**
     * @throws Exception
     */
    public static function removeAllExams($user_id) {
        $connection = get_connection($user_id);
        $query = "DELETE FROM exam WHERE \"user_id\"=$user_id";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
    }

    /**
     * @throws Exception
     */
    private function validate() {
        $length_name = mb_strlen($this->name);
        if ($length_name == 0 || $length_name > EXAM_NAME_MAX_LENGTH) {
            throw new Exception("Название экзамена должно быть не пустым и не больше " . EXAM_NAME_MAX_LENGTH . " символов.");
        }
        validate_date($this->date);
        validate_time($this->time);
    }
}
