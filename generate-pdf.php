<?php
/**
 * Direct PDF generation endpoint - bypasses WordPress admin
 * Access via: http://sai.local/wp-content/themes/lc-saialupack2025/generate-pdf.php
 */

// Start output buffering and clean any previous output
ob_start();
ob_clean();

// Include WordPress core
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

// Include TCPDF
require_once get_stylesheet_directory() . '/vendor/autoload.php';

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SAI Alupack');
$pdf->SetAuthor('SAI Alupack');
$pdf->SetTitle('SAI Alupack Product Brochure 2025');
$pdf->SetSubject('Premium Packaging Solutions');

// Remove default header/footer for custom design
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins (A4: 210x297mm)
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Custom colors matching React app design system
$col_dark         = array( 33, 37, 41 ); // #212529
$col_white        = array( 255, 255, 255 ); // #ffffff
$col_green_100    = array( 211, 235, 229 ); // #d3ebe5
$col_green_400    = array( 69, 184, 172 ); // #45b8ac
$col_green_700    = array( 39, 123, 108 ); // #277b6c
$col_green_900    = array( 23, 63, 61 ); // #173f3d
$col_grey_100     = array( 239, 239, 239 ); // #efefef
$col_grey_400     = array( 208, 211, 212 ); // #d0d3d4
$col_coral_400    = array( 255, 107, 94 ); // #ff6b5e
$col_bs_secondary = array( 102, 102, 102 ); // #666666

// Helper function to add page header matching React app design
function add_page_header( $pdf, $section_title, $page_number, $col_green_700, $col_bs_secondary, $col_white ) {
    // Header line at top
    $pdf->SetDrawColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
    $pdf->SetLineWidth( 0.8 );
    $pdf->Line( 15, 25, 195, 25 );
    
    // Logo area (left side)
    $pdf->SetTextColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
    $pdf->SetFont( 'helvetica', 'B', 10 );
    $pdf->SetXY( 15, 15 );
    $pdf->Cell( 80, 8, 'SAI ALUPACK', 0, 0, 'L' );
    
    // Section title (center)
    $pdf->SetFont( 'helvetica', '600', 12 );
    $pdf->SetXY( 80, 15 );
    $pdf->Cell( 50, 8, $section_title, 0, 0, 'C' );
    
    // Page number (right side)
    $pdf->SetTextColor( $col_bs_secondary[0], $col_bs_secondary[1], $col_bs_secondary[2] );
    $pdf->SetFont( 'helvetica', '', 9 );
    $pdf->SetXY( 160, 15 );
    $pdf->Cell( 35, 8, 'Page ' . $page_number, 0, 0, 'R' );
}

// Helper function to add page footer matching React app design
function add_page_footer( $pdf, $col_green_700, $col_white ) {
    // Footer background
    $pdf->SetFillColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
    $pdf->Rect( 0, 287, 210, 10, 'F' );
    
    // Footer text
    $pdf->SetTextColor( $col_white[0], $col_white[1], $col_white[2] );
    $pdf->SetFont( 'helvetica', 'B', 8 );
    
    // Left: Website
    $pdf->SetXY( 15, 289 );
    $pdf->Cell( 60, 6, 'www.sai-alupack.com', 0, 0, 'L' );
    
    // Center: Phone
    $pdf->SetXY( 75, 289 );
    $pdf->Cell( 60, 6, '+91 XXXX XXXXXX', 0, 0, 'C' );
    
    // Right: Email
    $pdf->SetXY( 135, 289 );
    $pdf->Cell( 60, 6, 'info@sai-alupack.com', 0, 0, 'R' );
}

//=============================================================================
// COVER PAGE - Matching React App Design
//=============================================================================
$pdf->AddPage();

// Create beautiful gradient background using overlapping rectangles
$pdf->SetFillColor( $col_green_900[0], $col_green_900[1], $col_green_900[2] );
$pdf->Rect( 0, 0, 210, 297, 'F' );

// Add gradient effect with multiple lighter green overlays
for ( $i = 0; $i < 30; $i++ ) {
    $alpha = 0.02 + ( $i * 0.008 ); // Gradual transparency
    $y     = $i * 10;
    $pdf->SetAlpha( $alpha );
    $pdf->SetFillColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
    $pdf->Rect( 0, $y, 210, 15, 'F' );
}
$pdf->SetAlpha( 1 ); // Reset transparency

