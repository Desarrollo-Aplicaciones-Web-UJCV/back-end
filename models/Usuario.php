<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/back-end/libs/jwt-php/src/JWT.php');
use Firebase\JWT\JWT;

class Usuario{
    private $connection;
    private $key = 'privatekey';
    private $tabla = 'usuarios';

    public $idUsuario;
    public $nombre;
    public $correo;
    public $nombreUsuario;
    public $clave;
    public $idRol;
    public $nombreRol;

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
    
    public function read(){
        $query = 'SELECT u.idUsuario, u.nombre, u.correo, u.nombreUsuario, r.nombre as rol FROM ' . $this->tabla .' u INNER JOIN roles r on u.idRol = r.idRol';
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){
        $query = 'SELECT u.nombre, u.correo, u.nombreUsuario,r.idRol, r.nombre as rol FROM ' . $this->tabla .' u INNER JOIN roles r on u.idRol = r.idRol WHERE idUsuario = ?  LIMIT 0,1';
        
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $this->idUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->nombre = $row['nombre'];
        $this->correo = $row['correo'];
        $this->nombreUsuario = $row['nombreUsuario'];
        $this->idRol = $row['idRol'];
      }

      public function update(){
        $query = 'UPDATE ' . $this->tabla . ' 
          SET
            idUsuario = :idUsuario,
            nombre = :nombre,
            correo = :correo,
            nombreUsuario = :nombreUsuario,
            idRol = :idRol

           WHERE idUsuario = :idUsuario';

           $stmt = $this->connection->prepare($query);


           $stmt->bindParam(':idUsuario', $this->idUsuario);
           $stmt->bindParam(':nombre', $this->nombre);
           $stmt->bindParam(':correo', $this->correo);
           $stmt->bindParam(':nombreUsuario', $this->nombreUsuario);
           $stmt->bindParam(':idRol', $this->idRol);

           if($stmt->execute()){
               return true;
           }else{
               printf("Error: %s. \n", $stmt->error);
               return false;
           }

    }

    public function delete(){
        $query = 'DELETE FROM ' . $this->tabla . ' WHERE idUsuario = :id';
        $stmt = $this->connection->prepare($query);

        $this->idUsuario = htmlspecialchars(strip_tags($this->idUsuario));
        $stmt->bindParam(':id', $this->idUsuario);

        if($stmt->execute()){
            return true;
        }else{
            printf("Error: %s. \n", $stmt->error);
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
        $query = 'SELECT idUsuario, idRol FROM ' . $this->tabla . ' WHERE nombreUsuario = ? LIMIT 1';
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(1, $this->nombreUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->idRol = $row['idRol'];
        $this->idUsuario = $row['idUsuario'];
    }

    public function auth(){
        $iat = time();
        $exp = $iat + 60 * 60;
        $payload = array(
            'user' => $this->idUsuario,
            'iss' => 'http://localhost/back-end',
            'aud' => 'http://localhost',
            'iat' => $iat,
            'exp' => $exp
        );
        $jwt = JWT::encode($payload, $this->key, 'HS512');
        //print_r(JWT::decode($jwt, $this->key, array('HS512')));
        return array(
            'token' => $jwt,
            'expires' => $exp
        );
    }

    public function verify_token($token){
        $obj = JWT::decode($token, $this->key, array('HS512'));
        echo $obj;
    }




}

?>