<div id="config-openai" class="ia-config" style="<?php echo get_option('gicachat_ia_default') === 'openai' ? '' : 'display:none;'; ?>">
    <h3>OpenAI</h3>
    <p>Key: <input type="password" name="gicachat_api_openai" id="gicachat_api_openai" placeholder="API Key" value="<?php echo esc_attr(get_option('gicachat_api_openai')); ?>" size="50">
    <button type="button" class="toggle-password" data-target="gicachat_api_openai">Ver</button></p>
    <p>Modelo: <input type="text" name="gicachat_openai_model" value="<?php echo esc_attr(get_option('gicachat_openai_model')); ?>"></p>
    <p>Temperatura: <input type="number" step="0.1" min="0" max="1" name="gicachat_openai_temperature" value="<?php echo esc_attr(get_option('gicachat_openai_temperature', 0.7)); ?>"></p>
</div>