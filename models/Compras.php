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
}
?>

