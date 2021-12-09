<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Configuracion.php';

  $database = new Database();
  $db = $database->connect();

  $configuracion = new Configuracion($db);

  $data = json_decode(file_get_contents('php://input'));
  
  $configuracion->rtn = $data->rtn;
  $configuracion->nombre = $data->nombre;
  $configuracion->razonSocial = $data->razonSocial;
  $configuracion->email = $data->email;
  $configuracion->telefono = $data->telefono;
  $configuracion->direccion = $data->direccion;

  if($configuracion->create() === true){
      echo json_encode(
          array('code' => 0, 'data'=> 'Configuracion fue agregada exitosamente.')
      );
    }else{
        $err = $configuracion->create();
        echo json_encode(
            array('code' => 1, 'data'=> $err->getMessage())
        );
}
?>
