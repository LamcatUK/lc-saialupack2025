<?php
/**
 * SAI Alupack Brochure PDF Generator using TCPDF
 * Much more reliable than client-side solutions
 */

// Install TCPDF via Composer or download manually
// composer require tecnickcom/tcpdf

require_once get_stylesheet_directory() . '/vendor/autoload.php'; // If using Composer

class SaiBrochurePDF {
    private $pdf;
    private $brochure_data;
    
    public function __construct() {
        // Create new PDF document (A4, Portrait)
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        // Set document information
        $this->pdf->SetCreator('SAI Alupack');
        $this->pdf->SetAuthor('SAI Alupack');
        $this->pdf->SetTitle('Product Brochure 2025');
        $this->pdf->SetSubject('Premium Packaging Solutions');
        
        // Remove default header/footer
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        
        // Set margins (15mm top/bottom, 20mm sides)
        $this->pdf->SetMargins(20, 15, 20);
        $this->pdf->SetAutoPageBreak(TRUE, 15);
        
        // Set font
        $this->pdf->SetFont('helvetica', '', 10);
    }
    
    public function generate_brochure() {
        // Get data from WordPress
        $this->brochure_data = $this->get_brochure_data();
        
        // Generate pages
        $this->add_cover_page();
        $this->add_table_of_contents();
        $this->add_about_page();
        $this->add_sectors_page();
        $this->add_products_pages();
        $this->add_compliance_page();
        
        // Output PDF
        return $this->pdf->Output('sai-alupack-brochure.pdf', 'D'); // D = Download
    }
    
    private function get_brochure_data() {
        // Get all the data from WordPress/GraphQL
        $cover_title = get_field('cover_title', 'option');
        if (empty($cover_title)) {
            $cover_title = 'SAI ALUPACK';
        }
        
        $cover_subtitle = get_field('cover_subtitle', 'option');
        if (empty($cover_subtitle)) {
            $cover_subtitle = 'Premium Packaging Solutions';
        }
        
        return array(
            'cover_title'    => $cover_title,
            'cover_subtitle' => $cover_subtitle,
            'cover_logo'     => get_field('cover_logo', 'option'),
            'about_heading'  => get_field('about_heading', 'option'),
            'about_text1'    => get_field('about_text1', 'option'),
            'about_text2'    => get_field('about_text2', 'option'),
            'about_images'   => array(
                get_field('about_image1', 'option'),
                get_field('about_image2', 'option'),
                get_field('about_image3', 'option'),
            ),
            'sectors'        => $this->get_sectors_data(),
            'products'       => $this->get_products_data(),
        );
    }
    
    private function add_cover_page() {
        $this->pdf->AddPage();
        
        // Full page gradient background
        $this->pdf->SetFillColor(23, 63, 61); // Dark green
        $this->pdf->Rect(0, 0, 210, 297, 'F');
        
        // Logo
        if ($logo = $this->brochure_data['cover_logo']) {
            $logo_path = $this->download_image($logo['url']);
            if ($logo_path) {
                $this->pdf->Image($logo_path, 55, 80, 100, 0, '', '', '', false, 300, '', false, false, 0);
            }
        }
        
        // Title
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', 'B', 36);
        $this->pdf->SetXY(20, 140);
        $this->pdf->Cell(170, 20, $this->brochure_data['cover_title'], 0, 1, 'C');
        
        // Subtitle
        $this->pdf->SetFont('helvetica', '', 18);
        $this->pdf->SetXY(20, 160);
        $this->pdf->Cell(170, 15, $this->brochure_data['cover_subtitle'], 0, 1, 'C');
        
        // Year
        $this->pdf->SetFont('helvetica', '', 14);
        $this->pdf->SetXY(20, 260);
        $this->pdf->Cell(170, 10, '2025', 0, 1, 'C');
    }
    
    private function add_table_of_contents() {
        $this->pdf->AddPage();
        $this->add_header('Table of Contents');
        
        $toc_items = [
            ['About SAI Alupack', '3'],
            ['Our Sectors', '4'],
            ['Products Overview', '5'],
            ['Product Categories', '6-12'],
            ['Compliance & Certifications', '13']
        ];
        
        $this->pdf->SetFont('helvetica', '', 12);
        $y = 50;
        
        foreach ($toc_items as $item) {
            $this->pdf->SetXY(20, $y);
            $this->pdf->Cell(130, 8, $item[0], 0, 0, 'L');
            
            // Dotted line
            $this->pdf->SetDrawColor(170, 170, 170);
            for ($x = 155; $x < 180; $x += 2) {
                $this->pdf->Circle($x, $y + 4, 0.3, 0, 360, 'F');
            }
            
            $this->pdf->Cell(20, 8, $item[1], 0, 1, 'R');
            $y += 12;
        }
        
        $this->add_footer();
    }
    
