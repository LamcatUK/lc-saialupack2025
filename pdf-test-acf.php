<?php
/**
 * PDF Test with ACF Product Data
 * Access via: http://sai.local/wp-content/themes/lc-saialupack2025/pdf-test-acf.php
 */

// Clean any previous output
if (ob_get_contents()) {
    ob_clean();
}
ob_start();

// Include WordPress
if ( isset( $_SERVER['DOCUMENT_ROOT'] ) ) {
	require_once wp_unslash( $_SERVER['DOCUMENT_ROOT'] ) . '/wp-config.php';
}

// Include TCPDF
require_once get_stylesheet_directory() . '/vendor/autoload.php';

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SAI Alupack');
$pdf->SetAuthor('SAI Alupack');
$pdf->SetTitle('SAI Alupack Brochure Test with ACF Data');
$pdf->SetSubject('Product Brochure');

// Set default header data
$pdf->SetHeaderData('', 0, 'SAI Alupack', 'Product Brochure Test with ACF Data');

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

// Title
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 15, 'SAI Alupack Test PDF with ACF Data', 0, 1, 'C');
$pdf->Ln(10);

// Get products from WordPress
$products_query = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 6,
    'post_status' => 'publish'
]);

$pdf->SetFont('helvetica', '', 12);

$html = '
<h2>Test PDF Generation with Real Product Data</h2>
<p>This test pulls real product data from ACF fields and WordPress Media Library.</p>

<h3>Database Status:</h3>
<ul>
    <li>Found ' . $products_query->found_posts . ' products in database</li>
    <li>WordPress ACF integration: ' . (function_exists('get_field') ? 'Active' : 'Not available') . '</li>
    <li>Generated on: ' . date('Y-m-d H:i:s') . '</li>
</ul>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Display individual products
if ($products_query->have_posts()) {
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 15, 'Sample Products from ACF', 0, 1, 'C');
    $pdf->Ln(10);

    $product_count = 0;
    while ($products_query->have_posts() && $product_count < 3) {
        $products_query->the_post();
        $product_id = get_the_ID();
        
        // Get ACF fields
        $sku = get_field('sku', $product_id);
        $capacity = get_field('capacity', $product_id);
        $top_out_a = get_field('top_out_a', $product_id);
        $lid = get_field('lid', $product_id);
        
        // Get product type and its image
        $product_types = get_the_terms($product_id, 'product_type');
        $product_type_name = '';
        $product_image_url = '';
        
        if ($product_types && !is_wp_error($product_types)) {
            $product_type = $product_types[0];
            $product_type_name = $product_type->name;
            
            // Get the card image from the product type
            $image_id = get_field('image', 'product_type_' . $product_type->term_id);
            if ($image_id) {
                $product_image_url = wp_get_attachment_url($image_id);
            }
        }
        
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, get_the_title(), 0, 1, 'L');
        $pdf->Ln(5);
        
        $product_html = '
        <table border="1" cellpadding="5">
            <tr>
                <td width="30%"><strong>Product Type</strong></td>
                <td width="70%">' . esc_html($product_type_name) . '</td>
            </tr>
            <tr>
                <td><strong>SKU</strong></td>
                <td>' . esc_html($sku ?: 'N/A') . '</td>
            </tr>
            <tr>
                <td><strong>Capacity</strong></td>
                <td>' . esc_html($capacity ? $capacity . 'ml' : 'N/A') . '</td>
            </tr>
            <tr>
                <td><strong>Top Out A</strong></td>
                <td>' . esc_html($top_out_a ?: 'N/A') . '</td>
            </tr>
            <tr>
                <td><strong>Lid</strong></td>
                <td>' . esc_html($lid ?: 'N/A') . '</td>
            </tr>
        </table>
        ';
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->writeHTML($product_html, true, false, true, false, '');
        
        // Add product image if available
        if ($product_image_url) {
            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 5, 'Product Image from ACF: ' . basename($product_image_url), 0, 1, 'L');
            
            try {
                $pdf->Image($product_image_url, '', '', 60, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $pdf->Ln(40);
            } catch (Exception $e) {
                $pdf->Cell(0, 5, 'Error loading image: ' . $e->getMessage(), 0, 1, 'L');
                $pdf->Ln(5);
            }
        } else {
            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 5, 'No product image available in ACF', 0, 1, 'L');
            $pdf->Ln(5);
        }
        
        $pdf->Ln(15);
        $product_count++;
    }
} else {
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'No products found in database', 0, 1, 'L');
}

wp_reset_postdata();

// Add product grid layout
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Product Grid Layout with ACF Images', 0, 1, 'C');
$pdf->Ln(10);

// Get products for grid
$grid_products = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 6,
    'post_status' => 'publish'
]);

if ($grid_products->have_posts()) {
    $pdf->SetFont('helvetica', '', 10);
    
    $row_count = 0;
    $col_count = 0;
    $products_per_row = 3;
    
    while ($grid_products->have_posts() && $row_count < 2) {
        if ($col_count == 0) {
            $current_y = $pdf->GetY();
        }
        
        $grid_products->the_post();
        $product_id = get_the_ID();
        
        // Get product data
        $sku = get_field('sku', $product_id);
        $capacity = get_field('capacity', $product_id);
        
        // Calculate position
        $cell_width = 60;
        $x_pos = PDF_MARGIN_LEFT + ($col_count * $cell_width);
        
        $pdf->SetXY($x_pos, $current_y);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell($cell_width - 5, 8, substr(get_the_title(), 0, 20), 1, 0, 'C');
        
        $pdf->SetXY($x_pos, $current_y + 8);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Cell($cell_width - 5, 6, 'SKU: ' . ($sku ?: 'N/A'), 1, 0, 'C');
        
        $pdf->SetXY($x_pos, $current_y + 14);
        $pdf->Cell($cell_width - 5, 6, 'Cap: ' . ($capacity ? $capacity . 'ml' : 'N/A'), 1, 0, 'C');
        
        // Get and add product image
        $product_types = get_the_terms($product_id, 'product_type');
        if ($product_types && !is_wp_error($product_types)) {
            $product_type = $product_types[0];
            $image_id = get_field('image', 'product_type_' . $product_type->term_id);
            if ($image_id) {
                $product_image_url = wp_get_attachment_url($image_id);
                if ($product_image_url) {
                    try {
                        $pdf->Image($product_image_url, $x_pos + 5, $current_y + 22, $cell_width - 15, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    } catch (Exception $e) {
                        // Skip image if error
                    }
                }
            }
        }
        
        $col_count++;
        if ($col_count >= $products_per_row) {
            $col_count = 0;
            $row_count++;
            $pdf->SetY($current_y + 50);
        }
    }
    
    wp_reset_postdata();
} else {
    $pdf->Cell(0, 10, 'No products available for grid layout', 0, 1, 'L');
}

// Output PDF
// Clean any output buffer before sending PDF
if (ob_get_contents()) {
    ob_end_clean();
}

$filename = 'sai-alupack-acf-test-' . date('Y-m-d-H-i-s') . '.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf->Output($filename, 'I');
exit;
?>
