<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Compras.php';

  $initDate = isset($_GET['initDate']) ? $_GET['initDate'] : die();
  $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : die();

  $database = new Database();
  $db = $database->connect();
  if($initDate || $endDate){
  $compras = new Compra($db);
  $result = $compras->read_all($initDate, $endDate);
  $num = $result->rowCount();

  if($num > 0){
      $compras_array = array();
      $compras_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);
          $total = $compras->get_total($idcompra);

          $compra_item = array(
              'idcompra' => $idcompra,
              'NombreUsuario' => $NombreUsuario,
              'NombreProveedor' => $NombreProveedor,
              'fechaHora' => $fechaHora,
              'total' => $total
          );
          array_push($compras_array['data'], $compra_item);

      }
      echo json_encode($compras_array);
  }else{
      echo json_encode(array('error' => 'Aun no existe ninguna compra registrada'));
  }
}else{
    echo json_encode(array(
        'code'=> 1,
        'error'=> 'Especifique una fecha'
    ));
}
?>