<?php
  header('Access-Controll-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Controll-Allow-Methods: PUT');
  header('Access-Controll-Allow-Headers: Access-Controll-Allow-Headers, Content-Type, Access-Controll-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';
  include_once '../../models/Rol.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);
  $rol = new Rol($db);

  $data = json_decode(file_get_contents('php://input'));

  $usuario->idUsuario = $data->idUsuario;
  $usuario->nombre = $data->nombre;
  $usuario->correo= $data->correo;
  $usuario->nombreUsuario = $data->nombreUsuario;
  $usuario->idRol = $data->idRol;

  if($usuario->update()){
    $rol->read_single($usuario->idRol);
      $data = array(
          'idUsuario' => $usuario->idUsuario,
          'nombre' => $usuario->nombre,
          'correo' => $usuario->correo,
          'nombreUsuario' => $usuario->nombreUsuario,
          'rol' =>array(
            'id' => $rol->idRol,
            'nombre' => $rol->nombre, 
        ),
      );
      echo json_encode(
          array('code'=> 0, 'message'=> 'Usuario updated', 'data' => $data)
      );
    }else{
        echo json_encode(
            array('code'=> 1, 'error' => 'No se pudo actualizar el usuario')
        );
    }
      
?>