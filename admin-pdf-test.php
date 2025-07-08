<?php
/**
 * Simple admin page to test PDF generation
 * Access via: /wp-admin/admin.php?page=sai-pdf-test
 */

// Add admin menu
add_action('admin_menu', 'sai_pdf_test_menu');

function sai_pdf_test_menu() {
    add_options_page(
        'SAI PDF Test',
        'SAI PDF Test', 
        'manage_options',
        'sai-pdf-test',
        'sai_pdf_test_page'
    );
}

function sai_pdf_test_page() {
    if (isset($_GET['generate_pdf'])) {
        // Redirect to the direct PDF endpoint to avoid admin header conflicts
        $pdf_url = get_stylesheet_directory_uri() . '/generate-pdf.php';
        wp_redirect($pdf_url);
        exit;
    }
    
    echo '<div class="wrap">';
    echo '<h1>SAI Brochure PDF Test</h1>';
    echo '<p>Click the button below to generate and download a test PDF with real ACF product data:</p>';
    
    $pdf_url = get_stylesheet_directory_uri() . '/generate-pdf.php';
    echo '<a href="' . esc_url($pdf_url) . '" class="button button-primary" target="_blank">Generate PDF</a>';
    
    echo '<hr>';
    echo '<h2>Technical Details</h2>';
    echo '<ul>';
    echo '<li><strong>PDF Generator:</strong> TCPDF Library</li>';
    echo '<li><strong>Data Source:</strong> WordPress ACF Fields</li>';
    echo '<li><strong>Images:</strong> WordPress Media Library</li>';
    echo '<li><strong>Products Found:</strong> ';
    
    $count_query = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids'
    ]);
    echo $count_query->found_posts;
    wp_reset_postdata();
    
    echo '</li>';
    echo '<li><strong>Direct URL:</strong> <code>' . esc_html($pdf_url) . '</code></li>';
    echo '</ul>';
    
    echo '<h3>Alternative Test Links</h3>';
    echo '<p><a href="' . esc_url(get_stylesheet_directory_uri() . '/pdf-test-acf.php') . '" target="_blank" class="button">Detailed ACF Test</a></p>';
    
    echo '</div>';
}

// Old function removed - now using direct PDF endpoint to avoid header conflicts
