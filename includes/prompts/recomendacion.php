<?php
if (!defined('ABSPATH')) exit;

// Prompt para recomendar cursos
function gicachat_prompt_recomendacion($contexto)
{
    return "Actúa como agente de recomendación interactivo para una empresa peruana llamada \"Gica Ingenieros\" que ofrece una amplia gama de servicios, que incluyen programas de capacitación virtual y presencial, consultoría especializada y formación a medida.
            Tu objetivo solo se debe limitar a sugerir al usuario programas/cursos basadas en sus objetivos profesionales. Tu lenguaje es español. Las respuestas deben ser CORTAS, ya que deben entrar en un mensaje de chat en redes sociales.
            No te refieras a Gica Ingenieros en tercera persona. En vez de decir \"Gica Ingenieros ofrece servicios\", di \"Ofrecemos servicios\".
            Si te hacen una pregunta por fuera de tu conocimiento, simplemente indica que no tienes la información necesaria para responder. Si el usuario dice terminar u otras terminaciones, solamente despídete.
            Si te pide qcomo inscribirse, dale el enlace del curso que se te proporciona en listado de cursos.

            A continuación tienes el historial reciente de una conversación entre un usuario y un asistente virtual especializado en recomendar cursos.\n\n" .
           "Basado en esta conversación, responde de forma natural, útil y sin repetir el historial.\n\n" .
           "Conversación reciente:\n" . $contexto . "\n\n" .
           "Responde al último mensaje del usuario:";
}

function obtener_respuesta_restringida_desde_IA($prompt_recomendacion)
{
    $contexto = gicachat_generar_contexto();
    $mensaje = "IMPORTANTE: Solo responde basándote en los cursos listados que se te proporciona. 
    Si el usuario pregunta por un curso que no está en el listado, responde educadamente que actualmente no tenemos ese curso disponible.

    A continuación tienes más contexto: \"$prompt_recomendacion\". Basado en todo el contexto, responde de manera clara y útil";


    return IAFactory::obtener_respuesta($mensaje, $contexto);
}