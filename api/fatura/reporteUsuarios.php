<?php

include_once '../../config/Database.php';
include_once '../../models/Usuario.php';
include_once '../../models/Configuracion.php';

$database = new Database();
$db = $database->connect();


$configuracion = new Configuracion($db);
$configuracion->read();


$usuario = new Usuario($db);
  $result = $usuario->read();
  $num = $result->rowCount();

  if($num > 0){
      $users_array = array();
      $users_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $user_item = array(
              'id' => $idUsuario,
              'nombre' => $nombre,
              'correo' => $correo,
              'nombreUsuario' => $nombreUsuario,
              'rol' => $rol
          );

          array_push($users_array['data'], $user_item);

      }
  }else{

    }
  

		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('L', 'mm', array(100, 150));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Reporte de Usuarios");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(125, 5, utf8_decode($configuracion->nombre), 0, 1, 'C');
		$pdf->Ln();
		$pdf->image("img/logo.png", 130, 3, 15, 15, 'png');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Reporte de Usuarios: ", 0, 1, 'L');
		$pdf->Cell(25, 5, 'Id de Usuario', 0, 0, 'C');
		$pdf->Cell(20, 5, 'Nombre', 0, 0, 'C');
        $pdf->Cell(30, 5, 'Correo', 0, 0, 'C');
        $pdf->Cell(30, 5, 'Nombre de Usuario', 0, 0, 'C');
        $pdf->Cell(45, 5, 'Rol', 0, 1, 'C');
		foreach ($users_array['data'] as &$row) {
			$pdf->Cell(25, 5, utf8_decode($row['id']), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode($row['nombre']), 0, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($row['correo']), 0, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($row['nombreUsuario']), 0, 0, 'C');
            $pdf->Cell(45, 5, utf8_decode($row['rol']), 0, 1, 'C');
		}

		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Ln();
		$pdf->Output("proveedores.pdf", "I");
		

?>