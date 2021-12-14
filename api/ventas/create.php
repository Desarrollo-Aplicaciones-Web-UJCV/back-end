<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');


  include_once '../../config/Database.php';
  include_once '../../models/Venta.php';
  include_once '../../models/Usuario.php';
  include_once '../../models/Producto.php';

  $database = new Database();
  $db = $database->connect();
  $venta = new Venta($db);
  $usuario = new Usuario($db);
  $producto = new Producto($db);
  $token = null;
  $headers = apache_request_headers();
  if(isset($headers['Authorization']))
  {
    $token = $headers['Authorization'];
    if($usuario->verify_token($token)['data']->idRol == 1){
      $decoded = $usuario->verify_token($token)['data'];
      $data = json_decode(file_get_contents('php://input'));
      
      $venta->idUsuario = $decoded->user;
      $venta->idCliente =  $data->idCliente;
      $venta->fechaHora = new DateTime(null, new DateTimeZone('America/Tegucigalpa'));

      $venta->detalleVenta = $data->detalleVenta;
      if($venta->create() === true){
        echo json_encode(array(
          'idventa' => $venta->idVenta,
          'code'=> 0,
          'message' => 'venta registrada correctamente'
        ));
      }else{
        echo json_encode(array(
          'code'=>1,
          'errmessage'=> $venta->create()->getMessage(),
          'message'=> 'Ocurrió un error al registrar la venta. Intente de nuevo.'
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
