<?php
    class Connection{
        protected $conn;
        public function __construct(){
            $hostname = "localhost";
            $user = "root";
            $database = "shop";

            // $mainigais = new mydqli(hostname, user, password, database);
            $this->conn = new mysqli($hostname, $user, '', $database);
            $this->conn->set_charset("utf8");

            if($this->conn->connect_error){
                die("Connection failed!".$conn->connect_error);
            }
        }
    }
?>