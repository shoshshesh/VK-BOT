<?php

class Lesson {
    private $vk_id;
    private $name;
    private $day_of_week;
    private $time;
    private $room;
    private $type;

    /**
     * @throws Exception
     */
    public function __construct($vk_id, $name, $day_of_week, $time, $room="", $type="") {
        $this->vk_id = $vk_id;
        $this->name = $name;
        $this->day_of_week = mb_strtolower($day_of_week);
        $this->time = $time;
        $this->room = $room;
        $this->type = $type;
        $this->validate();
    }

    /**
     * @throws Exception
     */
    public function insertLesson($user_id) {
        $connection = get_connection($user_id);
        $query = "INSERT INTO lesson (\"user_id\", \"name\", \"day_of_week\", \"time\", \"room\", \"type\") " .
            "VALUES (" . "$this->vk_id, " . "'$this->name', " . "$this->day_of_week, " . "'$this->time', " .
            "'$this->room', " . "'$this->type');";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
    }

    /**
     * @throws Exception
     */
    public static function removeLesson($user_id, $lesson_ids) {
        $connection = get_connection($user_id);
        $query = "DELETE FROM lesson WHERE \"user_id\"=$user_id AND \"id\" IN ($lesson_ids);";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
    }

    /**
     * @throws Exception
     */
    public static function removeAllLessons($user_id) {
        $connection = get_connection($user_id);
        $query = "DELETE FROM lesson WHERE \"user_id\"=$user_id";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
    }

    /**
     * @throws Exception
     */
    public static function getLessons($user_id) {
        $connection = get_connection($user_id);
        $query = "SELECT * FROM lesson WHERE \"user_id\"=$user_id ORDER BY \"day_of_week\", \"time\";";
        $result = pg_query($connection, $query);
        if (!$result) {
            validate_error(pg_last_error($connection));
        }
        $time_table = "";
        $prev = 0;
        $lessons_counter = 1;
        while ($lesson = pg_fetch_row($result)) {
            if ($lesson[3] != $prev) {
                $time_table .= Lesson::fromIntToDOW($lesson[3]) . "\n";
                $prev = $lesson[3];
                $lessons_counter = 1;
            }
            $time_table .= "$lessons_counter) $lesson[2]";
            if ($lesson[6]) {
                $time_table .= " ($lesson[6])\n";
            } else {
                $time_table .= "\n";
            }
            $time = substr($lesson[4], 0, -3); // removes seconds
            $time_table .= "- время: $time\n";
            if ($lesson[5]) {
                $time_table .= "- аудитория: $lesson[5]\n";
            }
            $time_table .= "- ID для удаления: $lesson[0]\n";
            $lessons_counter += 1;
        }
        if (!$time_table) {
            $time_table = "Ты ещё не добавил ни одного занятия.";
        }
        return $time_table;
    }

    /**
     * @throws Exception
     */
    private static function fromIntToDOW($int) {
        switch ($int) {
            case 1:
                return '===== ПОНЕДЕЛЬНИК =====';
            case 2:
                return '======= ВТОРНИК =======';
            case 3:
                return '======== СРЕДА ========';
            case 4:
                return '======= ЧЕТВЕРГ =======';
            case 5:
                return '======= ПЯТНИЦА =======';
            case 6:
                return '======= СУББОТА =======';
            case 7:
                return '===== ВОСКРЕСЕНЬЕ =====';
            default:
                throw new Exception("Неправильный день недели.");
        }
    }

    /**
     * @throws Exception
     */
    private function validate() {
        $length_name = mb_strlen($this->name);
        if ($length_name == 0 || $length_name > LESSON_NAME_MAX_LENGTH) {
            throw new Exception("Название предмета должно быть не пустым и не больше " . LESSON_NAME_MAX_LENGTH . " символов.");
        }
        $this->day_of_week = validate_day_of_week($this->day_of_week);
        validate_time($this->time);
        if (mb_strlen($this->room) > LESSON_ROOM_MAX_LENGTH) {
            throw new Exception("Название аудитории должно быть не больше " . LESSON_ROOM_MAX_LENGTH . " символов.");
        }
        if (mb_strlen($this->type) > LESSON_TYPE_MAX_LENGTH) {
            throw new Exception("Формат занятия должен быть не больше " . LESSON_TYPE_MAX_LENGTH . " символов.");
        }
    }
}

