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

  $data = file_get_contents("php://input");

  if (isset($data)) {
      $request = json_decode($data); 
      $usuario->idUsuario = $request->idUsuario;
      $usuario->clave = $request->clave;
      $nuevaClave = $request->nuevaClave;
    }


  
  
  

  if($usuario->change_password($request->nuevaClave) === true){
      echo json_encode(
          array('code'=> 0, 'message'=> 'Contraseña actualizada')
      );
    }else{
        $error['error'] = array('code'=> 1, 'error'=> $usuario->change_password($request->nuevaClave));
        echo json_encode(
            $error['error']
        );
    }
?>