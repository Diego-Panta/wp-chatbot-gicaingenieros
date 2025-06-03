<?php
if (!defined('ABSPATH')) exit;

/**
 * Carga un archivo JSON ubicado en includes/contenido/
 *
 * @param string $nombre Nombre del archivo sin extensión (por ejemplo: 'inicio')
 * @return array|null Devuelve el contenido decodificado o null si no existe.
 */
function gicachat_leer_contenido_recursivo($directorio)
{
    $contenido_total = "";

    $archivos = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directorio),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($archivos as $archivo) {
        if (!$archivo->isFile() || $archivo->getExtension() !== 'json') {
            continue;
        }

        $data = json_decode(file_get_contents($archivo->getPathname()), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("[GicaChat ERROR - JSON inválido en {$archivo->getPathname()}]: " . json_last_error_msg());
            continue;
        }

        if (is_array($data) && isset($data[0]) && is_array($data[0]) && isset($data[0]['titulo'])) {
            foreach ($data as $item) {
                if (isset($item['titulo']) && isset($item['contenido'])) {
                    $contenido_total .= "{$item['titulo']}:\n" . strip_tags($item['contenido']) . "\n";
                    /*if (isset($item['enlace'])) {
                        $contenido_total .= "Enlace: <a href=\"{$item['enlace']}\" target=\"_blank\">{$item['enlace']}</a>\n";
                    }*/
                    $contenido_total .= "\n";
                }
            }
        } elseif (isset($data['titulo']) && isset($data['contenido'])) {
            $contenido_total .= "{$data['titulo']}:\n" . strip_tags($data['contenido']) . "\n";
            /*if (isset($data['enlace'])) {
                $contenido_total .= "Enlace: <a href=\"{$data['enlace']}\" target=\"_blank\">{$data['enlace']}</a>\n";
            }*/
            $contenido_total .= "\n";
        }
    }

    return $contenido_total;
}
