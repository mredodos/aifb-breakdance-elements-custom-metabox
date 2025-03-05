<?php

namespace AIFB\BreakdanceMetaBox\Core;

/**
 * Class MetaBoxIntegration
 * Gestisce l'integrazione con Meta Box
 */
class MetaBoxIntegration {
    /**
     * @var MetaBoxIntegration|null
     */
    private static ?MetaBoxIntegration $instance = null;

    /**
     * Costruttore privato per il pattern Singleton
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Ottiene l'istanza della classe (Singleton)
     *
     * @return MetaBoxIntegration
     */
    public static function getInstance(): MetaBoxIntegration {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Inizializza l'integrazione
     */
    private function init(): void {
        // Aggiungi supporto per i campi clonabili
        add_filter('breakdance_dynamic_data_preview_value', [$this, 'handleClonableFields'], 10, 2);
    }

    /**
     * Gestisce i campi clonabili di Meta Box
     *
     * @param mixed $value Il valore del campo
     * @param array $field Informazioni sul campo
     * @return mixed Il valore modificato
     */
    public function handleClonableFields($value, $field) {
        // Verifica se è un campo Meta Box
        if (!isset($field['provider']) || $field['provider'] !== 'metabox') {
            return $value;
        }

        // Gestisci i campi clonabili
        if (is_array($value) && !empty($value)) {
            // Se è un array di array (campo clonabile di gruppo)
            if (isset($value[0]) && is_array($value[0])) {
                // Restituisci il primo elemento per la preview
                return $value[0];
            }
            
            // Se è un array semplice (campo clonabile normale)
            if (count($value) > 1) {
                // Restituisci il primo elemento per la preview
                return reset($value);
            }
        }

        return $value;
    }

    /**
     * Ottiene tutti i campi Meta Box disponibili
     *
     * @return array Array di campi Meta Box
     */
    public function getAvailableFields(): array {
        if (!function_exists('rwmb_get_registry')) {
            return [];
        }

        $fields = [];
        $registry = rwmb_get_registry('field');
        
        if (!$registry) {
            return [];
        }
        
        $metaBoxes = $registry->get_by_object_type('post');
        
        if (empty($metaBoxes)) {
            return [];
        }
        
        foreach ($metaBoxes as $postType => $metaBoxGroups) {
            foreach ($metaBoxGroups as $metaBox) {
                if (!isset($metaBox['fields']) || !is_array($metaBox['fields'])) {
                    continue;
                }
                
                foreach ($metaBox['fields'] as $field) {
                    if (!isset($field['id'])) {
                        continue;
                    }
                    
                    $fieldLabel = isset($field['name']) ? $field['name'] : $field['id'];
                    $fieldType = isset($field['type']) ? $field['type'] : 'unknown';
                    $isCloneable = isset($field['clone']) && $field['clone'];
                    
                    $fields[$field['id']] = [
                        'id' => $field['id'],
                        'name' => $fieldLabel,
                        'type' => $fieldType,
                        'is_cloneable' => $isCloneable,
                    ];
                }
            }
        }
        
        return $fields;
    }

    /**
     * Ottiene il valore di un campo Meta Box
     *
     * @param string $fieldId ID del campo
     * @param int|null $postId ID del post (opzionale)
     * @return mixed Valore del campo
     */
    public function getFieldValue(string $fieldId, ?int $postId = null): mixed {
        if (!function_exists('rwmb_meta')) {
            return '';
        }

        if (null === $postId) {
            global $post;
            $postId = $post->ID ?? 0;
        }

        if (!$postId) {
            return '';
        }

        return rwmb_meta($fieldId, '', $postId);
    }

    /**
     * Verifica se un campo è clonabile
     *
     * @param string $fieldId ID del campo
     * @return bool True se il campo è clonabile, false altrimenti
     */
    public function isFieldCloneable(string $fieldId): bool {
        $fields = $this->getAvailableFields();
        
        if (isset($fields[$fieldId])) {
            return $fields[$fieldId]['is_cloneable'] ?? false;
        }
        
        return false;
    }

    /**
     * Ottiene il tipo di un campo
     *
     * @param string $fieldId ID del campo
     * @return string Tipo del campo
     */
    public function getFieldType(string $fieldId): string {
        $fields = $this->getAvailableFields();
        
        if (isset($fields[$fieldId])) {
            return $fields[$fieldId]['type'] ?? 'unknown';
        }
        
        return 'unknown';
    }
} 