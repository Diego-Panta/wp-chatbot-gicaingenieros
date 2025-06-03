<?php
if (!defined('ABSPATH')) exit;

class GeminiClient implements IAInterface
{

    public function responder(string $mensaje, string $contexto): string
    {
        try {
            $api_key = get_option('gicachat_api_gemini');
            $modelo = get_option('gicachat_gemini_model', 'gemini-1.5-flash-8b');
            $temperature = floatval(get_option('gicachat_gemini_temperature', 0.7));
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelo}:generateContent?key={$api_key}";

            $data = array(
                "contents" => array(
                    array(
                        "role" => "user",
                        "parts" => array(
                            array("text" => $contexto . "\n\nPregunta del usuario: " . $mensaje)
                        )
                    )
                ),
                "generationConfig" => array(
                    "temperature" => $temperature
                )
            );

            $args = array(
                'body'        => json_encode($data),
                'headers'     => array('Content-Type' => 'application/json'),
                'timeout'     => 30
            );

            $response = wp_remote_post($url, $args);

            if (is_wp_error($response)) {
                return "Error en la API de Gemini.";
            }

            $body = json_decode(wp_remote_retrieve_body($response), true);
            return $body['candidates'][0]['content']['parts'][0]['text'] ?? "No entendí la respuesta.";
        } catch (Exception $e) {
            return "Excepción al procesar respuesta de Gemini: " . $e->getMessage();
        }
    }
}
