<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Venta.php';

  $initDate = isset($_GET['initDate']) ? $_GET['initDate'] : die();
  $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : die();
  
    $database = new Database();
    $db = $database->connect();
    
    if($initDate || $endDate){
      $venta = new Venta($db);
      $result = $venta->read_all($initDate, $endDate);
      $num = $result->rowCount();
      
        if($num > 0){
            $venta_array = array();
            $venta_array['data'] = array();
      
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $total = $venta->get_total($idventa);
      
                $venta_item = array(
                    'idventa' => $idventa,
                    'NombreUsuario' => $NombreUsuario,
                    'NombreCliente' => $NombreCliente,
                    'fechaHora' => $fechaHora,
                    'total' => $total
                );
                array_push($venta_array['data'], $venta_item);
      
            }
            echo json_encode($venta_array);
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
