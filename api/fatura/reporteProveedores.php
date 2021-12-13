<?php

include_once '../../config/Database.php';
include_once '../../models/Proveedor.php';
include_once '../../models/Configuracion.php';

$database = new Database();
$db = $database->connect();


$configuracion = new Configuracion($db);
$configuracion->read();


$proveedor = new Proveedor($db);
$result = $proveedor->read();
$num = $result->rowCount();

  if($num > 0){
      $proveedores_array = array();
      $proveedores_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $product_item = array(
              'idproveedor' => $idproveedor,
              'nombre' => $nombre,
              'email' => $email,
              'telefono' => $telefono,
              'direccion' => $direccion
          );
          array_push($proveedores_array['data'], $product_item);

      }
  }else{
  }
  

		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('L', 'mm', array(100, 150));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Reporte de Proveedores");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(125, 5, utf8_decode($configuracion->nombre), 0, 1, 'C');
		$pdf->Ln();
		$pdf->image("img/logo.png", 130, 3, 15, 15, 'png');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Reporte de Proveedores: ", 0, 1, 'L');
		$pdf->Cell(25, 5, 'RTN', 0, 0, 'C');
		$pdf->Cell(20, 5, 'Nombre', 0, 0, 'C');
        $pdf->Cell(30, 5, 'Correo', 0, 0, 'C');
        $pdf->Cell(20, 5, 'Telefono', 0, 0, 'C');
        $pdf->Cell(55, 5, 'Direccion', 0, 1, 'C');
		foreach ($proveedores_array['data'] as &$row) {
			$pdf->Cell(25, 5, utf8_decode($row['idproveedor']), 0, 0, 'C');
			$pdf->Cell(20, 5, utf8_decode($row['nombre']), 0, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($row['email']), 0, 0, 'C');
            $pdf->Cell(20, 5, utf8_decode($row['telefono']), 0, 0, 'C');
            $pdf->Cell(55, 5, utf8_decode($row['direccion']), 0, 1, 'C');
		}

		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Ln();
		$pdf->Output("proveedores.pdf", "I");
		

?>