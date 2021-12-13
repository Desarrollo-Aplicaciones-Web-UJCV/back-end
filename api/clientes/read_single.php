<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

  include_once '../../config/Database.php';
  include_once '../../models/Cliente.php';
  
  $database = new Database();
  $db = $database->connect();

  $cliente = new Cliente($db);
  $cliente->idcliente = isset($_GET['id']) ? $_GET['id'] : die();

 
  $cliente->read_single();
   

  $cliente_array = array(
    'id' => $cliente->idcliente,
    'nombre' => $cliente->nombre
  );
  print_r(json_encode($cliente_array));
 
?>