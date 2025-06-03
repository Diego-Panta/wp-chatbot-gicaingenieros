<div id="chat-flotante-icono">
    <img class="chat-flotante-icon" src="<?php echo esc_url(get_option('gicachat_imagen_flotante', GICACHAT_URL . 'assets/img/chat-icon.webp')); ?>" alt="Chat">
</div>

<div id="chat-flotante-ventana">
    <div class="chat-header">
        <img class="icon-primary" src="<?php echo esc_url(get_option('gicachat_imagen_cabecera', GICACHAT_URL . 'assets/img/chat-icon.webp')); ?>" alt="Soporte">
        <div class="chat-header-info">
            <div class="title-primary">GicaBot</div>
            <div class="chat-header-buttons">
                <button id="minimizar-chat">—</button>
                <button id="cerrar-chat">✖</button>
            </div>
        </div>
    </div>

    <div class="chat-body">
        <div id="chat-mensajes" class="chat-mensajes">
            <div class="chat-intro">
                <div class="chat-logo"><img class="chat-icon" src="<?php echo esc_url(get_option('gicachat_imagen_banner', GICACHAT_URL . 'assets/img/gica_icon.png')); ?>" alt="Soporte"></div>
                <div class="chat-title">GicaBot</div>
                <div class="chat-subtitle">Soy tu asistente virtual ¿Tienes alguna duda?</div>
            </div>
            <div class="chat-mensajes-send" id="chat-mensajes-send"></div>
        </div>

        <div class="footer">
            <!-- Botón inicial de arranque -->
            <div id="chat-iniciar" class="chat-iniciar" style="text-align:center; margin-top: 20px;">
                <button class="gicachat-boton-iniciar">Iniciar conversación</button>
            </div>
            <div class="chat-input-container" id="chat-input-contenedor" style="display: none;"> <!-- style="display: none;" -->
                <div class="scrool-down">
                    <button class="scrool-down-button"><img src="<?php echo GICACHAT_URL; ?>assets/img/scrool-down.png" alt="Enviar"></button>
                </div>
                <div class="chat-input-container-message">
                    <div class="input-style"></div>
                    <div class="chat-input-container-message-area">
                        <!-- <input type="text" id="chat-input" placeholder="Escribe un mensaje..."> -->
                        <input class="chat-input" type="text" name="" id="chat-input" placeholder="Escribe un mensaje..."></input>
                    </div>
                    <div class="chat-input-container-message-send">
                        <button class="separador"></button>
                        <button id="enviar-chat" class="enviar-chat">
                            <img src="<?php echo GICACHAT_URL; ?>assets/img/send.webp" alt="Enviar">
                        </button>
                    </div>

                </div>
            </div>

            <!-- Modal de confirmación para cerrar (ahora dentro del footer) -->
            <div id="chat-overlay-oscuro" class="chat-overlay-oscuro" style="display: none;"></div>
            <div id="modal-cerrar-chat" class="c-koXsWI" style="display: none;">
                <button id="confirmar-cierre-chat" tabindex="-1" class="c-dzcdPv vfrc-button c-jjMiVY vfrc-button--primary c-bXTvXv c-bXTvXv-XJvOL-type-warn">
                    Sí, cerrar
                </button>
                <button id="cancelar-cierre-chat" tabindex="-1" class="c-dzcdPv vfrc-button c-jjMiVY vfrc-button--primary c-bXTvXv c-bXTvXv-igaDqE-type-subtle">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>