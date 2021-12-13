<?php

class Proveedor{
    private $connection;
    private $tabla = 'proveedores';

    public $idproveedor;
    public $nombre;
    public $email;
    public $telefono;
    public $direccion;


    public function __construct($db){
        $this->connection = $db;
    }

    public function create(){
        $query = 'INSERT INTO ' . $this->tabla . '
        SET nombre = :nombre,
        idproveedor = :idproveedor,
        email = :email,
        telefono = :telefono,
        direccion = :direccion';

        $stmt = $this->connection->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->idproveedor = htmlspecialchars(strip_tags($this->idproveedor));

        $stmt->bindParam(':idproveedor', $this->idproveedor);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':direccion', $this->direccion);

        try{
            $stmt->execute();
            return true;
        } catch(PDOException $err){
            return $err;
        }
    }

    public function read(){
        $query = 'SELECT idproveedor, nombre, email, telefono, direccion FROM ' . $this->tabla .' ';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update(){
        $query = 'UPDATE ' . $this->tabla . ' 
        SET
        nombre = :nombre,
        email = :email,
        telefono = :telefono,
        direccion = :direccion
        WHERE idproveedor = :idproveedor';

         $stmt = $this->connection->prepare($query);

         $stmt->bindParam(':idproveedor', $this->idproveedor);
         $stmt->bindParam(':nombre', $this->nombre);
         $stmt->bindParam(':email', $this->email);
         $stmt->bindParam(':telefono', $this->telefono);
         $stmt->bindParam(':direccion', $this->direccion);

         if($stmt->execute()){
             return true;
         }else{
             printf("Error: %s. \n", $stmt->error);
             return false;
         }
     }

     public function read_single(){
        $query = 'SELECT idproveedor, nombre, email, telefono, direccion FROM ' . $this->tabla . ' WHERE idproveedor= ? LIMIt 0,1';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->idproveedor);
                
        $stmt->execute();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre = $row['nombre'];
        $this->email = $row['email'];
        $this->telefono = $row['telefono'];
        $this->direccion = $row['direccion'];
    }

    public function delete(){
        $query = 'DELETE FROM ' . $this->tabla . ' WHERE idproveedor = :idproveedor';
        $stmt = $this->connection->prepare($query);

        $this->idproveedor = htmlspecialchars(strip_tags($this->idproveedor));
        $stmt->bindParam(':idproveedor', $this->idproveedor);

        if($stmt->execute()){
            return true;
        }else{
            printf("Error: %s. \n", $stmt->error);
            return false;
        }
    }
}
?>
