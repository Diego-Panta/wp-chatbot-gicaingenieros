<?php
/**
 * Plugin Name: GicaChat - Chat Flotante
 * Plugin URI:  https://gicaingenieros.com/website
 * Description: Agrega un icono flotante con un chat emergente en WordPress.
 * Version:     1.2
 * Author:      Diego Panta
 * Author URI:  https://github.com/Diego-Panta/wp-chatbot-gicaingenieros
 */

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
}

// Definir constantes
define('GICACHAT_DIR', plugin_dir_path(__FILE__));
define('GICACHAT_URL', plugin_dir_url(__FILE__));

// Incluir archivos necesarios
require_once GICACHAT_DIR . 'admin/admin-settings.php';
require_once GICACHAT_DIR . 'functions.php';
require_once GICACHAT_DIR . 'includes/core/loader.php';

// Registrar los archivos JS y CSS
function gicachat_enqueue_assets()
{
    // Estilos CSS
    wp_enqueue_style('gicachat-main-css', GICACHAT_URL . 'assets/css/gicachat-main.css');
    wp_enqueue_style('gicachat-mensajes-css', GICACHAT_URL . 'assets/css/gicachat-mensajes.css');
    wp_enqueue_style('gicachat-botones-css', GICACHAT_URL . 'assets/css/gicachat-botones.css');
    wp_enqueue_style('gicachat-carrusel-css', GICACHAT_URL . 'assets/css/gicachat-carrusel.css');
    wp_enqueue_style('gicachat-modal-css', GICACHAT_URL . 'assets/css/gicachat-modal.css');
    wp_enqueue_style('gicachat-colors-css', GICACHAT_URL . 'assets/css/gicachat-colors.css');
    wp_enqueue_style('gicachat-responsive-css', GICACHAT_URL . 'assets/css/gicachat-responsive.css');

    // JS compartido (se carga siempre)
    wp_enqueue_script('helpers-js', GICACHAT_URL . 'assets/js/shared/helpers.js', array('jquery'), null, true);

    // JS principal que decide qué modo cargar
    wp_enqueue_script('gicachat-js', GICACHAT_URL . 'assets/js/core/gicachat.js', array('jquery'), null, true);
    wp_enqueue_script('events-js', GICACHAT_URL . 'assets/js/shared/events.js', array('jquery'), null, true);

    // Localizar script con el modo actual (ajustalo según cómo determines el modo)
    wp_localize_script('gicachat-js', 'chat_ajax', array(
        'ajax_url'   => admin_url('admin-ajax.php'),
        'plugin_url' => GICACHAT_URL,
        'imagen_usuario' => get_option('gicachat_imagen_usuario', GICACHAT_URL . 'assets/img/user.webp'),
        'imagen_bot' => get_option('gicachat_imagen_bot', GICACHAT_URL . 'assets/img/bot.webp'),
        'modo'       => get_option('gicachat_modo', 'default')
    ));
}
add_action('wp_enqueue_scripts', 'gicachat_enqueue_assets');

add_action('init', function () {
    if (!session_id()) {
        session_start();
        error_log('[GicaChat] Sesión iniciada correctamente');
    }

    // Inicializar la estructura de sesión si no existe
    if (!isset($_SESSION['gicachat'])) {
        $_SESSION['gicachat'] = [
            'estado_actual' => 'inicio',
            'historial' => []
        ];
        error_log('[GicaChat] Sesión gicachat inicializada');
    }
});

// Incluir el HTML del chat
function gicachat_include_chat()
{
    include GICACHAT_DIR . 'templates/index.php';
}
add_action('wp_footer', 'gicachat_include_chat');

function gicachat_inject_color_vars() {
    echo '<style>:root {';
    echo '--gicachat-color-flotante: ' . get_option('gicachat_var_flotante', '#ea7f0b') . ';';
    echo '--gicachat-color-flotante-hover: ' . get_option('gicachat_var_flotante_hover', '#d16d09') . ';';
    echo '--gicachat-color-header: ' . get_option('gicachat_var_header', '#ea7f0b') . ';';
    echo '--gicachat-color-header-hover: ' . get_option('gicachat_var_header_hover', '#ffdddd') . ';';
    echo '--gicachat-color-fondo-chat: ' . get_option('gicachat_var_fondo', '#ffffff') . ';';
    echo '--gicachat-color-usuario-msg-bg: ' . get_option('gicachat_var_usuario_msg', '#ea7f0b') . ';';
    echo '--gicachat-color-bot-msg-bg: ' . get_option('gicachat_var_bot_msg', '#f1f2f2') . ';';
    echo '--gicachat-color-boton-opcion-bg: ' . get_option('gicachat_var_botones_opciones', '#fef3e7') . ';';
    echo '--gicachat-color-enviar-chat-bg: ' . get_option('gicachat_var_botones_envio', '#f4840b') . ';';
    echo '--gicachat-color-boton-iniciar-bg: ' . get_option('gicachat_var_botones_inicio', '#f4840b') . ';';
    echo '}</style>';
}
add_action('wp_footer', 'gicachat_inject_color_vars');