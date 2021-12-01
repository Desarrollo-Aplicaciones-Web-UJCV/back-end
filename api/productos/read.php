<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Producto.php';

  $database = new Database();
  $db = $database->connect();

  $producto = new Producto($db);
  $result = $producto->read();
  $num = $result->rowCount();

  if($num > 0){
      $products_array = array();
      $products_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $product_item = array(
              'idproducto' => $idproducto,
              'descripcion' => $descripcion,
              'precioVenta' => $precioVenta
          );
          array_push($products_array['data'], $product_item);

      }

      echo json_encode($products_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ningun producto registrado'));
  }
?>
