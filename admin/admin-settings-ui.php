<?php
if (!defined('ABSPATH')) exit;

$modo = get_option('gicachat_modo', 'default');
$ia_default = get_option('gicachat_ia_default', 'gemini');
$fuente_cursos = get_option('gicachat_fuente_cursos', 'masterstudy');
?>

<div class="wrap">
    <?php settings_fields('gicachat_settings_group'); ?>
    <?php do_settings_sections('gicachat'); ?>

    <div id="poststuff">

        <!-- Acordeón: Modo del Chat -->
        <div class="postbox">
            <button type="button" class="handlediv" aria-expanded="true">
                <span class="screen-reader-text">Toggle panel: Modo del Chat</span>
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle"><span>Modo del Chat</span></h2>
            <div class="inside">
                <?php include GICACHAT_DIR . 'admin/sections/modo.php'; ?>
            </div>
        </div>

        <!-- Acordeón: Imágenes del Chat -->
        <div class="postbox">
            <button type="button" class="handlediv" aria-expanded="false">
                <span class="screen-reader-text">Toggle panel: Imágenes del Chat</span>
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle"><span>Imágenes del Chat</span></h2>
            <div class="inside">
                <?php include GICACHAT_DIR . 'admin/sections/imagenes.php'; ?>
            </div>
        </div>

        <!-- Acordeón: Colores del Chat -->
        <div class="postbox">
            <button type="button" class="handlediv" aria-expanded="false">
                <span class="screen-reader-text">Toggle panel: Colores del Chat</span>
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle"><span>Colores del Chat</span></h2>
            <div class="inside">
                <?php include GICACHAT_DIR . 'admin/sections/colores.php'; ?>
            </div>
        </div>

    </div>
    <style>
        .toggle-password {
            margin-left: 5px;
            cursor: pointer;
            background: transparent;
            border: none;
            font-size: 16px;
        }

        .gicachat-imagen-preview-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            margin-top: 10px;
            background: repeating-conic-gradient(#ccc 0% 25%, #fff 0% 50%) 50% / 20px 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            max-width: 160px;
            max-height: 160px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .gicachat-imagen-preview-wrapper img {
            max-width: 140px;
            height: auto;
            display: block;
            border-radius: 4px;
        }

        /* Opcional: centra todo el campo de imagen */
        .form-table td {
            vertical-align: top;
        }

        .gicachat-color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .gicachat-color-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .gicachat-color-card label {
            font-weight: 600;
            font-size: 13px;
            color: #333;
        }

        .gicachat-color-card input[type="color"] {
            height: 40px;
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
        }
    </style>
</div>

