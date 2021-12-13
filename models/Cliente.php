<?php

class Cliente{
    private $connection;
    private $tabla = 'cliente';

    public $idcliente;
    public $nombre;

    public function __construct($db){
        $this->connection = $db;
    }

    public function create(){
        $query = 'INSERT INTO ' . $this->tabla . '
        SET idcliente = :idcliente,
        nombre = :nombre';

        $stmt = $this->connection->prepare($query);
        $this->idcliente = htmlspecialchars(strip_tags($this->idcliente));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));

        $stmt->bindParam(':idcliente', $this->idcliente);
        $stmt->bindParam(':nombre', $this->nombre);
        try{
            $stmt->execute();
            return true;
        } catch(PDOException $err){
            return $err;
        }
    }

    public function read(){
        $query = 'SELECT idcliente, nombre FROM ' . $this->tabla .' ';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update(){
        $query = 'UPDATE ' . $this->tabla . ' 
        SET
          nombre = :nombre
         WHERE idcliente = :idcliente';

         $stmt = $this->connection->prepare($query);

         $stmt->bindParam(':nombre', $this->nombre);
         $stmt->bindParam(':idcliente', $this->idcliente);

         if($stmt->execute()){
             return true;
         }else{
             printf("Error: %s. \n", $stmt->error);
             return false;
         }

    }

    public function read_single(){
        $query = 'SELECT nombre FROM ' . $this->tabla . ' WHERE idcliente= ? LIMIt 0,1';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->idcliente);
                
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre = $row['nombre'];
    }

    public function delete(){
        $query = 'DELETE FROM ' . $this->tabla . ' WHERE idcliente = :idcliente';
        $stmt = $this->connection->prepare($query);

        $this->idCliente = htmlspecialchars(strip_tags($this->idcliente));
        $stmt->bindParam(':idcliente', $this->idcliente);

        if($stmt->execute()){
            return true;
        }else{
            printf("Error: %s. \n", $stmt->error);
            return false;
        }
    }

    public function get_count_clientes(){
        $query = 'SELECT COUNT(idcliente) AS count FROM ' . $this->tabla . ' ';
        
         $stmt = $this->connection->prepare($query);
         $stmt->execute();

         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         return $row['count'];
    }
}
?>