    private function add_about_page() {
        $this->pdf->AddPage();
        $this->add_header('About SAI Alupack');
        
        // Two column layout
        $col1_x = 20;
        $col2_x = 110;
        $y = 50;
        
        // Left column - text
        $this->pdf->SetXY($col1_x, $y);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->MultiCell(80, 5, $this->brochure_data['about_text1'], 0, 'L');
        
        // Right column - images
        $img_y = $y;
        foreach ($this->brochure_data['about_images'] as $i => $image) {
            if ($image) {
                $img_path = $this->download_image($image['url']);
                if ($img_path) {
                    $this->pdf->Image($img_path, $col2_x, $img_y, 70, 30, '', '', '', false, 300);
                    $img_y += 35;
                }
            }
        }
        
        $this->add_footer();
    }
    
    private function add_products_pages() {
        $products = $this->get_products_data();
        $products_per_page = 12; // 3x4 grid
        
        // Products intro page
        $this->pdf->AddPage();
        $this->add_header('Products');
        
        $intro_text = "Discover our comprehensive range of premium packaging solutions, engineered to meet the diverse needs of today's dynamic marketplace.";
        $this->pdf->SetXY(20, 50);
        $this->pdf->SetFont('helvetica', '', 12);
        $this->pdf->MultiCell(170, 6, $intro_text, 0, 'L');
        
        $this->add_footer();
        
        // Product grid pages
        $product_chunks = array_chunk($products, $products_per_page);
        
        foreach ($product_chunks as $chunk) {
            $this->pdf->AddPage();
            $this->add_header('Products');
            
            $this->add_product_grid($chunk);
            $this->add_footer();
        }
    }
    
    private function add_product_grid($products) {
        $cols = 3;
        $rows = 4;
        $card_width = 50;
        $card_height = 45;
        $start_x = 20;
        $start_y = 50;
        $gap = 5;
        
        $index = 0;
        for ($row = 0; $row < $rows && $index < count($products); $row++) {
            for ($col = 0; $col < $cols && $index < count($products); $col++) {
                $product = $products[$index];
                
                $x = $start_x + ($col * ($card_width + $gap));
                $y = $start_y + ($row * ($card_height + $gap));
                
                $this->add_product_card($product, $x, $y, $card_width, $card_height);
                $index++;
            }
        }
    }
    
    private function add_product_card($product, $x, $y, $width, $height) {
        // Card border
        $this->pdf->SetDrawColor(230, 230, 230);
        $this->pdf->Rect($x, $y, $width, $height, 'D');
        
        // Product image
        if ($product['image']) {
            $img_path = $this->download_image($product['image']);
            if ($img_path) {
                $this->pdf->Image($img_path, $x + 2, $y + 2, $width - 4, 15, '', '', '', false, 300);
            }
        }
        
        // Product title
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->SetXY($x + 2, $y + 18);
        $this->pdf->MultiCell($width - 4, 3, $product['title'], 0, 'L');
        
        // Specifications
        $specs = [
            'Top Out: ' . $this->format_dimension($product['top_out_a'], $product['top_out_b']),
            'Base: ' . $this->format_dimension($product['base_a'], $product['base_b']),
            'Depth: ' . ($product['depth'] ? $product['depth'] . 'mm' : '—'),
            'Capacity: ' . ($product['capacity'] ? $product['capacity'] . 'ml' : '—')
        ];
        
        $this->pdf->SetFont('helvetica', '', 6);
        $spec_y = $y + 25;
        
        foreach ($specs as $spec) {
            $this->pdf->SetXY($x + 2, $spec_y);
            $this->pdf->Cell($width - 4, 2.5, $spec, 0, 1, 'L');
            
            // Dotted line
            $this->pdf->SetDrawColor(170, 170, 170);
            $this->pdf->Line($x + 2, $spec_y + 2.3, $x + $width - 2, $spec_y + 2.3);
            
            $spec_y += 3;
        }
    }
    
    private function add_header($title) {
        // Logo
        $this->pdf->SetXY(20, 15);
        $this->pdf->SetFont('helvetica', 'B', 8);
        $this->pdf->Cell(50, 5, 'SAI ALUPACK', 0, 0, 'L');
        
        // Title
        $this->pdf->SetFont('helvetica', 'B', 14);
        $this->pdf->SetXY(20, 25);
        $this->pdf->Cell(170, 8, $title, 'B', 1, 'L');
        
        // Page number
        $this->pdf->SetFont('helvetica', '', 8);
        $this->pdf->SetXY(170, 15);
        $this->pdf->Cell(20, 5, 'Page ' . $this->pdf->getPage(), 0, 0, 'R');
    }
    
    private function add_footer() {
        // Green footer bar
        $this->pdf->SetFillColor(39, 123, 108);
        $this->pdf->Rect(0, 282, 210, 15, 'F');
        
        // Footer content
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', '', 8);
        
        $footer_items = [
            '+44 (0) 20 7946 0123',
            'info@sai-alupack.com',
            'www.sai-alupack.com'
        ];
        
        $x_positions = [25, 85, 145];
        foreach ($footer_items as $i => $item) {
            $this->pdf->SetXY($x_positions[$i], 287);
            $this->pdf->Cell(50, 5, $item, 0, 0, 'C');
        }
        
        $this->pdf->SetTextColor(0, 0, 0); // Reset color
    }
    
