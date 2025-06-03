<?php

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo
}

// Función para obtener los cursos de MasterStudy LMS
function obtener_cursos_masterstudy()
{
    try {
        $args = array(
            'post_type'      => 'stm-courses',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        $cursos = new WP_Query($args);
        $cursos_array = [];

        if ($cursos->have_posts()) {
            while ($cursos->have_posts()) {
                $cursos->the_post();

                $curso_id = get_the_ID();

                $cursos_array[] = array(
                    'titulo'       => get_the_title(),
                    'descripcion'  => mb_substr(wp_strip_all_tags(apply_filters('the_content', get_the_content())), 0, 10000) . '...',
                    'enlace'       => get_permalink(),
                    'precio'       => get_post_meta($curso_id, 'stm_lms_course_price', true) ?: 'Gratis',
                    'imagen'       => get_the_post_thumbnail_url($curso_id, 'medium') ?: '',
                    'vistas'       => get_post_meta($curso_id, 'stm_lms_views', true) ?: 0,
                    'puntuacion'   => get_post_meta($curso_id, 'course_rating_avg', true) ?: '0.0',
                    'total_votos'  => get_post_meta($curso_id, 'course_rating_total', true) ?: 0,
                );
                error_log('[GicaChat DEBUG - Curso obtenido] ' . print_r(end($cursos_array), true));
            }
            wp_reset_postdata();
        }

        return $cursos_array;

    } catch (Exception $e) {
        error_log('[GicaChat ERROR - obtener_cursos_masterstudy] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}
