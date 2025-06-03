jQuery(document).ready(function ($) {

    if (!localStorage.getItem('gicachat_user_id')) {
        const uuid = 'user_' + Date.now() + '_' + Math.random().toString(16).substring(2);
        localStorage.setItem('gicachat_user_id', uuid);
    }

    // Confirmar cierre del chat
    $("#confirmar-cierre-chat").click(function () {
        $("#modal-cerrar-chat").fadeOut();
        $("#chat-overlay-oscuro").fadeOut();
        $("#chat-flotante-ventana").removeClass("abierto");
        $.post(chat_ajax.ajax_url, {
            action: "gicachat_reiniciar_chat",
            modo: "voiceflow",
            user_id: localStorage.getItem('gicachat_user_id')
        }, function (response) {
            console.log("Voiceflow reiniciado:", response);
        });
        $("#chat-flotante-icono").fadeIn();
        document.cookie = "gicachat_estado=cerrado; path=/";
        $("#chat-mensajes-send").children().not(".chat-intro").remove();
        localStorage.removeItem("gicachat_historial");

        $("#chat-input-contenedor").hide();
        $("#chat-iniciar").show();
        $("#chat-cerrar").hide();

    });

    // Iniciar conversación
    $(document).on('click', '.gicachat-boton-iniciar', function () {
        // Reiniciar correctamente
        $("#chat-mensajes-send").children().not(".chat-intro").remove();
        gicachat_reiniciarConversacion();
        localStorage.removeItem("gicachat_conversacion_finalizada");

        $("#chat-iniciar").hide();
        $("#chat-input-contenedor").show();
        mostrarEscribiendo();

        $.post(chat_ajax.ajax_url, {
            action: "gicachat_iniciar_chat",
            modo: "voiceflow",
            user_id: localStorage.getItem('gicachat_user_id')
        }, function (response) {
            eliminarEscribiendo();
            if (response.success && response.data.respuesta) {
                renderMensajeBotVoiceflow(response.data.respuesta);
            }
        });
    });

    // Función para enviar mensaje
    function enviarMensaje() {

        if (!gicachat_control_envio()) return;

        let mensaje = $("#chat-input").val().trim();
        
        if (mensaje === "") return;

        gicachat_mensaje_usuario(mensaje);
        // Esta es la parte buena:
        enviarMensajeAJAX(mensaje);
    }

    function renderMensajeBotVoiceflow(respuesta) {
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = respuesta;

        let bloquesHTML = '';
        let mensajeBotHTML = "<div class='contenido-mensaje'><div class='mensaje-bot'>";

        for (let i = 0; i < tempDiv.children.length; i++) {
            const el = tempDiv.children[i];
            const clase = el.className;

            if (clase.includes('mensaje-texto')) {
                // Solo si no contiene ya la imagen de bot
                if (!el.querySelector('.icono')) {
                    // Añadir el icono si no existe
                    const icono = `<div class='icono'><img src="${chat_ajax.imagen_bot}" width="30" alt="Bot"></div>`;
                    const contenido = `<div class='mensaje-texto-send'>${el.innerHTML}</div>`;
                    bloquesHTML += `<div class='mensaje-texto'>${icono}${contenido}</div>`;
                } else {
                    // Ya contiene el icono, lo añadimos directo
                    bloquesHTML += el.outerHTML;
                }
            } else if (clase.includes('carrusel-contenedor')) {
                bloquesHTML += el.outerHTML;
            } else if (clase.includes('mensaje-opciones')) {
                bloquesHTML += el.outerHTML;
            } else {
                // Por si acaso llega algo inesperado pero válido
                bloquesHTML += el.outerHTML;
            }
        }

        if ($(tempDiv).find(".fin-conversacion").length > 0) {
            gicachat_finalizarConversacion();
        }
        mensajeBotHTML += bloquesHTML + "</div></div>";
        $("#chat-mensajes-send").append(mensajeBotHTML);
        scrollChatAbajo();
        gicachat_guardarHistorial();
    }

    // Enviar mensaje al hacer clic en el botón
    $("#enviar-chat").click(function () {
        enviarMensaje();
    });

    // Enviar mensaje al presionar Enter
    $("#chat-input").keypress(function (e) {
        if (e.which === 13) { // Código de tecla Enter
            enviarMensaje();
        }
    });

    // Capturar el clic en los botones de opción generados por el mensaje de bienvenida
    $(document).on("click", ".boton-opcion", function () {
        const requestData = $(this).data("request");
        $(this).closest('.mensaje-opciones').remove();
        if (requestData) {
            // Mostrar el texto como mensaje del usuario
            const textoUsuario = $(this).text();
            gicachat_mensaje_usuario(textoUsuario);
            // Ver si hay una URL de redirección
            let redireccionURL = null;

            if (
                requestData.payload &&
                Array.isArray(requestData.payload.actions)
            ) {
                const actionOpenUrl = requestData.payload.actions.find(
                    action => action.type === "open_url"
                );

                if (actionOpenUrl && actionOpenUrl.payload?.url) {
                    redireccionURL = actionOpenUrl.payload.url;
                }
            }
            // Enviar el request a Voiceflow sí o sí
            enviarMensajeAJAX(null, requestData);
            // Si hay URL, redirigir después de un delay (ej: 2 segundos)
            if (redireccionURL) {
                setTimeout(() => {
                    window.open(redireccionURL, "_blank");
                }, 2000);
            }
        }
    });

    function enviarMensajeAJAX(texto = null, request = null) {
        const data = new FormData();
        data.append("action", "gicachat_enviar_mensaje");
        data.append("modo", "voiceflow");
        data.append("user_id", localStorage.getItem('gicachat_user_id'));

        if (request) {
            data.append("request", JSON.stringify(request));
        } else if (texto) {
            data.append("mensaje", texto);
        }
        mostrarEscribiendo();
        fetch(chat_ajax.ajax_url, {
            method: "POST",
            body: data
        })
            .then(response => response.json())
            .then(res => {
                eliminarEscribiendo();
                if (res.success) {
                    eliminarEscribiendo();
                    renderMensajeBotVoiceflow(res.data);
                } else {
                    console.error("Error:", res);
                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
            });
    }
});