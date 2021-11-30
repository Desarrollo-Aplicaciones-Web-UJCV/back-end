<?php 
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');

  include_once '../../config/Database.php';
  include_once '../../models/Usuario.php';

  $database = new Database();
  $db = $database->connect();

  $usuario = new Usuario($db);
    
  //$usuario->get_users_count();
  //echo json_encode($usuario->get_users_count()); 
  //echo json_encode('hola');
  if($usuario->get_users_count()== true){
    echo json_encode($usuario->get_users_count());
  }else{
    echo json_encode($usuario->get_users_count());
  }
?>
