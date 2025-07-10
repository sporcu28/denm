<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function gsa_generate_ai_alt($attachment_id) {
    // Kullanıcı kendi OpenAI API anahtarını buraya eklemeli
    $openai_api_key = defined('GSA_OPENAI_API_KEY') ? GSA_OPENAI_API_KEY : '';
    if (empty($openai_api_key)) return 'Lütfen API anahtarınızı ayarlayın.';

    $filename = basename(get_attached_file($attachment_id));
    $prompt = 'Bir web sitesinde SEO için uygun, kısa ve açıklayıcı bir görsel alt metni öner: ' . $filename;

    $response = wp_remote_post('https://api.openai.com/v1/completions', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $openai_api_key,
        ],
        'body' => json_encode([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 30,
            'temperature' => 0.7,
        ]),
        'timeout' => 20,
    ]);
    if (is_wp_error($response)) return '';
    $body = json_decode(wp_remote_retrieve_body($response), true);
    return isset($body['choices'][0]['text']) ? trim($body['choices'][0]['text']) : '';
}