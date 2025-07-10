<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function gsa_export_csv($images) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=gorsel-seo-eksik-alt.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Görsel URL', 'Dosya Adı', 'Bulunduğu İçerik']);
    foreach ($images as $img) {
        fputcsv($output, [$img['url'], $img['filename'], $img['post_title']]);
    }
    fclose($output);
}

function gsa_export_json($images) {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=gorsel-seo-eksik-alt.json');
    echo json_encode($images, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}