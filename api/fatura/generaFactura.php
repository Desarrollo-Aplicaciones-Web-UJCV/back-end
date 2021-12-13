<?php

include_once '../../config/Database.php';
include_once '../../models/Venta.php';
include_once '../../models/Cliente.php';
include_once '../../models/Configuracion.php';

$database = new Database();
  $db = $database->connect();

$configuracion = new Configuracion($db);
$venta = new Venta($db);
$cliente = new Cliente($db);

$idventa = isset($_GET['id']) ? $_GET['id'] : die();

$configuracion->read();

$venta->idVenta = $idventa;
$venta->read_head();
$venta->read_details();
$total = $venta->get_total($venta->idVenta);

$cliente->idcliente = $venta->idCliente;
$cliente->read_single();

		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('P', 'mm', array(80, 200));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Factura");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(60, 5, utf8_decode($configuracion->nombre), 0, 1, 'C');
		$pdf->Ln();
		$pdf->image("img/logo.png", 50, 18, 15, 15, 'png');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "RTN: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $configuracion->rtn, 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $configuracion->telefono, 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, utf8_decode("Dirección: "), 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, utf8_decode($configuracion->direccion), 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Factura: ", 0, 0, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(20, 5, $venta->idVenta, 0, 0, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(16, 5, "Fecha: ", 0, 0, 'R');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(25, 5, $venta->fechaHora, 0, 1, 'R');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(60, 5, "Datos del cliente", 0, 1, 'L');
		$pdf->Cell(40, 5, "Nombre", 0, 0, 'L');
		$pdf->Cell(20, 5, utf8_decode("RTN"), 0, 0, 'L');
		$pdf->Cell(25, 5, utf8_decode(""), 0, 1, 'L');
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(40, 5, utf8_decode($cliente->nombre), 0, 0, 'L');
		$pdf->Cell(20, 5, utf8_decode($cliente->idcliente), 0, 0, 'L');
		$pdf->Cell(25, 10, "", 0, 1, 'L');
		// $pdf->Cell(25, 5, utf8_decode($result_cliente['direccion']), 0, 1, 'L');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(75, 5, "Detalle de Productos", 0, 1, 'L');
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(42, 5, 'Nombre', 0, 0, 'L');
		$pdf->Cell(8, 5, 'Cant', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Total', 0, 1, 'L');
		$pdf->SetFont('Arial', '', 7);
		foreach ($venta->detalleVenta as &$row) {
			$pdf->Cell(42, 5, utf8_decode($row['NombreProducto']), 0, 0, 'L');
			$pdf->Cell(8, 5, $row['cantidad'], 0, 0, 'L');
			$pdf->Cell(15, 5, number_format($row['precioVenta'], 2, '.', ','), 0, 0, 'L');
			$importe = number_format($row['cantidad'] * $row['precioVenta'], 2, '.', ',');
			$pdf->Cell(15, 5, $importe, 0, 1, 'L');
		}

		// while ($row = $venta->detalleVenta) {
		// 	$pdf->Cell(42, 5, utf8_decode($row['nombreProducto']), 0, 0, 'L');
		// 	$pdf->Cell(8, 5, $row['cantidad'], 0, 0, 'L');
		// 	$pdf->Cell(15, 5, number_format($row['precioVenta'], 2, '.', ','), 0, 0, 'L');
		// 	$importe = number_format($row['cantidad'] * $row['precioVenta'], 2, '.', ',');
		// 	$pdf->Cell(15, 5, $importe, 0, 1, 'L');
		// }
		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Cell(76, 5, 'Total: ' . number_format($total, 2, '.', ','), 0, 1, 'R');
		$pdf->Ln();
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(80, 5, utf8_decode("Gracias por su preferencia"), 0, 1, 'C');
		$pdf->Output("compra.pdf", "I");
		

?>
