<?php
class Compra{
    private $connection;
    private $tabla = 'compras';

    public $idCompra;
    public $idUsuario;
    public $idProveedor;
    public $fechaHora;

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
        $this->fechaHora = $this->fechaHora->format('Y-m-d H:m:s');
        $stmt = $this->connection->prepare($query); 
        $this->idUsuario = htmlspecialchars(strip_tags($this->idUsuario));
        $this->idProveedor = htmlspecialchars(strip_tags($this->idProveedor));

        $stmt->bindParam(':idUsuario', $this->idUsuario );
        $stmt->bindParam(':idProveedor', $this->idProveedor);
        $stmt->bindParam(':fechaHora', $this->fechaHora);

        try{
            $stmt->execute();
            return true;
        }catch(PDOException $err){
            return $err;
        }
    }
}
?>

