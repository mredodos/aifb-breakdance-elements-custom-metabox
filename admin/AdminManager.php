<?php

namespace AIFB\BreakdanceMetaBox\Admin;

/**
 * Class AdminManager
 * Gestisce le funzionalità di amministrazione
 */
class AdminManager {
    /**
     * @var AdminManager|null
     */
    private static ?AdminManager $instance = null;

    /**
     * Costruttore privato per il pattern Singleton
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Ottiene l'istanza della classe (Singleton)
     *
     * @return AdminManager
     */
    public static function getInstance(): AdminManager {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inizializza le funzionalità di amministrazione
     */
    private function init(): void {
        // Aggiungi link alle impostazioni nella pagina dei plugin
        add_filter('plugin_action_links_' . plugin_basename(AIFB_BMB_FILE), [$this, 'addPluginActionLinks']);
        
        // Aggiungi meta link nella pagina dei plugin
        add_filter('plugin_row_meta', [$this, 'addPluginRowMeta'], 10, 2);
    }

    /**
     * Aggiunge link alle impostazioni nella pagina dei plugin
     *
     * @param array $links Link esistenti
     * @return array Link modificati
     */
    public function addPluginActionLinks(array $links): array {
        // Aggiungi link alle impostazioni
        $settingsLink = '<a href="' . admin_url('admin.php?page=breakdance_settings') . '">' . esc_html__('Settings', 'aifb-breakdance-metabox') . '</a>';
        array_unshift($links, $settingsLink);
        
        return $links;
    }

    /**
     * Aggiunge meta link nella pagina dei plugin
     *
     * @param array $links Link esistenti
     * @param string $file File del plugin
     * @return array Link modificati
     */
    public function addPluginRowMeta(array $links, string $file): array {
        if (plugin_basename(AIFB_BMB_FILE) !== $file) {
            return $links;
        }
        
        // Aggiungi link alla documentazione
        $links[] = '<a href="https://plugins.aifb.ch/docs/aifb-breakdance-elements-custom-metabox" target="_blank">' . esc_html__('Documentation', 'aifb-breakdance-metabox') . '</a>';
        
        // Aggiungi link al supporto
        $links[] = '<a href="https://plugins.aifb.ch/support" target="_blank">' . esc_html__('Support', 'aifb-breakdance-metabox') . '</a>';
        
        return $links;
    }
} 