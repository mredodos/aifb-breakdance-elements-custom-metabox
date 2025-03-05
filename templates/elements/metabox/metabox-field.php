<?php

namespace AIFB\BreakdanceMetaBox\Elements;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;

/**
 * @class MetaBoxField
 */
class MetaBoxField extends \Breakdance\Elements\Element
{
    static function name()
    {
        return 'Meta Box Field';
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
        return 'dynamic';
    }

    static function icon()
    {
        return 'DatabaseIcon';
    }

    static function label()
    {
        return esc_html__('Meta Box Field', 'aifb-breakdance-metabox');
    }

    static function controls()
    {
        return [
            c(
                "content",
                esc_html__("Content", 'aifb-breakdance-metabox'),
                [
                    c(
                        "field_settings",
                        esc_html__("Field Settings", 'aifb-breakdance-metabox'),
                        [
                            c(
                                "field_id",
                                esc_html__("Field ID", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'dropdown'],
                                false,
                                false,
                                [],
                                [
                                    'layout' => 'vertical',
                                    'items' => function () {
                                        return self::getMetaBoxFields();
                                    },
                                ]
                            ),
                            c(
                                "is_clonable",
                                esc_html__("Is Clonable Field", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'toggle'],
                                false,
                                false,
                                [],
                                ['layout' => 'inline']
                            ),
                            c(
                                "field_type",
                                esc_html__("Field Type", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'dropdown'],
                                false,
                                false,
                                [],
                                [
                                    'layout' => 'vertical',
                                    'items' => [
                                        ['text' => 'URL', 'value' => 'url'],
                                        ['text' => 'Text', 'value' => 'text'],
                                        ['text' => 'Image', 'value' => 'image'],
                                        ['text' => 'File', 'value' => 'file'],
                                        ['text' => 'Video', 'value' => 'video'],
                                    ],
                                ]
                            ),
                        ],
                        ['type' => 'section'],
                        false,
                        false,
                        [],
                        ['layout' => 'vertical']
                    ),
                    c(
                        "display_settings",
                        esc_html__("Display Settings", 'aifb-breakdance-metabox'),
                        [
                            c(
                                "show_label",
                                esc_html__("Show Field Label", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'toggle'],
                                false,
                                false,
                                [],
                                ['layout' => 'inline']
                            ),
                            c(
                                "custom_label",
                                esc_html__("Custom Label", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'text'],
                                false,
                                false,
                                [],
                                [
                                    'layout' => 'vertical',
                                    'condition' => [
                                        'path' => 'content.display_settings.show_label',
                                        'operand' => 'is set',
                                        'value' => true
                                    ]
                                ]
                            ),
                            c(
                                "link_settings",
                                esc_html__("Link Settings", 'aifb-breakdance-metabox'),
                                [
                                    c(
                                        "link_text",
                                        esc_html__("Link Text", 'aifb-breakdance-metabox'),
                                        [],
                                        ['type' => 'text'],
                                        false,
                                        false,
                                        [],
                                        [
                                            'layout' => 'vertical',
                                            'placeholder' => 'Read More'
                                        ]
                                    ),
                                    c(
                                        "open_in_new_tab",
                                        esc_html__("Open in New Tab", 'aifb-breakdance-metabox'),
                                        [],
                                        ['type' => 'toggle'],
                                        false,
                                        false,
                                        [],
                                        ['layout' => 'inline']
                                    ),
                                    c(
                                        "add_nofollow",
                                        esc_html__("Add Nofollow", 'aifb-breakdance-metabox'),
                                        [],
                                        ['type' => 'toggle'],
                                        false,
                                        false,
                                        [],
                                        ['layout' => 'inline']
                                    ),
                                ],
                                ['type' => 'section'],
                                false,
                                false,
                                [],
                                [
                                    'condition' => [
                                        'path' => 'content.field_settings.field_type',
                                        'operand' => 'equals',
                                        'value' => 'url'
                                    ],
                                    'sectionOptions' => [
                                        'type' => 'popout'
                                    ]
                                ]
                            ),
                        ],
                        ['type' => 'section'],
                        false,
                        false,
                        [],
                        ['layout' => 'vertical']
                    ),
                    c(
                        "advanced_settings",
                        esc_html__("Advanced Settings", 'aifb-breakdance-metabox'),
                        [
                            c(
                                "fallback",
                                esc_html__("Fallback Content", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'text'],
                                false,
                                false,
                                [],
                                [
                                    'layout' => 'vertical',
                                    'placeholder' => 'No content available'
                                ]
                            ),
                        ],
                        ['type' => 'section'],
                        false,
                        false,
                        [],
                        ['layout' => 'vertical']
                    ),
                ],
                ['type' => 'section'],
                false,
                false,
                [],
                ['layout' => 'vertical']
            ),
        ];
    }

