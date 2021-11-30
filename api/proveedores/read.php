<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Proveedor.php';

  $database = new Database();
  $db = $database->connect();

  $proveedor = new Proveedor($db);
  $result = $proveedor->read();
  $num = $result->rowCount();

  if($num > 0){
      $proveedores_array = array();
      $proveedores_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $product_item = array(
              'idproveedor' => $idproveedor,
              'nombre' => $nombre,
              'email' => $email,
              'telefono' => $telefono,
              'direccion' => $direccion
          );
          array_push($proveedores_array['data'], $product_item);

      }

      echo json_encode($proveedores_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ningun proveedor registrado.'));
  }
?>