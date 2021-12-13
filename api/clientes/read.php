<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Cliente.php';

  $database = new Database();
  $db = $database->connect();

  $cliente = new Cliente($db);
  $result = $cliente->read();
  $num = $result->rowCount();

  if($num > 0){
      $clientes_array = array();
      $clientes_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $cliente_item = array(
              'idcliente' => $idcliente,
              'nombre' => $nombre
          );
          array_push($clientes_array['data'], $cliente_item);
      }
      echo json_encode($clientes_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ningun cliente registrado.'));
  }
?>