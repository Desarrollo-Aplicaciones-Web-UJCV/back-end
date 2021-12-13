<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

  include_once '../../config/Database.php';
  include_once '../../models/Proveedor.php';
  
  $database = new Database();
  $db = $database->connect();

  $proveedor = new Proveedor($db);
  $proveedor->idproveedor = isset($_GET['id']) ? $_GET['id'] : die();

 
  $proveedor->read_single();
   

  $proveedor_array = array(
    'id' => $proveedor->idproveedor,
    'nombre' => $proveedor->nombre,
    'email' => $proveedor->email,
    'telefono' => $proveedor->telefono,
    'direccion' => $proveedor->direccion
  );

  print_r(json_encode($proveedor_array));
 
?>
