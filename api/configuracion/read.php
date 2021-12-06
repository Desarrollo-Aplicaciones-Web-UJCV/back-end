<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

  include_once '../../config/Database.php';
  include_once '../../models/Configuracion.php';
  
  $database = new Database();
  $db = $database->connect();

  $configuracion = new Configuracion($db);
  //$configuracion->idconfiguracion = isset($_GET['id']) ? $_GET['id'] : die();

 
  $configuracion->read();

  
  if($configuracion->read()){
    $configuracion_array = array(
        'rtn' => $configuracion->rtn,
        'nombre' => $configuracion->nombre,
        'razonSocial' => $configuracion->razonSocial,
        'email' => $configuracion->email,
        'telefono' => $configuracion->telefono,
        'direccion' => $configuracion->direccion,
        'IGV' => $configuracion->IGV
      );
      print_r(json_encode($configuracion_array));
  }else{
    print_r(json_encode(array("code" => 1, "mensaje" => "No hay configuracion registrada.")));
  }
   
?>