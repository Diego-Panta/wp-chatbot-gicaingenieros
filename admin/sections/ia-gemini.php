<div id="config-gemini" class="ia-config" style="<?php echo get_option('gicachat_ia_default') === 'gemini' ? '' : 'display:none;'; ?>">
    <h3>Gemini</h3>
    <p>Key: <input type="password" name="gicachat_api_gemini" id="gicachat_api_gemini" placeholder="API Key" value="<?php echo esc_attr(get_option('gicachat_api_gemini')); ?>" size="50">
    <button type="button" class="toggle-password" data-target="gicachat_api_gemini">Ver</button></p>
    <p>Modelo: <input type="text" name="gicachat_gemini_model" value="<?php echo esc_attr(get_option('gicachat_gemini_model')); ?>"></p>
    <p>Temperatura: <input type="number" step="0.1" min="0" max="1" name="gicachat_gemini_temperature" value="<?php echo esc_attr(get_option('gicachat_gemini_temperature', 0.7)); ?>"></p>
</div>