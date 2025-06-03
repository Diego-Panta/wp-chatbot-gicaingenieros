<?php
if (!defined('ABSPATH')) exit;

function gicachat_manejar_api_action($api_action, &$session, $estado, $flujo)
{
    try {
        switch ($estado['api_action']) {
            case 'listar_cursos_virtuales':
                $cursos = obtener_cursos();

                if (empty($cursos)) {
                    $respuesta_html = '<div class="mensaje-texto">
                        ' . gicachat_icono_bot_html() . '
                        <div class="mensaje-texto-send"><p>😔 No se encontraron cursos virtuales en este momento. ¡Vuelve pronto!</p></div>
                    </div>';
                } else {
                    $respuesta_html = '<div class="mensaje-texto">' . gicachat_icono_bot_html() . '
                        <div class="mensaje-texto-send"><p>' . htmlspecialchars($estado['message']) . '</p></div></div>';
                    $respuesta_html .= '<div class="mensaje-texto"><div class="icono"><img src="' . esc_url(get_option('gicachat_imagen_bot', GICACHAT_PLUGIN_URL . '../assets/img/bot.webp')) . '" width="30" alt="Bot"></div>';
                    $respuesta_html .= '<div class="carrusel-contenedor">';
                    $respuesta_html .= '<div class="carrusel-contenedor-tarjetas">';
                    $respuesta_html .= '<div class="carrusel-contenedor-tarjetas-cards">';
                    $respuesta_html .= '<div class="carrusel-curso" id="carruselCursos">';

                    foreach ($cursos as $curso) {
                        $descripcion_corta = mb_substr(strip_tags($curso['descripcion']), 0, 100) . '...';

                        $respuesta_html .= '<div class="card-curso">';
                        $respuesta_html .= '<img src="' . htmlspecialchars($curso['imagen']) . '" alt="' . htmlspecialchars($curso['titulo']) . '">';
                        $respuesta_html .= '<div class="card-content">';
                        $respuesta_html .= '<div class="card-content-title">' . $curso['titulo'] . '</div>';
                        $respuesta_html .= '<div class="card-content-subtitle"><p>' . $descripcion_corta . '</p></div>';
                        $respuesta_html .= '<div class="card-content-precio"><p class="precio">' . $curso['precio'] . '</p></div>';
                        $respuesta_html .= '</div>'; // card-content
                        $respuesta_html .= '<div class="card-button">';
                        $respuesta_html .= '<a href="' . $curso['enlace'] . '" target="_blank" class="cta">Ver Curso</a>';
                        $respuesta_html .= '</div>'; // card-button
                        $respuesta_html .= '</div>'; // card-curso
                    }

                    $respuesta_html .= '</div>'; // /carrusel-curso
                    $respuesta_html .= '</div>'; // /carrusel-contenedor-tarjetas-cards
                    $respuesta_html .= '<button class="carrusel-flecha derecha" onclick="moverCarrusel()">→</button>';
                    $respuesta_html .= '<button class="carrusel-flecha izquierda" onclick="moverCarrusel(-1)" style="display: none;">←</button>';
                    $respuesta_html .= '</div>'; // /carrusel-contenedor-tarjetas
                    $respuesta_html .= '</div>'; // /carrusel-contenedor
                    $respuesta_html .= '</div>'; // /mensaje-texto                    
                }

                $session['estado_actual'] = $estado['next'];
                return $respuesta_html . gicachat_manejar_flujo('', $session);
        }
    } catch (Throwable $e) {
        error_log('[GicaChat][API Action] Error: ' . $e->getMessage());
        return construir_mensaje_error();
    }
}
