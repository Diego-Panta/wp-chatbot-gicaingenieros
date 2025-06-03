<?php
if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
}

function obtener_cursos()
{
    try {
        $modo = get_option('gicachat_modo', 'default');
        $fuente_cursos = get_option('gicachat_fuente_cursos', 'masterstudy');

        if ($modo === 'default') {
            if ($fuente_cursos === 'woocommerce') {
                return obtener_cursos_woocommerce();
            } else {
                return obtener_cursos_masterstudy();
            }
        }
        return [];
        
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - obtener_cursos] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}
