<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Proveedor.php';

  $database = new Database();
  $db = $database->connect();

  $proveedor = new Proveedor($db);

  $data = file_get_contents("php://input");

  if (isset($data)) {
    $request = json_decode($data);
    $proveedor->idproveedor = $request->idproveedor;
    $proveedor->nombre = $request->nombre;
    $proveedor->email = $request->email;
    $proveedor->telefono = $request->telefono;
    $proveedor->direccion = $request->direccion;
    }


  if($proveedor->update()){
      $data = array(
          'idproveedor' => $proveedor->idproveedor,
          'nombre' => $proveedor->nombre,
          'email' => $proveedor->email,
          'telefono' => $proveedor->telefono,
          'direccion' => $proveedor->direccion
      );
      echo json_encode(
          array('code'=> 0, 'message'=> 'Proveedor actualizado.', 'data' => $data)
      );
    }else{
        echo json_encode(
            array('code'=> 1, 'error' => 'No se pudo actualizar el proveedor.')
        );
    }
      
?>