// Company logo from ACF options - centered and elegant
$logo_field   = get_field( 'cover_logo', 'option' );
$cover_title_field = get_field( 'cover_title', 'option' );
$cover_subtitle_field = get_field( 'cover_subtitle', 'option' );
$logo_loaded  = false;
$logo_y_start = 80; // Higher position for better balance

// Fallback values if ACF fields are empty
$cover_title = $cover_title_field ? $cover_title_field : 'PRODUCT BROCHURE';
$cover_subtitle = $cover_subtitle_field ? $cover_subtitle_field : '2025';

if ( $logo_field ) {
    // Handle different ACF image return formats
    $logo_url = '';
    if ( is_array( $logo_field ) ) {
        // Array format (with url, alt, etc.)
        $logo_url = $logo_field['url'];
    } elseif ( is_numeric( $logo_field ) ) {
        // ID format
        $logo_url = wp_get_attachment_url( $logo_field );
    } elseif ( is_string( $logo_field ) ) {
        // URL format
        $logo_url = $logo_field;
    }
    
    if ( $logo_url ) {
        try {
            // Check if it's a supported format for TCPDF
            $image_info = getimagesize( $logo_url );
            if ( false !== $image_info ) {
                $mime_type = $image_info['mime'];
                if ( in_array( $mime_type, array( 'image/jpeg', 'image/png', 'image/gif' ), true ) ) {
                    // Center the logo horizontally with elegant white backdrop
                    $logo_width = 100; // Larger, more prominent logo
                    $logo_x     = ( 210 - $logo_width ) / 2; // Center horizontally
                    
                    // Add elegant white backdrop with soft edges
                    $pdf->SetFillColor( 255, 255, 255 );
                    $pdf->SetAlpha( 0.15 );
                    $pdf->RoundedRect( $logo_x - 15, $logo_y_start - 15, $logo_width + 30, 50, 15, '1111', 'F' );
                    $pdf->SetAlpha( 1 );
                    
                    $pdf->Image( $logo_url, $logo_x, $logo_y_start, $logo_width, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false );
                    $logo_loaded = true;
                }
            }
        } catch ( Exception $e ) {
            // Continue to text fallback
        }
    }
}

// If no logo loaded from ACF, create elegant text-based logo
if ( ! $logo_loaded ) {
    $pdf->SetTextColor( 255, 255, 255 );
    $pdf->SetFont( 'helvetica', 'B', 52 );
    $pdf->SetXY( 0, $logo_y_start );
    $pdf->Cell( 210, 25, 'SAI ALUPACK', 0, 1, 'C' );
    
    $pdf->SetFont( 'helvetica', '', 20 );
    $pdf->SetTextColor( $col_green_100[0], $col_green_100[1], $col_green_100[2] );
    $pdf->SetXY( 0, $logo_y_start + 30 );
    $pdf->Cell( 210, 12, 'Premium Packaging Solutions', 0, 1, 'C' );
}

// Main brochure title - elegant and large
$pdf->SetTextColor( 255, 255, 255 );
$pdf->SetFont( 'helvetica', 'B', 56 );
$title_y = 180;
$pdf->SetXY( 0, $title_y );
$pdf->Cell( 210, 30, $cover_title, 0, 1, 'C' );

// Subtitle with year - refined styling
$pdf->SetFont( 'helvetica', '', 28 );
$pdf->SetTextColor( $col_green_100[0], $col_green_100[1], $col_green_100[2] );
$pdf->SetXY( 0, $title_y + 35 );
$pdf->Cell( 210, 18, $cover_subtitle, 0, 1, 'C' );

// Elegant tagline
$pdf->SetFont( 'helvetica', '', 14 );
$pdf->SetTextColor( 255, 255, 255 );
$pdf->SetXY( 0, $title_y + 55 );
$pdf->Cell( 210, 10, 'Quality • Innovation • Sustainability', 0, 1, 'C' );

// Cover image from ACF - positioned at bottom
$cover_image_field = get_field( 'cover_image', 'option' );
$cover_loaded      = false;

