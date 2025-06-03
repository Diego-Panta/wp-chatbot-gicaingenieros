<?php
if (!defined('ABSPATH')) exit;

class IAFactory
{
    public static function crear(): IAInterface
    {
        $modo = get_option('gicachat_ia', 'gemini');

        switch ($modo) {
            case 'openai':
                return new OpenAIClient();
            case 'deepseek':
                return new DeepseekClient();
            case 'gemini':
            default:
                return new GeminiClient();
        }
    }

    public static function obtener_respuesta($mensaje, $contexto)
    {
        try {
            $ia = IAFactory::crear();
            return $ia->responder($mensaje, $contexto);
        } catch (Exception $e) {
            error_log('[GicaChat ERROR - obtener_respuesta] ' . $e->getMessage());
            return construir_mensaje_error();
        }
    }

    public static function get_modo()
    {
        return get_option('gicachat_ia', 'gemini');
    }

    public static function obtener_resumen($pregunta)
    {
        $mensaje = "Solo debes resumir esta pregunta del usuario: \"$pregunta\"";
        return self::obtener_respuesta($mensaje, gicachat_generar_contexto_informacion_detallada());
    }

    public static function obtener_resultado($pregunta)
    {
        $mensaje = "Responde a esta pregunta de forma clara y concisa: \"$pregunta\"";
        return self::obtener_respuesta($mensaje, gicachat_generar_contexto_informacion_detallada());
    }

    public static function reformular_respuesta($pregunta)
    {
        $mensaje = "reformula la respuesta de forma clara y concisa: \"$pregunta\"";
        return self::obtener_respuesta($mensaje, gicachat_generar_contexto_informacion_detallada());
    }
}
