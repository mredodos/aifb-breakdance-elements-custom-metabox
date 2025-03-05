<?php

namespace AIFB\BreakdanceMetaBox\Core;

/**
 * Class Loader
 * Carica tutti i componenti necessari
 */
class Loader {
    /**
     * @var Loader|null
     */
    private static ?Loader $instance = null;

    /**
     * Costruttore privato per il pattern Singleton
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Ottiene l'istanza della classe (Singleton)
     *
     * @return Loader
     */
    public static function getInstance(): Loader {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inizializza il loader
     */
    private function init(): void {
        // Carica i file degli elementi
        $this->loadElementFiles();
        
        // Registra gli hook
        $this->registerHooks();
    }

    /**
     * Carica i file degli elementi
     */
    private function loadElementFiles(): void {
        $metaboxFieldFile = AIFB_BMB_PATH . 'templates/elements/metabox/metabox-field.php';
        
        if (file_exists($metaboxFieldFile)) {
            require_once $metaboxFieldFile;
        }
    }

    /**
     * Registra gli hook
     */
    private function registerHooks(): void {
        // Registra gli elementi quando Breakdance è caricato
        add_action('breakdance_loaded', [$this, 'registerElements'], 20);
    }

    /**
     * Registra gli elementi
     */
    public function registerElements(): void {
        // Verifica se Breakdance è attivo
        if (!function_exists('Breakdance\\Elements\\register')) {
            return;
        }
        
        // Verifica se Meta Box è attivo
        if (!class_exists('RWMB_Loader')) {
            return;
        }
        
        // Registra l'elemento Meta Box Field
        if (class_exists('AIFB\\BreakdanceMetaBox\\Elements\\MetaBoxField')) {
            \Breakdance\Elements\register(
                'AIFB\\BreakdanceMetaBox\\Elements\\MetaBoxField'
            );
        }
    }
} 