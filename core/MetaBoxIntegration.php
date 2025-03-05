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
     * @param mixed $value Valore del campo
     * @param array|null $field Informazioni sul campo
     * @return mixed Valore elaborato
     */
    public function handleClonableFields($value, $field = null) {
        // Verifica se il valore è un array
        if (!is_array($value)) {
            return $value;
        }
        
        // Verifica se il campo è un campo Meta Box
        if (!$field || !isset($field['id'])) {
            return $value;
        }
        
        // Ottieni il tipo di campo
        $fieldType = $this->getFieldType($field['id']);
        
        // Verifica se il campo è clonabile
        $isCloneable = $this->isFieldCloneable($field['id']);
        
        // Se il campo è clonabile, restituisci un'istanza di MetaBoxRepeaterData
        if ($isCloneable) {
            return MetaBoxRepeaterData::fromArray($value);
        }
        
        // Altrimenti, restituisci il valore originale
        return $value;
    }

    /**
     * Ottieni tutti i campi Meta Box disponibili
     *
     * @return array Array di campi Meta Box
     */
    public function getAvailableFields(): array {
        // Utilizzo una cache statica per evitare chiamate ripetute
        static $cachedFields = null;
        
        if ($cachedFields !== null) {
            return $cachedFields;
        }
        
        // Verifica se siamo nel builder di Breakdance
        $isInBuilder = defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING;
        
        if (!function_exists('rwmb_get_registry')) {
            $cachedFields = [];
            return $cachedFields;
        }

        $fields = [];
        $registry = rwmb_get_registry('field');
        
        if (!$registry) {
            $cachedFields = [];
            return $cachedFields;
        }
        
        try {
            // Utilizziamo un timeout per evitare blocchi
            $startTime = microtime(true);
            $timeout = 1.0; // 1 secondo di timeout
            
            $metaBoxes = $registry->get_by_object_type('post');
            
            if (empty($metaBoxes)) {
                $cachedFields = [];
                return $cachedFields;
            }
            
            foreach ($metaBoxes as $postType => $metaBoxGroups) {
                // Verifica se abbiamo superato il timeout
                if ($isInBuilder && (microtime(true) - $startTime) > $timeout) {
                    break; // Interrompi il ciclo se siamo nel builder e abbiamo superato il timeout
                }
                
                foreach ($metaBoxGroups as $metaBox) {
                    // Verifica se abbiamo superato il timeout
                    if ($isInBuilder && (microtime(true) - $startTime) > $timeout) {
                        break; // Interrompi il ciclo se siamo nel builder e abbiamo superato il timeout
                    }
                    
                    if (!isset($metaBox['fields']) || !is_array($metaBox['fields'])) {
                        continue;
                    }
                    
                    foreach ($metaBox['fields'] as $field) {
                        // Verifica se abbiamo superato il timeout
                        if ($isInBuilder && (microtime(true) - $startTime) > $timeout) {
                            break; // Interrompi il ciclo se siamo nel builder e abbiamo superato il timeout
                        }
                        
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
        } catch (\Exception $e) {
            // In caso di errore, restituisci un array vuoto
            $cachedFields = [];
            return $cachedFields;
        }
        
        $cachedFields = $fields;
        return $cachedFields;
    }

    /**
     * Ottiene il valore di un campo Meta Box
     *
     * @param string $fieldId ID del campo
     * @param int|null $postId ID del post (opzionale)
     * @return mixed Valore del campo
     */
    public function getFieldValue(string $fieldId, ?int $postId = null): mixed {
        // Utilizzo una cache statica per evitare chiamate ripetute
        static $valueCache = [];
        
        // Crea una chiave di cache unica per questo campo e post
        $cacheKey = $fieldId . '_' . ($postId ?? get_the_ID());
        
        // Se il valore è già in cache, restituiscilo
        if (isset($valueCache[$cacheKey])) {
            return $valueCache[$cacheKey];
        }
        
        // Verifica se siamo nel builder di Breakdance
        $isInBuilder = defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING;
        
        // Se siamo nel builder, restituisci un valore di esempio
        if ($isInBuilder) {
            // Verifica se il campo è clonabile
            $isCloneable = $this->isFieldCloneable($fieldId);
            
            if ($isCloneable) {
                $valueCache[$cacheKey] = new MetaBoxRepeaterData([
                    'https://example.com/link1',
                    'https://example.com/link2'
                ]);
            } else {
                $valueCache[$cacheKey] = 'https://example.com';
            }
            
            return $valueCache[$cacheKey];
        }
        
        // Se Meta Box non è attivo, restituisci un valore vuoto
        if (!function_exists('rwmb_meta')) {
            $valueCache[$cacheKey] = '';
            return $valueCache[$cacheKey];
        }
        
        try {
            // Ottieni il valore del campo
            $value = rwmb_meta($fieldId, '', $postId);
            
            // Verifica se il campo è clonabile
            $isCloneable = $this->isFieldCloneable($fieldId);
            
            // Gestisci i campi clonabili
            if ($isCloneable && is_array($value)) {
                $value = new MetaBoxRepeaterData($value);
            } elseif ($isCloneable && !is_array($value)) {
                $value = new MetaBoxRepeaterData([$value]);
            }
            
            // Memorizza il valore nella cache
            $valueCache[$cacheKey] = $value;
            
            return $value;
        } catch (\Exception $e) {
            // In caso di errore, restituisci un valore vuoto
            $valueCache[$cacheKey] = '';
            return $valueCache[$cacheKey];
        }
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

    /**
     * Itera su un campo clonabile
     * 
     * @param string $fieldId ID del campo
     * @param callable $callback Funzione di callback
     * @param int|null $postId ID del post (opzionale)
     * @return array Risultati dell'iterazione
     */
    public function iterateCloneableField(string $fieldId, callable $callback, ?int $postId = null): array {
        // Ottieni il valore del campo
        $value = $this->getFieldValue($fieldId, $postId);
        
        // Se il valore non è un'istanza di MetaBoxRepeaterData, restituisci un array vuoto
        if (!$value instanceof MetaBoxRepeaterData) {
            return [];
        }
        
        // Ottieni i valori del campo
        $values = $value->getValue();
        
        // Se non ci sono valori, restituisci un array vuoto
        if (empty($values)) {
            return [];
        }
        
        // Itera sui valori e applica la funzione di callback
        $results = [];
        foreach ($values as $index => $item) {
            $results[] = $callback($item, $index);
        }
        
        return $results;
    }
    
    /**
     * Ottiene un elemento specifico di un campo clonabile
     * 
     * @param string $fieldId ID del campo
     * @param int $index Indice dell'elemento
     * @param int|null $postId ID del post (opzionale)
     * @return mixed Valore dell'elemento
     */
    public function getCloneableFieldItem(string $fieldId, int $index, ?int $postId = null): mixed {
        // Ottieni il valore del campo
        $value = $this->getFieldValue($fieldId, $postId);
        
        // Se il valore non è un'istanza di MetaBoxRepeaterData, restituisci null
        if (!$value instanceof MetaBoxRepeaterData) {
            return null;
        }
        
        // Ottieni i valori del campo
        $values = $value->getValue();
        
        // Se l'indice non esiste, restituisci null
        if (!isset($values[$index])) {
            return null;
        }
        
        return $values[$index];
    }
}

/**
 * Classe per gestire i dati dei campi clonabili (ripetitori)
 */
class MetaBoxRepeaterData {
    /**
     * @var array Valore del campo clonabile
     */
    public array $value = [];

    /**
     * Costruttore
     * 
     * @param array $value Valore del campo clonabile
     */
    public function __construct(array $value = []) {
        $this->value = $value;
    }

    /**
     * Ottiene il valore del campo clonabile
     * 
     * @param array $attributes Attributi aggiuntivi
     * @return array Valore del campo clonabile
     */
    public function getValue(array $attributes = []): array {
        return $this->value;
    }

    /**
     * Verifica se il campo ha un valore
     * 
     * @return bool True se il campo ha un valore, false altrimenti
     */
    public function hasValue(): bool {
        return !empty($this->value);
    }

    /**
     * Crea un'istanza da un array
     * 
     * @param array $repeater Array di valori
     * @return self Istanza della classe
     */
    public static function fromArray(array $repeater): self {
        return new self($repeater);
    }
} 