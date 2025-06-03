<?php
if (!defined('ABSPATH')) exit;

function gicachat_save_settings()
{
    $options = [
        'gicachat_modo', 'gicachat_ia_default',
        'gicachat_api_openai', 'gicachat_openai_model', 'gicachat_openai_temperature',
        'gicachat_api_gemini', 'gicachat_gemini_model', 'gicachat_gemini_temperature',
        'gicachat_api_deepseek', 'gicachat_deepseek_model', 'gicachat_deepseek_temperature',
        'gicachat_voiceflow_api_key', 'gicachat_voiceflow_version_id',
        'gicachat_fuente_cursos',
        'gicachat_imagen_cabecera', 'gicachat_imagen_banner',
        'gicachat_imagen_flotante', 'gicachat_imagen_bot', 'gicachat_imagen_usuario',
        'gicachat_var_flotante', 'gicachat_var_flotante_hover', 'gicachat_var_header', 'gicachat_var_header_hover',
        'gicachat_var_fondo', 'gicachat_var_usuario_msg', 'gicachat_var_bot_msg',
        'gicachat_var_botones_opciones', 'gicachat_var_botones_envio', 'gicachat_var_botones_inicio'
    ];

    foreach ($options as $opt) {
        $value = isset($_POST[$opt]) ? sanitize_text_field($_POST[$opt]) : '';
        update_option($opt, $value);
    }

    echo '<div class="updated"><p>Configuración guardada correctamente.</p></div>';
}
?>