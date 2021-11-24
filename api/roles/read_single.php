<?php
  header('Access-Controll-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');


  include_once '../../config/Database.php';
  include_once '../../models/Rol.php';

  $database = new Database();
  $db = $database->connect();

  $rol = new Rol($db);

  //$rol->idRol = isset($_GET['id']) ? $_GET['id'] : die();

  $rol->read_single($idRol);

  $rol_array = array(
      'id' => $rol->idRol,
      'nombre' => $rol->nombre, 
  );

  return $rol_array;
?>