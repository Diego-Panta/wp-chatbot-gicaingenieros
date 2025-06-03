<?php
if (!defined('ABSPATH')) exit;
/* --------------------------- Funciones para voiceflow --------------------------- */

function gicachat_iniciar_chat()
{
    $modo = isset($_POST['modo']) ? sanitize_text_field($_POST['modo']) : 'voiceflow';

    // Si el modo es voiceflow, no hacer nada, no enviar mensaje
    if ($modo === 'voiceflow') {
        try {
            $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : 'gicabot_beta';
            $respuesta = obtener_respuesta_voiceflow("iniciar", $user_id);
            $respuesta = preg_replace('/(<a href=[\'"])([^\'"]+)%3Cbr\/([\'"])/', '$1$2$3', $respuesta);
            $respuesta = str_replace(['*', '**'], '', $respuesta);
            $respuesta = nl2br($respuesta);
            wp_send_json_success(array('respuesta' => $respuesta));
        } catch (Exception $e) {
            error_log('[GicaChat ERROR - gicachat_ai_response] ' . $e->getMessage());
            wp_send_json_error(array('error' => construir_mensaje_error()));
        }
        return;
    }
}

add_action('wp_ajax_gicachat_iniciar_chat', 'gicachat_iniciar_chat');
add_action('wp_ajax_nopriv_gicachat_iniciar_chat', 'gicachat_iniciar_chat');

function gicachat_enviar_mensaje()
{
    try {
        $modo = isset($_POST['modo']) ? sanitize_text_field($_POST['modo']) : 'voiceflow';

        if ($modo !== 'voiceflow') {
            wp_send_json_error('Modo no compatible con esta acción.');
        }

        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : 'gicabot_beta';

        if (isset($_POST['request'])) {
            $request = json_decode(stripslashes($_POST['request']), true);
            $mensaje = obtener_respuesta_voiceflow($request, $user_id);
        } elseif (isset($_POST['mensaje'])) {
            $mensaje = sanitize_text_field($_POST['mensaje']);
            $mensaje = obtener_respuesta_voiceflow($mensaje, $user_id);
        } else {
            wp_send_json_error("No se recibió ningún mensaje.");
        }

        // Devuelve el HTML plano (texto + botones + carrusel)
        wp_send_json_success($mensaje);
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - gicachat_ai_response] ' . $e->getMessage());
        wp_send_json_error(array('error' => construir_mensaje_error()));
    }
}

add_action('wp_ajax_gicachat_enviar_mensaje', 'gicachat_enviar_mensaje');
add_action('wp_ajax_nopriv_gicachat_enviar_mensaje', 'gicachat_enviar_mensaje');

function gicachat_reiniciar_chat()
{
    $modo = isset($_POST['modo']) ? sanitize_text_field($_POST['modo']) : 'voiceflow';

    if ($modo !== 'voiceflow') {
        wp_send_json_error("Modo no soportado para reinicio.");
    }
    try {

        $user_id = isset($_POST['user_id']) ? sanitize_text_field($_POST['user_id']) : 'gicabot_beta';

        $result = reiniciar_sesion_voiceflow($user_id);
        wp_send_json_success(array('resultado' => 'Sesión reiniciada'));
        
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - gicachat_ai_response] ' . $e->getMessage());
        wp_send_json_error(array('error' => construir_mensaje_error()));
    }
}
add_action('wp_ajax_gicachat_reiniciar_chat', 'gicachat_reiniciar_chat');
add_action('wp_ajax_nopriv_gicachat_reiniciar_chat', 'gicachat_reiniciar_chat');
