<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Cliente.php';

  $database = new Database();
  $db = $database->connect();

  $cliente = new Cliente($db);

  $data = file_get_contents("php://input");

  if (isset($data)) {
    $request = json_decode($data);
    $cliente->idcliente = $request->idcliente;
    $cliente->nombre = $request->nombre;
    }


  if($cliente->update()){
      $data = array(
          'idcliente' => $cliente->idcliente,
          'nombre' => $cliente->nombre
      );
      echo json_encode(
          array('code'=> 0, 'message'=> 'Cliente actualizado.')
      );
    }else{
        echo json_encode(
            array('code'=> 1, 'error' => 'No se pudo actualizar el cliente.')
        );
    }
      
?>
