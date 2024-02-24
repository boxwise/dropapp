<?php

require __DIR__.'/../library/core.php';

require 'pdf-card.php';

define('PDFSCRIPT', true);
define('FPDF_FONTPATH', 'fonts/');

$pdf = new PDF();

$pdf->AddFont('helvetica', '', 'helvetica.php');
$pdf->AddFont('helvetica', 'B', 'helveticab.php');
$pdf->AddFont('helvetica', 'I', 'helveticai.php');
$pdf->AddFont('helvetica', 'BI', 'helveticabi.php');

$pdf->SetFont('helvetica', '', 10);
$pdf->LeftMargin = 15;
$pdf->TopMargin = 15;
$pdf->W = 85;
$pdf->H = 55;
$pdf->SetAutoPageBreak(false);
$pdf->SetLineWidth(0.1);

// $id = "1050,1067,1068,1084,1093";
$ids = explode(',', $_GET['id']);
foreach ($ids as $key => $id) {
    $ids[$key] = intval($id);
}
$ids = join(',', $ids);

$result = db_query('SELECT *, CONCAT(firstname," ",lastname) AS name, DATE_FORMAT(date_of_birth,"%d-%m-%Y") AS birthdate, DATE_FORMAT(NOW(),"%d-%m-%Y %H:%i") AS issued FROM people WHERE id IN ('.$ids.')');

while ($p = db_fetch($result)) {
    verify_campaccess_people($p['id']);

    $picture = (file_exists($settings['upload_dir'].'/people/'.intval($p['id']).'.jpg') ?
        $settings['upload_dir'].'/people/'.intval($p['id']).'.jpg' : '');

    // if($p['bicycletraining']) {

    if (!$picture) {
        $picture = $_SERVER['DOCUMENT_ROOT'].'/assets/img/no-picture.jpg';
    }
    $exif = exif_read_data($picture);
    $rotate = (3 == $exif['Orientation'] ? 180 : (6 == $exif['Orientation'] ? -90 : (8 == $exif['Orientation'] ? 90 : 0)));
    [$imgw, $imgh] = getimagesize($picture);

    $pdf->newCard();
    $pdf->Rect($pdf->X, $pdf->Y, $pdf->W, $pdf->H);
    $pdf->Rect($pdf->X, $pdf->Y + $pdf->H, $pdf->W, $pdf->H);

    if ('occ' == $card) {
        $pdf->SetTextColor(72, 12, 17);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->PDFPrint(4, 7, 'OCCycle');
        $pdf->SetTextColor(0);
        $pdf->PDFPrint(23, 7, 'bicycle certificate');

        $logo = $_SERVER['DOCUMENT_ROOT'].'/assets/img/occ-logo.jpg';
        $pdf->Image($logo, $pdf->X + 66, $pdf->Y + 2.5, 15, 8.3);

        $pdf->Rotate(180, $pdf->X + 42.5, $pdf->Y + 82.5);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->PDFPrint(4, 106, 'Open Cultural Center');
        $pdf->PDFPrint(4, 101, 'In case of emergency');
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->PDFPrint(42, 106, '+30 694 903 9092');
        $pdf->PDFPrint(43, 101, '112');
        $pdf->Rotate(0);
    } elseif ('id' == $card) {
        $pdf->SetTextColor(100, 0, 0);
        $pdf->SetFont('helvetica', 'B', 12.5);
        $pdf->PDFPrint(4, 7, $_SESSION['organisation']['label']);
        $pdf->SetTextColor(0);
        $pdf->PDFPrint(43, 7, 'Membership Card');

        $pdf->Rotate(180, $pdf->X + 42.5, $pdf->Y + 82.5);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->PDFPrint(4, 101, $_SESSION['organisation']['label']);
        $pdf->PDFPrint(4, 106, 'In case of emergency');
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->PDFPrint(39, 101, '+30 69........');
        $pdf->PDFPrint(43, 106, '112');
        $pdf->Rotate(0);
    } else {
        $pdf->SetTextColor(100, 0, 0);
        $pdf->SetFont('helvetica', 'B', 12.5);
        $pdf->PDFPrint(4, 7, $_SESSION['organisation']['label']);
        $pdf->SetTextColor(0);
        $pdf->PDFPrint(43, 7, 'Bicycle Certificate');

        $pdf->Rotate(180, $pdf->X + 42.5, $pdf->Y + 82.5);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->PDFPrint(4, 106, $_SESSION['organisation']['label']);
        $pdf->PDFPrint(4, 101, 'In case of emergency');
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->PDFPrint(39, 106, '+30 694 6899518');
        $pdf->PDFPrint(43, 101, '112');
        $pdf->Rotate(0);
    }

    $photow = 30;
    $photoh = 40;
    $photox = 4;
    $photoy = 10;

    // dump($imgw);
    $pdf->ClippingRect($pdf->X + $photox, $pdf->Y + $photoy, $photow, $photoh, 1);
    if ($rotate) {
        $pdf->Rotate($rotate, $pdf->X + $photox + ($photow / 2), $pdf->Y + $photoy + ($photoh / 2));
    }
    if ($imgw / $imgh > $photow / $photoh) {
        $w = $photoh * $imgw / $imgh;
        $pdf->Image($picture, $pdf->X + $photox + ($photow / 2) - ($w / 2), $pdf->Y + $photoy, $w, $photoh);
    } else {
        $h = $photow * $imgh / $imgw;
        $pdf->Image($picture, $pdf->X + $photox, $pdf->Y + $photoy + ($photoh / 2) - ($h / 2), $photow, $h);
    }
    $pdf->Rotate(0);
    $pdf->UnsetClipping();

    $labels = ['name', 'address', 'date of birth', 'phone', 'issued'];
    $data = ['name', 'container', 'birthdate', 'phone', 'issued'];
    for ($l = 0; $l < 5; ++$l) {
        $pdf->line($pdf->X + 38, $pdf->Y + 15.5 + ($l * 8), $pdf->X + 81, $pdf->Y + 15.5 + ($l * 8));
        $pdf->SetTextColor(50);
        $pdf->SetFont('helvetica', '', 6.5);
        $pdf->PDFPrint(38, 18 + ($l * 8), $labels[$l]);
        $pdf->SetTextColor(0);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->PDFPrint(38, 14.5 + ($l * 8), $p[$data[$l]]);
    }
    $pdf->Rotate(180, $pdf->X + 42.5, $pdf->Y + 82.5);
    $pdf->SetFont('helvetica', '', 6.5);
    $pdf->SetXY($pdf->X + 3, $pdf->Y + 59);
    if ('id' == $card) {
        $pdf->MultiCell(80, 3, $translate['idcard-rules']);
    } else {
        $pdf->MultiCell(80, 3, $translate['bicycle-rules']);
    }
    $pdf->Rotate(0);
}

// }

$pdf->Output('I', 'Bicycle Cards.pdf');
