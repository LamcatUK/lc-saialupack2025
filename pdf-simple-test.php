<?php
/**
 * Simple PDF Test without WordPress dependencies
 * Access via: http://sai.local/wp-content/themes/lc-saialupack2025/pdf-simple-test.php
 */

// Include TCPDF directly
require_once __DIR__ . '/vendor/autoload.php';

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SAI Alupack');
$pdf->SetAuthor('SAI Alupack');
$pdf->SetTitle('SAI Alupack Simple Test');
$pdf->SetSubject('PDF Test');

// Set default header data
$pdf->SetHeaderData('', 0, 'SAI Alupack', 'Simple PDF Test');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 15, 'Simple PDF Test', 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 12);

$html = '
<h2>Basic PDF Generation Test</h2>
<p>This is a simple test to verify TCPDF is working correctly.</p>

<h3>Image Test</h3>
<p>Testing image rendering:</p>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Test image rendering
$product_img_path = __DIR__ . '/img/default-product.png';
if (file_exists($product_img_path)) {
    $pdf->Cell(0, 5, 'Product image found and rendering:', 0, 1, 'L');
    $pdf->Ln(5);
    $pdf->Image($product_img_path, '', '', 40, 0, 'PNG');
    $pdf->Ln(25);
} else {
    $pdf->Cell(0, 5, 'Product image not found at: ' . $product_img_path, 0, 1, 'L');
    $pdf->Ln(5);
}

$company_img_path = __DIR__ . '/img/lc-full.jpg';
if (file_exists($company_img_path)) {
    $pdf->Cell(0, 5, 'Company image found and rendering:', 0, 1, 'L');
    $pdf->Ln(5);
    $pdf->Image($company_img_path, '', '', 60, 0, 'JPG');
    $pdf->Ln(35);
} else {
    $pdf->Cell(0, 5, 'Company image not found at: ' . $company_img_path, 0, 1, 'L');
    $pdf->Ln(5);
}

// Add file listing for debugging
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Available Images:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$img_dir = __DIR__ . '/img/';
if (is_dir($img_dir)) {
    $files = scandir($img_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $pdf->Cell(0, 5, '• ' . $file, 0, 1, 'L');
        }
    }
} else {
    $pdf->Cell(0, 5, 'Image directory not found: ' . $img_dir, 0, 1, 'L');
}

// Output PDF
$filename = 'sai-simple-test-' . date('Y-m-d-H-i-s') . '.pdf';

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output($filename, 'I');
?>
