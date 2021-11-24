<?php

class Rol{
    private $connection;
    private $tabla = 'roles';

    public $idRol;
    public $nombre;

    public function __construct($db){
        $this->connection = $db;
    }
    
    public function read(){
        $query = 'SELECT * FROM ' . $this->tabla;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single($idRol){
        $this->idRol = $idRol;
        $query = 'SELECT * FROM ' . $this->tabla .' WHERE idRol = ?  LIMIT 0,1';
        
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->idRol);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->idRol = $row['idRol'];
        $this->nombre = $row['nombre'];
      }
}

?>