<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);

  $usuario->idUsuario = isset($_GET['id']) ? $_GET['id'] : die();
  

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