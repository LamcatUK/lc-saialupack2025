<?php

require_once __DIR__ . '/../vendor/autoload.php';

// --- 0. Custom TCPDF class for header/footer ---
class CustomPDF extends TCPDF {
    public function Header() {
        $this->SetFont('dejavusans', '', 10);
        $this->Cell(0, 10, 'SAI Alupack Brochure', 0, 1, 'R');
    }
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
    }
}

$pdf = new CustomPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Sai Alupack');
$pdf->SetAuthor('SAI');
$pdf->SetTitle('SAI Alupack Brochure');
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(true, 20);
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// --- 1. CSS ---
$css = file_get_contents(get_stylesheet_directory() . '/pdf/pdf-style.css');

// --- 2. COVER PAGE ---
$pdf->AddPage();
$pdf->writeHTML('<style>' . $css . '</style>', true, false, true, false, '');
ob_start();
include get_stylesheet_directory() . '/pdf/partials/cover.php';
$pdf->writeHTML(ob_get_clean(), true, false, true, false, '');

// --- 3. TOC PAGE (leave blank for now, fill later) ---
$pdf->AddPage();
$toc_page = $pdf->getPage();
$pdf->writeHTML('<h1 style="margin-top:0;">Table of Contents</h1><div id="toc-placeholder"></div>', true, false, true, false, '');

// --- 4. ABOUT PAGE ---
$pdf->AddPage();
$about_page = $pdf->getAliasNumPage();
ob_start();
include get_stylesheet_directory() . '/pdf/partials/about.php';
$pdf->writeHTML(ob_get_clean(), true, false, true, false, '');

// --- 5. SECTORS PAGE ---
$pdf->AddPage();
$sectors_page = $pdf->getAliasNumPage();
ob_start();
include get_stylesheet_directory() . '/pdf/partials/sectors.php';
$pdf->writeHTML(ob_get_clean(), true, false, true, false, '');

// --- 6. PRODUCT PAGES ---
$product_types = get_terms([
    'taxonomy' => 'product_type',
    'hide_empty' => false,
    'orderby' => 'name',
]);
$toc_entries = [
    ['title' => 'About', 'page' => $about_page],
    ['title' => 'Sectors', 'page' => $sectors_page],
];
foreach ($product_types as $term) {
    $pdf->AddPage();
    $section_page = $pdf->getAliasNumPage();
    $toc_entries[] = ['title' => $term->name, 'page' => $section_page];

    $pdf->SetFont('dejavusans', 'B', 18);
    $pdf->Write(0, $term->name, '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('dejavusans', '', 10);

    // Query products for this term
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'tax_query' => [
            [
                'taxonomy' => 'product_type',
                'field' => 'term_id',
                'terms' => $term->term_id,
            ],
        ],
        'orderby' => 'title',
        'order' => 'ASC',
    ];
    $products = new WP_Query($args);

    $rows = [];
    $row = '';
    $col_count = 0;
    $product_count = 0;

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();

            if ($col_count === 0) {
                $row = '<tr>';
            }

            ob_start();
            include get_stylesheet_directory() . '/pdf/partials/product-card.php';
            $row .= ob_get_clean();
            $col_count++;
            $product_count++;

            if ($col_count === 3) {
                $row .= '</tr>';
                $rows[] = $row;
                $col_count = 0;
            }

            // PAGE BREAK after every 12 products (4 rows of 3)
            if ($product_count % 12 === 0) {
                if ($col_count > 0) {
                    $row .= str_repeat('<td></td>', 3 - $col_count) . '</tr>';
                    $rows[] = $row;
                    $col_count = 0;
                }
                $table = '<table class="product-grid">' . implode('', $rows) . '</table>';
                $pdf->writeHTML($table, true, false, true, false, '');
                $pdf->AddPage();
                $pdf->SetFont('dejavusans', 'B', 18);
                $pdf->Write(0, $term->name, '', 0, 'L', true, 0, false, false, 0);
                $pdf->SetFont('dejavusans', '', 10);
                $rows = [];
            }
        }
        wp_reset_postdata();

        if ($col_count > 0) {
            $row .= str_repeat('<td></td>', 3 - $col_count) . '</tr>';
            $rows[] = $row;
        }
        if (!empty($rows)) {
            $table = '<table class="product-grid">' . implode('', $rows) . '</table>';
            $pdf->writeHTML($table, true, false, true, false, '');
        }
    }
}

// --- 7. STATEMENT OF COMPLIANCE ---
$pdf->AddPage();
$compliance_page = $pdf->getAliasNumPage();
$toc_entries[] = ['title' => 'Statement of Compliance', 'page' => $compliance_page];
ob_start();
include get_stylesheet_directory() . '/pdf/partials/compliance.php';
$pdf->writeHTML(ob_get_clean(), true, false, true, false, '');

// --- 8. Render ToC (go back to ToC page and write it) ---
$pdf->setPage($toc_page);
$toc_html = '<ul style="font-size:14px;">';
foreach ($toc_entries as $entry) {
    $toc_html .= '<li>' . esc_html($entry['title']) . ' ............ <span style="float:right;">' . $entry['page'] . '</span></li>';
}
$toc_html .= '</ul>';
$pdf->writeHTMLCell(0, 0, '', '', $toc_html, 0, 1, 0, true, '', true);

// --- 9. OUTPUT ---
$pdf->Output('sai-alupack-brochure.pdf', 'I');