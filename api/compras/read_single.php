<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Compras.php';

  $database = new Database();
  $db = $database->connect();

  $compras = new Compra($db);

  $compras->idCompra = isset($_GET['id']) ? $_GET['id'] : die();

  $compras->read_head();
  $compras->read_details();


  $compras_array = array(
      'idCompra' => $compras->idCompra,
      'usuario' => $compras->idUsuario, 
      'proveedor' => $compras->idProveedor, 
      'fechaHora' => $compras->fechaHora, 
      'detallecompra' => $compras->detalleCompra
  );

  print_r(json_encode($compras_array));
?>