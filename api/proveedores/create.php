<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Proveedor.php';

  $database = new Database();
  $db = $database->connect();

  $proveedor = new Proveedor($db);

  $data = json_decode(file_get_contents('php://input'));
  

  $proveedor->nombre = $data->nombre;
  $proveedor->email = $data->email;
  $proveedor->telefono = $data->telefono;
  $proveedor->direccion = $data->direccion;

  if($proveedor->create() === true){
      echo json_encode(
          array('code' => 0, 'data'=> 'El proveedor fue agregado exitosamente.')
      );
    }else{
        $err = $proveedor->create();
        echo json_encode(
            array('code' => 1, 'data'=> $err->getMessage())
        );
}
?>