<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';
  include_once '../../models/Rol.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);

  $data = json_decode(file_get_contents('php://input'));

  
  $usuario->idUsuario = $data->idUsuario;
  $usuario->nombreUsuario = $data->nombreUsuario;
  $usuario->clave = $data->clave;
  $nuevaClave = $data->nuevaClave;
  

  if($usuario->change_password($data->nuevaClave)){
      $data = array(
          'idUsuario' => $usuario->idUsuario,
          'nombreUsuario' => $usuario->nombreUsuario,
          'clave' => $usuario->clave,
          'nuevaClave' => $data->nuevaClave
      );
      echo json_encode(
          array('code'=> 0, 'message'=> 'Contraseña actualizada', 'data' => $data)
      );
    }else{
        echo json_encode(
            array('code'=> 1, 'error' => 'No se pudo actualizar la contraseña')
        );
    }
      
?>