<?php 

class Configuracion{

    private $connection;
    private $tabla = 'configuracion';

    public $rtn;
    public $nombre;
    public $razonSocial;
    public $telefono;
    public $email;
    public $direccion;
    public $IGV;

    public function __construct($db){
        $this->connection = $db;
    }

    public function read(){
        $query = 'SELECT rtn, nombre, razonSocial, telefono, email, direccion, IGV FROM ' . $this->tabla .' LIMIt 0,1';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->rtn = $row['rtn'];
            $this->nombre = $row['nombre'];
            $this->razonSocial = $row['razonSocial'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->direccion = $row['direccion'];
            $this->IGV = $row['IGV'];
            return true;
        }else{
            return false;
        }
    }

    public function create(){
        $query = 'INSERT INTO ' . $this->tabla . '
        SET rtn = :rtn,
        nombre = :nombre,
        razonSocial = :razonSocial,
        telefono = :telefono,
        email = :email,
        direccion = :direccion,
        IGV = :IGV';

        $stmt = $this->connection->prepare($query);
        $this->rtn = htmlspecialchars(strip_tags($this->rtn));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->razonSocial = htmlspecialchars(strip_tags($this->razonSocial));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->IGV = htmlspecialchars(strip_tags($this->IGV));


        $stmt->bindParam(':rtn', $this->rtn);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':razonSocial', $this->razonSocial);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':IGV', $this->IGV);
        try{
            $stmt->execute();
            return true;
        } catch(PDOException $err){
            return $err;
        }
    }

    public function update(){
        $query = 'UPDATE ' . $this->tabla . ' 
        SET
        nombre = :nombre,
        razonSocial = :razonSocial,
        telefono = :telefono,
        email = :email,
        direccion = :direccion,
        IGV = :IGV,
        rtn = :rtn
        WHERE id = 0';

         $stmt = $this->connection->prepare($query);

         $stmt->bindParam(':rtn', $this->rtn);
         $stmt->bindParam(':nombre', $this->nombre);
         $stmt->bindParam(':razonSocial', $this->razonSocial);
         $stmt->bindParam(':telefono', $this->telefono);
         $stmt->bindParam(':email', $this->email);
         $stmt->bindParam(':direccion', $this->direccion);
         $stmt->bindParam(':IGV', $this->IGV);

         try{
            $stmt->execute();
            return true;
        } catch(PDOException $err){
            return $err;
        }
    }
} 
?>