<?php
if (!defined('ABSPATH')) exit;

function obtener_cursos_woocommerce()
{
    try {
        // Verificar que WooCommerce está activo
        if (!class_exists('WooCommerce')) {
            return []; // WooCommerce no está disponible, devolver vacío
        }

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        $productos = new WP_Query($args);
        $cursos_array = [];

        if ($productos->have_posts()) {
            while ($productos->have_posts()) {
                $productos->the_post();
                $producto = wc_get_product(get_the_ID());

                // Asegurarse que sea un producto visible y válido
                if (!$producto || !$producto->is_visible()) continue;

                $cursos_array[] = array(
                    'titulo'      => get_the_title(),
                    'descripcion' => get_the_excerpt(),
                    'enlace'      => get_permalink(),
                    'precio'      => $producto->get_price_html() ?: 'Gratis',
                    'imagen' => get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: '',
                );
            }
            wp_reset_postdata();
        }
        return $cursos_array;
        
    } catch (Exception $e) {
        error_log('[GicaChat ERROR - obtener_cursos_woocommerce] ' . $e->getMessage());
        return construir_mensaje_error();
    }
}
