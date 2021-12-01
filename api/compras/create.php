<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


  include_once '../../config/Database.php';
  include_once '../../models/Compras.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();
  $compra = new Compra($db);
  $usuario = new Usuario($db);

  $token = null;
  $headers = apache_request_headers();
  if(isset($headers['Authorization']))
  {
    $token = $headers['Authorization'];
    if($usuario->verify_token($token)['data']->idRol == 1){
      $decoded = $usuario->verify_token($token)['data'];
      $data = json_decode(file_get_contents('php://input'));
      $compra->idUsuario = $decoded->user;
      $compra->idProveedor =  $data->idProveedor;
      $compra->fechaHora = new DateTime(null, new DateTimeZone('America/Tegucigalpa'));
     
      if($compra->create() === true){
        echo json_encode(array(
          'code'=> 0,
          'message' => 'Compra registrada correctamente'
        ));
      }else{
        echo json_encode(array(
          'code'=>1,
          'errmessage'=> $compra->create()->getMessage(),
          'message'=> 'Ocurrió un error al registrar la compra. Intente de nuevo.'
        ));
      }
    }else if($usuario->verify_token($token)['data']->idRol == 2){
      echo json_encode(array(
        'code' =>1,
        'message'=> 'El usuario no cuenta con los permisos necesarios para realizar esta acción.'
      ));
    }
  }
  else
  {
    echo json_encode( array ('code' => 1, 'message'  => 'Usuario no autenticado.'));
  }  




?>
