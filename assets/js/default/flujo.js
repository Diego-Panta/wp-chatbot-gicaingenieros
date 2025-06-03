jQuery(document).ready(function ($) {

    $("#confirmar-cierre-chat").click(function () {
        $("#modal-cerrar-chat").fadeOut();
        $("#chat-overlay-oscuro").fadeOut(); // <-- Oculta overlay
        $("#chat-flotante-ventana").removeClass("abierto");
        $("#chat-flotante-icono").fadeIn();

        document.cookie = "gicachat_estado=cerrado; path=/";
        $("#chat-mensajes-send").children().not(".chat-intro").remove();
        localStorage.removeItem("gicachat_historial");
        $("#chat-input-contenedor").hide();
        $("#chat-iniciar").show();
        $("#chat-cerrar").hide();

        // Llamada AJAX para limpiar historial del servidor
        $.post(chat_ajax.ajax_url, {
            action: "gicachat_reset_chat"
        }, function (response) {
            if (response.success) {
                console.log("Historial del servidor eliminado correctamente");
            } else {
                console.warn("Error al eliminar historial del servidor");
            }
        });
    });

    // Iniciar conversación
    $(document).on('click', '.gicachat-boton-iniciar', function () {
        // Limpiar todo
        $("#chat-mensajes-send").children().not(".chat-intro").remove();
        gicachat_reiniciarConversacion();

        $("#chat-iniciar").hide();
        $("#chat-input-contenedor").show();
        mostrarEscribiendo();

        $.post(chat_ajax.ajax_url, {
            action: "gicachat_ai_response",
            mensaje: "__init__"
        }, function (response) {
            eliminarEscribiendo();
            if (response.success) {
                const respuesta = response.data.respuesta;
                const mensajeBot = construirMensajeBot(respuesta);
                $('#chat-mensajes-send').append(mensajeBot);
                scrollChatAbajo();
                gicachat_guardarHistorial();
            }
        });
    });

    function enviarMensaje() {

        if (!gicachat_control_envio()) return;

        const mensaje = $("#chat-input").val().trim();

        if (mensaje === "") return;

        gicachat_mensaje_usuario(mensaje);
        mostrarEscribiendo();

        $.post(chat_ajax.ajax_url, {
            action: "gicachat_ai_response",
            mensaje: mensaje
        }, function (response) {
            eliminarEscribiendo();
            if (response.success) {
                const respuesta = response.data.respuesta;
                const mensajeBot = construirMensajeBot(respuesta);
                $('#chat-mensajes-send').append(mensajeBot);

                // Si contiene clase fin-conversacion, mostrar botón de reinicio
                if ($(mensajeBot).find('.fin-conversacion').length > 0) {
                    gicachat_finalizarConversacion();
                }
                scrollChatAbajo();
                gicachat_guardarHistorial();
            } else {
                const errorHTML = construirMensajeBot("<p>Error al obtener respuesta.</p>");
                $("#chat-mensajes-send").append(errorHTML);
                scrollChatAbajo();
                gicachat_guardarHistorial();
            }
        });
    }

    // Separa texto de opciones para renderizar correctamente
    function construirMensajeBot(htmlString) {
        const contenedor = $('<div class="mensaje-bot"></div>');
        // Contenido del mensaje (texto + opciones)
        const contenido = $('<div class="contenido-mensaje"></div>').append($(htmlString));
        contenedor.append(contenido);

        return contenedor;
    }

    $("#enviar-chat").click(function () {
        enviarMensaje();
    });

    $("#chat-input").keypress(function (e) {
        if (e.which === 13) {
            enviarMensaje();
        }
    });

    $(document).on('click', '.boton-opcion', function () {
        const comando = $(this).data('comando');

        $(this).closest('.mensaje-opciones').remove();

        $("#chat-input").val(comando);
        $("#enviar-chat").click();
    });

});