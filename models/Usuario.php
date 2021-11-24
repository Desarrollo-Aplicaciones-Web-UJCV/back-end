<?php

class Usuario{
    private $connection;
    private $tabla = 'usuarios';

    public $idUsuario;
    public $nombre;
    public $correo;
    public $nombreUsuario;
    public $clave;
    public $idRol;

    public function __construct($db){
        $this->connection = $db;
    }

    public function create(){
        $query = 'INSERT INTO ' . $this->tabla . '
        SET nombre = :nombre,
        correo = :correo,
        nombreUsuario = :nombreUsuario,
        clave = :clave,
        idRol = :idRol';

        $stmt = $this->connection->prepare($query);
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->correo = htmlspecialchars(strip_tags($this->correo));
        $this->nombreUsuario = htmlspecialchars(strip_tags($this->nombreUsuario));
        $this->clave = htmlspecialchars(strip_tags( password_hash($this->clave, PASSWORD_DEFAULT)));
        $this->idRol = htmlspecialchars(strip_tags($this->idRol));

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':nombreUsuario', $this->nombreUsuario);
        $stmt->bindParam(':clave', $this->clave);
        $stmt->bindParam(':idRol', $this->idRol);

        if($stmt->execute()){
            return true;
        }else{
            printf('Error: %s. \n', $stmt->error);
            return false;
        }
    }

    public function login(){
        $query = 'SELECT clave FROM ' . $this->tabla . ' WHERE nombreUsuario = ? LIMIT 1';
        $stmt = $this->connection->prepare($query);        
        
        $stmt->bindParam(1, $this->nombreUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $claveIngresada = $this->clave;
        $hashDb = $row['clave'];

        
        
        
        $verificaClave = password_verify($claveIngresada, $hashDb);
        return $verificaClave;
    }

    public function get_user_role(){
        $query = 'SELECT idRol FROM ' . $this->tabla . ' WHERE nombreUsuario = ? LIMIT 1';
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(1, $this->nombreUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->idRol = $row['idRol'];
    }



}

?>