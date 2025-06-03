<?php
if (!defined('ABSPATH')) exit;

define('GICACHAT_PLUGIN_URL', plugin_dir_url(__DIR__));

function gicachat_manejar_flujo($mensaje_usuario, &$session)
{
    try {
        $flujo_path = GICACHAT_DIR . 'includes/flujo/flujo.json';
        $flujo = json_decode(file_get_contents($flujo_path), true);

        // Reiniciar flujo si el usuario lo pide
        if (in_array(strtolower($mensaje_usuario), ['reiniciar', 'inicio', 'volver al inicio'])) {
            $session = ['estado_actual' => 'start'];
            return construir_respuesta($flujo['start']);
        }

        // Estado actual
        if (!isset($session['estado_actual'])) {
            $session['estado_actual'] = 'start';
        }
        $estado = $flujo[$session['estado_actual']] ?? $flujo['start'];

        // Manejar handler dinámicamente
        if (!empty($estado['handler'])) {
            $handler = $estado['handler'];
            $funcion_handler = 'gicachat_handler_' . $handler;

            if (function_exists($funcion_handler)) {
                return $funcion_handler($mensaje_usuario, $session, $estado, $flujo);
            }
        }

        // Si requiere input del usuario
        if (!empty($estado['input']) && !empty($estado['save_as'])) {
            if (empty($mensaje_usuario)) {
                // Esperar input: mostrar mensaje del estado actual
                return construir_respuesta($estado);
            } else {
                // Guardar el input del usuario y avanzar
                $session[$estado['save_as']] = $mensaje_usuario;
                $session['estado_actual'] = $estado['next'];
                return gicachat_manejar_flujo('', $session);
            }
        }

        // IA
        if (!empty($estado['ai_action'])) {
            return gicachat_manejar_ai_action($estado['ai_action'], $session, $estado, $flujo);
        }

        // API
        if (!empty($estado['api_action'])) {
            return gicachat_manejar_api_action($estado['api_action'], $session, $estado, $flujo);
        }

        // Condition
        if (!empty($estado['condition'])) {
            return gicachat_manejar_condition($estado['condition'], $session, $estado, $flujo);
        }

        // Evaluar opciones del usuario
        if (!empty($estado['options'])) {
            foreach ($estado['options'] as $opcion) {
                $input_normalizado = normalizar_texto($mensaje_usuario);
                $label_normalizado = normalizar_texto($opcion['label']);

                if ($input_normalizado === $label_normalizado) {
                    if (!empty($opcion['url']) && !empty($opcion['next'])) {
                        $session['estado_actual'] = $opcion['next'];
                        $respuesta_flujo = gicachat_manejar_flujo('', $session);

                        // Inyectar JS que abre la URL en nueva pestaña
                        $link = '<div class="mensaje-texto">' . gicachat_icono_bot_html() . '
                        <div class="mensaje-texto-send"><p>Puedes verlo aquí:</p><a href="' . esc_url($opcion['url']) . '" target="_blank">' . esc_html($opcion['label']) . '</a></div>
                        </div>';
                        return $link . $respuesta_flujo;
                    } elseif (!empty($opcion['url'])) {
                        // Solo URL sin next
                        return '<div class="mensaje-texto">' . gicachat_icono_bot_html() . '
                                <div class="mensaje-texto-send"><p>Puedes verlo aquí:</p><a href="' . esc_url($opcion['url']) . '" target="_blank">' . esc_html($opcion['label']) . '</a></div>
                            </div>';
                    } elseif (!empty($opcion['next'])) {
                        $session['estado_actual'] = $opcion['next'];
                        return gicachat_manejar_flujo('', $session);
                    }
                }
            }
            return construir_respuesta($estado, true); // Repetir mensaje si no se reconoció opción
        }

        // Mensaje simple
        if (!empty($estado['message'])) {
            $session['estado_actual'] = $estado['next'] ?? 'start';

            $clase_extra = ($session['estado_actual'] === 'start' && isset($estado['message']) && str_contains(strtolower($estado['message']), 'gracias')) ? ' fin-conversacion' : '';

            return '<div class="mensaje-texto' . $clase_extra . '">' . gicachat_icono_bot_html() . '
                    <div class="mensaje-texto-send"><p>' . $estado['message'] . '</p></div>
                </div>';
        }

        return "Lo siento, no entendí eso. ¿Podrías repetirlo?";

    } catch (Exception $e) {
        error_log('[GicaChat Error] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}

function construir_respuesta($estado, $reintento = false)
{
    $mensaje = $estado['message'] ?? '';
    $clase_extra = (!empty($estado['es_final'])) ? ' fin-conversacion' : '';
    $html = '<div class="mensaje-texto' . $clase_extra . '">' . gicachat_icono_bot_html() . '
                <div class="mensaje-texto-send"><p>' . $mensaje . '</p></div>
            </div>';

    if (!empty($estado['options'])) {
        $html .= "<div class='mensaje-opciones'>";
        foreach ($estado['options'] as $opcion) {
            $label = htmlspecialchars($opcion['label'], ENT_QUOTES, 'UTF-8');
            $html .= "<button class='boton-opcion' data-comando=\"$label\">$label</button>";
        }
        $html .= "</div>";
    }

    return $html;
}

function construir_mensaje_error($mensaje='Lo siento, ocurrió un problema al procesar tu solicitud. Por favor intenta nuevamente.') {
    return '<div class="mensaje-texto">' . gicachat_icono_bot_html() . '
        <div class="mensaje-texto-send mensaje-error">
            <p>' . esc_html($mensaje) . '</p>
        </div>
    </div>';
}

function obtener_resumen_desde_IA($pregunta)
{
    return IAFactory::obtener_resumen($pregunta);
}

function obtener_respuesta_desde_IA($pregunta)
{
    return IAFactory::obtener_resultado($pregunta);
}

function reformular_respuesta_desde_IA($pregunta)
{
    return IAFactory::reformular_respuesta($pregunta);
}

function normalizar_texto($texto)
{
    $texto = mb_strtolower(trim($texto), 'UTF-8');
    $texto = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'ñ'],
        ['a', 'e', 'i', 'o', 'u', 'n'],
        $texto
    );
    return $texto;
}

function gicachat_icono_bot_html()
{
    $url = esc_url(get_option('gicachat_imagen_bot', GICACHAT_PLUGIN_URL . '../assets/img/bot.webp'));
    return '<div class="icono"><img src="' . $url . '" width="30" alt="Bot"></div>';
}
