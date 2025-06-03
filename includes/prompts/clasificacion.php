<?php
if (!defined('ABSPATH')) exit;

// Prompt para clasificar intención de terminar o preguntar
function gicachat_prompt_clasificacion($mensaje_usuario) {
    return "Analiza el mensaje del usuario y clasifícalo en una de las siguientes dos categorías: 'pregunta' o 'terminar'. Utiliza las siguientes pautas:
- Si el mensaje indica que el usuario no tiene más preguntas o desea finalizar la conversación (por ejemplo, \"No, gracias\", \"Eso es todo\", \"Hasta luego\", \"Nada más\"), clasifícalo como 'terminar'.
- Si el mensaje no corresponde a finalizar la conversación, clasifícalo como 'pregunta'.

Mensaje del usuario: \"$mensaje_usuario\"

Devuelve únicamente el nombre de la categoría como respuesta.";
}