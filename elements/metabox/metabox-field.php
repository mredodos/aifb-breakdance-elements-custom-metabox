<?php

namespace AIFB\BreakdanceMetaBox\Elements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;
use AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration;

\Breakdance\ElementStudio\registerElementForEditing(
    "AIFB\\BreakdanceMetaBox\\Elements\\MetaBoxField",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

/**
 * @class MetaBoxField
 */
class MetaBoxField extends \Breakdance\Elements\Element
{
    static function name()
    {
        return esc_html__('Meta Box URL Field', 'aifb-breakdance-metabox');
    }

    static function slug()
    {
        return 'aifb-metabox-field';
    }

    static function tag()
    {
        return 'div';
    }

    static function category()
    {
        return esc_html__('Custom Fields', 'aifb-breakdance-metabox');
    }

    static function icon()
    {
        return 'LinkIcon';
    }

    static function label()
    {
        return esc_html__('Meta Box URL Field', 'aifb-breakdance-metabox');
    }

    static function template()
    {
        return file_get_contents(__DIR__ . '/html.twig');
    }

    static function defaultCss()
    {
        return file_get_contents(__DIR__ . '/default.css');
    }

    static function cssTemplate()
    {
        return file_get_contents(__DIR__ . '/css.twig');
    }

    /**
     * Regola di nesting - definisce come l'elemento può essere annidato
     */
    static function nestingRule()
    {
        return ["type" => "final"];
    }

    /**
     * Barre di spaziatura - definisce le barre di spaziatura visibili nel builder
     */
    static function spacingBars()
    {
        return [
            [
                'cssProperty' => 'margin-top', 
                'location' => 'outside-top', 
                'affectedPropertyPath' => 'design.spacing.margin_top.%%BREAKPOINT%%'
            ], 
            [
                'cssProperty' => 'margin-bottom', 
                'location' => 'outside-bottom', 
                'affectedPropertyPath' => 'design.spacing.margin_bottom.%%BREAKPOINT%%'
            ]
        ];
    }

    /**
     * Attributi - definisce attributi HTML aggiuntivi
     */
    static function attributes()
    {
        return false;
    }

    /**
     * Sperimentale - indica se l'elemento è sperimentale
     */
    static function experimental()
    {
        return false;
    }

    /**
     * Ordine - definisce l'ordine dell'elemento nella lista
     */
    static function order()
    {
        return 2101;
    }

    /**
     * Percorsi di proprietà dinamiche - definisce quali proprietà possono accettare dati dinamici
     */
    static function dynamicPropertyPaths()
    {
        return [
            [
                'accepts' => 'string', 
                'path' => 'content.field.field_id'
            ],
            [
                'accepts' => 'string', 
                'path' => 'content.display.custom_label'
            ],
            [
                'accepts' => 'string', 
                'path' => 'content.display.link_text'
            ]
        ];
    }

    /**
     * Classi aggiuntive - definisce classi CSS aggiuntive
     */
    static function additionalClasses()
    {
        return false;
    }

    /**
     * Gestione del progetto - impostazioni per la gestione del progetto
     */
    static function projectManagement()
    {
        return false;
    }

    /**
     * Percorsi di proprietà da includere nella whitelist in flatProps
     */
    static function propertyPathsToWhitelistInFlatProps()
    {
        return [
            'content.field.field_id',
            'content.field.is_cloneable',
            'content.display.show_label',
            'content.display.custom_label',
            'content.display.link_text',
            'content.display.target',
            'content.display.add_nofollow',
            'content.layout.list_type',
            'content.layout.max_items'
        ];
    }

    /**
     * Percorsi di proprietà che richiedono il rendering SSR quando il valore cambia
     */
    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return [
            'content.field.field_id',
            'content.field.is_cloneable',
            'content.display.show_label',
            'content.display.custom_label',
            'content.display.link_text',
            'content.display.target',
            'content.display.add_nofollow',
            'content.layout.list_type',
            'content.layout.max_items'
        ];
    }

    static function controls()
    {
        return [
            'content' => [
                'field' => [
                    'label' => esc_html__('Field Settings', 'aifb-breakdance-elements'),
                    'type' => 'section',
                    'layout' => 'vertical',
                    'controls' => [
                        'field_id' => [
                            'label' => esc_html__('Field ID', 'aifb-breakdance-elements'),
                            'type' => 'dropdown',
                            'options' => 'function:getMetaBoxFields',
                            'searchable' => true,
                            'placeholder' => esc_html__('Select a field', 'aifb-breakdance-elements'),
                        ],
                        'is_cloneable' => [
                            'label' => esc_html__('Is Cloneable Field?', 'aifb-breakdance-elements'),
                            'type' => 'toggle',
                            'defaultValue' => false,
                        ],
                    ],
                ],
                'display' => [
                    'label' => esc_html__('Display Settings', 'aifb-breakdance-elements'),
                    'type' => 'section',
                    'layout' => 'vertical',
                    'controls' => [
                        'show_label' => [
                            'label' => esc_html__('Show Field Label', 'aifb-breakdance-elements'),
                            'type' => 'toggle',
                            'defaultValue' => true,
                        ],
                        'custom_label' => [
                            'label' => esc_html__('Custom Label', 'aifb-breakdance-elements'),
                            'type' => 'text',
                            'placeholder' => esc_html__('Leave empty to use field label', 'aifb-breakdance-elements'),
                            'condition' => [
                                'path' => 'content.display.show_label',
                                'operand' => 'equals',
                                'value' => true,
                            ],
                        ],
                        'link_text' => [
                            'label' => esc_html__('Link Text', 'aifb-breakdance-elements'),
                            'type' => 'text',
                            'placeholder' => esc_html__('Leave empty to use URL as text', 'aifb-breakdance-elements'),
                        ],
                        'target' => [
                            'label' => esc_html__('Open in', 'aifb-breakdance-elements'),
                            'type' => 'dropdown',
                            'options' => [
                                ['text' => 'Same Tab', 'value' => '_self'],
                                ['text' => 'New Tab', 'value' => '_blank'],
                            ],
                            'defaultValue' => '_self',
                        ],
                        'add_nofollow' => [
                            'label' => esc_html__('Add nofollow', 'aifb-breakdance-elements'),
                            'type' => 'toggle',
                            'defaultValue' => false,
                        ],
                    ],
                ],
                'layout' => [
                    'label' => esc_html__('Layout', 'aifb-breakdance-elements'),
                    'type' => 'section',
                    'layout' => 'vertical',
                    'controls' => [
                        'list_type' => [
                            'label' => esc_html__('List Type', 'aifb-breakdance-elements'),
                            'type' => 'dropdown',
                            'options' => [
                                ['text' => 'None', 'value' => 'none'],
                                ['text' => 'Unordered List', 'value' => 'ul'],
                                ['text' => 'Ordered List', 'value' => 'ol'],
                                ['text' => 'Comma Separated', 'value' => 'comma'],
                            ],
                            'defaultValue' => 'none',
                            'condition' => [
                                'path' => 'content.field.is_cloneable',
                                'operand' => 'equals',
                                'value' => true,
                            ],
                        ],
                        'max_items' => [
                            'label' => esc_html__('Max Items', 'aifb-breakdance-elements'),
                            'type' => 'number',
                            'placeholder' => esc_html__('Leave empty to show all', 'aifb-breakdance-elements'),
                            'condition' => [
                                'path' => 'content.field.is_cloneable',
                                'operand' => 'equals',
                                'value' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    static function designControls()
    {
        return [
            c(
                "typography",
                esc_html__("Typography", 'aifb-breakdance-metabox'),
                [
                    c(
                        "label",
                        esc_html__("Label", 'aifb-breakdance-metabox'),
                        [
                            c("typography", esc_html__("Typography", 'aifb-breakdance-metabox'), [], ['type' => 'typography'], false, false, []),
                            c("color", esc_html__("Color", 'aifb-breakdance-metabox'), [], ['type' => 'color'], false, false, []),
                            c("margin", esc_html__("Margin", 'aifb-breakdance-metabox'), [], ['type' => 'unit'], false, false, []),
                        ],
                        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
                        false,
                        false,
                        []
                    ),
                    c(
                        "link",
                        esc_html__("Link", 'aifb-breakdance-metabox'),
                        [
                            c("typography", esc_html__("Typography", 'aifb-breakdance-metabox'), [], ['type' => 'typography'], false, false, []),
                            c("color", esc_html__("Color", 'aifb-breakdance-metabox'), [], ['type' => 'color'], false, false, []),
                            c("hover_color", esc_html__("Hover Color", 'aifb-breakdance-metabox'), [], ['type' => 'color'], false, true, []),
                        ],
                        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
                        false,
                        false,
                        []
                    ),
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "list",
                esc_html__("List", 'aifb-breakdance-metabox'),
                [
                    c("spacing", esc_html__("Item Spacing", 'aifb-breakdance-metabox'), [], ['type' => 'unit'], false, false, []),
                    c("list_style", esc_html__("List Style", 'aifb-breakdance-metabox'), [], ['type' => 'dropdown', 'items' => [
                        ['text' => 'Default', 'value' => 'default'],
                        ['text' => 'None', 'value' => 'none'],
                        ['text' => 'Disc', 'value' => 'disc'],
                        ['text' => 'Circle', 'value' => 'circle'],
                        ['text' => 'Square', 'value' => 'square'],
                        ['text' => 'Decimal', 'value' => 'decimal'],
                        ['text' => 'Decimal Leading Zero', 'value' => 'decimal-leading-zero'],
                        ['text' => 'Lower Roman', 'value' => 'lower-roman'],
                        ['text' => 'Upper Roman', 'value' => 'upper-roman'],
                        ['text' => 'Lower Alpha', 'value' => 'lower-alpha'],
                        ['text' => 'Upper Alpha', 'value' => 'upper-alpha'],
                    ]], false, false, []),
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
            c(
                "container",
                esc_html__("Container", 'aifb-breakdance-metabox'),
                [
                    c("margin", esc_html__("Margin", 'aifb-breakdance-metabox'), [], ['type' => 'unit'], false, false, []),
                    c("padding", esc_html__("Padding", 'aifb-breakdance-metabox'), [], ['type' => 'unit'], false, false, []),
                ],
                ['type' => 'section'],
                false,
                false,
                []
            ),
        ];
    }

    public function render($props, $breakpoints, $inBuilder)
    {
        // Limita l'esecuzione nel builder per evitare blocchi
        $isInBuilder = defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING;
        
        $fieldId = $props['content']['field']['field_id'] ?? '';
        $isCloneable = $props['content']['field']['is_cloneable'] ?? false;
        $showLabel = $props['content']['display']['show_label'] ?? true;
        $customLabel = $props['content']['display']['custom_label'] ?? '';
        $linkText = $props['content']['display']['link_text'] ?? '';
        $target = $props['content']['display']['target'] ?? '_self';
        $addNofollow = $props['content']['display']['add_nofollow'] ?? false;
        $listType = $props['content']['layout']['list_type'] ?? 'none';
        $maxItems = $props['content']['layout']['max_items'] ?? 0;
        
        if (empty($fieldId)) {
            return '<div class="aifb-metabox-field-error">Please select a Meta Box field.</div>';
        }
        
        // Ottieni l'istanza di MetaBoxIntegration una sola volta
        static $metaBoxIntegration = null;
        if ($metaBoxIntegration === null) {
            $metaBoxIntegration = \AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration::getInstance();
        }
        
        // Verifica se il campo è clonabile (se non specificato manualmente)
        if (!isset($props['content']['field']['is_cloneable'])) {
            $isCloneable = $metaBoxIntegration->isFieldCloneable($fieldId);
        }
        
        // Get field label
        $fieldLabel = '';
        if ($showLabel) {
            $fieldLabel = !empty($customLabel) ? $customLabel : $this->getFieldLabel($fieldId);
        }
        
        // Inizia l'output
        ob_start();
        
        echo '<div class="aifb-metabox-field">';
        
        // Mostra l'etichetta se richiesto
        if ($showLabel && !empty($fieldLabel)) {
            echo '<div class="aifb-metabox-field-label">' . esc_html($fieldLabel) . '</div>';
        }
        
        // Gestisci i diversi tipi di layout per campi clonabili
        if ($isCloneable) {
            // Funzione di callback per renderizzare un elemento
            $renderItem = function($value, $index) use ($linkText, $target, $addNofollow) {
                ob_start();
                $this->renderLink($value, $linkText, $target, $addNofollow);
                return ob_get_clean();
            };
            
            // Ottieni i risultati dell'iterazione
            $items = $this->iterateCloneableField($fieldId, $renderItem);
            
            // Limita il numero di elementi se specificato
            if ($maxItems > 0 && count($items) > $maxItems) {
                $items = array_slice($items, 0, $maxItems);
            }
            
            // Se siamo nel builder, limita il numero di elementi per evitare blocchi
            if ($isInBuilder && count($items) > 3) {
                $items = array_slice($items, 0, 3);
            }
            
            // Renderizza gli elementi in base al tipo di lista
            switch ($listType) {
                case 'ul':
                    echo '<ul class="aifb-metabox-field-list">';
                    foreach ($items as $item) {
                        echo '<li>' . $item . '</li>';
                    }
                    echo '</ul>';
                    break;
                    
                case 'ol':
                    echo '<ol class="aifb-metabox-field-list">';
                    foreach ($items as $item) {
                        echo '<li>' . $item . '</li>';
                    }
                    echo '</ol>';
                    break;
                    
                case 'comma':
                    echo '<div class="aifb-metabox-field-comma">';
                    echo implode(', ', $items);
                    echo '</div>';
                    break;
                    
                default:
                    echo '<div class="aifb-metabox-field-items">';
                    foreach ($items as $item) {
                        echo '<div class="aifb-metabox-field-item">' . $item . '</div>';
                    }
                    echo '</div>';
                    break;
            }
        } else {
            // Campo singolo
            echo '<div class="aifb-metabox-field-single">';
            $value = $this->getFieldValue($fieldId, false);
            $this->renderLink($value, $linkText, $target, $addNofollow);
            echo '</div>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }
    
    /**
     * Renderizza un link
     * 
     * @param string $url URL del link
     * @param string $text Testo del link (opzionale)
     * @param string $target Target del link
     * @param bool $addNofollow Aggiungere nofollow
     */
    private function renderLink($url, $text, $target, $addNofollow)
    {
        if (empty($url)) {
            echo '<span class="aifb-metabox-field-empty">No value</span>';
            return;
        }
        
        $rel = $addNofollow ? ' rel="nofollow"' : '';
        $linkText = !empty($text) ? esc_html($text) : esc_url($url);
        
        echo '<a href="' . esc_url($url) . '" target="' . esc_attr($target) . '"' . $rel . '>' . $linkText . '</a>';
    }

    public function getFieldValue($fieldId, $isCloneable)
    {
        // Ottieni l'istanza di MetaBoxIntegration una sola volta
        static $metaBoxIntegration = null;
        if ($metaBoxIntegration === null) {
            $metaBoxIntegration = \AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration::getInstance();
        }
        
        // Se siamo nel builder, restituiamo valori di esempio
        if (defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING) {
            if ($isCloneable) {
                return new \AIFB\BreakdanceMetaBox\Core\MetaBoxRepeaterData([
                    'https://example.com/link1',
                    'https://example.com/link2',
                    'https://example.com/link3'
                ]);
            } else {
                return 'https://example.com';
            }
        }
        
        // Ottieni il valore del campo
        $value = $metaBoxIntegration->getFieldValue($fieldId);
        
        // Gestisci i campi clonabili
        if ($isCloneable && !is_array($value)) {
            // Se il campo è clonabile ma il valore non è un array, lo convertiamo in array
            return new \AIFB\BreakdanceMetaBox\Core\MetaBoxRepeaterData([$value]);
        }
        
        // Gestisci i campi clonabili con array
        if ($isCloneable && is_array($value)) {
            return new \AIFB\BreakdanceMetaBox\Core\MetaBoxRepeaterData($value);
        }
        
        // Gestisci i campi non clonabili
        if (!$isCloneable && is_array($value)) {
            // Se il campo non è clonabile ma il valore è un array, restituiamo il primo elemento
            return isset($value[0]) ? $value[0] : '';
        }
        
        // Gestisci i valori vuoti
        if (empty($value)) {
            return $isCloneable ? new \AIFB\BreakdanceMetaBox\Core\MetaBoxRepeaterData([]) : '';
        }
        
        return $value;
    }

    public function getFieldLabel($fieldId)
    {
        // Ottieni l'istanza di MetaBoxIntegration una sola volta
        static $metaBoxIntegration = null;
        if ($metaBoxIntegration === null) {
            $metaBoxIntegration = \AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration::getInstance();
        }
        
        // Ottieni tutti i campi disponibili
        $fields = $metaBoxIntegration->getAvailableFields();
        
        // Cerca il campo e restituisci il nome
        if (isset($fields[$fieldId])) {
            return $fields[$fieldId]['name'] ?? $fieldId;
        }
        
        return $fieldId;
    }

    public static function getMetaBoxFields()
    {
        // Utilizzo una cache statica per evitare chiamate ripetute
        static $cachedFields = null;
        
        if ($cachedFields !== null) {
            return $cachedFields;
        }
        
        // Ottieni l'istanza di MetaBoxIntegration
        $metaBoxIntegration = \AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration::getInstance();
        
        // Ottieni tutti i campi disponibili
        $fields = $metaBoxIntegration->getAvailableFields();
        
        if (empty($fields)) {
            $cachedFields = [
                ['text' => 'No MetaBox fields found', 'value' => '']
            ];
            return $cachedFields;
        }
        
        $result = [];
        
        // Filtra solo i campi URL e text
        foreach ($fields as $field) {
            if (isset($field['type']) && ($field['type'] === 'url' || $field['type'] === 'text')) {
                $isCloneable = $field['is_cloneable'] ?? false;
                $cloneableText = $isCloneable ? ' (cloneable)' : '';
                $result[] = [
                    'text' => $field['name'] . ' (' . $field['type'] . ')' . $cloneableText,
                    'value' => $field['id']
                ];
            }
        }
        
        if (empty($result)) {
            $cachedFields = [
                ['text' => 'No URL or text fields found', 'value' => '']
            ];
            return $cachedFields;
        }
        
        $cachedFields = $result;
        return $cachedFields;
    }

    /**
     * Definisce le dipendenze dell'elemento
     */
    static function dependencies()
    {
        return [
            '0' => [
                'title' => 'MetaBox Field',
                'styles' => ['%%BREAKDANCE_ELEMENTS_PLUGIN_URL%%elements/metabox/default.css'],
            ]
        ];
    }

    /**
     * Definisce le impostazioni dell'elemento
     */
    static function settings()
    {
        return [
            'proOnly' => false,
            'requiredPlugins' => ['MetaBox']
        ];
    }

    /**
     * Definisce le regole del pannello
     */
    static function addPanelRules()
    {
        return false;
    }

    /**
     * Definisce le azioni JavaScript dell'elemento
     */
    static public function actions()
    {
        return [
            'onMountedElement' => [
                [
                    'script' => '
                    // Inizializzazione dell\'elemento MetaBox Field
                    console.log("MetaBox Field mounted", { 
                        id: "%%ID%%", 
                        selector: "%%SELECTOR%%"
                    });
                    '
                ]
            ],
            'onPropertyChange' => [
                [
                    'script' => '
                    // Aggiornamento dell\'elemento quando cambiano le proprietà
                    console.log("MetaBox Field property changed", { 
                        id: "%%ID%%", 
                        selector: "%%SELECTOR%%"
                    });
                    '
                ]
            ],
            'onBeforeDeletingElement' => [
                [
                    'script' => '
                    // Pulizia prima della rimozione dell\'elemento
                    console.log("MetaBox Field will be deleted", { 
                        id: "%%ID%%", 
                        selector: "%%SELECTOR%%"
                    });
                    '
                ]
            ]
        ];
    }

    /**
     * Itera su un campo clonabile e applica una funzione di callback
     * 
     * @param string $fieldId ID del campo
     * @param callable $callback Funzione di callback
     * @return array Risultati dell'iterazione
     */
    public function iterateCloneableField(string $fieldId, callable $callback): array {
        // Ottieni l'istanza di MetaBoxIntegration una sola volta
        static $metaBoxIntegration = null;
        if ($metaBoxIntegration === null) {
            $metaBoxIntegration = \AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration::getInstance();
        }
        
        return $metaBoxIntegration->iterateCloneableField($fieldId, $callback);
    }
    
    /**
     * Ottiene un elemento specifico di un campo clonabile
     * 
     * @param string $fieldId ID del campo
     * @param int $index Indice dell'elemento
     * @return mixed Valore dell'elemento
     */
    public function getCloneableFieldItem(string $fieldId, int $index): mixed {
        // Ottieni l'istanza di MetaBoxIntegration una sola volta
        static $metaBoxIntegration = null;
        if ($metaBoxIntegration === null) {
            $metaBoxIntegration = \AIFB\BreakdanceMetaBox\Core\MetaBoxIntegration::getInstance();
        }
        
        return $metaBoxIntegration->getCloneableFieldItem($fieldId, $index);
    }
} 