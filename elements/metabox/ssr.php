<?php

use function Breakdance\Util\WP\isBuilder;

/**
 * @param array $propertiesData
 * @return string
 */
function renderMetaBoxField($propertiesData)
{
    try {
        // Extract settings
        $fieldSettings = $propertiesData['content']['field'] ?? [];
        $displaySettings = $propertiesData['content']['display'] ?? [];
        $advancedSettings = $propertiesData['content']['advanced'] ?? [];
        $conditionalSettings = $propertiesData['content']['conditional'] ?? [];
        $layoutSettings = $propertiesData['content']['layout'] ?? [];
        
        // Field settings
        $fieldId = $fieldSettings['field_id'] ?? '';
        $isCloneable = $fieldSettings['is_cloneable'] ?? false;
        $fieldType = $fieldSettings['field_type'] ?? 'text';
        
        // Display settings
        $showLabel = $displaySettings['show_label'] ?? true;
        $customLabel = $displaySettings['custom_label'] ?? '';
        $linkSettings = $displaySettings['link'] ?? [];
        
        // Advanced settings
        $fallbackContent = $advancedSettings['fallback_content'] ?? '';
        $cacheDuration = intval($advancedSettings['cache_duration'] ?? 300);
        
        // Conditional display settings
        $enableConditional = $conditionalSettings['enable_conditional'] ?? false;
        $conditionField = $conditionalSettings['condition_field'] ?? '';
        $conditionType = $conditionalSettings['condition_type'] ?? 'equals';
        $conditionValue = $conditionalSettings['condition_value'] ?? '';
        
        // Layout settings
        $layoutStyle = $layoutSettings['style'] ?? 'default';
        $alignment = $layoutSettings['alignment'] ?? 'left';
        
        // Generate a unique cache key for this instance
        $cacheKey = 'aifb_metabox_field_' . md5(json_encode($propertiesData) . get_the_ID());
        
        // Check if we have cached output
        $cachedOutput = get_transient($cacheKey);
        if ($cachedOutput !== false && !isBuilder()) {
            return $cachedOutput;
        }
        
        // If conditional display is enabled, check the condition
        if ($enableConditional && !empty($conditionField) && !empty($conditionValue)) {
            $metaValue = get_post_meta(get_the_ID(), $conditionField, true);
            
            $conditionMet = false;
            switch ($conditionType) {
                case 'equals':
                    $conditionMet = ($metaValue == $conditionValue);
                    break;
                case 'not_equals':
                    $conditionMet = ($metaValue != $conditionValue);
                    break;
                case 'contains':
                    $conditionMet = (strpos($metaValue, $conditionValue) !== false);
                    break;
                case 'not_contains':
                    $conditionMet = (strpos($metaValue, $conditionValue) === false);
                    break;
                case 'greater_than':
                    $conditionMet = ($metaValue > $conditionValue);
                    break;
                case 'less_than':
                    $conditionMet = ($metaValue < $conditionValue);
                    break;
                case 'is_empty':
                    $conditionMet = empty($metaValue);
                    break;
                case 'is_not_empty':
                    $conditionMet = !empty($metaValue);
                    break;
            }
            
            if (!$conditionMet) {
                return ''; // Don't render anything if condition is not met
            }
        }
        
        // If no field ID is provided, show a notice in builder mode
        if (empty($fieldId)) {
            if (isBuilder()) {
                return \Breakdance\Render\render(
                    __DIR__ . '/html.twig',
                    [
                        'is_builder' => true,
                        'field_id' => '',
                    ]
                );
            }
            return '';
        }
        
        // Get the field value
        $fieldValue = get_post_meta(get_the_ID(), $fieldId, true);
        
        // If the field is empty, show fallback content or a notice
        if (empty($fieldValue)) {
            if (!empty($fallbackContent)) {
                return \Breakdance\Render\render(
                    __DIR__ . '/html.twig',
                    [
                        'fallback_content' => $fallbackContent,
                    ]
                );
            } elseif (isBuilder()) {
                return \Breakdance\Render\render(
                    __DIR__ . '/html.twig',
                    [
                        'is_builder' => true,
                        'field_id' => $fieldId,
                    ]
                );
            }
            return '';
        }
        
        // Get the field label
        $label = '';
        if ($showLabel) {
            if (!empty($customLabel)) {
                $label = $customLabel;
            } else {
                // Try to get the field label from MetaBox
                $label = getMetaBoxFieldLabel($fieldId);
            }
        }
        
        // Format the field value based on field type
        $content = formatFieldValue($fieldValue, $fieldType, $isCloneable, $linkSettings);
        
        // Set layout classes
        $layoutClass = 'aifb-metabox-layout-' . $layoutStyle;
        $alignmentClass = 'aifb-metabox-align-' . $alignment;
        
        // Render the template
        $output = \Breakdance\Render\render(
            __DIR__ . '/html.twig',
            [
                'content' => $content,
                'label' => $label,
                'show_label' => $showLabel,
                'layout_class' => $layoutClass,
                'alignment_class' => $alignmentClass,
            ]
        );
        
        // Cache the output if not in builder mode
        if (!isBuilder() && $cacheDuration > 0) {
            set_transient($cacheKey, $output, $cacheDuration);
        }
        
        return $output;
    } catch (\Exception $e) {
        // Log the error
        error_log('AIFB MetaBox Field Error: ' . $e->getMessage());
        
        // Show error in builder mode
        if (isBuilder()) {
            return '<div class="aifb-metabox-field-error">Error: ' . esc_html($e->getMessage()) . '</div>';
        }
        
        return '';
    }
}

