<?php

class User {
    private $vk_id;

    public function __construct($vk_id) {
        $this->vk_id = $vk_id;
    }

    /**
     * @throws Exception
     */
    public function insertUser($connection) {
        $query = "INSERT INTO \"user\" (\"vk_id\") VALUES ($this->vk_id)";
        log_msg($query);
        $result = pg_query($connection, $query);
        log_msg(pg_last_error());
        if (!$result) {
            throw new Exception("Ты уже зарегистрирован, больше не нужно использовать эту команду :).");
        }
    }
}