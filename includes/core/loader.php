<?php
if (!defined('ABSPATH')) exit;

// AI Integraciones
require_once GICACHAT_DIR . '/includes/core/ajax/default-mode.php';
require_once GICACHAT_DIR . '/includes/core/ajax/voiceflow-mode.php';

require_once GICACHAT_DIR . 'includes/ai/integraciones/voiceflow.php';
require_once GICACHAT_DIR . 'includes/cursos/fuentes/ms_lms.php';
require_once GICACHAT_DIR . 'includes/cursos/fuentes/woocommerce.php';

require_once GICACHAT_DIR . 'includes/ai/interfaces/IAInterface.php';
require_once GICACHAT_DIR . 'includes/ai/integraciones/GeminiClient.php';
require_once GICACHAT_DIR . 'includes/ai/integraciones/OpenAIClient.php';
require_once GICACHAT_DIR . 'includes/ai/integraciones/DeepseekClient.php';

require_once GICACHAT_DIR . 'includes/prompts/recomendacion.php';
require_once GICACHAT_DIR . 'includes/prompts/clasificacion.php';
require_once GICACHAT_DIR . 'includes/prompts/resolver_pregunta.php';
require_once GICACHAT_DIR . 'includes/prompts/problema_ayuda.php';

require_once GICACHAT_DIR . 'includes/flujo/historyContext/contexto_historial_chat.php';

// Flujo
require_once GICACHAT_DIR . 'includes/flujo/handlers.php';
require_once GICACHAT_DIR . 'includes/flujo/actions/ai-actions.php';
require_once GICACHAT_DIR . 'includes/flujo/actions/api-actions.php';
require_once GICACHAT_DIR . 'includes/flujo/actions/condition-actions.php';
require_once GICACHAT_DIR . 'includes/flujo/manejador.php';

require_once GICACHAT_DIR . 'includes/ai/factory.php';

// Cursos
require_once GICACHAT_DIR . 'includes/cursos/manager.php';

// Utilidades / Core
require_once GICACHAT_DIR . 'includes/core/contexto/generar_contexto.php';
require_once GICACHAT_DIR . 'includes/core/contexto/leer_contenido_json.php';
