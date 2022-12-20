<?php



/**
 * @throws Exception
 */
function get_connection($user_id) {
    $connection = pg_connect(
            "host=" . BOT_DATABASE_HOST .
            " port="              . BOT_DATABASE_PORT .
            " dbname="            . BOT_DATABASE_NAME .
            " user="              . BOT_DATABASE_USER .
            " password="          . BOT_DATABASE_PASSWORD
    );
    if (!$connection) {
        log_error($connection);
        error($user_id, ERROR_CONNECTION_FAILED);
        exit();
    }
    return $connection;
    }

//class DataBase
//{
//    public  $connection;
//    private $host          = BOT_DATABASE_HOST;
//    private $port          = BOT_DATABASE_PORT;
//    private $database_name = BOT_DATABASE_NAME;
//    private $user          = BOT_DATABASE_USER;
//    private $password      = BOT_DATABASE_PASSWORD;
//
//    public function getConnection()
//    {
//        if ($this->connection == null) {
//            $this->connection = pg_connect(
//                "host=" . $this->host .
//                " port=" . $this->port .
//                " dbname=" . $this->database_name .
//                " user=" . $this->user .
//                " password=" . $this->password);
//        }
//        return $this->connection;
//    }
//}