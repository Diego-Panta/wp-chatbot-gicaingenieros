<?php
if (!defined('ABSPATH')) exit;

function gicachat_construir_contexto_desde_historial($session, $mensaje_actual)
{
    $historial = array_slice($session['historial'] ?? [], -6);
    $contexto = "";

    foreach ($historial as $entrada) {
        $contexto .= $entrada['mensaje'] . "\n";
        if (!empty($entrada['respuesta'])) {
            $contexto .= $entrada['respuesta'] . "\n";
        }
    }

    $contexto .= $mensaje_actual . "\n";
    return $contexto;
}

function gicachat_guardar_en_historial(&$session, $mensaje_usuario, $respuesta)
{
    if (!isset($session['historial'])) {
        $session['historial'] = [];
    }

    $session['historial'][] = [
        'estado'   => $session['estado_actual'] ?? 'desconocido',
        'mensaje'  => $mensaje_usuario,
        'respuesta'=> $respuesta,
        'timestamp'=> time()
    ];
}

function gicachat_reset_chat() {
    if (!session_id()) {
        session_start();
    }

    if (isset($_SESSION['gicachat'])) {
        unset($_SESSION['gicachat']['historial']);
        error_log('[GicaChat] Historial del servidor eliminado correctamente.');
        wp_send_json_success();
    } else {
        error_log('[GicaChat] No se encontró sesión activa para eliminar historial.');
        wp_send_json_error('No hay sesión activa');
    }
}

add_action('wp_ajax_gicachat_reset_chat', 'gicachat_reset_chat');
add_action('wp_ajax_nopriv_gicachat_reset_chat', 'gicachat_reset_chat');