<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/back-end/libs/jwt-php/src/SignatureInvalidException.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/back-end/libs/jwt-php/src/ExpiredException.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/back-end/libs/jwt-php/src/Key.php');

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

        try{
            $stmt->execute();
            return true;
        } catch(PDOException $err){
           // echo substr($err->getMessage(), 87, 101);
            return $err;
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
            'idRol'=> $this->idRol,
            'iss' => 'http://localhost/back-end',
            'aud' => 'http://localhost',
            'iat' => $iat,
            'exp' => $exp
        );
        $jwt = JWT::encode($payload, $this->key, 'HS512');
        //print_r(JWT::decode($jwt, $this->key, array('HS512')));
        return $jwt;
    }

    public function change_password($newPass){
        $query = 'SELECT clave FROM ' . $this->tabla . ' WHERE idUsuario = ? LIMIT 1';
        $stmt = $this->connection->prepare($query);        


        $stmt->bindParam(1, $this->idUsuario);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $claveIngresada = $this->clave;
        $hashDb = $row['clave'];
        
        $verificaClave = password_verify($claveIngresada, $hashDb);
        
        if($verificaClave){
            
            $query = 'UPDATE ' . $this->tabla . ' 
               SET
               clave = :clave
               WHERE idUsuario = :idUsuario';
               $stmt = $this->connection->prepare($query);
               $stmt->bindParam(':idUsuario', $this->idUsuario);
               $nuevaContra = password_hash($newPass, PASSWORD_DEFAULT);
               $stmt->bindParam(':clave',$nuevaContra);           
                if($stmt->execute()){
                return true;
            }else{
                return array(
                    'message'=> 'ocurrió un problema al actualizar la contraseña.'
                );
            }
            
        }else{
            return array(
                'message' => 'Ingresa correctamente tu contraseña anterior.'
            );
        }
        
    }

    public function verify_token($token){
        try{
            $decoded_token = JWT::decode($token, $this->key, array('HS512'));
            return array(
                'code '=>0,
                'data' => $decoded_token
            );
        }catch(Exception $err){
            return $err->getMessage();
        }
    }

    public function get_users_count(){
        $query = 'SELECT COUNT(idUsuario) FROM ' . $this->tabla . ' AS count';
        
         $stmt = $this->connection->prepare($query);
        try{
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $err){
            return $err.getMessage();        
        }
    }


    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }



}

?>
