<h2 class="title">Imágenes del Chat</h2>
<table class="form-table">
    <?php
    $imagenes = [
        'cabecera' => 'Imagen de cabecera',
        'bot' => 'Imagen del bot',
        'usuario' => 'Imagen del usuario',
        'banner' => 'Imagen del banner',
        'flotante' => 'Imagen del ícono flotante'
    ];

    foreach ($imagenes as $key => $label) :
        $option_name = "gicachat_imagen_$key";
        $url = esc_url(get_option($option_name));
    ?>
        <tr>
            <th scope="row"><label for="<?php echo $option_name; ?>"><?php echo $label; ?></label></th>
            <td>
                <input type="text" name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" value="<?php echo $url; ?>" class="regular-text" />
                <input type="button" class="button gicachat-subir-imagen" data-target="<?php echo $option_name; ?>" value="Subir Imagen" />
                <?php if ($url): ?>
                    <div class="gicachat-imagen-preview-wrapper">
                        <img src="<?php echo $url; ?>" alt="<?php echo $label; ?>">
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>