if ( $cover_image_field ) {
    $cover_url = '';
    if ( is_array( $cover_image_field ) ) {
        $cover_url = $cover_image_field['url'];
    } elseif (is_numeric($cover_image_field)) {
        $cover_url = wp_get_attachment_url($cover_image_field);
    } elseif (is_string($cover_image_field)) {
        $cover_url = $cover_image_field;
    }
    
    if ($cover_url) {
        try {
            $image_info = getimagesize($cover_url);
            if ($image_info !== false) {
                $mime_type = $image_info['mime'];
                if (in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                    $pdf->Image($cover_url, 20, 150, 170, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                    $cover_loaded = true;
                }
            }
        } catch (Exception $e) {
            // Continue to placeholder
        }
    }
}

if (!$cover_loaded) {
    // Add design placeholder
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Rect(20, 150, 170, 100, 'F');
    
    // Add some design text
    $pdf->SetTextColor($col_green_700[0], $col_green_700[1], $col_green_700[2]);
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetXY(20, 190);
    $pdf->Cell(170, 10, 'Premium Aluminum Packaging Solutions', 0, 0, 'C');
    
    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetXY(20, 205);
    $pdf->Cell(170, 8, 'Quality • Innovation • Sustainability', 0, 0, 'C');
}

// Footer contact info
$pdf->SetTextColor($col_bs_secondary[0], $col_bs_secondary[1], $col_bs_secondary[2]);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetY(-25);
$pdf->Cell(0, 5, 'SAI Alupack | Premium Packaging Solutions', 0, 1, 'C');
$pdf->Cell(0, 5, 'www.sai-alupack.com | info@sai-alupack.com', 0, 1, 'C');

//=============================================================================
// TABLE OF CONTENTS
//=============================================================================
$pdf->AddPage();
add_page_header( $pdf, 'Contents', 2, $col_green_700, $col_bs_secondary, $col_white );

// Main heading
$pdf->SetTextColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
$pdf->SetFont( 'helvetica', 'B', 28 );
$pdf->SetXY( 15, 35 );
$pdf->Cell( 0, 15, 'Table of Contents', 0, 1, 'L' );

// TOC entries with dotted leaders
$toc_y = 60;
$line_height = 12;

$toc_entries = array(
    array( 'About SAI Alupack', '3-4' ),
    array( 'Our Products', '5-8' ),
    array( 'Industry Applications', '9-10' ),
    array( 'Quality & Compliance', '11' ),
    array( 'Contact Information', '12' ),
);

$pdf->SetFont( 'helvetica', '', 14 );
foreach ( $toc_entries as $entry ) {
    // Title
    $pdf->SetTextColor( $col_dark[0], $col_dark[1], $col_dark[2] );
    $pdf->SetXY( 15, $toc_y );
    $pdf->Cell( 30, $line_height, $entry[0], 0, 0, 'L' );
    
    // Dotted line
    $pdf->SetDrawColor( $col_grey_400[0], $col_grey_400[1], $col_grey_400[2] );
    $dots_start = 90;
    $dots_end = 160;
    for ( $x = $dots_start; $x < $dots_end; $x += 3 ) {
        $pdf->Circle( $x, $toc_y + 6, 0.3, 0, 360, 'F' );
    }
    
    // Page numbers
    $pdf->SetTextColor( $col_bs_secondary[0], $col_bs_secondary[1], $col_bs_secondary[2] );
    $pdf->SetXY( 165, $toc_y );
    $pdf->Cell( 25, $line_height, $entry[1], 0, 0, 'R' );
    
    $toc_y += $line_height + 5;
}

// Add footer
add_page_footer( $pdf, $col_green_700, $col_white );

//=============================================================================
// ABOUT SAI ALUPACK
//=============================================================================
$pdf->AddPage();
add_page_header( $pdf, 'About SAI Alupack', 3, $col_green_700, $col_bs_secondary, $col_white );

// Page heading
$pdf->SetTextColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
$pdf->SetFont( 'helvetica', 'B', 28 );
$pdf->SetXY( 15, 35 );
$pdf->Cell( 0, 15, 'About SAI Alupack', 0, 1, 'L' );

// Get about content from ACF options - correct field names
$about_heading = get_field( 'about_heading', 'option' );
$about_text_1  = get_field( 'about_text_1', 'option' );
$about_text_2  = get_field( 'about_text_2', 'option' );
$about_image_1 = get_field( 'about_image_1', 'option' );
$about_image_2 = get_field( 'about_image_2', 'option' );
$about_image_3 = get_field( 'about_image_3', 'option' );

// Fallback to hardcoded content if ACF fields are empty
if ( ! $about_heading ) {
    $about_heading = 'About SAI Alupack';
}
if ( ! $about_text_1 ) {
    $about_text_1 = 'SAI Alupack is a leading manufacturer of premium aluminum packaging solutions. With years of experience in the industry, we specialize in providing high-quality, sustainable packaging options for various sectors including food service, retail, and industrial applications.';
}
if ( ! $about_text_2 ) {
    $about_text_2 = 'Our commitment to innovation and quality has made us a trusted partner for businesses seeking reliable packaging solutions. We combine advanced manufacturing techniques with rigorous quality control to ensure every product meets the highest standards.';
}

// Two-column layout like React app
$content_y = 55;
$text_column_width = 125; // 70% of content width
$image_column_width = 55;  // 30% of content width

// Text content (left column)
$pdf->SetTextColor( $col_dark[0], $col_dark[1], $col_dark[2] );
$pdf->SetFont( 'helvetica', '', 11 );
$pdf->SetXY( 15, $content_y );

// Use the actual text fields
if ( $about_text_1 ) {
    $pdf->MultiCell( $text_column_width, 5, wp_strip_all_tags( $about_text_1 ), 0, 'L' );
} else {
    $fallback_text = 'SAI Alupack is a leading manufacturer of premium aluminum packaging solutions. With years of experience in the industry, we specialize in providing high-quality, sustainable packaging options for various sectors.';
    $pdf->MultiCell( $text_column_width, 5, $fallback_text, 0, 'L' );
}

// About images from ACF (right column stack)
$about_image_1 = get_field( 'about_image_1', 'option' );
$about_image_2 = get_field( 'about_image_2', 'option' );

$image_x = 15 + $text_column_width + 10; // Right column position
$image_y = $content_y;
$image_spacing = 35; // Space between stacked images

// First image
if ( $about_image_1 ) {
    $about_img_url = '';
    if ( is_array( $about_image_1 ) ) {
        $about_img_url = $about_image_1['url'];
    } elseif ( is_numeric( $about_image_1 ) ) {
        $about_img_url = wp_get_attachment_url( $about_image_1 );
    }
    
    if ( $about_img_url ) {
        try {
            $image_info = getimagesize( $about_img_url );
            if ( false !== $image_info ) {
                $mime_type = $image_info['mime'];
                if ( in_array( $mime_type, array( 'image/jpeg', 'image/png', 'image/gif' ), true ) ) {
                    $pdf->Image( $about_img_url, $image_x, $image_y, $image_column_width, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false );
                }
            }
        } catch ( Exception $e ) {
            // Continue without image
        }
    }
}

// Second image (stacked below first)
if ( $about_image_2 ) {
    $about_img_url_2 = '';
    if ( is_array( $about_image_2 ) ) {
        $about_img_url_2 = $about_image_2['url'];
    } elseif ( is_numeric( $about_image_2 ) ) {
        $about_img_url_2 = wp_get_attachment_url( $about_image_2 );
    }
    
    if ( $about_img_url_2 ) {
        try {
            $image_info_2 = getimagesize( $about_img_url_2 );
            if ( false !== $image_info_2 ) {
                $mime_type_2 = $image_info_2['mime'];
                if ( in_array( $mime_type_2, array( 'image/jpeg', 'image/png', 'image/gif' ), true ) ) {
                    $pdf->Image( $about_img_url_2, $image_x, $image_y + $image_spacing, $image_column_width, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false );
                }
            }
        } catch ( Exception $e ) {
            // Continue without image
        }
    }
}

// Add footer
add_page_footer( $pdf, $col_green_700, $col_white );

//=============================================================================
// SECTORS PAGE - Missing from original code
//=============================================================================
$pdf->AddPage();
add_page_header( $pdf, 'Sectors', 4, $col_green_700, $col_bs_secondary, $col_white );

// Page heading
$pdf->SetTextColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
$pdf->SetFont( 'helvetica', 'B', 28 );
$pdf->SetXY( 15, 35 );

// Get sectors content from ACF options
$sectors_title = get_field( 'sectors_page_title', 'option' );
$sectors_intro = get_field( 'sectors_page_intro', 'option' );
$sectors_list  = get_field( 'sectors', 'option' );
$sector_image_1 = get_field( 'sector_image_1', 'option' );
$sector_image_2 = get_field( 'sector_image_2', 'option' );
$sector_image_3 = get_field( 'sector_image_3', 'option' );
$sector_image_4 = get_field( 'sector_image_4', 'option' );

// Fallback content if ACF fields are empty
if ( ! $sectors_title ) {
    $sectors_title = 'Sectors We Serve';
}
if ( ! $sectors_intro ) {
    $sectors_intro = 'Our aluminum packaging solutions serve a diverse range of industries, each with unique requirements and specifications.';
}

$pdf->Cell( 0, 15, $sectors_title, 0, 1, 'L' );

// Intro text
$pdf->SetTextColor( $col_dark[0], $col_dark[1], $col_dark[2] );
$pdf->SetFont( 'helvetica', '', 12 );
$pdf->SetXY( 15, 55 );
$pdf->MultiCell( 180, 6, $sectors_intro, 0, 'L' );

// Two-column layout like React app
$content_y = 75;
$text_column_width = 125;
$image_column_width = 55;

// Left column - Sectors text
$pdf->SetXY( 15, $content_y );

// Default sectors if none in ACF
if ( ! $sectors_list || ! is_array( $sectors_list ) ) {
    $sectors_list = array(
        array(
            'sector_title' => 'Food Service Industry',
            'sector_description' => 'Specialized containers for restaurants, catering, and food delivery services. Our food-grade aluminum solutions ensure safety and freshness while maintaining optimal presentation.'
        ),
        array(
            'sector_title' => 'Retail & Consumer Goods',
            'sector_description' => 'Premium packaging solutions for retail environments. From cosmetics to specialty foods, our containers provide excellent product protection and shelf appeal.'
        ),
        array(
            'sector_title' => 'Industrial Applications',
            'sector_description' => 'Heavy-duty packaging for industrial components and materials. Our robust designs withstand demanding conditions while providing reliable containment.'
        ),
        array(
            'sector_title' => 'Healthcare & Pharmaceuticals',
            'sector_description' => 'Clean, sterile packaging solutions for medical and pharmaceutical products. Our precision manufacturing ensures compliance with strict industry standards.'
        ),
    );
}

$sector_y = $content_y;
foreach ( $sectors_list as $sector ) {
    // Sector title
    $pdf->SetTextColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
    $pdf->SetFont( 'helvetica', 'B', 14 );
    $pdf->SetXY( 15, $sector_y );
    $sector_title = is_array( $sector ) ? $sector['sector_title'] : $sector->sector_title;
    $pdf->Cell( $text_column_width, 8, $sector_title, 0, 1, 'L' );
    
    // Sector description
    $pdf->SetTextColor( $col_dark[0], $col_dark[1], $col_dark[2] );
    $pdf->SetFont( 'helvetica', '', 10 );
    $pdf->SetXY( 15, $sector_y + 8 );
    $sector_desc = is_array( $sector ) ? $sector['sector_description'] : $sector->sector_description;
    $pdf->MultiCell( $text_column_width, 5, wp_strip_all_tags( $sector_desc ), 0, 'L' );
    
    $sector_y += 45; // Space between sectors
}

// Right column - Sector images
$image_x = 15 + $text_column_width + 10;
$image_y = $content_y;
$image_spacing = 40;

$sector_images = array( $sector_image_1, $sector_image_2, $sector_image_3, $sector_image_4 );

foreach ( $sector_images as $index => $sector_image ) {
    if ( $sector_image ) {
        $sector_img_url = '';
        if ( is_array( $sector_image ) ) {
            $sector_img_url = $sector_image['url'];
        } elseif ( is_numeric( $sector_image ) ) {
            $sector_img_url = wp_get_attachment_url( $sector_image );
        }
        
        if ( $sector_img_url ) {
            try {
                $image_info = getimagesize( $sector_img_url );
                if ( false !== $image_info ) {
                    $mime_type = $image_info['mime'];
                    if ( in_array( $mime_type, array( 'image/jpeg', 'image/png', 'image/gif' ), true ) ) {
                        $pdf->Image( $sector_img_url, $image_x, $image_y + ( $index * $image_spacing ), $image_column_width, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false );
                    }
                }
            } catch ( Exception $e ) {
                // Continue without image
            }
        }
    }
}

// Add footer
add_page_footer( $pdf, $col_green_700, $col_white );

//=============================================================================
// PRODUCT CATALOG - OVERVIEW PAGE
//=============================================================================
$pdf->AddPage();
add_page_header( $pdf, 'Product Range', 5, $col_green_700, $col_bs_secondary, $col_white );

$pdf->SetTextColor($col_green_700[0], $col_green_700[1], $col_green_700[2]);
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetXY( 15, 35 );
$pdf->Cell(0, 15, 'Product Range', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetTextColor($col_dark[0], $col_dark[1], $col_dark[2]);
$pdf->SetFont('helvetica', '', 12);
$pdf->SetXY( 15, 55 );
$pdf->MultiCell(180, 6, 'Our comprehensive range of aluminum packaging solutions caters to diverse industry needs. Each product is crafted with precision and attention to detail, ensuring optimal performance and aesthetic appeal.', 0, 'L');
$pdf->Ln(10);

// Get product types for category overview
$product_types = get_terms([
    'taxonomy' => 'product_type',
    'hide_empty' => false,
]);

if (!is_wp_error($product_types) && !empty($product_types)) {
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetXY( 15, 85 );
    $pdf->Cell(0, 10, 'Product Categories:', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 11);
    
    $cat_y = 100;
    foreach ($product_types as $type) {
        $pdf->SetXY( 15, $cat_y );
        $pdf->Cell(10, 6, '•', 0, 0, 'L');
        $pdf->Cell(0, 6, $type->name . ' (' . $type->count . ' products)', 0, 1, 'L');
        $cat_y += 8;
    }
}

// Add footer
add_page_footer( $pdf, $col_green_700, $col_white );

//=============================================================================
// DETAILED PRODUCT PAGES - Grid Layout
//=============================================================================

$pdf->AddPage();
add_page_header( $pdf, 'Product Specifications', 5, $col_green_700, $col_bs_secondary, $col_white );

// Page heading
$pdf->SetTextColor( $col_green_700[0], $col_green_700[1], $col_green_700[2] );
$pdf->SetFont( 'helvetica', 'B', 28 );
$pdf->SetXY( 15, 35 );
$pdf->Cell( 0, 15, 'Our Products', 0, 1, 'L' );

// Get products from WordPress with ACF data
$products_query = new WP_Query( array(
    'post_type'      => 'product',
    'posts_per_page' => 12,
    'post_status'    => 'publish',
    'meta_key'       => 'sku',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
) );

if ( $products_query->have_posts() ) {
    // Grid settings matching React app beautiful layout
    $grid_start_x     = 15;
    $grid_start_y     = 55;
    $card_width       = 58;   // Wider cards for better proportions
    $card_height      = 62;   // Taller cards to match React app
    $card_spacing_x   = 4;    // Horizontal spacing between cards
    $card_spacing_y   = 6;    // Vertical spacing between cards
    $cards_per_row    = 3;
    $rows_per_page    = 3;    // Fewer rows per page for better spacing
    
    $product_count = 0;
    $page_count    = 0;
    
    while ( $products_query->have_posts() && $product_count < 18 ) { // Show more products
        $products_query->the_post();
        $product_id = get_the_ID();
        
        // Calculate grid position
        $col = $product_count % $cards_per_row;
        $row = intval( $product_count / $cards_per_row ) % $rows_per_page;
        
        // Start new page after 9 products (3x3 grid)
        if ( $product_count > 0 && $product_count % 9 === 0 ) {
            add_page_footer( $pdf, $col_green_700, $col_white );
            $pdf->AddPage();
            add_page_header( $pdf, 'Product Specifications', 6 + $page_count, $col_green_700, $col_bs_secondary, $col_white );
            $page_count++;
            $row = 0;
        }
        
        $card_x = $grid_start_x + ( $col * ( $card_width + $card_spacing_x ) );
        $card_y = $grid_start_y + ( $row * ( $card_height + $card_spacing_y ) );
        
        // Product card background with shadow effect
        $pdf->SetFillColor( $col_white[0], $col_white[1], $col_white[2] );
        $pdf->SetDrawColor( $col_grey_100[0], $col_grey_100[1], $col_grey_100[2] );
        $pdf->SetLineWidth( 0.2 );
        
        // Add subtle shadow first
        $pdf->SetFillColor( 240, 240, 240 );
        $pdf->RoundedRect( $card_x + 0.5, $card_y + 0.5, $card_width, $card_height, 2, '1111', 'F' );
        
        // Main card
        $pdf->SetFillColor( $col_white[0], $col_white[1], $col_white[2] );
        $pdf->RoundedRect( $card_x, $card_y, $card_width, $card_height, 2, '1111', 'DF' );
        
        // Get ACF fields with correct field names (snake_case not camelCase)
        $sku        = get_field( 'sku', $product_id );
        $capacity   = get_field( 'capacity', $product_id );
        $top_out_a  = get_field( 'top_out_a', $product_id );  // Correct field name
        $top_out_b  = get_field( 'top_out_b', $product_id );  // Correct field name
        $top_in_a   = get_field( 'top_in_a', $product_id );   // Correct field name
        $top_in_b   = get_field( 'top_in_b', $product_id );   // Correct field name
        $base_a     = get_field( 'base_a', $product_id );     // Correct field name
        $base_b     = get_field( 'base_b', $product_id );     // Correct field name
        $depth      = get_field( 'depth', $product_id );
        $weight     = get_field( 'weight', $product_id );
        
        // Product image area (top of card) - larger and better proportioned
        $image_area_height = 24;
        $image_padding     = 3;
        $pdf->SetFillColor( 245, 245, 245 );
        $pdf->RoundedRect( $card_x + $image_padding, $card_y + $image_padding, $card_width - ( 2 * $image_padding ), $image_area_height, 1.5, '1111', 'F' );
        
        // Get product image from ACF or product type
        $product_image_url = '';
        $product_types     = get_the_terms( $product_id, 'product_type' );
        if ( $product_types && ! is_wp_error( $product_types ) ) {
            $product_type = $product_types[0];
            $image_id     = get_field( 'image', 'product_type_' . $product_type->term_id );
            if ( $image_id ) {
                $product_image_url = wp_get_attachment_url( $image_id );
            }
        }
        
        // Add product image or elegant placeholder
        if ( $product_image_url ) {
            try {
                $image_info = getimagesize( $product_image_url );
                if ( false !== $image_info ) {
                    $mime_type = $image_info['mime'];
                    if ( in_array( $mime_type, array( 'image/jpeg', 'image/png', 'image/gif' ), true ) ) {
                        $image_x = $card_x + $image_padding + 2;
                        $image_y = $card_y + $image_padding + 2;
                        $image_w = $card_width - ( 2 * $image_padding ) - 4;
                        $pdf->Image( $product_image_url, $image_x, $image_y, $image_w, 0, '', '', 'T', false, 300, '', false, false, 0, false, false, false );
                    }
                }
            } catch ( Exception $e ) {
                // Show elegant placeholder
                $pdf->SetTextColor( $col_grey_400[0], $col_grey_400[1], $col_grey_400[2] );
                $pdf->SetFont( 'helvetica', '', 8 );
                $pdf->SetXY( $card_x + $image_padding, $card_y + $image_padding + 8 );
                $pdf->Cell( $card_width - ( 2 * $image_padding ), 8, '📦 Product', 0, 0, 'C' );
            }
        } else {
            // Show elegant placeholder
            $pdf->SetTextColor( $col_grey_400[0], $col_grey_400[1], $col_grey_400[2] );
            $pdf->SetFont( 'helvetica', '', 8 );
            $pdf->SetXY( $card_x + $image_padding, $card_y + $image_padding + 8 );
            $pdf->Cell( $card_width - ( 2 * $image_padding ), 8, '📦 Product', 0, 0, 'C' );
        }
        
        // Product title - bigger and better styled
        $product_title = get_the_title();
        if ( $sku ) {
            $product_title = $sku;
        }
        
        $pdf->SetTextColor( $col_dark[0], $col_dark[1], $col_dark[2] );
        $pdf->SetFont( 'helvetica', 'B', 9 );
        $title_y = $card_y + $image_area_height + $image_padding + 2;
        $pdf->SetXY( $card_x + 3, $title_y );
        $pdf->Cell( $card_width - 6, 6, $product_title, 0, 0, 'C' );
        
        // Product specifications - better spacing and typography
        $spec_y           = $title_y + 8;
        $spec_line_height = 5;
        $spec_padding     = 4;
        
        $pdf->SetFont( 'helvetica', '', 7 );
        
        // Helper function to format dimensions like React app
        $format_dimension = function( $a, $b, $unit = 'mm' ) {
            if ( ! $a || $a == 0 ) return '—';
            if ( ! $b || $b == 0 || $a == $b ) return $a . $unit;
            return $a . ' x ' . $b . $unit;
        };
        
        $specs = array();
        if ( $capacity ) {
            $specs[] = array( 'Capacity', $capacity . 'ml' );
        }
        if ( $top_out_a || $top_out_b ) {
            $specs[] = array( 'Top Out', $format_dimension( $top_out_a, $top_out_b ) );
        }
        if ( $top_in_a || $top_in_b ) {
            $specs[] = array( 'Top In', $format_dimension( $top_in_a, $top_in_b ) );
        }
        if ( $base_a || $base_b ) {
            $specs[] = array( 'Base', $format_dimension( $base_a, $base_b ) );
        }
        if ( $depth ) {
            $specs[] = array( 'Depth', $depth . 'mm' );
        }
        
        // Limit to 4 most important specs for clean layout
        $specs = array_slice( $specs, 0, 4 );
        
        foreach ( $specs as $spec ) {
            // Spec row with dotted border
            $pdf->SetDrawColor( 200, 200, 200 );
            $pdf->SetLineWidth( 0.1 );
            if ( $spec !== end( $specs ) ) { // Don't draw line after last spec
                $pdf->Line( $card_x + $spec_padding, $spec_y + $spec_line_height - 1, $card_x + $card_width - $spec_padding, $spec_y + $spec_line_height - 1 );
            }
            
            // Label
            $pdf->SetTextColor( $col_bs_secondary[0], $col_bs_secondary[1], $col_bs_secondary[2] );
            $pdf->SetXY( $card_x + $spec_padding, $spec_y );
            $pdf->Cell( 25, $spec_line_height, $spec[0] . ':', 0, 0, 'L' );
            
            // Value
            $pdf->SetTextColor( $col_dark[0], $col_dark[1], $col_dark[2] );
            $pdf->SetFont( 'helvetica', 'B', 7 );
            $pdf->SetXY( $card_x + $spec_padding + 25, $spec_y );
            $pdf->Cell( $card_width - $spec_padding - 25 - $spec_padding, $spec_line_height, $spec[1], 0, 0, 'R' );
            
            $pdf->SetFont( 'helvetica', '', 7 );
            $spec_y += $spec_line_height;
        }
        
        $product_count++;
    }
    
    wp_reset_postdata();
} else {
    // No products found message
    $pdf->SetTextColor( $col_bs_secondary[0], $col_bs_secondary[1], $col_bs_secondary[2] );
    $pdf->SetFont( 'helvetica', '', 12 );
    $pdf->SetXY( 15, 60 );
    $pdf->Cell( 0, 10, 'No products found in the catalog.', 0, 1, 'L' );
}

// Add footer to current page
add_page_footer( $pdf, $col_green_700, $col_white );

//=============================================================================
// COMPLIANCE PAGE  
//=============================================================================
$pdf->AddPage();
add_page_header( $pdf, 'Quality & Compliance', 8, $col_green_700, $col_bs_secondary, $col_white );

$pdf->SetTextColor($col_green_700[0], $col_green_700[1], $col_green_700[2]);
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetXY( 15, 35 );
$pdf->Cell(0, 15, 'Quality & Compliance', 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetTextColor($col_dark[0], $col_dark[1], $col_dark[2]);
$pdf->SetFont('helvetica', '', 12);
$pdf->SetXY( 15, 55 );

// Get compliance statement from ACF if available
$compliance_statement = get_field( 'statement_of_compliance', 'option' );

if ( ! $compliance_statement ) {
    $compliance_statement = 'At SAI Alupack, quality is not just a goal—it\'s our commitment. We adhere to the highest international standards and maintain rigorous quality control processes throughout our manufacturing operations.

Our certifications include:
• ISO 9001:2015 Quality Management Systems
• BRC Global Standard for Packaging and Packaging Materials
• FSSC 22000 Food Safety System Certification
• FDA Compliance for Food Contact Materials

Every product undergoes comprehensive testing to ensure it meets or exceeds industry standards for safety, durability, and performance.';
}

$pdf->MultiCell(180, 6, wp_strip_all_tags( $compliance_statement ), 0, 'L');

// Add footer
add_page_footer( $pdf, $col_green_700, $col_white );

//=============================================================================
// CONTACT & FOOTER
//=============================================================================
$pdf->AddPage();
add_page_header( $pdf, 'Contact Information', 9, $col_green_700, $col_bs_secondary, $col_white );

$pdf->SetTextColor($col_green_700[0], $col_green_700[1], $col_green_700[2]);
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetXY( 15, 35 );
$pdf->Cell(0, 15, 'Contact Information', 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetTextColor($col_dark[0], $col_dark[1], $col_dark[2]);
$pdf->SetFont('helvetica', '', 12);
$pdf->SetXY( 15, 60 );

$contact_text = 'Ready to discover how SAI Alupack can meet your packaging needs? Our team of experts is here to help you find the perfect solution for your business.

Get in touch with us today to discuss your requirements, request samples, or learn more about our custom packaging solutions.';

$pdf->MultiCell(180, 6, $contact_text, 0, 'L');
$pdf->Ln(15);

// Contact details
$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetXY( 15, 110 );
$pdf->Cell(0, 8, 'SAI Alupack', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->SetXY( 15, 120 );
$pdf->Cell(0, 6, 'Email: info@sai-alupack.com', 0, 1, 'L');
$pdf->SetXY( 15, 128 );
$pdf->Cell(0, 6, 'Website: www.sai-alupack.com', 0, 1, 'L');

// Add footer
add_page_footer( $pdf, $col_green_700, $col_white );

// Clean output buffer and send PDF
ob_end_clean();

// Set headers for PDF download
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="sai-brochure-' . date('Y-m-d') . '.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Output PDF - Now includes all content from WordPress ACF fields
$pdf->Output('sai-brochure-' . date('Y-m-d') . '.pdf', 'I');
exit;
