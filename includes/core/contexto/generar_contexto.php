<?php
if (!defined('ABSPATH')) exit;

function gicachat_generar_contexto_informacion_detallada()
{
    try {
        $contexto = "Información institucional, es necesario que respondas de manera clara y precisa:\n\n";
        $contexto .= gicachat_leer_contenido_recursivo(GICACHAT_DIR . "includes/contenido/informacion_detallada/");
        return $contexto;
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - contexto_informacion_detallada] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}

function gicachat_generar_contexto_cursos_detallados()
{
    try {
        $contexto = "Información detallada de los cursos, es necesario que respondas de manera clara y precisa:\n\n";
        $contexto .= gicachat_leer_contenido_recursivo(GICACHAT_DIR . "includes/contenido/cursos_detallados/");
        return $contexto;
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - contexto_cursos_detallados] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}

function gicachat_generar_contexto()
{
    try {
        $contexto = "Información específica de cada curso:\n\n";

        // Bloque 1: Cursos básicos
        $cursos = obtener_cursos();
        if ($cursos) {
            $contexto .= "Se te proporcionará un listado con los cursos actualmente activos en el sitio web de la empresa Gica. Este listado incluye título, descripción, precio, enlace, puntuación y número de vistas de cada curso. 
            Es importante que tomes esta lista como tu fuente principal de verdad, ya que representa los cursos actualmente disponibles para los usuarios. 
            Además, se te proporcionará una base de conocimiento con información detallada de todos los cursos que ofrece o ha ofrecido la empresa, pero debes tener en cuenta que puede contener cursos inactivos o que aún no están disponibles en el sitio web. 
            Por lo tanto, **debes priorizar exclusivamente los cursos activos listados a continuación** y no recomendar o tomar en cuenta cursos que no estén en esta lista, aunque aparezcan en la base de conocimiento:\n";

            foreach ($cursos as $curso) {
                $contexto .= "- {$curso['titulo']}: {$curso['descripcion']}. Precio: {$curso['precio']}. ";
                $contexto .= "Puntuación: {$curso['puntuacion']} ({$curso['total_votos']} votos), ";
                $contexto .= "Vistas: {$curso['vistas']}. <enlace_curso>{$curso['enlace']}</enlace_curso>\n";
            }

            $contexto .= "\n";
        }

        // Bloque 2: Cursos detallados (si existe contenido)
        $detallado = gicachat_generar_contexto_cursos_detallados();
        if ($detallado) {
            $contexto .= "La base de conocimiento que se presenta a continuación puede ser útil como fuente de información adicional sobre los cursos listados anteriormente, pero **no debes sugerir cursos que estén aquí si no están también en el listado de cursos activos**. Úsala solo como referencia complementaria para enriquecer la información de los cursos activos:\n";
            $contexto .= $detallado;
        }

        return $contexto;
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - generar_contexto_curso_especifico] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}
