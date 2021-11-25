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
  $rol = new Rol($db);

  $data = file_get_contents("php://input");

  if (isset($data)) {
    $request = json_decode($data);
    $usuario->idUsuario = $request->idUsuario;
    $usuario->nombre = $request->nombre;
    $usuario->correo= $request->correo;
    $usuario->nombreUsuario = $request->nombreUsuario;
    $usuario->idRol = $request->idRol; 
    }


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