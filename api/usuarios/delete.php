<?php
  header('Access-Controll-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Controll-Allow-Methods: DELETE');
  header('Access-Controll-Allow-Headers: Access-Controll-Allow-Headers, Content-Type, Access-Controll-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);

  $data = json_decode(file_get_contents('php://input'));

  
  $usuario->idUsuario = $data->idUsuario;

  if($usuario->delete()){
      echo json_encode(
          array('code'=> 0, 'message'=> 'Usuario eliminado')
      );
    }else{
        echo json_encode(
            array('code' => 1, 'error' => 'No se pudo eliminar el usuario')
        );
    }
      
?>