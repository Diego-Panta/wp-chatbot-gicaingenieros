<?php
if (!defined('ABSPATH')) exit;

// Añadir menú al panel de administración
function gicachat_add_admin_menu()
{
    add_menu_page(
        'GicaChat Configuración',
        'GicaChat',
        'manage_options',
        'gicachat-settings',
        'gicachat_settings_page',
        'dashicons-format-chat',
        100
    );
}
add_action('admin_menu', 'gicachat_add_admin_menu');

// Página principal de configuración
function gicachat_settings_page()
{
    // Guardar configuración
    if (isset($_POST['gicachat_save'])) {
        require_once GICACHAT_DIR . 'admin/save-settings.php';
        gicachat_save_settings(); //
    }

?>
    <div class="wrap">
        <h1>Configuración de GicaChat</h1>
        <form method="post">
            <?php require_once GICACHAT_DIR . 'admin/admin-settings-ui.php'; ?>
            <div id="gurdar_config" class="gurdar_config" style="margin-top:10px;">
                <p><input type="submit" name="gicachat_save" class="button-primary" value="Guardar Configuración"></p>
            </div>
        </form>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectModo = document.getElementById('gicachat_modo');
        const modoDefault = document.getElementById('modo-default');
        const modoVoiceflow = document.getElementById('modo-voiceflow');
        const radiosIA = document.querySelectorAll('input[name="gicachat_ia_default"]');

        selectModo.addEventListener('change', function() {
            modoDefault.style.display = this.value === 'default' ? 'block' : 'none';
            modoVoiceflow.style.display = this.value === 'voiceflow' ? 'block' : 'none';
        });

        radiosIA.forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.ia-config').forEach(div => div.style.display = 'none');
                const selected = this.value;
                document.getElementById('config-' + selected).style.display = 'block';
            });
        });
    });
</script>
<script>
jQuery(document).ready(function($) {
    $('.gicachat-subir-imagen').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var targetId = button.data('target');

        var custom_uploader = wp.media({
            title: 'Selecciona una imagen',
            button: {
                text: 'Usar esta imagen'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            var imageUrl = attachment.url;

            // Actualiza el campo de texto
            $('#' + targetId).val(imageUrl);

            // Busca la celda del botón clicado
            var td = button.closest('td');

            // Busca o crea el contenedor de vista previa
            var previewWrapper = td.find('.gicachat-imagen-preview-wrapper');
            if (previewWrapper.length === 0) {
                previewWrapper = $('<div class="gicachat-imagen-preview-wrapper"></div>').appendTo(td);
            }

            // Inserta o actualiza la imagen de vista previa
            var img = previewWrapper.find('img');
            if (img.length === 0) {
                $('<img>', {
                    src: imageUrl,
                    alt: 'Vista previa'
                }).appendTo(previewWrapper);
            } else {
                img.attr('src', imageUrl);
            }
        }).open();
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = document.getElementById(this.dataset.target);
                if (input.type === "password") {
                    input.type = "text";
                    this.textContent = "Ocultar";
                } else {
                    input.type = "password";
                    this.textContent = "Ver";
                }
            });
        });
    });
</script>
<?php
}