    static function designControls()
    {
        return [
            c(
                "typography",
                esc_html__("Typography", 'aifb-breakdance-metabox'),
                [
                    getPresetSection("EssentialElements\\typography_with_effects", "Label", "label", ['type' => 'popout']),
                    getPresetSection("EssentialElements\\typography_with_effects", "Content", "content", ['type' => 'popout']),
                ],
                ['type' => 'section'],
                false,
                false,
                [],
                []
            ),
            c(
                "spacing",
                esc_html__("Spacing", 'aifb-breakdance-metabox'),
                [
                    c(
                        "container",
                        esc_html__("Container", 'aifb-breakdance-metabox'),
                        [
                            c(
                                "padding",
                                esc_html__("Padding", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'spacing_complex'],
                                false,
                                false,
                                [],
                                []
                            ),
                            c(
                                "margin",
                                esc_html__("Margin", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'spacing_complex'],
                                false,
                                false,
                                [],
                                []
                            ),
                        ],
                        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
                        false,
                        false,
                        [],
                        []
                    ),
                    c(
                        "label",
                        esc_html__("Label", 'aifb-breakdance-metabox'),
                        [
                            c(
                                "margin",
                                esc_html__("Margin", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'spacing_complex'],
                                false,
                                false,
                                [],
                                []
                            ),
                        ],
                        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
                        false,
                        false,
                        [],
                        []
                    ),
                ],
                ['type' => 'section'],
                false,
                false,
                [],
                []
            ),
            c(
                "links",
                esc_html__("Links", 'aifb-breakdance-metabox'),
                [
                    getPresetSection("EssentialElements\\AtomV1ButtonDesign", "Button", "button", ['condition' => ['path' => 'content.field_settings.field_type', 'operand' => 'equals', 'value' => 'url']]),
                ],
                ['type' => 'section', 'condition' => ['path' => 'content.field_settings.field_type', 'operand' => 'equals', 'value' => 'url']],
                false,
                false,
                [],
                []
            ),
            c(
                "images",
                esc_html__("Images", 'aifb-breakdance-metabox'),
                [
                    c(
                        "size",
                        esc_html__("Size", 'aifb-breakdance-metabox'),
                        [
                            c(
                                "width",
                                esc_html__("Width", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['0' => 'px', '1' => '%', '2' => 'em', '3' => 'rem', '4' => 'vw'], 'defaultType' => 'px']],
                                false,
                                false,
                                [],
                                []
                            ),
                            c(
                                "height",
                                esc_html__("Height", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'unit', 'layout' => 'inline', 'unitOptions' => ['types' => ['0' => 'px', '1' => '%', '2' => 'em', '3' => 'rem', '4' => 'vh'], 'defaultType' => 'px']],
                                false,
                                false,
                                [],
                                []
                            ),
                            c(
                                "object_fit",
                                esc_html__("Object Fit", 'aifb-breakdance-metabox'),
                                [],
                                ['type' => 'dropdown', 'layout' => 'inline', 'items' => ['0' => ['text' => 'Default', 'value' => 'default'], '1' => ['text' => 'Cover', 'value' => 'cover'], '2' => ['text' => 'Contain', 'value' => 'contain'], '3' => ['text' => 'Fill', 'value' => 'fill'], '4' => ['text' => 'None', 'value' => 'none'], '5' => ['text' => 'Scale Down', 'value' => 'scale-down']]],
                                false,
                                false,
                                [],
                                []
                            ),
                        ],
                        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
                        false,
                        false,
                        [],
                        []
                    ),
                ],
                ['type' => 'section', 'condition' => ['path' => 'content.field_settings.field_type', 'operand' => 'equals', 'value' => 'image']],
                false,
                false,
                [],
                []
            ),
        ];
    }

    static function render($propertiesData)
    {
        // Check if we're in the builder context
        $is_builder = (defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING) || 
                      (isset($_GET['breakdance']) && $_GET['breakdance'] === '1');
        
        $fieldId = $propertiesData['content']['field_settings']['field_id'] ?? '';
        $fieldType = $propertiesData['content']['field_settings']['field_type'] ?? 'text';
        $isClonable = $propertiesData['content']['field_settings']['is_clonable'] ?? false;
        $showLabel = $propertiesData['content']['display_settings']['show_label'] ?? false;
        $customLabel = $propertiesData['content']['display_settings']['custom_label'] ?? '';
        $fallback = $propertiesData['content']['advanced_settings']['fallback'] ?? '';
        
        // Link settings for URL fields
        $linkText = $propertiesData['content']['display_settings']['link_settings']['link_text'] ?? 'Read More';
        $openInNewTab = $propertiesData['content']['display_settings']['link_settings']['open_in_new_tab'] ?? false;
        $addNofollow = $propertiesData['content']['display_settings']['link_settings']['add_nofollow'] ?? false;

        // In builder context, show a placeholder
        if ($is_builder) {
            $output = '<div class="aifb-metabox-field aifb-metabox-field-' . esc_attr($fieldType) . '">';
            
            if ($showLabel) {
                $labelText = !empty($customLabel) ? $customLabel : ($fieldId ? self::getFieldLabel($fieldId) : 'Field Label');
                $output .= '<div class="aifb-metabox-field-label">' . esc_html($labelText) . '</div>';
            }
            
            $output .= '<div class="aifb-metabox-field-content">';
            
            // Show different placeholders based on field type
            switch ($fieldType) {
                case 'url':
                    $output .= '<a href="#" class="aifb-metabox-field-link">' . esc_html($linkText) . '</a>';
                    break;
                case 'image':
                    $output .= '<div class="aifb-metabox-field-image-placeholder" style="width: 200px; height: 150px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">Image Placeholder</div>';
                    break;
                case 'file':
                    $output .= '<a href="#" class="aifb-metabox-field-file">Example File</a>';
                    break;
                case 'video':
                    $output .= '<div class="aifb-metabox-field-video-placeholder" style="width: 320px; height: 180px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">Video Placeholder</div>';
                    break;
                case 'text':
                default:
                    $output .= '<span class="aifb-metabox-field-text">Example content for ' . ($fieldId ? esc_html($fieldId) : 'this field') . '</span>';
                    break;
            }
            
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;
        }

        if (empty($fieldId)) {
            return '<div class="aifb-metabox-field-error">Please select a Meta Box field.</div>';
        }

        try {
            // Generate a cache key based on the field and current post
            $post_id = get_the_ID();
            $cache_key = 'aifb_metabox_field_' . md5($fieldId . '_' . $post_id . '_' . $fieldType . '_' . (int)$isClonable);
            $cached_output = wp_cache_get($cache_key, 'aifb_metabox');
            
            if (false !== $cached_output) {
                return $cached_output;
            }
            
            // Get field value
            $fieldValue = self::getFieldValue($fieldId, $isClonable);
            
            // If no value and fallback is set, use fallback
            if (empty($fieldValue) && !empty($fallback)) {
                $output = '<div class="aifb-metabox-field-fallback">' . esc_html($fallback) . '</div>';
                wp_cache_set($cache_key, $output, 'aifb_metabox', 5 * MINUTE_IN_SECONDS);
                return $output;
            }

            // If no value and no fallback, return empty
            if (empty($fieldValue)) {
                wp_cache_set($cache_key, '', 'aifb_metabox', 5 * MINUTE_IN_SECONDS);
                return '';
            }

            $output = '<div class="aifb-metabox-field aifb-metabox-field-' . esc_attr($fieldType) . '">';
            
            // Show label if enabled
            if ($showLabel) {
                $labelText = !empty($customLabel) ? $customLabel : self::getFieldLabel($fieldId);
                $output .= '<div class="aifb-metabox-field-label">' . esc_html($labelText) . '</div>';
            }

            // Render field based on type
            $output .= '<div class="aifb-metabox-field-content">';
            
            if ($isClonable) {
                $output .= self::renderClonableField($fieldValue, $fieldType, $linkText, $openInNewTab, $addNofollow);
            } else {
                $output .= self::renderSingleField($fieldValue, $fieldType, $linkText, $openInNewTab, $addNofollow);
            }
            
            $output .= '</div>';
            $output .= '</div>';

            // Cache the output for 5 minutes
            wp_cache_set($cache_key, $output, 'aifb_metabox', 5 * MINUTE_IN_SECONDS);
            
            return $output;
        } catch (\Exception $e) {
            // Log the error for debugging
            error_log('AIFB MetaBox Field Render Error: ' . $e->getMessage());
            
            return '<div class="aifb-metabox-field-error">Error rendering Meta Box field: ' . esc_html($e->getMessage()) . '</div>';
        }
    }

    /**
     * Get Meta Box field value
     */
    private static function getFieldValue($fieldId, $isClonable)
    {
        // Check if we're in the builder context
        $is_builder = (defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING) || 
                      (isset($_GET['breakdance']) && $_GET['breakdance'] === '1');
        
        // Return placeholder values in builder context
        if ($is_builder) {
            if ($isClonable) {
                return ['Example value 1', 'Example value 2'];
            }
            return 'Example value';
        }
        
        if (!function_exists('rwmb_meta')) {
            return '';
        }

        global $post;
        
        if (!$post) {
            // Try to get the current post ID from the query
            $post_id = get_the_ID();
            
            if (!$post_id) {
                return '';
            }
            
            // Check cache first
            $cache_key = 'aifb_metabox_value_' . md5($fieldId . '_' . $post_id . '_' . (int)$isClonable);
            $cached_value = wp_cache_get($cache_key, 'aifb_metabox_values');
            
            if (false !== $cached_value) {
                return $cached_value;
            }
            
            // Get the value and cache it
            $value = rwmb_meta($fieldId, '', $post_id);
            wp_cache_set($cache_key, $value, 'aifb_metabox_values', 5 * MINUTE_IN_SECONDS);
            
            return $value;
        }

        // Check cache first for post object
        $cache_key = 'aifb_metabox_value_' . md5($fieldId . '_' . $post->ID . '_' . (int)$isClonable);
        $cached_value = wp_cache_get($cache_key, 'aifb_metabox_values');
        
        if (false !== $cached_value) {
            return $cached_value;
        }
        
        // Get the value and cache it
        $value = rwmb_meta($fieldId, '', $post->ID);
        wp_cache_set($cache_key, $value, 'aifb_metabox_values', 5 * MINUTE_IN_SECONDS);
        
        return $value;
    }

    /**
     * Get Meta Box field label
     */
    private static function getFieldLabel($fieldId)
    {
        // This is a simplified approach - in a real implementation, you'd need to get the actual field label from Meta Box
        return ucwords(str_replace(['_', '-'], ' ', $fieldId));
    }

    /**
     * Render a single field value
     */
    private static function renderSingleField($value, $fieldType, $linkText, $openInNewTab, $addNofollow)
    {
        switch ($fieldType) {
            case 'url':
                $target = $openInNewTab ? ' target="_blank"' : '';
                $rel = $addNofollow ? ' rel="nofollow"' : '';
                return '<a href="' . esc_url($value) . '"' . $target . $rel . ' class="aifb-metabox-field-link">' . esc_html($linkText) . '</a>';
            
            case 'image':
                if (is_array($value) && isset($value['ID'])) {
                    $imageUrl = wp_get_attachment_image_url($value['ID'], 'full');
                    return '<img src="' . esc_url($imageUrl) . '" alt="' . esc_attr(get_post_meta($value['ID'], '_wp_attachment_image_alt', true)) . '" class="aifb-metabox-field-image">';
                } elseif (is_numeric($value)) {
                    $imageUrl = wp_get_attachment_image_url($value, 'full');
                    return '<img src="' . esc_url($imageUrl) . '" alt="' . esc_attr(get_post_meta($value, '_wp_attachment_image_alt', true)) . '" class="aifb-metabox-field-image">';
                }
                return '<span class="aifb-metabox-field-text">' . esc_html($value) . '</span>';
            
            case 'file':
                if (is_array($value) && isset($value['ID'])) {
                    $fileUrl = wp_get_attachment_url($value['ID']);
                    $fileName = get_the_title($value['ID']);
                    return '<a href="' . esc_url($fileUrl) . '" class="aifb-metabox-field-file">' . esc_html($fileName) . '</a>';
                } elseif (is_numeric($value)) {
                    $fileUrl = wp_get_attachment_url($value);
                    $fileName = get_the_title($value);
                    return '<a href="' . esc_url($fileUrl) . '" class="aifb-metabox-field-file">' . esc_html($fileName) . '</a>';
                }
                return '<span class="aifb-metabox-field-text">' . esc_html($value) . '</span>';
            
            case 'video':
                if (is_array($value) && isset($value['ID'])) {
                    $videoUrl = wp_get_attachment_url($value['ID']);
                    return '<video src="' . esc_url($videoUrl) . '" controls class="aifb-metabox-field-video"></video>';
                } elseif (is_numeric($value)) {
                    $videoUrl = wp_get_attachment_url($value);
                    return '<video src="' . esc_url($videoUrl) . '" controls class="aifb-metabox-field-video"></video>';
                } elseif (filter_var($value, FILTER_VALIDATE_URL)) {
                    // Handle YouTube or Vimeo URLs
                    if (strpos($value, 'youtube.com') !== false || strpos($value, 'youtu.be') !== false) {
                        $videoId = self::getYoutubeVideoId($value);
                        if ($videoId) {
                            return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr($videoId) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="aifb-metabox-field-video"></iframe>';
                        }
                    } elseif (strpos($value, 'vimeo.com') !== false) {
                        $videoId = self::getVimeoVideoId($value);
                        if ($videoId) {
                            return '<iframe src="https://player.vimeo.com/video/' . esc_attr($videoId) . '" width="560" height="315" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen class="aifb-metabox-field-video"></iframe>';
                        }
                    }
                    
                    // Default to a direct video URL
                    return '<video src="' . esc_url($value) . '" controls class="aifb-metabox-field-video"></video>';
                }
                return '<span class="aifb-metabox-field-text">' . esc_html($value) . '</span>';
            
            case 'text':
            default:
                return '<span class="aifb-metabox-field-text">' . esc_html($value) . '</span>';
        }
    }

    /**
     * Render a clonable field value
     */
    private static function renderClonableField($values, $fieldType, $linkText, $openInNewTab, $addNofollow)
    {
        if (!is_array($values)) {
            return self::renderSingleField($values, $fieldType, $linkText, $openInNewTab, $addNofollow);
        }

        $output = '<div class="aifb-metabox-field-clonable">';
        
        foreach ($values as $value) {
            $output .= '<div class="aifb-metabox-field-clone-item">';
            $output .= self::renderSingleField($value, $fieldType, $linkText, $openInNewTab, $addNofollow);
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }

    /**
     * Get YouTube video ID from URL
     */
    private static function getYoutubeVideoId($url)
    {
        $videoId = '';
        
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        
        return $videoId;
    }

    /**
     * Get Vimeo video ID from URL
     */
    private static function getVimeoVideoId($url)
    {
        $videoId = '';
        
        if (preg_match('/vimeo\.com\/([0-9]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        
        return $videoId;
    }

    /**
     * Get available Meta Box fields
     */
    private static function getMetaBoxFields()
    {
        // Check if we're in the builder context
        $is_builder = (defined('BREAKDANCE_BUILDING') && BREAKDANCE_BUILDING) || 
                      (isset($_GET['breakdance']) && $_GET['breakdance'] === '1');
        
        // Return a placeholder in builder context to prevent crashes
        if ($is_builder) {
            return [
                ['text' => '-- Select a Meta Box field --', 'value' => ''],
                ['text' => 'Example Text Field', 'value' => 'example_text'],
                ['text' => 'Example Image Field', 'value' => 'example_image'],
                ['text' => 'Example URL Field', 'value' => 'example_url'],
                ['text' => 'Example File Field', 'value' => 'example_file'],
                ['text' => 'Example Video Field', 'value' => 'example_video'],
            ];
        }
        
        // Check if Meta Box functions exist
        if (!function_exists('rwmb_get_registry')) {
            return [['text' => 'Meta Box plugin not active', 'value' => '']];
        }

        // Use transient cache to improve performance
        $cache_key = 'aifb_metabox_fields_' . get_the_ID();
        $cached_fields = get_transient($cache_key);
        
        if (false !== $cached_fields) {
            return $cached_fields;
        }

        try {
            $fields = [];
            $registry = rwmb_get_registry('field');
            
            if (!$registry) {
                return [['text' => 'No Meta Box fields found', 'value' => '']];
            }
            
            $metaBoxes = $registry->get_by_object_type('post');
            
            if (empty($metaBoxes)) {
                return [['text' => 'No Meta Box fields found', 'value' => '']];
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
                        $fieldType = isset($field['type']) ? ' (' . $field['type'] . ')' : '';
                        
                        $fields[] = [
                            'text' => $fieldLabel . $fieldType,
                            'value' => $field['id']
                        ];
                    }
                }
            }
            
            if (empty($fields)) {
                return [['text' => 'No Meta Box fields found', 'value' => '']];
            }
            
            // Cache the result for 1 hour
            set_transient($cache_key, $fields, HOUR_IN_SECONDS);
            
            return $fields;
        } catch (\Exception $e) {
            // Log the error for debugging
            error_log('AIFB MetaBox Field Error: ' . $e->getMessage());
            
            // Return a fallback in case of any errors
            return [['text' => 'Error loading Meta Box fields', 'value' => '']];
        }
    }

    static function cssTemplate()
    {
        return '
        /* Base styles */
        %%SELECTOR%% {
            width: 100%;
            display: block;
        }
        
        /* Container styles */
        %%SELECTOR%% .aifb-metabox-field {
            {{ spacing.container.padding }}
            {{ spacing.container.margin }}
        }
        
        /* Label styles */
        %%SELECTOR%% .aifb-metabox-field-label {
            {{ typography.label.style }}
            {{ spacing.label.margin }}
            display: block;
        }
        
        /* Content styles */
        %%SELECTOR%% .aifb-metabox-field-content {
            {{ typography.content.style }}
            display: block;
        }
        
        /* Error and fallback styles */
        %%SELECTOR%% .aifb-metabox-field-error,
        %%SELECTOR%% .aifb-metabox-field-fallback {
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        %%SELECTOR%% .aifb-metabox-field-error {
            background-color: #ffebee;
            color: #c62828;
        }
        
        %%SELECTOR%% .aifb-metabox-field-fallback {
            background-color: #f5f5f5;
            color: #757575;
        }
        
        /* Clonable field styles */
        %%SELECTOR%% .aifb-metabox-field-clonable {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        
        %%SELECTOR%% .aifb-metabox-field-clone-item {
            padding: 5px 0;
            width: 100%;
        }
        
        /* URL Field styles */
        %%SELECTOR%% .aifb-metabox-field-link {
            {{ links.button.style }}
            display: inline-block;
        }
        
        /* Image Field styles */
        %%SELECTOR%% .aifb-metabox-field-image {
            max-width: 100%;
            height: auto;
            display: block;
            {% if images.size.width %}width: {{ images.size.width }};{% endif %}
            {% if images.size.height %}height: {{ images.size.height }};{% endif %}
            {% if images.size.object_fit and images.size.object_fit != "default" %}object-fit: {{ images.size.object_fit }};{% endif %}
        }
        
        /* Video Field styles */
        %%SELECTOR%% .aifb-metabox-field-video {
            max-width: 100%;
            display: block;
            {% if images.size.width %}width: {{ images.size.width }};{% endif %}
            {% if images.size.height %}height: {{ images.size.height }};{% endif %}
        }
        
        /* Responsive styles */
        @media (max-width: 767px) {
            %%SELECTOR%% .aifb-metabox-field-image,
            %%SELECTOR%% .aifb-metabox-field-video {
                width: 100% !important;
                height: auto !important;
            }
        }
        ';
    }
} 