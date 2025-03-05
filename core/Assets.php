<?php

namespace AIFB\BreakdanceMetaBox\Core;

/**
 * Class Assets
 * Gestisce gli asset CSS
 */
class Assets {
    /**
     * @var Assets|null
     */
    private static ?Assets $instance = null;

    /**
     * Costruttore privato per il pattern Singleton
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Ottiene l'istanza della classe (Singleton)
     *
     * @return Assets
     */
    public static function getInstance(): Assets {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inizializza gli asset
     */
    private function init(): void {
        // Registra e carica gli stili CSS
        add_action('wp_enqueue_scripts', [$this, 'registerFrontendStyles']);
        
        // Registra e carica gli stili CSS per l'admin
        add_action('admin_enqueue_scripts', [$this, 'registerAdminStyles']);
        
        // Aggiungi stili inline per Breakdance - usando wp_enqueue_scripts con priorità alta
        add_action('wp_enqueue_scripts', [$this, 'addBreakdanceStyles'], 999);
    }

    /**
     * Registra e carica gli stili per il frontend
     */
    public function registerFrontendStyles(): void {
        // Registra e carica il CSS
        wp_enqueue_style(
            'aifb-metabox-field',
            AIFB_BMB_URL . 'assets/css/metabox-field.css',
            [],
            AIFB_BMB_VERSION
        );
    }

    /**
     * Registra e carica gli stili per l'admin
     */
    public function registerAdminStyles(): void {
        // Registra e carica il CSS per l'admin
        wp_enqueue_style(
            'aifb-metabox-field-admin',
            AIFB_BMB_URL . 'assets/css/metabox-field-admin.css',
            [],
            AIFB_BMB_VERSION
        );
    }
    
    /**
     * Aggiungi stili inline per Breakdance
     * Questo è un approccio migliore per assicurarsi che gli stili siano disponibili nel builder
     */
    public function addBreakdanceStyles(): void {
        // Verifica se Breakdance è attivo e se lo stile 'breakdance' è stato registrato
        if (!wp_style_is('breakdance', 'registered')) {
            return;
        }
        
        // Aggiungi stili inline per il builder di Breakdance
        $styles = '
        .aifb-metabox-field {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .aifb-metabox-field-label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .aifb-metabox-field-content {
            line-height: 1.5;
        }
        
        .aifb-metabox-field-error,
        .aifb-metabox-field-fallback {
            padding: 10px;
            border-radius: 4px;
        }
        
        .aifb-metabox-field-error {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .aifb-metabox-field-fallback {
            background-color: #f5f5f5;
            color: #757575;
        }
        
        .aifb-metabox-field-clonable {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .aifb-metabox-field-clone-item {
            padding: 5px 0;
        }
        ';
        
        // Aggiungi gli stili inline
        wp_add_inline_style('breakdance', $styles);
    }
} 