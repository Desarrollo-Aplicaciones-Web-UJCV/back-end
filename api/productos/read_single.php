<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

  include_once '../../config/Database.php';
  include_once '../../models/Producto.php';
  
  $database = new Database();
  $db = $database->connect();

  $producto = new Producto($db);
  $producto->idproducto = isset($_GET['id']) ? $_GET['id'] : die();

 
  $producto->read_single();
   

  $producto_array = array(
    'id' => $producto->idproducto,
    'descripcion' => $producto->descripcion,
    'precioVenta' => $producto->precioVenta
  );

  print_r(json_encode($producto_array));
 
?>