    private function download_image($url) {
        if (!$url) return false;
        
        $upload_dir = wp_upload_dir();
        $temp_file = $upload_dir['basedir'] . '/temp_' . basename($url);
        
        $image_data = file_get_contents($url);
        if ($image_data) {
            file_put_contents($temp_file, $image_data);
            return $temp_file;
        }
        
        return false;
    }
    
    private function format_dimension($a, $b, $unit = 'mm') {
        if (!$a) return '—';
        if (!$b || $a == $b) return $a . $unit;
        return $a . ' x ' . $b . $unit;
    }
    
    private function get_products_data() {
        $products = get_posts([
            'post_type' => 'product',
            'numberposts' => 100,
            'post_status' => 'publish'
        ]);
        
        $product_data = [];
        foreach ($products as $product) {
            $product_data[] = [
                'title' => $product->post_title,
                'image' => get_field('featured_image', $product->ID)['url'] ?? '',
                'top_out_a' => get_field('top_out_a', $product->ID),
                'top_out_b' => get_field('top_out_b', $product->ID),
                'base_a' => get_field('base_a', $product->ID),
                'base_b' => get_field('base_b', $product->ID),
                'depth' => get_field('depth', $product->ID),
                'capacity' => get_field('capacity', $product->ID)
            ];
        }
        
        return $product_data;
    }
    
    private function get_sectors_data() {
        return array(
            array(
                'title'       => 'Food & Beverage',
                'description' => 'Premium packaging solutions for the food and beverage industry.',
                'icon'        => 'food-icon.svg',
            ),
            array(
                'title'       => 'Cosmetics & Personal Care',
                'description' => 'Elegant packaging for cosmetics and personal care products.',
                'icon'        => 'cosmetics-icon.svg',
            ),
            array(
                'title'       => 'Pharmaceuticals',
                'description' => 'Compliant packaging for pharmaceutical applications.',
                'icon'        => 'pharma-icon.svg',
            ),
            array(
                'title'       => 'Industrial',
                'description' => 'Robust solutions for industrial packaging needs.',
                'icon'        => 'industrial-icon.svg',
            ),
        );
    }
    
    private function add_sectors_page() {
        $this->pdf->AddPage();
        $this->add_header('Our Sectors');
        
        $sectors = $this->get_sectors_data();
        $y = 50;
        
        foreach ($sectors as $sector) {
            // Sector card
            $this->pdf->SetDrawColor(230, 230, 230);
            $this->pdf->Rect(20, $y, 170, 30, 'D');
            
            // Sector title
            $this->pdf->SetFont('helvetica', 'B', 12);
            $this->pdf->SetXY(25, $y + 5);
            $this->pdf->Cell(160, 8, $sector['title'], 0, 1, 'L');
            
            // Sector description
            $this->pdf->SetFont('helvetica', '', 10);
            $this->pdf->SetXY(25, $y + 15);
            $this->pdf->MultiCell(160, 5, $sector['description'], 0, 'L');
            
            $y += 40;
        }
        
        $this->add_footer();
    }
    
    private function add_compliance_page() {
        $this->pdf->AddPage();
        $this->add_header('Compliance & Certifications');
        
        $compliance_text = "SAI Alupack is committed to the highest standards of quality and compliance. Our products meet all relevant industry standards and certifications including:\n\n• ISO 9001:2015 Quality Management Systems\n• ISO 14001:2015 Environmental Management\n• BRC Food Safety Standard\n• FDA Compliance for Food Contact Materials\n• EU Regulation 1935/2004 for Food Contact Materials\n• REACH Compliance\n\nAll our products undergo rigorous testing to ensure they meet the specific requirements of your industry and application.";
        
        $this->pdf->SetXY(20, 50);
        $this->pdf->SetFont('helvetica', '', 11);
        $this->pdf->MultiCell(170, 6, $compliance_text, 0, 'L');
        
        // Add certification logos if available
        $cert_y = 150;
        $cert_logos = [
            'ISO-9001-logo.png',
            'BRC-logo.png', 
            'FDA-logo.png'
        ];
        
        $cert_x = 30;
        foreach ($cert_logos as $logo) {
            $logo_path = get_stylesheet_directory() . '/img/certifications/' . $logo;
            if (file_exists($logo_path)) {
                $this->pdf->Image($logo_path, $cert_x, $cert_y, 40, 20, '', '', '', false, 300);
                $cert_x += 50;
            }
        }
        
        $this->add_footer();
    }
}

// WordPress hook to generate PDF
add_action('wp_ajax_generate_brochure_pdf', 'handle_pdf_generation');
add_action('wp_ajax_nopriv_generate_brochure_pdf', 'handle_pdf_generation');

function handle_pdf_generation() {
    $generator = new SaiBrochurePDF();
    $generator->generate_brochure();
    exit;
}
