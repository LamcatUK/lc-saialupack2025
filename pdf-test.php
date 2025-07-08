<?php
/**
 * Simple PDF Test Page using TCPDF
 * Access via: http://sai.local/wp-content/themes/lc-saialupack2025/pdf-test.php
 *
 * @package SAI_Alupack
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include WordPress.
if ( isset( $_SERVER['DOCUMENT_ROOT'] ) ) {
	require_once wp_unslash( $_SERVER['DOCUMENT_ROOT'] ) . '/wp-config.php';
}

// Debug: Check if WordPress loaded
if (!function_exists('get_stylesheet_directory')) {
    die('Error: WordPress not loaded properly. get_stylesheet_directory() function not available.');
}

// Include TCPDF.
$autoload_path = get_stylesheet_directory() . '/vendor/autoload.php';
if (!file_exists($autoload_path)) {
    die('Error: TCPDF autoload not found at: ' . $autoload_path);
}
require_once $autoload_path;

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SAI Alupack');
$pdf->SetAuthor('SAI Alupack');
$pdf->SetTitle('SAI Alupack Brochure Test');
$pdf->SetSubject('Product Brochure');

// Set default header data
$pdf->SetHeaderData('', 0, 'SAI Alupack', 'Product Brochure Test');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', 'B', 20);

// Add content
$pdf->Cell(0, 15, 'SAI Alupack Test PDF', 0, 1, 'C');

$pdf->Ln(10);

$pdf->SetFont('helvetica', '', 12);

$html = '
<h2>Test PDF Generation</h2>
<p>This is a test PDF generated using TCPDF library for the SAI Alupack brochure project.</p>

<h3>Features to Test:</h3>
<ul>
    <li>PDF generation working correctly</li>
    <li>Text rendering and formatting</li>
    <li>Image support (coming next)</li>
    <li>Page layout and margins</li>
    <li>WordPress integration</li>
</ul>

<h3>Next Steps:</h3>
<p>Once this basic test works, we will:</p>
<ol>
    <li>Add image support and test with actual product images</li>
    <li>Create the full brochure layout</li>
    <li>Add product data from WordPress/GraphQL</li>
    <li>Style to match the design requirements</li>
    <li>Add proper cover page, table of contents, etc.</li>
</ol>

<p><strong>Generated on:</strong> ' . date('Y-m-d H:i:s') . '</p>
';

// Print text using writeHTMLCell()
$pdf->writeHTML($html, true, false, true, false, '');

// Add a new page for more content
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Sample Product Layout', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 12);

$product_html = '
<table border="1" cellpadding="5">
    <tr>
        <td width="30%"><strong>Product Name</strong></td>
        <td width="70%">Sample Product Title</td>
    </tr>
    <tr>
        <td><strong>Description</strong></td>
        <td>This is a sample product description that would be pulled from WordPress.</td>
    </tr>
    <tr>
        <td><strong>Specifications</strong></td>
        <td>
            • Capacity: 500ml<br/>
            • Material: Aluminum<br/>
            • Dimensions: 120x80mm<br/>
            • Weight: 45g
        </td>
    </tr>
</table>

<br/><br/>

<h3>Image Testing</h3>
<p>Testing image rendering with TCPDF:</p>
';

$pdf->writeHTML($product_html, true, false, true, false, '');

// Test image rendering
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Image Rendering Tests', 0, 1, 'L');
$pdf->Ln(5);

// Test 1: SAI Logo (SVG)
$logo_path = get_stylesheet_directory() . '/img/sai-logo.svg';
if (file_exists($logo_path)) {
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'SAI Logo (SVG):', 0, 1, 'L');
    $pdf->Image($logo_path, '', '', 50, 0, 'SVG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(20);
} else {
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'SAI Logo not found at: ' . $logo_path, 0, 1, 'L');
    $pdf->Ln(5);
}

// Test 2: Default Product Image (PNG)
$product_img_path = get_stylesheet_directory() . '/img/default-product.png';
if (file_exists($product_img_path)) {
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Default Product Image (PNG):', 0, 1, 'L');
    $pdf->Image($product_img_path, '', '', 40, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(25);
} else {
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Default product image not found at: ' . $product_img_path, 0, 1, 'L');
    $pdf->Ln(5);
}

// Test 3: Company Image (JPG)
$company_img_path = get_stylesheet_directory() . '/img/lc-full.jpg';
if (file_exists($company_img_path)) {
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Company Image (JPG):', 0, 1, 'L');
    $pdf->Image($company_img_path, '', '', 60, 0, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(30);
} else {
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, 'Company image not found at: ' . $company_img_path, 0, 1, 'L');
    $pdf->Ln(5);
}

// Add a new page for product grid layout test
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Product Grid Layout Test', 0, 1, 'C');
$pdf->Ln(10);

// Create a simple product grid with images
$pdf->SetFont('helvetica', '', 10);

$grid_html = '
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <td width="33%" align="center">
            <strong>Product 1</strong><br/>
            Aluminum Container 500ml<br/>
            <em>Image below:</em>
        </td>
        <td width="33%" align="center">
            <strong>Product 2</strong><br/>
            Aluminum Container 750ml<br/>
            <em>Image below:</em>
        </td>
        <td width="34%" align="center">
            <strong>Product 3</strong><br/>
            Aluminum Container 1000ml<br/>
            <em>Image below:</em>
        </td>
    </tr>
</table>
';

$pdf->writeHTML($grid_html, true, false, true, false, '');

// Add product images in a row
$pdf->Ln(10);
$current_x = $pdf->GetX();
$current_y = $pdf->GetY();

// Product image 1
if (file_exists($product_img_path)) {
    $pdf->Image($product_img_path, $current_x + 15, $current_y, 30, 0, 'PNG');
}

// Product image 2 
if (file_exists($product_img_path)) {
    $pdf->Image($product_img_path, $current_x + 70, $current_y, 30, 0, 'PNG');
}

// Product image 3
if (file_exists($product_img_path)) {
    $pdf->Image($product_img_path, $current_x + 125, $current_y, 30, 0, 'PNG');
}

$pdf->Ln(40);

// Add icon tests
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Icon Rendering Test', 0, 1, 'L');
$pdf->Ln(5);

$icons = ['icon-capacity', 'icon-weight', 'icon-depth', 'icon-base'];
$icon_x = $pdf->GetX();
$icon_y = $pdf->GetY();

foreach ($icons as $index => $icon) {
    $icon_path = get_stylesheet_directory() . '/img/' . $icon . '.svg';
    if (file_exists($icon_path)) {
        $x_pos = $icon_x + ($index * 40);
        $pdf->Image($icon_path, $x_pos, $icon_y, 15, 0, 'SVG');
        $pdf->SetXY($x_pos, $icon_y + 20);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell(35, 5, ucfirst(str_replace(['icon-', '-'], ['', ' '], $icon)), 0, 0, 'C');
    }
}

$pdf->Ln(30);

// Close and output PDF document
$filename = 'sai-alupack-test-images-' . date('Y-m-d-H-i-s') . '.pdf';

// Output PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output($filename, 'I');
?>
