<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function gsa_get_images_without_alt() {
    global $wpdb;
    $results = [];
    // Medya kütüphanesindeki görseller
    $attachments = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_status' => 'inherit',
    ]);
    foreach ($attachments as $att) {
        $alt = get_post_meta($att->ID, '_wp_attachment_image_alt', true);
        if (empty($alt)) {
            $results[] = [
                'id' => $att->ID,
                'url' => wp_get_attachment_url($att->ID),
                'filename' => basename(get_attached_file($att->ID)),
                'post_title' => $att->post_title,
            ];
        }
    }
    return $results;
}

function gsa_bulk_update_alt_texts() {
    if (!isset($_POST['gsa_alt_texts']) || !is_array($_POST['gsa_alt_texts'])) return 0;
    $count = 0;
    foreach ($_POST['gsa_alt_texts'] as $id => $alt) {
        if (!empty($alt)) {
            update_post_meta($id, '_wp_attachment_image_alt', sanitize_text_field($alt));
            $count++;
        }
    }
    return $count;
}