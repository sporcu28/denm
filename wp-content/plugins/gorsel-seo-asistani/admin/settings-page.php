<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// SEO bilgilendirme kutusu
include_once GSA_PATH . 'admin/seo-info-box.php';

// Görselleri tara ve eksik alt etiketlileri al
$gsa_images = gsa_get_images_without_alt();

// Toplu güncelleme ve dışa aktarım işlemleri
if (isset($_POST['gsa_bulk_update']) && check_admin_referer('gsa_bulk_update_action', 'gsa_bulk_update_nonce')) {
    $updated = gsa_bulk_update_alt_texts();
    echo '<div class="updated"><p>' . esc_html($updated) . ' görselin alt etiketi güncellendi.</p></div>';
}
if (isset($_POST['gsa_export_csv'])) {
    gsa_export_csv($gsa_images);
    exit;
}
if (isset($_POST['gsa_export_json'])) {
    gsa_export_json($gsa_images);
    exit;
}
?>
<div class="wrap">
    <h1>Görsel SEO Asistanı</h1>
    <form method="post">
        <?php wp_nonce_field('gsa_bulk_update_action', 'gsa_bulk_update_nonce'); ?>
        <table class="widefat fixed" style="margin-top:20px;">
            <thead>
                <tr>
                    <th>Görsel</th>
                    <th>Dosya Adı</th>
                    <th>Bulunduğu İçerik</th>
                    <th>Önerilen Alt Metin</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($gsa_images)): ?>
                    <tr><td colspan="4">Tüm görsellerin alt etiketi mevcut!</td></tr>
                <?php else: foreach ($gsa_images as $img): ?>
                    <tr>
                        <td><img src="<?php echo esc_url($img['url']); ?>" width="60" /></td>
                        <td><?php echo esc_html($img['filename']); ?></td>
                        <td><?php echo esc_html($img['post_title']); ?></td>
                        <td>
                            <?php $suggestion = gsa_generate_ai_alt($img['id']); ?>
                            <input type="text" name="gsa_alt_texts[<?php echo esc_attr($img['id']); ?>]" value="<?php echo esc_attr($suggestion); ?>" style="width:100%" />
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
        <p style="margin-top:20px;">
            <button type="submit" name="gsa_bulk_update" class="button button-primary">Tümünü Güncelle</button>
            <button type="submit" name="gsa_export_csv" class="button">CSV Dışa Aktar</button>
            <button type="submit" name="gsa_export_json" class="button">JSON Dışa Aktar</button>
        </p>
    </form>
</div>