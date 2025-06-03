jQuery(document).ready(function ($) {

    window.gicachat_getCookie = function (name) {
        let value = "; " + document.cookie;
        let parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    };

    window.gicachat_guardarHistorial = function () {
        const historialHTML = $("#chat-mensajes-send").html();
        localStorage.setItem("gicachat_historial", historialHTML);
    };

    window.gicachat_cargarHistorial = function () {
        const historial = localStorage.getItem('gicachat_historial');
        if (historial) {
            $('#chat-mensajes-send').html(historial);
            if (typeof scrollAbajo === "function") scrollChatAbajo();
        }
    };

    window.gicachat_finalizarConversacion = function () {
        /*localStorage.setItem('gicachat_conversacion_finalizada', '1');
        $("#chat-input-contenedor").hide();
        $("#chat-iniciar").fadeIn();*/
        document.cookie = "gicachat_estado=cerrado; path=/";
        localStorage.removeItem("gicachat_historial");
        $("#chat-input-contenedor").hide();
        $("#chat-iniciar").show();
    };

    window.gicachat_reiniciarConversacion = function () {
        localStorage.removeItem('gicachat_historial');
        localStorage.removeItem('gicachat_conversacion_finalizada');
        $("#chat-mensajes-send").children().not(".chat-intro").remove();
    };

    window.gicachat_mensaje_usuario = function (mensaje) {

        if (mensaje.length > 500) {
            alert("Mensaje demasiado largo");
            return;
        }

        $("#chat-mensajes-send").append(`
            <div class='mensaje-usuario'>
                <div class='mensaje-texto'><div class='mensaje-texto-send'>${escaparHTML(mensaje)}</div></div>
                <div class='icono'><img src='${chat_ajax.imagen_usuario}' width='30' alt='Usuario'></div>
            </div>
            `);
        $("#chat-input").val("");
        scrollChatAbajo();
        gicachat_guardarHistorial();
    };

    window.mostrarEscribiendo = function () {
        const chatMensajes = document.getElementById('chat-mensajes-send');

        const escribiendo = document.createElement('div');
        escribiendo.id = 'gicabot-escribiendo';
        escribiendo.className = 'mensaje-bot';

        escribiendo.innerHTML = `
            <div class="contenido-mensaje">
                <div class="mensaje-texto">
                    <div class="icono">
                        <img src="${chat_ajax.imagen_bot}" width="30" alt="Bot">
                    </div>
                    <div class="mensaje-texto-send">
                        <p>Escribiendo...</p>
                    </div>
                </div>
            </div>
        `;

        chatMensajes.appendChild(escribiendo);
    };

    window.scrollChatAbajo = function (intentos = 10) {
        const $chatMensajes = $("#chat-mensajes");

        if ($chatMensajes.length && $chatMensajes[0].scrollHeight > 0) {
            $chatMensajes.animate({ scrollTop: $chatMensajes[0].scrollHeight }, 10);
        }

        if (intentos > 0) {
            setTimeout(() => scrollChatAbajo(intentos - 1), 10);
        }
    };

    // Función para eliminar el mensaje "Escribiendo..."
    window.eliminarEscribiendo = function () {
        const escribiendo = document.getElementById('gicabot-escribiendo');
        if (escribiendo) escribiendo.remove();
    };

    window.gicachat_control_envio = (function () {
        let ultimoEnvio = 0;

        return function () {
            const ahora = Date.now();
            if (ahora - ultimoEnvio < 1000) {
                return false; // No permitir enviar aún
            }
            ultimoEnvio = ahora;
            return true; // Permitir envío
        };
    })();

    $(".scrool-down-button").click(function () {
        const $chatMensajes = $("#chat-mensajes");
        $chatMensajes.animate({
            scrollTop: $chatMensajes[0].scrollHeight
        }, 300);
    });

    const $chatMensajes = $("#chat-mensajes");
    const $scroolBtn = $(".scrool-down-button");

    $chatMensajes.on("scroll", function () {
        const scrollTop = $chatMensajes.scrollTop();
        const scrollHeight = $chatMensajes[0].scrollHeight;
        const offsetHeight = $chatMensajes.outerHeight();

        if (scrollHeight - (scrollTop + offsetHeight) > 50) {
            $scroolBtn.fadeIn().css("pointer-events", "auto");
        } else {
            $scroolBtn.fadeOut().css("pointer-events", "none");
        }
    });

    $("#chat-flotante-icono").click(function () {
        $("#chat-flotante-icono").fadeOut();
        $("#chat-flotante-ventana").addClass("abierto");

        const estado = gicachat_getCookie("gicachat_estado");
        const finalizada = localStorage.getItem("gicachat_conversacion_finalizada");
        const historial = localStorage.getItem("gicachat_historial");

        if ((!estado || estado === "cerrado" || finalizada === "1") && (!historial || historial.trim() === '')) {
            gicachat_finalizarConversacion();
            $("#chat-cerrar").hide();
            $("#chat-input-contenedor").hide();
            $("#chat-iniciar").show();
        } else {
            gicachat_cargarHistorial();
            $("#chat-iniciar").hide();
            $("#chat-cerrar").show();
            $("#chat-input-contenedor").show();
        }
    });

    $("#minimizar-chat").click(function () {
        $("#chat-flotante-ventana").removeClass("abierto");
        $("#chat-flotante-icono").fadeIn();
    });

    $("#cerrar-chat").click(function () {
        $("#modal-cerrar-chat").fadeIn();
        $("#chat-overlay-oscuro").fadeIn();
    });

    $("#cancelar-cierre-chat").click(function () {
        $("#modal-cerrar-chat").fadeOut();
        $("#chat-overlay-oscuro").fadeOut();
    });

    function escaparHTML(texto) {
        return texto.replace(/[&<>"']/g, function (match) {
            const escape = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            };
            return escape[match];
        });
    }

});