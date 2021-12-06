<?php
    include_once 'Producto.php';
class Venta{
    private $connection;
    private $tabla = 'ventas';

    public $idCompra;
    public $idUsuario;
    public $idCliente;
    public $fechaHora;
    public $detalleVenta = array();

    public function __construct($db)
    {
       $this->connection = $db; 
    }
    
    public function create()
    {
        $query = 'INSERT INTO ' .  $this->tabla . ' 
        SET idUsuario = :idUsuario,
        idCliente = :idCliente,
        fechaHora = :fechaHora';
        $date =  new DateTime(null, new DateTimeZone('America/Tegucigalpa'));
        $this->fechaHora = date_format($date, 'Y-m-d H-i-s');
        $stmt = $this->connection->prepare($query); 
        $this->idUsuario = htmlspecialchars(strip_tags($this->idUsuario));
        $this->idCliente = htmlspecialchars(strip_tags($this->idCliente));

        $stmt->bindParam(':idUsuario', $this->idUsuario );
        $stmt->bindParam(':idCliente', $this->idCliente);
        $stmt->bindParam(':fechaHora', $this->fechaHora);

        try{
            $stmt->execute();
            $this->idVenta = $this->connection->lastInsertId();
            $this->create_detail();
           return true; 
        }catch(PDOException $err){
            return $err;
        }
    }

    public function create_detail(){
        $productoObj = new Producto($this->connection);
        $tabla_detalle = 'detalleventa';
        $query2 =  'INSERT INTO ' . $tabla_detalle . '
            SET idVenta = :idVenta,
                idProducto = :idProducto,
                cantidad = :cantidad,
                precioVenta = :precioVenta';
            $stmt2 = $this->connection->prepare($query2);

        foreach($this->detalleVenta as $producto){
            
            $stmt2->bindParam(':idVenta', $this->idVenta); 
            $stmt2->bindParam(':idProducto', $producto->idProducto);
            $stmt2->bindParam(':cantidad', $producto->cantidad);
            $precio = $productoObj->get_precio($producto->idProducto);
            $stmt2->bindParam(':precioVenta', $precio);
            $stmt2->execute();
        }
    }

    
    public function get_total($idcompra){
        $query = 'CALL totalVenta(?)';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1,$idcompra);
        $stmt->execute();
        $rs2 = $stmt->fetchAll();
        return $rs2[0][0];
    }

    public function read_head(){
            $query = 'SELECT ventas.idventa, ventas.idUsuario, ventas.fechaHora, ventas.idCliente, usuarios.nombre AS NombreUsuario FROM '.$this->tabla.' 
            INNER JOIN usuarios ON ventas.idUsuario = usuarios.idUsuario WHERE idventa=:idventa';
            $stmt = $this->connection->prepare($query);
            $stmt->bindParam(':idventa',$this->idVenta);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->idUsuario = $row['NombreUsuario'];
            $this->fechaHora = $row['fechaHora'];
            $this->idCliente = $row['idCliente'];

        }

        
    public function read_all(){
        $query = 'SELECT ventas.idventa, ventas.idUsuario, ventas.fechaHora, ventas.idCliente, usuarios.nombre AS NombreUsuario, cliente.nombre AS NombreCliente FROM '.$this->tabla.' 
        INNER JOIN usuarios ON ventas.idUsuario = usuarios.idUsuario INNER JOIN cliente ON ventas.idCliente = cliente.idcliente';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function read_details(){
        $query = 'SELECT detalleventa.idProducto, detalleventa.cantidad, detalleventa.precioVenta, productos.descripcion as NombreProducto FROM detalleventa
        INNER JOIN productos ON detalleventa.idProducto = productos.idProducto WHERE idVenta = :idVenta';
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':idVenta',$this->idVenta);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->detalleVenta = $row;
    }
}


?>

