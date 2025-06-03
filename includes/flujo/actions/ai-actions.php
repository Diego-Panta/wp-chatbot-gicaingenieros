<?php
if (!defined('ABSPATH')) exit;

function gicachat_manejar_ai_action($ai_action, &$session, $estado, $flujo)
{
    try {
        switch ($estado['ai_action']) {
            case 'resumir_pregunta':
                $pregunta = $session['pregunta_usuario'] ?? '';
                $resumen = obtener_resumen_desde_IA($pregunta);
                $session['resumen_generado'] = $resumen;
                $session['estado_actual'] = $estado['next'];

                $siguiente_estado = $flujo[$estado['next']];
                $siguiente_estado['message'] = str_replace('{resumen_generado}', $resumen, $siguiente_estado['message']);

                return construir_respuesta($siguiente_estado);

            case 'responder_pregunta':
                $pregunta = $session['pregunta_usuario'] ?? '';
                $respuesta = obtener_respuesta_desde_IA($pregunta);
                $session['ultima_respuesta'] = $respuesta;
                $session['estado_actual'] = $estado['next'];

                $siguiente_estado = $flujo[$estado['next']] ?? [];

                // Encadena la respuesta + mensaje de confirmación

                $html_respuesta = '<div class="mensaje-texto">
                ' . gicachat_icono_bot_html() . '
                <div class="mensaje-texto-send"><p>' . $respuesta . '</p></div>
            </div>';

                if (!empty($siguiente_estado['message'])) {
                    $mensaje = str_replace('{respuesta_generada}', $respuesta, $siguiente_estado['message']);
                    $siguiente_estado['message'] = $mensaje;
                    return $html_respuesta . construir_respuesta($siguiente_estado);
                }

                return $html_respuesta;

            case 'reformular_respuesta':
                $reformulada = $session['ultima_respuesta'] ?? '';
                $respuesta = reformular_respuesta_desde_IA($reformulada);
                $session['ultima_respuesta'] = $respuesta;
                $session['estado_actual'] = $estado['next'];

                $siguiente_estado = $flujo[$estado['next']] ?? [];

                // Encadena la respuesta + mensaje de confirmación
                $html_respuesta = '<div class="mensaje-texto">
                ' . gicachat_icono_bot_html() . '
                <div class="mensaje-texto-send"><p>' . $respuesta . '</p></div>
            </div>';

                if (!empty($siguiente_estado['message'])) {
                    if (strpos($siguiente_estado['message'], '{respuesta_generada}') !== false) {
                        $mensaje = str_replace('{respuesta_generada}', $respuesta, $siguiente_estado['message']);
                        $siguiente_estado['message'] = $mensaje;
                    }
                    return $html_respuesta . construir_respuesta($siguiente_estado);
                }

                return $html_respuesta;
        }
    } catch (Throwable $e) {
        error_log('[GicaChat][AI Action] Error: ' . $e->getMessage());
        return construir_mensaje_error();
    }
}
