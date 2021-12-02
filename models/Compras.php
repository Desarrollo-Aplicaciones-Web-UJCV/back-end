<?php
class Compra{
    private $connection;
    private $tabla = 'compras';

    public $idCompra;
    public $idUsuario;
    public $idProveedor;
    public $fechaHora;
    public $detalleCompra = array();

    public function __construct($db)
    {
       $this->connection = $db; 
    }

    
    public function create()
    {
        $query = 'INSERT INTO ' .  $this->tabla . ' 
        SET idUsuario = :idUsuario,
        idProveedor = :idProveedor,
        fechaHora = :fechaHora';
        $date =  new DateTime(null, new DateTimeZone('America/Tegucigalpa'));
        $this->fechaHora = date_format($date, 'Y-m-d H-i-s');
        $stmt = $this->connection->prepare($query); 
        $this->idUsuario = htmlspecialchars(strip_tags($this->idUsuario));
        $this->idProveedor = htmlspecialchars(strip_tags($this->idProveedor));

        $stmt->bindParam(':idUsuario', $this->idUsuario );
        $stmt->bindParam(':idProveedor', $this->idProveedor);
        $stmt->bindParam(':fechaHora', $this->fechaHora);

        try{
            $stmt->execute();
            $this->idCompra = $this->connection->lastInsertId();
            $this->create_detail();
           return true; 
        }catch(PDOException $err){
            return $err;
        }
    }

    public function create_detail(){
        $tabla_detalle = 'detallecompra';
        $query2 =  'INSERT INTO ' . $tabla_detalle . '
            SET idCompra = :idCompra,
                idProducto = :idProducto,
                cantidad = :cantidad,
                precioCompra = :precioCompra';
            $stmt2 = $this->connection->prepare($query2);

        foreach($this->detalleCompra as $producto){
                        $stmt2->bindParam(':idCompra', $this->idCompra); 
            $stmt2->bindParam(':idProducto', $producto->idProducto);
            $stmt2->bindParam(':cantidad', $producto->cantidad);
            $stmt2->bindParam(':precioCompra', $producto->precioCompra);
            $stmt2->execute();
        }
    }

    
    public function get_total($idcompra){
        $query = 'CALL totalCompra(?)';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1,$idcompra);
        $stmt->execute();
        $rs2 = $stmt->fetchAll();
        return $rs2[0][0];
    }

    public function read_head(){
            $query = 'SELECT compras.idcompra, compras.idUsuario, compras.fechaHora, compras.idProveedor, usuarios.nombre AS NombreUsuario FROM '.$this->tabla.' 
            INNER JOIN usuarios ON compras.idUsuario = usuarios.idUsuario WHERE idcompra=:idcompra';
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':idcompra',$this->idCompra);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->idUsuario = $row['NombreUsuario'];
            $this->fechaHora = $row['fechaHora'];
            $this->idProveedor = $row['idProveedor'];

        }

        
    public function read_all(){
        $query = 'SELECT compras.idcompra, compras.idUsuario, compras.fechaHora, compras.idProveedor, usuarios.nombre AS NombreUsuario, proveedores.Nombre AS NombreProveedor FROM '.$this->tabla.' 
        INNER JOIN usuarios ON compras.idUsuario = usuarios.idUsuario INNER JOIN proveedores ON compras.idProveedor = proveedores.idproveedor';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function read_details(){
        $query = 'SELECT detallecompra.idProducto, detallecompra.cantidad, detallecompra.precioCompra, productos.descripcion as NombreProducto FROM detallecompra
        INNER JOIN productos ON detallecompra.idProducto = productos.idProducto WHERE idCompra = :idCompra';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':idCompra',$this->idCompra);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->detalleCompra = $row;
    }
}


?>

