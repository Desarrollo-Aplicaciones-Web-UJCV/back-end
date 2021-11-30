<?php

class Database{
    private $host = 'localhost';
    private $db_name = 'ferreteria_daw_ujcv';
    private $username = 'root';
    private $password = 'delay';
    private $connection;

    public function connect(){
        $this->connection = null;

        try{
            $this->connection = new PDO('mysql:host='.$this->host.';dbname='. $this->db_name, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);      
        } catch(PDOException $e){
            echo 'Error de conexión: ' . $e->getMessage(); 
        }
        return $this->connection;
    }
}

?>
