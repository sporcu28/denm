<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// SEO bilgilendirme kutusu
include_once GSA_PATH . 'admin/seo-info-box.php';

// Sayfalama
$paged = isset($_GET['gsa_page']) ? max(1, intval($_GET['gsa_page'])) : 1;
$per_page = 50;
$all_images = gsa_get_images_without_alt();
$total = count($all_images);
$pages = ceil($total / $per_page);
$gsa_images = array_slice($all_images, ($paged-1)*$per_page, $per_page);

// Toplu güncelleme ve dışa aktarım işlemleri
if (isset($_POST['gsa_bulk_update']) && check_admin_referer('gsa_bulk_update_action', 'gsa_bulk_update_nonce')) {
    $updated = gsa_bulk_update_alt_texts();
    if ($updated > 0) {
        echo '<div class="updated"><p>' . esc_html($updated) . ' görselin alt etiketi güncellendi.</p></div>';
    } else {
        echo '<div class="error"><p>Hiçbir görsel güncellenmedi.</p></div>';
    }
}
if (isset($_POST['gsa_export_csv'])) {
    gsa_export_csv($gsa_images);
    exit;
}
if (isset($_POST['gsa_export_json'])) {
    gsa_export_json($gsa_images);
    exit;
}

// OpenAI API anahtarı kontrolü
$openai_api_key = defined('GSA_OPENAI_API_KEY') ? GSA_OPENAI_API_KEY : '';
if (empty($openai_api_key)) {
    echo '<div class="notice notice-error"><p><strong>UYARI:</strong> Yapay zekâ ile alt metin önerisi için OpenAI API anahtarınızı wp-config.php dosyanıza ekleyin.</p></div>';
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
                            <?php 
                            $suggestion = '';
                            if (!empty($openai_api_key)) {
                                $suggestion = gsa_generate_ai_alt($img['id']);
                                if (empty($suggestion)) {
                                    $suggestion = 'Yapay zekâdan öneri alınamadı.';
                                }
                            } else {
                                $suggestion = 'API anahtarı yok.';
                            }
                            ?>
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
    <?php if ($pages > 1): ?>
    <div style="margin-top:20px;">
        <strong>Sayfa:</strong>
        <?php for ($i=1; $i<=$pages; $i++): ?>
            <?php if ($i == $paged): ?>
                <span style="font-weight:bold;"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="<?php echo esc_url(add_query_arg('gsa_page', $i)); ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
<div style="margin-top:40px;">
    <a href="https://openai.com/" target="_blank">OpenAI API anahtarı al</a> | <a href="admin.php?page=gorsel-seo-asistani-help">Yardım & Destek</a>
</div>