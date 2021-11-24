<?php
  header('Access-Controll-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);
  $result = $usuario->read();
  $num = $result->rowCount();

  if($num > 0){
      $users_array = array();
      $users_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $user_item = array(
              'nombre' => $nombre,
              'correo' => $correo,
              'nombreUsuario' => $nombreUsuario,
              'rol' => $rol
          );

          array_push($users_array['data'], $user_item);

      }

      echo json_encode($users_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ningun usuario registrado'));
  }
?>