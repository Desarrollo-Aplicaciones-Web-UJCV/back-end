<?php

include_once '../../config/Database.php';
include_once '../../models/Producto.php';
include_once '../../models/Configuracion.php';

$database = new Database();
$db = $database->connect();


$configuracion = new Configuracion($db);
$configuracion->read();


$producto = new Producto($db);
  $result = $producto->read();
  $num = $result->rowCount();

  if($num > 0){
      $products_array = array();
      $products_array['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $product_item = array(
              'idproducto' => $idproducto,
              'descripcion' => $descripcion,
              'precioVenta' => $precioVenta
          );
          array_push($products_array['data'], $product_item);

      }
  }else{
  }
  

		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('P', 'mm', array(100, 140));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Reporte de Productos");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(75, 5, utf8_decode($configuracion->nombre), 0, 1, 'C');
		$pdf->Ln();
		$pdf->image("img/logo.png", 75, 3, 15, 15, 'png');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Reporte de Productos: ", 0, 1, 'L');
		$pdf->Cell(30, 10, 'Id de Producto', 0, 0, 'C');
		$pdf->Cell(30, 10, 'Nombre', 0, 0, 'C');
        $pdf->Cell(30, 10, 'Precio de Venta', 0, 1, 'C');
		foreach ($products_array['data'] as &$row) {
			$pdf->Cell(30, 5, utf8_decode($row['idproducto']), 0, 0, 'C');
			$pdf->Cell(30, 5, utf8_decode($row['descripcion']), 0, 0, 'C');
            $pdf->Cell(30, 5, utf8_decode($row['precioVenta']), 0, 1, 'C');
		}

		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Ln();
		$pdf->Output("proveedores.pdf", "I");
		

?>