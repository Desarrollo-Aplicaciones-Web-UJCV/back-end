<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Producto.php';

  $database = new Database();
  $db = $database->connect();

  $producto = new Producto($db);

  $data = json_decode(file_get_contents('php://input'));
  

  $producto->descripcion = $data->descripcion;

  if($producto->create() === true){
      echo json_encode(
          array('code' => 0, 'data'=> 'El producto fue agregado exitosamente.')
      );
    }else{
        echo json_encode(
            array('code' => 1, 'data'=> 'El producto no se pudo agregar.')
        );
}
?>