<?php
if (!defined('ABSPATH')) exit;

function gicachat_manejar_condition($condition, &$session, $estado, $flujo)
{
    try {
        switch ($condition) {
            case '¿existe_respuesta?':
                $session['estado_actual'] = $session['existe_respuesta'] ? $estado['true'] : $estado['false'];
                return gicachat_manejar_flujo('', $session);
        }
    } catch (Throwable $e) {
        error_log('[GicaChat][Condition] Error: ' . $e->getMessage());
        return construir_mensaje_error();
    }

    return '';
}
