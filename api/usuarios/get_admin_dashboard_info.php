<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';
  include_once '../../models/Venta.php';
  include_once '../../models/Cliente.php';
  include_once '../../models/Producto.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);
  $cliente = new Cliente($db);
  $venta = new Venta($db);
  $producto = new Producto($db);

  $count_usuarios = $usuario->get_count_usuarios(); 

  echo json_encode(array(
    'usuarios' => $count_usuarios,
    
  ))
?>
