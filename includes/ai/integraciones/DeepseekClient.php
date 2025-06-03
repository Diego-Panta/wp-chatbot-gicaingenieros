<?php
if (!defined('ABSPATH')) exit;

class DeepseekClient implements IAInterface
{

    public function responder(string $mensaje, string $contexto): string
    {
        try {
            $api_key = get_option('gicachat_api_deepseek');
            $modelo = get_option('gicachat_deepseek_model', 'deepseek-chat');
            $temperature = floatval(get_option('gicachat_deepseek_temperature', 0.7));
            $url = 'https://api.deepseek.com/v1/chat/completions';

            $data = array(
                "model" => $modelo,
                "messages" => array(
                    array("role" => "system", "content" => $contexto),
                    array("role" => "user", "content" => $mensaje)
                ),
                "temperature" => $temperature
            );

            $args = array(
                'body'        => json_encode($data),
                'headers'     => array(
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key
                ),
                'timeout'     => 30
            );

            $response = wp_remote_post($url, $args);

            if (is_wp_error($response)) {
                return "Error en la API de DeepSeek.";
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);

            return $body['choices'][0]['message']['content'] ?? "No entendí la respuesta.";
        } catch (Exception $e) {
            return "Excepción al procesar respuesta de DeepSeek: " . $e->getMessage();
        }
    }
}