/**
 * Get the label of a MetaBox field
 *
 * @param string $fieldId
 * @return string
 */
function getMetaBoxFieldLabel($fieldId)
{
    // This is a simplified implementation
    // In a real scenario, you would query MetaBox API to get the field label
    $fieldParts = explode('.', $fieldId);
    $fieldName = end($fieldParts);
    
    return ucfirst(str_replace('_', ' ', $fieldName));
}

/**
 * Format the field value based on field type
 *
 * @param mixed $value
 * @param string $fieldType
 * @param bool $isCloneable
 * @param array $linkSettings
 * @return string
 */
function formatFieldValue($value, $fieldType, $isCloneable, $linkSettings = [])
{
    $output = '';
    
    // Handle cloneable fields
    if ($isCloneable && is_array($value)) {
        $items = [];
        foreach ($value as $item) {
            $items[] = formatSingleValue($item, $fieldType, $linkSettings);
        }
        
        if (!empty($items)) {
            $output = '<ul class="aifb-metabox-cloneable-list">';
            foreach ($items as $item) {
                $output .= '<li>' . $item . '</li>';
            }
            $output .= '</ul>';
        }
    } else {
        $output = formatSingleValue($value, $fieldType, $linkSettings);
    }
    
    return $output;
}

/**
 * Format a single field value
 *
 * @param mixed $value
 * @param string $fieldType
 * @param array $linkSettings
 * @return string
 */
function formatSingleValue($value, $fieldType, $linkSettings = [])
{
    $output = '';
    
    switch ($fieldType) {
        case 'image':
            if (is_numeric($value)) {
                $imageUrl = wp_get_attachment_image_url($value, 'full');
                if ($imageUrl) {
                    $output = '<img src="' . esc_url($imageUrl) . '" alt="" class="aifb-metabox-image">';
                }
            } elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                $output = '<img src="' . esc_url($value) . '" alt="" class="aifb-metabox-image">';
            }
            break;
            
        case 'file':
            if (is_numeric($value)) {
                $fileUrl = wp_get_attachment_url($value);
                $fileName = get_the_title($value);
                if ($fileUrl) {
                    $output = '<a href="' . esc_url($fileUrl) . '" class="aifb-metabox-file" download>' . esc_html($fileName) . '</a>';
                }
            } elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                $fileName = basename($value);
                $output = '<a href="' . esc_url($value) . '" class="aifb-metabox-file" download>' . esc_html($fileName) . '</a>';
            }
            break;
            
        case 'wysiwyg':
            $output = wpautop($value);
            break;
            
        case 'textarea':
            $output = nl2br(esc_html($value));
            break;
            
        case 'date':
            if (is_string($value) && !empty($value)) {
                $timestamp = strtotime($value);
                if ($timestamp) {
                    $output = date_i18n(get_option('date_format'), $timestamp);
                } else {
                    $output = esc_html($value);
                }
            }
            break;
            
        case 'checkbox':
            if (is_array($value)) {
                $items = array_map('esc_html', $value);
                $output = implode(', ', $items);
            } elseif (is_string($value) || is_numeric($value)) {
                $output = esc_html($value);
            } elseif (is_bool($value)) {
                $output = $value ? __('Yes', 'aifb-breakdance-elements') : __('No', 'aifb-breakdance-elements');
            }
            break;
            
        case 'select':
        case 'radio':
            $output = esc_html($value);
            break;
            
        case 'url':
            $url = esc_url($value);
            $linkText = !empty($linkSettings['text']) ? $linkSettings['text'] : $url;
            $target = !empty($linkSettings['target']) ? ' target="' . esc_attr($linkSettings['target']) . '"' : '';
            $rel = !empty($linkSettings['rel']) ? ' rel="' . esc_attr($linkSettings['rel']) . '"' : '';
            
            $output = '<a href="' . $url . '"' . $target . $rel . ' class="aifb-metabox-link">' . esc_html($linkText) . '</a>';
            break;
            
        case 'email':
            $email = sanitize_email($value);
            $linkText = !empty($linkSettings['text']) ? $linkSettings['text'] : $email;
            
            $output = '<a href="mailto:' . $email . '" class="aifb-metabox-link">' . esc_html($linkText) . '</a>';
            break;
            
        case 'text':
        default:
            if (is_array($value)) {
                $output = esc_html(json_encode($value));
            } elseif (is_object($value)) {
                $output = esc_html(json_encode($value));
            } else {
                $output = esc_html($value);
            }
            
            // Apply link if specified
            if (!empty($linkSettings['url'])) {
                $url = esc_url($linkSettings['url']);
                $target = !empty($linkSettings['target']) ? ' target="' . esc_attr($linkSettings['target']) . '"' : '';
                $rel = !empty($linkSettings['rel']) ? ' rel="' . esc_attr($linkSettings['rel']) . '"' : '';
                
                $output = '<a href="' . $url . '"' . $target . $rel . ' class="aifb-metabox-link">' . $output . '</a>';
            }
            break;
    }
    
    return $output;
}

/**
 * Get all MetaBox fields for the current post type
 *
 * @return array
 */
function getMetaBoxFields()
{
    $fields = [];
    
    // This is a simplified implementation
    // In a real scenario, you would query MetaBox API to get all fields
    
    // For now, return some example fields
    $fields = [
        ['text' => 'Select a field', 'value' => ''],
        ['text' => 'Title', 'value' => 'title'],
        ['text' => 'Description', 'value' => 'description'],
        ['text' => 'Price', 'value' => 'price'],
        ['text' => 'Status', 'value' => 'status'],
    ];
    
    return $fields;
}