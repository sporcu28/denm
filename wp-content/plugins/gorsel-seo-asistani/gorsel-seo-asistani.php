<?php
/*
Plugin Name: Görsel SEO Asistanı
Description: Eksik alt etiketli görselleri bulur, öneri sunar ve toplu düzeltme imkanı sağlar.
Version: 1.0
Author: Senin Adın
Text Domain: gorsel-seo-asistani
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'GSA_PATH', plugin_dir_path( __FILE__ ) );
define( 'GSA_URL', plugin_dir_url( __FILE__ ) );

date_default_timezone_set('Europe/Istanbul');

// Admin paneli menüsü
add_action('admin_menu', function() {
    add_menu_page(
        'Görsel SEO Asistanı',
        'Görsel SEO Asistanı',
        'manage_options',
        'gorsel-seo-asistani',
        'gsa_admin_page',
        'dashicons-format-image',
        80
    );
    add_submenu_page(
        'gorsel-seo-asistani',
        'Yardım & Destek',
        'Yardım & Destek',
        'manage_options',
        'gorsel-seo-asistani-help',
        'gsa_help_page'
    );
});

// Admin paneli sayfası
function gsa_admin_page() {
    require_once GSA_PATH . 'admin/settings-page.php';
}

function gsa_help_page() {
    require_once GSA_PATH . 'admin/help-page.php';
}

// Gerekli dosyaları dahil et
require_once GSA_PATH . 'includes/scanner.php';
require_once GSA_PATH . 'includes/ai-alt-generator.php';
require_once GSA_PATH . 'includes/exporter.php';

// Stil dosyası
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook == 'toplevel_page_gorsel-seo-asistani') {
        wp_enqueue_style('gsa-admin-style', GSA_URL . 'assets/style.css');
    }
});