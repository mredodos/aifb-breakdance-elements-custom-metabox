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
        return 'Meta Box URL Field';
    }

    static function slug()
    {
        return 'aifb-metabox-url-field';
    }

    static function tag()
    {
        return 'div';
    }

    static function category()
    {
        return 'dynamic';
    }

    static function icon()
    {
        return 'DatabaseIcon';
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
        
        // Ottieni l'istanza di MetaBoxIntegration
        $metaBoxIntegration = MetaBoxIntegration::getInstance();
        
        // Verifica se il campo è clonabile (se non specificato manualmente)
        if (!isset($props['content']['field']['is_cloneable'])) {
            $isCloneable = $metaBoxIntegration->isFieldCloneable($fieldId);
        }
        
        // Get field label
        $fieldLabel = '';
        if ($showLabel) {
            $fieldLabel = !empty($customLabel) ? $customLabel : $this->getFieldLabel($fieldId);
        }
        
        // Get field value(s)
        $values = $this->getFieldValue($fieldId, $isCloneable);
        
        if (empty($values)) {
            return '<div class="aifb-metabox-field-empty">No value found for this field.</div>';
        }
        
        // Start output
        ob_start();
        
        echo '<div class="aifb-metabox-field">';
        
        // Show label if needed
        if ($showLabel && !empty($fieldLabel)) {
            echo '<div class="aifb-metabox-field-label">' . esc_html($fieldLabel) . '</div>';
        }
        
        // Handle single value
        if (!$isCloneable || !is_array($values)) {
            $rel = $addNofollow ? ' rel="nofollow"' : '';
            $displayText = !empty($linkText) ? $linkText : $values;
            echo '<a href="' . esc_url($values) . '" target="' . esc_attr($target) . '"' . $rel . ' class="aifb-metabox-field-link">' . esc_html($displayText) . '</a>';
        } 
        // Handle multiple values (cloneable field)
        else {
            // Limit items if max_items is set
            if ($maxItems > 0 && count($values) > $maxItems) {
                $values = array_slice($values, 0, $maxItems);
            }
            
            // Determine list type
            switch ($listType) {
                case 'ul':
                    echo '<ul class="aifb-metabox-field-list">';
                    foreach ($values as $value) {
                        if (!empty($value)) {
                            $rel = $addNofollow ? ' rel="nofollow"' : '';
                            $displayText = !empty($linkText) ? $linkText : $value;
                            echo '<li><a href="' . esc_url($value) . '" target="' . esc_attr($target) . '"' . $rel . ' class="aifb-metabox-field-link">' . esc_html($displayText) . '</a></li>';
                        }
                    }
                    echo '</ul>';
                    break;
                    
                case 'ol':
                    echo '<ol class="aifb-metabox-field-list">';
                    foreach ($values as $value) {
                        if (!empty($value)) {
                            $rel = $addNofollow ? ' rel="nofollow"' : '';
                            $displayText = !empty($linkText) ? $linkText : $value;
                            echo '<li><a href="' . esc_url($value) . '" target="' . esc_attr($target) . '"' . $rel . ' class="aifb-metabox-field-link">' . esc_html($displayText) . '</a></li>';
                        }
                    }
                    echo '</ol>';
                    break;
                    
                case 'comma':
                    $links = [];
                    foreach ($values as $value) {
                        if (!empty($value)) {
                            $rel = $addNofollow ? ' rel="nofollow"' : '';
                            $displayText = !empty($linkText) ? $linkText : $value;
                            $links[] = '<a href="' . esc_url($value) . '" target="' . esc_attr($target) . '"' . $rel . ' class="aifb-metabox-field-link">' . esc_html($displayText) . '</a>';
                        }
                    }
                    echo '<div class="aifb-metabox-field-comma">' . implode(', ', $links) . '</div>';
                    break;
                    
                default: // 'none'
                    echo '<div class="aifb-metabox-field-links">';
                    foreach ($values as $value) {
                        if (!empty($value)) {
                            $rel = $addNofollow ? ' rel="nofollow"' : '';
                            $displayText = !empty($linkText) ? $linkText : $value;
                            echo '<div class="aifb-metabox-field-link-item"><a href="' . esc_url($value) . '" target="' . esc_attr($target) . '"' . $rel . ' class="aifb-metabox-field-link">' . esc_html($displayText) . '</a></div>';
                        }
                    }
                    echo '</div>';
                    break;
            }
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    public function getFieldValue($fieldId, $isCloneable)
    {
        // Ottieni l'istanza di MetaBoxIntegration
        $metaBoxIntegration = MetaBoxIntegration::getInstance();
        
        // Se siamo nel builder, restituiamo valori di esempio
        if (defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING) {
            if ($isCloneable) {
                return [
                    'https://example.com/link1',
                    'https://example.com/link2',
                    'https://example.com/link3'
                ];
            } else {
                return 'https://example.com';
            }
        }
        
        // Ottieni il valore del campo
        $value = $metaBoxIntegration->getFieldValue($fieldId);
        
        // Per i campi clonabili, assicuriamoci che il valore sia un array
        if ($isCloneable && !is_array($value)) {
            if (empty($value)) {
                return [];
            }
            return [$value];
        }
        
        return $value;
    }

    public function getFieldLabel($fieldId)
    {
        // Ottieni l'istanza di MetaBoxIntegration
        $metaBoxIntegration = MetaBoxIntegration::getInstance();
        
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
        // Ottieni l'istanza di MetaBoxIntegration
        $metaBoxIntegration = MetaBoxIntegration::getInstance();
        
        // Ottieni tutti i campi disponibili
        $fields = $metaBoxIntegration->getAvailableFields();
        
        if (empty($fields)) {
            return [
                ['text' => 'No MetaBox fields found', 'value' => '']
            ];
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
            return [
                ['text' => 'No URL or text fields found', 'value' => '']
            ];
        }
        
        return $result;
    }
} 