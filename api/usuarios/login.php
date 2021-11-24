<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);

  $data = file_get_contents("php://input");


  if (isset($data)) {
  $request = json_decode($data);
  $usuario->nombreUsuario = $request->nombreUsuario;
  $usuario->clave = $request->clave;

    }

  if($usuario->login()){
    try{
      $usuario->get_user_role();
    } finally{
      $session_array = array(
        'code' => 0, 'message' => 'Usuario logueado', 'idRol' => $usuario->idRol
      );
        echo json_encode($session_array);;
    }
    }else{
        echo json_encode(
            array('code' => 1, 'error' => 'El usuario o contraseña es incorrecto.')
        );
    }
