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
     * Utilizza un approccio più dinamico per caricare tutti gli elementi nella directory
     */
    private function loadElementFiles(): void {
        $elementsDir = AIFB_BMB_PATH . 'templates/elements';
        
        if (!is_dir($elementsDir)) {
            return;
        }
        
        // Carica l'elemento Meta Box Field
        $metaboxFieldFile = $elementsDir . '/metabox/metabox-field.php';
        
        if (file_exists($metaboxFieldFile)) {
            require_once $metaboxFieldFile;
        }
        
        // Carica eventuali elementi aggiuntivi
        $this->loadElementsFromDirectory($elementsDir);
    }
    
    /**
     * Carica tutti gli elementi da una directory
     * 
     * @param string $directory Directory contenente gli elementi
     */
    private function loadElementsFromDirectory(string $directory): void {
        // Salta se la directory non esiste
        if (!is_dir($directory)) {
            return;
        }
        
        // Ottieni tutti i file PHP nella directory
        $files = glob($directory . '/*/*.php');
        
        if (empty($files)) {
            return;
        }
        
        foreach ($files as $file) {
            // Salta il file già caricato
            if (strpos($file, 'metabox-field.php') !== false) {
                continue;
            }
            
            // Carica il file
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    /**
     * Registra gli hook
     */
    private function registerHooks(): void {
        // Registra gli elementi quando Breakdance è caricato
        add_action('breakdance_loaded', [$this, 'registerElements'], 20);
        
        // Aggiungi supporto per le traduzioni
        add_action('init', [$this, 'loadTextDomain']);
    }

    /**
     * Registra gli elementi
     * Utilizza un approccio più dinamico per registrare tutti gli elementi disponibili
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
        
        // Registra eventuali elementi aggiuntivi
        $this->registerAdditionalElements();
    }
    
    /**
     * Registra elementi aggiuntivi
     * Questo metodo può essere esteso per registrare elementi aggiuntivi in futuro
     */
    private function registerAdditionalElements(): void {
        // Esempio di come registrare elementi aggiuntivi in futuro
        $elements = [
            // 'AIFB\\BreakdanceMetaBox\\Elements\\AnotherElement',
            // 'AIFB\\BreakdanceMetaBox\\Elements\\YetAnotherElement',
        ];
        
        foreach ($elements as $element) {
            if (class_exists($element)) {
                \Breakdance\Elements\register($element);
            }
        }
    }

    /**
     * Carica il dominio di traduzione
     */
    public function loadTextDomain(): void {
        load_plugin_textdomain(
            'aifb-breakdance-metabox',
            false,
            dirname(plugin_basename(AIFB_BMB_FILE)) . '/languages'
        );
    }
} 