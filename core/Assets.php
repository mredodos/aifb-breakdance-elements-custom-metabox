<?php

namespace AIFB\BreakdanceMetaBox\Core;

/**
 * Class Assets
 * Gestisce gli asset CSS e JS
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
        // Registra gli asset
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
        
        // Registra gli asset per l'admin
        add_action('admin_enqueue_scripts', [$this, 'registerAdminAssets']);
    }

    /**
     * Registra gli asset per il frontend
     */
    public function registerAssets(): void {
        // Registra e carica il CSS
        wp_register_style(
            'aifb-metabox-field',
            AIFB_BMB_URL . 'assets/css/metabox-field.css',
            [],
            AIFB_BMB_VERSION
        );
        
        // Registra e carica il JS
        wp_register_script(
            'aifb-metabox-field',
            AIFB_BMB_URL . 'assets/js/metabox-field.js',
            ['jquery'],
            AIFB_BMB_VERSION,
            true
        );
    }

    /**
     * Registra gli asset per l'admin
     */
    public function registerAdminAssets(): void {
        // Registra e carica il CSS per l'admin
        wp_register_style(
            'aifb-metabox-field-admin',
            AIFB_BMB_URL . 'assets/css/metabox-field-admin.css',
            [],
            AIFB_BMB_VERSION
        );
        
        // Registra e carica il JS per l'admin
        wp_register_script(
            'aifb-metabox-field-admin',
            AIFB_BMB_URL . 'assets/js/metabox-field-admin.js',
            ['jquery'],
            AIFB_BMB_VERSION,
            true
        );
    }

    /**
     * Carica gli asset per un elemento specifico
     *
     * @param string $element Nome dell'elemento
     */
    public function enqueueElementAssets(string $element): void {
        if ($element === 'metabox-field') {
            wp_enqueue_style('aifb-metabox-field');
            wp_enqueue_script('aifb-metabox-field');
        }
    }
} 