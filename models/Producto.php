<?php

class Producto{
    private $connection;
    private $tabla = 'productos';

    public $idproducto;
    public $descripcion;
    public $precioVenta;

    public function __construct($db){
        $this->connection = $db;
    }

    public function create(){
        $query = 'INSERT INTO ' . $this->tabla . '
        SET descripcion = :descripcion,
        PrecioVenta = :precioVenta';

        $stmt = $this->connection->prepare($query);
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precioVenta = htmlspecialchars(strip_tags($this->precioVenta));

        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precioVenta', $this->precioVenta);
        try{
            $stmt->execute();
            return true;
        } catch(PDOException $err){
            return $err;
        }
    }

    public function read(){
        $query = 'SELECT idproducto, descripcion, precioVenta FROM ' . $this->tabla .' ';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update(){
        $query = 'UPDATE ' . $this->tabla . ' 
        SET
          descripcion = :descripcion,
          precioVenta = :precioVenta
         WHERE idproducto = :idproducto';

         $stmt = $this->connection->prepare($query);


         $stmt->bindParam(':descripcion', $this->descripcion);
         $stmt->bindParam(':idproducto', $this->idproducto);
         $stmt->bindParam(':precioVenta', $this->precioVenta);

         if($stmt->execute()){
             return true;
         }else{
             printf("Error: %s. \n", $stmt->error);
             return false;
         }

    }

    public function read_single(){
        $query = 'SELECT idproducto, descripcion, precioVenta FROM ' . $this->tabla . ' WHERE idproducto= ? LIMIt 0,1';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->idproducto);
                
        $stmt->execute();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->descripcion = $row['descripcion'];
        $this->precioVenta = $row['precioVenta'];
    }

    public function delete(){
        $query = 'DELETE FROM ' . $this->tabla . ' WHERE idproducto = :idproducto';
        $stmt = $this->connection->prepare($query);

        $this->idproducto = htmlspecialchars(strip_tags($this->idproducto));
        $stmt->bindParam(':idproducto', $this->idproducto);

        if($stmt->execute()){
            return true;
        }else{
            printf("Error: %s. \n", $stmt->error);
            return false;
        }
    }

    public function get_precio($idProducto){
        $query = 'SELECT precioVenta from '. $this->tabla . ' WHERE idproducto = :idProducto';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $precio = $row['precioVenta'];
        return $precio;
    }

    public function get_count_productos(){
        $query = 'SELECT COUNT(idproducto) AS count FROM ' . $this->tabla . ' ';
        
         $stmt = $this->connection->prepare($query);
         $stmt->execute();

         $row = $stmt->fetch(PDO::FETCH_ASSOC);

         return $row['count'];
    }
}
?>
