<?php
if (!defined('ABSPATH')) exit;

function gicachat_handler_recomendar_curso($mensaje_usuario, &$session, $estado, $flujo)
{
    return gicachat_handler_generico(
        $mensaje_usuario,
        $session,
        $estado,
        $flujo,
        'recomendacion',
        'obtener_respuesta_restringida_desde_IA',
        'recomendar_curso'
    );
}

function gicachat_handler_pregunta_curso($mensaje_usuario, &$session, $estado, $flujo)
{
    return gicachat_handler_generico(
        $mensaje_usuario,
        $session,
        $estado,
        $flujo,
        'pregunta_curso',
        'obtener_respuesta_sobre_cursos_desde_IA',
        'pregunta_curso'
    );
}

function gicachat_handler_resolver_problema($mensaje_usuario, &$session, $estado, $flujo)
{
    return gicachat_handler_generico(
        $mensaje_usuario,
        $session,
        $estado,
        $flujo,
        'resolver_problema',
        'restringir_respuesta_desde_IA',
        'resolver_problema'
    );
}

function gicachat_handler_generico($mensaje_usuario, &$session, $estado, $flujo, $tipo_prompt, $funcion_respuesta, $tag_error = 'generico')
{
    if (empty($mensaje_usuario)) {
        return construir_respuesta($estado);
    }

    try {
        $contexto = gicachat_construir_contexto_desde_historial($session, $mensaje_usuario);

        // Llamar a la función correcta para generar el prompt
        $prompt_func = 'gicachat_prompt_' . $tipo_prompt;
        if (!function_exists($prompt_func)) {
            throw new Exception("No existe la función $prompt_func");
        }
        $prompt = $prompt_func($contexto);

        // Obtener la respuesta desde la función indicada
        if (!function_exists($funcion_respuesta)) {
            throw new Exception("No existe la función $funcion_respuesta");
        }
        $respuesta = $funcion_respuesta($prompt);

        // Construir respuesta HTML
        $html_respuesta = '<div class="mensaje-texto">' . gicachat_icono_bot_html() . '
            <div class="mensaje-texto-send"><p>' . esc_html($respuesta) . '</p></div>
        </div>';

        // Clasificación del mensaje
        $prompt_clasificar = gicachat_prompt_clasificacion($mensaje_usuario);
        $clasificacion = strtolower(trim(obtener_respuesta_desde_IA($prompt_clasificar)));

        if ($clasificacion === 'terminar') {
            $session['estado_actual'] = 'flujo_final_pregunta';
            return gicachat_manejar_flujo('', $session);
        } else {
            gicachat_guardar_en_historial($session, $mensaje_usuario, $respuesta);
            return $html_respuesta;
        }
    } catch (Exception $e) {
        error_log("[GicaChat ERROR - $tag_error] " . $e->getMessage());
        return construir_mensaje_error();
    }
}

/*function filtrar_repeticion_historial($respuesta, $contexto) {
    // Si la respuesta contiene una línea completa del contexto, elimínala
    $lineas_contexto = array_filter(array_map('trim', explode("\n", $contexto)));
    foreach ($lineas_contexto as $linea) {
        if (stripos($respuesta, $linea) !== false) {
            $respuesta = str_ireplace($linea, '', $respuesta);
        }
    }
    return trim($respuesta);
}*/

