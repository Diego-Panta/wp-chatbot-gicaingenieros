jQuery(document).ready(function ($) {
    const modoChat = chat_ajax.modo; // o 'default' dinámicamente si lo decides

    if (modoChat === 'default') {
        $.getScript(chat_ajax.plugin_url + 'assets/js/default/flujo.js');
    } else if (modoChat === 'voiceflow') {
        $.getScript(chat_ajax.plugin_url + 'assets/js/voiceflow/flujo.js');
    }
});