<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Cliente.php';

  $database = new Database();
  $db = $database->connect();

  $cliente = new Cliente($db);

  $cliente->idcliente = isset($_GET['id']) ? $_GET['id'] : die();
  

  if($cliente->delete()){
      echo json_encode(
          array('code'=> 0, 'message'=> 'Cliente eliminado.')
      );
    }else{
        echo json_encode(
            array('code' => 1, 'error' => 'No se pudo eliminar el cliente.')
        );
    }
?>