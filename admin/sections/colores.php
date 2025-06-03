<div class="gicachat-color-grid">
    <?php
    $colores = [
        'var_flotante' => 'Color del chat flotante',
        'var_flotante_hover' => 'Color flotante (hover)',
        'var_header' => 'Color del encabezado',
        'var_header_hover' => 'Texto hover encabezado',
        'var_fondo' => 'Fondo del chat',
        'var_usuario_msg' => 'Mensaje del usuario',
        'var_bot_msg' => 'Mensaje del bot',
        'var_botones_opciones' => 'Botón de opciones',
        'var_botones_envio' => 'Botón de envío',
        'var_botones_inicio' => 'Botón iniciar conversación',
    ];

    foreach ($colores as $key => $label) :
        $option_name = "gicachat_$key";
        $value = esc_attr(get_option($option_name, '#ffffff'));
    ?>
        <div class="gicachat-color-card">
            <label for="<?php echo $option_name; ?>"><?php echo $label; ?></label>
            <div style="display: flex; gap: 8px; align-items: center;">
                <input type="color" name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" value="<?php echo $value; ?>" oninput="document.getElementById('<?php echo $option_name; ?>_text').value = this.value">
                <input type="text" id="<?php echo $option_name; ?>_text" value="<?php echo $value; ?>" oninput="document.getElementById('<?php echo $option_name; ?>').value = this.value" maxlength="7" style="width:80px;">
            </div>
        </div>
    <?php endforeach; ?>
</div>