<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Venta.php';

  $database = new Database();
  $db = $database->connect();

  $venta = new Venta($db);

  $venta->idVenta = isset($_GET['id']) ? $_GET['id'] : die();

  $venta->read_head();
  $venta->read_details();


  $ventas_array = array(
      'idVenta' => $venta->idVenta,
      'usuario' => $venta->idUsuario, 
      'cliente' => $venta->idCliente, 
      'fechaHora' => $venta->fechaHora, 
      'detalleventa' => $venta->detalleVenta
  );

  print_r(json_encode($ventas_array));
?>
