<?php

#ini_set('allow_url_fopen', 1);
#ini_set('allow_url_include', 1);
#error_reporting(E_ALL);
require('/home/quesosar/scm/web/fpfd/fpdf.php');
require('/home/quesosar/scm/web/phpqrcode/qrlib.php');


$a=$formato->getAncho();
$l=$formato->getLargo();

$largo = ($l>$a) ? $l : $a ;
$ancho = ($l>$a) ? $a : $l ;


$pdf = new FPDF('P','cm',array($ancho,$largo));
$margenX=$ancho/10;
$margenY=$largo/10;
$pdf->SetMargins($margenX,$margenY,$margenX);
$pdf->SetFont('Arial','B',2*$ancho);

//genero el QR del Lote
$texto = $lote->getNumero();
$tipo = $lote->getProducto()->getNombre().' '.$lote->getProducto()->getPresentacion().' '.$lote->getProducto()->getUnidad();
$pdf->AddPage();
$anchoCell= $ancho-2*$margenX;
$altoCell= ($largo-2*$margenY)/4;
$pdf->Cell($anchoCell,$altoCell/2,$texto,0, 0, 'C');
$pdf->Ln();
$pdf->Cell($anchoCell,$altoCell/2,$tipo,0, 0, 'C');
$porteQR = ($altoCell*3<$anchoCell) ? $altoCell*3 : $anchoCell ;
QRcode::png($texto, 'qr.png');
$pdf->Image('qr.png',($ancho-$porteQR)/2,$margenY + $altoCell,$porteQR,0,'PNG');

//genero el QR de las unidades del Lote
$n = $lote->getCantidad_Actual();
for($i=0; $i<$n; $i++)
{
	$pdf->AddPage();
	$pdf->Cell($anchoCell,$altoCell/2,$texto. ' N: '.$i,0, 0, 'C');
	$pdf->Ln();
	$pdf->Cell($anchoCell,$altoCell/2,$tipo,0, 0, 'C');
	$textoQR= $texto.'@'.$i;
	QRcode::png($textoQR, 'qr.png');
	$pdf->Image('qr.png',($ancho-$porteQR)/2,$margenY + $altoCell,$porteQR,0,'PNG');
}

$pdf->Output();
exit();
?>
