<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Rol.php';

  $database = new Database();
  $db = $database->connect();

  $rol = new Rol($db);
  $result = $rol->read();
  $num = $result->rowCount();

  if($num > 0){
      $rol_array = array();
      $rol_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $rol_item = array(
              'idRol' => $idRol,
              'nombre' => $nombre,
          );

          array_push($rol_array['data'], $rol_item);

      }

      echo json_encode($rol_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ningun rol registrado'));
  }
?>