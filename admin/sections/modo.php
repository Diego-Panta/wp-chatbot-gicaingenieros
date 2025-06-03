<?php
$modo = get_option('gicachat_modo', 'default');
$ia_default = get_option('gicachat_ia_default', 'gemini');
$fuente_cursos = get_option('gicachat_fuente_cursos', 'masterstudy');
?>

<div id="gicachat-admin" class="wrap">
    <h2 class="title">Modo de Funcionamiento</h2>

    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="gicachat_modo">Selecciona el modo:</label>
            </th>
            <td>
                <select name="gicachat_modo" id="gicachat_modo">
                    <option value="default" <?php selected($modo, 'default'); ?>>Modo Default</option>
                    <option value="voiceflow" <?php selected($modo, 'voiceflow'); ?>>Modo Voiceflow</option>
                </select>
                <p class="description">Elige cómo deseas que funcione el chatbot: con lógica personalizada o con flujos de Voiceflow.</p>
            </td>
        </tr>
    </table>

    <div id="modo-default" style="<?php echo $modo === 'default' ? '' : 'display:none;'; ?>">
        <h2 class="title">Configuración IA (Modo Default)</h2>

        <table class="form-table">
            <tr>
                <th scope="row">Modelo de IA</th>
                <td>
                    <label><input type="radio" name="gicachat_ia_default" value="openai" <?php checked($ia_default, 'openai'); ?>> ChatGPT (OpenAI)</label><br>
                    <label><input type="radio" name="gicachat_ia_default" value="gemini" <?php checked($ia_default, 'gemini'); ?>> Gemini (Google)</label><br>
                    <label><input type="radio" name="gicachat_ia_default" value="deepseek" <?php checked($ia_default, 'deepseek'); ?>> Deepseek</label>
                </td>
            </tr>

            <tr>
                <th scope="row">Fuente de Cursos</th>
                <td>
                    <select name="gicachat_fuente_cursos">
                        <option value="masterstudy" <?php selected($fuente_cursos, 'masterstudy'); ?>>MasterStudy LMS</option>
                        <option value="woocommerce" <?php selected($fuente_cursos, 'woocommerce'); ?>>WooCommerce</option>
                    </select>
                    <p class="description">Selecciona desde dónde se obtendrán los cursos en el modo Default.</p>
                </td>
            </tr>
        </table>

        <?php
            // Secciones de configuración para cada IA
            require_once GICACHAT_DIR . 'admin/sections/ia-openai.php';
            require_once GICACHAT_DIR . 'admin/sections/ia-gemini.php';
            require_once GICACHAT_DIR . 'admin/sections/ia-deepseek.php';
        ?>
    </div>

    <div id="modo-voiceflow" style="<?php echo $modo === 'voiceflow' ? '' : 'display:none;'; ?>">
        <h2 class="title">Configuración Voiceflow</h2>

        <table class="form-table">
            <tr>
                <th scope="row"><label for="gicachat_voiceflow_api_key">API Key</label></th>
                <td><input type="password" id="gicachat_voiceflow_api_key" name="gicachat_voiceflow_api_key" value="<?php echo esc_attr(get_option('gicachat_voiceflow_api_key')); ?>" size="50" class="regular-text">
                <button type="button" class="toggle-password" data-target="gicachat_voiceflow_api_key">Ver</button></td>
            </tr>
            <tr>
                <th scope="row"><label for="gicachat_voiceflow_version_id">Version ID</label></th>
                <td><input type="password" id="gicachat_voiceflow_version_id" name="gicachat_voiceflow_version_id" value="<?php echo esc_attr(get_option('gicachat_voiceflow_version_id')); ?>" size="50" class="regular-text">
                <button type="button" class="toggle-password" data-target="gicachat_voiceflow_version_id">Ver</button></td>
            </tr>
        </table>
    </div>
</div>