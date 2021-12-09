<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Configuracion.php';

  $database = new Database();
  $db = $database->connect();

  $configuracion = new Configuracion($db);

  $data = file_get_contents("php://input");

  if (isset($data)) {
    $request = json_decode($data);
    $configuracion->rtn = $request->rtn;
    $configuracion->nombre = $request->nombre;
    $configuracion->razonSocial = $request->razonSocial;
    $configuracion->email = $request->email;
    $configuracion->telefono = $request->telefono;
    $configuracion->direccion = $request->direccion;
    $configuracion->IGV = $request->IGV;
    }


  if($configuracion->update()){
      $data = array(
          'rtn' => $configuracion->rtn,
          'nombre' => $configuracion->nombre,
          'razonSocial' => $configuracion->razonSocial,
          'email' => $configuracion->email,
          'telefono' => $configuracion->telefono,
          'direccion' => $configuracion->direccion,
          'IGV' => $configuracion->IGV
      );
      echo json_encode(
          array('code'=> 0, 'message'=> 'Configuracion actualizado.', 'data' => $data)
      );
    }else{
        $err = $configuracion->update();
        echo json_encode(
            array('code'=> 1, 'error' => 'No se pudo actualizar el configuracion.')
        );
    }
      
?>