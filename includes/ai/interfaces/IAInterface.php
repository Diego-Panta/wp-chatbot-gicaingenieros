<?php
if (!defined('ABSPATH')) exit;

interface IAInterface {
    public function responder(string $mensaje, string $contexto): string;
}