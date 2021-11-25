<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';
  include_once '../../models/Rol.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);
  $rol = new Rol($db);

  $usuario->idUsuario = isset($_GET['id']) ? $_GET['id'] : die();

  $usuario->read_single();
  $rol->read_single($usuario->idRol);

  $usuario_array = array(
      'id' => $usuario->idUsuario,
      'nombre' => $usuario->nombre, 
      'correo' => $usuario->correo, 
      'nombreUsuario' => $usuario->nombreUsuario, 
      'rol' =>array(
        'id' => $rol->idRol,
        'nombre' => $rol->nombre, 
    ),
  );

  print_r(json_encode($usuario_array));
?>