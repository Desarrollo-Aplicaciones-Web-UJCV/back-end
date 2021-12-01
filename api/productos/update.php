<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Producto.php';

  $database = new Database();
  $db = $database->connect();

  $producto = new Producto($db);

  $data = file_get_contents("php://input");

  if (isset($data)) {
    $request = json_decode($data);
    $producto->descripcion = $request->descripcion;
    $producto->idproducto = $request->idproducto;
    $producto->precioVenta = $request->precioVenta;
    }


  if($producto->update()){
      $data = array(
          'idproducto' => $producto->idproducto,
          'descripcion' => $producto->descripcion,
          'precioVenta' => $producto->precioVenta
      );
      echo json_encode(
          array('code'=> 0, 'message'=> 'Producto actualizado.')
      );
    }else{
        echo json_encode(
            array('code'=> 1, 'error' => 'No se pudo actualizar el producto.')
        );
    }
      
?>
