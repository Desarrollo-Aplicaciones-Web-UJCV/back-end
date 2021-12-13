<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Compras.php';

  $database = new Database();
  $db = $database->connect();

  $compras = new Compra($db);
  $result = $compras->read_all();
  $num = $result->rowCount();

  if($num > 0){
      $compras_array = array();
      $compras_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);
          $total = $compras->get_total($idcompra);

          $compra_item = array(
              'idcompra' => $idcompra,
              'NombreUsuario' => $NombreUsuario,
              'NombreProveedor' => $NombreProveedor,
              'fechaHora' => $fechaHora,
              'total' => $total
          );
          array_push($compras_array['data'], $compra_item);

      }
      echo json_encode($compras_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ninguna compra registrada'));
  }
?>