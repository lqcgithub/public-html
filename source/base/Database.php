<?php 
    define('HOST', '127.0.0.1');
    define('USER', 'root');
    define('PASS', '');
    define('DB', 'finalprojectdb');

    
    class Database {
        private static $db;
        private function __construct(){

        }   

        public static function open(){
            if(self::$db == NULL){
                self::$db = new mysqli(HOST, USER, PASS, DB);
            }
            return self::$db;
        }

        
    }
?>