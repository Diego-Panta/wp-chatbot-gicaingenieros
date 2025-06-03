<?php
if (!defined('ABSPATH')) exit;

function obtener_respuesta_voiceflow($input, $user_id = null)
{
    try {
        $api_key = get_option('gicachat_voiceflow_api_key');
        $version_id = get_option('gicachat_voiceflow_version_id');
        $user_id = $user_id ?: 'gicabot_beta';

        // Detectar si es un request completo o un mensaje simple
        if (is_array($input)) {
            $request = $input;
            $mensaje = null;
        } else {
            $mensaje = $input;
            $request = null;
        }

        $respuesta = hacer_llamada_voiceflow($mensaje, $request, $api_key, $version_id, $user_id);

        // Si no hay respuesta válida, intenta una segunda vez automáticamente
        if (!$respuesta || $respuesta === "El bot no respondió.") {
            error_log("GicaChat - Reintentando la llamada a Voiceflow...");
            $respuesta = hacer_llamada_voiceflow($mensaje, $request, $api_key, $version_id, $user_id);
        }

        return $respuesta;
    } catch (Throwable $e) {
        error_log("GicaChat - Error en obtener_respuesta_voiceflow: " . $e->getMessage());
        return "Hubo un error al procesar tu mensaje con Voiceflow.";
    }
}

function hacer_llamada_voiceflow($mensaje = null, $request = null, $api_key, $version_id, $user_id)
{
    try {
        $ch = curl_init("https://general-runtime.voiceflow.com/state/$version_id/user/$user_id/interact");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: $api_key",
            "Content-Type: application/json"
        ));
        $payload = $request ?: [
            "type" => "text",
            "payload" => $mensaje
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "request" => $payload
        ]));

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) {
            error_log("GicaChat - Voiceflow vacío");
            return "Lo siento, hubo un error al conectarme con Voiceflow.";
        }

        $data = json_decode($response, true);
        if (!$data) {
            error_log("GicaChat - Error al decodificar JSON de Voiceflow:\n$response");
            return "No entendí la respuesta del bot.";
        }

        error_log("GicaChat - Respuesta de Voiceflow:\n" . print_r($data, true));

        $respuestas = [];

        foreach ($data as $bloque) {
            if (!isset($bloque['type'])) continue;

            if ($bloque['type'] === 'text') {
                $payload = $bloque['payload'];
                if (is_array($payload) && isset($payload['message'])) {
                    $respuestas[] = '<div class="mensaje-texto"><p>' . esc_html($payload['message']) . '</p></div>';
                } elseif (is_string($payload)) {
                    $respuestas[] = '<div class="mensaje-texto"><p>' . esc_html($payload) . '</p></div>';
                }
            } elseif ($bloque['type'] === 'carousel' && isset($bloque['payload']['cards'])) {
                $carouselHTML = '<div class="mensaje-texto"><div class="icono"><img src="' . esc_url(get_option('gicachat_imagen_bot', GICACHAT_PLUGIN_URL . '../assets/img/bot.webp')) . '" width="30" alt="Bot"></div>';
                $carouselHTML .= "<div class='carrusel-contenedor'>";
                $carouselHTML .= "<div class='carrusel-contenedor-tarjetas'>";
                $carouselHTML .= "<div class='carrusel-contenedor-tarjetas-cards'>";
                $carouselHTML .= "<div class='carrusel-curso' id='carruselCursos'>";

                foreach ($bloque['payload']['cards'] as $card) {
                    $image = esc_url($card['imageUrl']);
                    $title = esc_html($card['title']);
                    $description = esc_html($card['description']['text'] ?? '');
                    $buttonText = esc_html($card['buttons'][0]['name'] ?? 'Ver Curso');
                    $buttonUrl = esc_url($card['buttons'][0]['request']['payload'][0]['payload']['url'] ?? '#');

                    $carouselHTML .= "<div class='card-curso'>";
                    $carouselHTML .= "<img src='$image' alt='$title'>";
                    $carouselHTML .= "<div class='card-content'>";
                    $carouselHTML .= "<div class='card-content-title'>$title</div>";
                    $carouselHTML .= "<div class='card-content-subtitle'><p>$description</p></div>";
                    $carouselHTML .= "<div class='card-content-precio'><p class='precio'></p></div>"; // puedes agregar precio si hay
                    $carouselHTML .= "</div>"; // card-content
                    $request_json = esc_attr(json_encode($card['buttons'][0]['request'] ?? []));
                    $carouselHTML .= "<div class='card-button'><button class='cta boton-opcion' data-request='{$request_json}'>{$buttonText}</button></div>";
                    $carouselHTML .= "</div>"; // card-curso
                }

                $carouselHTML .= "</div>"; // /carrusel-curso
                $carouselHTML .= "</div>"; // /carrusel-contenedor-tarjetas-cards
                $carouselHTML .= "<button class='carrusel-flecha derecha' onclick='moverCarrusel()'>→</button>";
                $carouselHTML .= "<button class='carrusel-flecha izquierda' onclick='moverCarrusel(-1)' style='display: none;'>←</button>";
                $carouselHTML .= "</div>"; // /carrusel-contenedor-tarjetas
                $carouselHTML .= "</div>"; // /carrusel-contenedor
                $carouselHTML .= "</div>"; // /mensaje-texto

                $respuestas[] = $carouselHTML;
            } elseif ($bloque['type'] === 'choice' && isset($bloque['payload']['buttons'])) {
                // ⬅️ AQUI VIENE LA MAGIA EXTRA:
                if (isset($bloque['payload']['message']) && !empty($bloque['payload']['message'])) {
                    $respuestas[] = '<div class="mensaje-texto"><p>' . esc_html($bloque['payload']['message']) . '</p></div>';
                }
                $botonesHTML = '';
                foreach ($bloque['payload']['buttons'] as $boton) {
                    $texto = esc_html($boton['name']);
                    $request_json = esc_attr(json_encode($boton['request']));
                    $botonesHTML .= "<button class='boton-opcion' data-request='{$request_json}'>{$texto}</button> ";
                }
                $respuestas[] = "<div class='mensaje-opciones'>{$botonesHTML}</div>";
            }

            if ($bloque['type'] === 'end') {
                $respuestas[] = '<div class="fin-conversacion mensaje-texto"><p>Gracias por usar GicaBot. ¡Hasta pronto!</p></div>';
            }
        }
        error_log("GicaChat - HTML completo para mostrar:\n" . implode("<br>", $respuestas));
        return !empty($respuestas) ? implode($respuestas) : "El bot no respondió.";
    } catch (Throwable $e) {
        error_log("GicaChat - Error en hacer_llamada_voiceflow: " . $e->getMessage());
        return "No pude obtener respuesta de Voiceflow debido a un error.";
    }
}

function reiniciar_sesion_voiceflow($user_id = null)
{
    try {
        $version_id = get_option('gicachat_voiceflow_version_id');
        $api_key = get_option('gicachat_voiceflow_api_key');
        $user_id = $user_id ?: 'gicabot_beta';

        $ch = curl_init("https://general-runtime.voiceflow.com/state/$version_id/user/$user_id");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // <-- IMPORTANTE: Esto borra el estado del usuario
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: $api_key"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    } catch (Throwable $e) {
        error_log("GicaChat - Error al reiniciar sesión de Voiceflow: " . $e->getMessage());
        return false;
    }
}