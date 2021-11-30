<?php 
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Producto.php';

$database = new Database();
$db = $database->connect();

$producto = new Producto($db);

$producto->idproducto = isset($_GET['id']) ? $_GET['id'] : die();

if($producto->delete()){
    echo json_encode(
        array('code'=> 0, 'message'=> 'Producto eliminado')
    );
  }else{
      echo json_encode(
          array('code' => 1, 'error' => 'No se pudo eliminar el producto.')
      );
  }
    
?>
