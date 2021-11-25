<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);

  $data = json_decode(file_get_contents('php://input'));
  

  $usuario->nombre = $data->nombre;
  $usuario->correo = $data->correo;
  $usuario->nombreUsuario = $data->nombreUsuario;
  $usuario->clave = $data->clave;
  $usuario->idRol = $data->idRol;

  if($usuario->create() === true){
      echo json_encode(
          array('code' => 0, 'data'=> 'El usuario se creo exitosamente.')
      );
    }else{
        $err = $usuario->create();
        $errCode = $err->errorInfo[0];
        $errMessage = $err->errorInfo[2];
        $error_array = array();


        if($errCode == 23000 && strpos($errMessage, 'unique_email')== true){
            $error = array(
                'code'=> $errCode,
                'error' => 'Este correo ya se encuentra registrado.'
            );
            array_push($error_array, $error);
        }
        if($errCode == 23000 && strpos($errMessage, 'unique_username') == true){
            $error = array(
                'code'=> $errCode,
                'error' => 'Este nombre de usuario ya se encuentra registrado.'
            );
            array_push($error_array, $error);
        }


        echo json_encode(
            array(
                'code'=>http_response_code(422),
                'error' => $error_array
            )
        );
}
      
?>