<div id="config-deepseek" class="ia-config" style="<?php echo get_option('gicachat_ia_default') === 'deepseek' ? '' : 'display:none;'; ?>">
    <h3>Deepseek</h3>
    <p>Key: <input type="password" name="gicachat_api_deepseek" id="gicachat_api_deepseek" placeholder="API Key" value="<?php echo esc_attr(get_option('gicachat_api_deepseek')); ?>" size="50">
    <button type="button" class="toggle-password" data-target="gicachat_api_deepseek">Ver</button></p>
    <p>Modelo: <input type="text" name="gicachat_deepseek_model" value="<?php echo esc_attr(get_option('gicachat_deepseek_model')); ?>"></p>
    <p>Temperatura: <input type="number" step="0.1" min="0" max="1" name="gicachat_deepseek_temperature" value="<?php echo esc_attr(get_option('gicachat_deepseek_temperature', 0.7)); ?>"></p>
</div>