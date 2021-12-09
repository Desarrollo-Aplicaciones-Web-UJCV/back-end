<?php

include_once '../../config/Database.php';
include_once '../../models/Cliente.php';
include_once '../../models/Configuracion.php';

$database = new Database();
$db = $database->connect();


$configuracion = new Configuracion($db);
$configuracion->read();


$cliente = new Cliente($db);
$result = $cliente->read();
$num = $result->rowCount();

if($num > 0){
    $clientes_array = array();
    $clientes_array['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $cliente_item = array(
            'idcliente' => $idcliente,
            'nombre' => $nombre
        );
        array_push($clientes_array['data'], $cliente_item);
    }
}else{
}
  

		require_once 'fpdf/fpdf.php';
		$pdf = new FPDF('P', 'mm', array(80, 125));
		$pdf->AddPage();
		$pdf->SetMargins(1, 0, 0);
		$pdf->SetTitle("Reporte de Clientes");
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(50, 5, utf8_decode($configuracion->nombre), 0, 1, 'C');
		$pdf->Ln();
		$pdf->image("img/logo.png", 60, 3, 15, 15, 'png');
		$pdf->SetFont('Arial', 'B', 7);
		$pdf->Cell(15, 5, "Reporte de Clientes: ", 0, 1, 'L');
		$pdf->Cell(42, 5, 'RTN', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Nombre', 0, 1, 'R');
		foreach ($clientes_array['data'] as &$row) {
			$pdf->Cell(42, 5, utf8_decode($row['idcliente']), 0, 0, 'L');
			$pdf->Cell(15, 5, utf8_decode($row['nombre']), 0, 1, 'L');
		}

		$pdf->Ln();
		$pdf->SetFont('Arial', 'B', 10);

		$pdf->Ln();
		$pdf->Output("clientes.pdf", "I");
		

?>