<?php
if (!defined('ABSPATH')) exit;


function gicachat_ai_response()
{
    try {
        $modo = isset($_POST['modo']) ? sanitize_text_field($_POST['modo']) : 'default';

        if (!isset($_POST['mensaje']) || empty($_POST['mensaje'])) {
            wp_send_json_error(array('error' => 'Mensaje vacío'));
        }

        $mensaje_usuario = trim(strip_tags(sanitize_text_field($_POST['mensaje'])));
        if (strlen($mensaje_usuario) > 500) {
            wp_send_json_error(['error' => 'Mensaje demasiado largo']);
        }

        if ($modo === 'default') {
            // Obtener estado actual desde la cookie
            $estado_json = isset($_COOKIE['gicachat_estado']) ? stripslashes($_COOKIE['gicachat_estado']) : '{}';
            $estado = json_decode($estado_json, true);
            if (!is_array($estado)) $estado = [];

            // Si es mensaje inicial "__init__", forzamos el nodo de inicio
            if ($mensaje_usuario === '__init__') {
                $estado['estado_actual'] = 'start';
                $estado['datos'] = [];
                $respuesta = gicachat_manejar_flujo('', $estado);
                setcookie('gicachat_estado', json_encode($estado), time() + 3600, '/');
                wp_send_json_success(array('respuesta' => nl2br($respuesta)));
            }

            // Procesar el flujo
            $respuesta = gicachat_manejar_flujo($mensaje_usuario, $estado);

            // Guardar nuevo estado actualizado en la cookie
            setcookie('gicachat_estado', json_encode($estado), time() + 3600, '/'); // 1 hora de duración

            wp_send_json_success(array('respuesta' => nl2br($respuesta)));
        }

        // FALLBACK
        wp_send_json_success(array('respuesta' => "Modo no reconocido."));
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - gicachat_ai_response] ' . $e->getMessage());
        wp_send_json_error(array('error' => construir_mensaje_error()));
    }
}

add_action('wp_ajax_gicachat_ai_response', 'gicachat_ai_response');
add_action('wp_ajax_nopriv_gicachat_ai_response', 'gicachat_ai_response');
