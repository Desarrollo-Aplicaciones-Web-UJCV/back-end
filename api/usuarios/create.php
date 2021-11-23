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

  if($usuario->create()){
      echo json_encode(
          array('data'=> 'El usuario se creo exitosamente.')
      );
    }else{
        echo json_encode(
            array('error' => 'El usuario no se pudo crear.')
        );
    }
      
?>