<?php
/**
 * The header for the theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package lc-saialupack2025
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
session_start();
// Hide admin bar for this template only.
add_action(
    'init',
    function () {
        add_filter( 'show_admin_bar', '__return_false' );
    }
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta
        charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() ); ?>/fonts/manrope-v15-latin-regular.woff2"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() ); ?>/fonts/manrope-v15-latin-600.woff2"
        as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload"
        href="<?= esc_url( get_stylesheet_directory_uri() ); ?>/fonts/manrope-v15-latin-700.woff2"
        as="font" type="font/woff2" crossorigin="anonymous">
    <?php
    wp_head();
    ?>
</head>
<body <?php body_class(); ?>
    <?php understrap_body_attributes(); ?>>
    <?php
    do_action( 'wp_body_open' );
    ?>
