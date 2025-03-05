<?php

/**
 * Plugin Name: AIFB Breakdance Elements Custom MetaBox
 * Plugin URI: https://plugins.aifb.ch/aifb-breakdance-elements-custom-metabox
 * Description: Advanced MetaBox integration for Breakdance Builder
 * Version: 0.0.2
 * Author: Edoardo Guzzi, AIFB
 * Author URI: https://plugins.aifb.ch
 * License: GPL-3.0+
 * Text Domain: aifb-breakdance-metabox
 * Domain Path: /languages
 * Requires PHP: 8.1
 * Requires at least: 6.0
 */

namespace AIFB\BreakdanceMetaBox;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Manual Autoloader
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'AIFB\\BreakdanceMetaBox\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/';

    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Composer autoloader (if available)
if (\file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Main Plugin Class
 * 
 * @since 0.0.1
 */
final class Plugin
{
    /**
     * Plugin instance
     *
     * @var Plugin|null
     */
    private static ?Plugin $instance = null;

    /**
     * Plugin version
     */
    public const VERSION = '0.0.2';

    /**
     * Minimum PHP version required
     */
    public const MIN_PHP_VERSION = '8.1';

    /**
     * Minimum WordPress version required
     */
    public const MIN_WP_VERSION = '6.0';

    /**
     * Plugin basename
     *
     * @var string
     */
    private string $plugin_basename;

    /**
     * Constructor
     */
    private function __construct()
    {
        // Define basic constants
        $this->define_constants();
        
        // Initialize plugin_basename
        $this->plugin_basename = \plugin_basename(AIFB_BMB_FILE);
        define('AIFB_BMB_BASENAME', $this->plugin_basename);

        // Check requirements before proceeding
        if (!$this->checkRequirements()) {
            return;
        }

        $this->initializePlugin();
        $this->registerHooks();
    }

    /**
     * Define plugin constants
     */
    private function define_constants(): void
    {
        if (!defined('AIFB_BMB_VERSION')) {
            define('AIFB_BMB_VERSION', self::VERSION);
        }
        if (!defined('AIFB_BMB_FILE')) {
            define('AIFB_BMB_FILE', __FILE__);
        }
        if (!defined('AIFB_BMB_PATH')) {
            define('AIFB_BMB_PATH', plugin_dir_path(__FILE__));
        }
        if (!defined('AIFB_BMB_URL')) {
            define('AIFB_BMB_URL', plugin_dir_url(__FILE__));
        }
    }

    /**
     * Get plugin instance
     *
     * @return Plugin
     */
    public static function getInstance(): Plugin
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Check if all requirements are met
     *
     * @return bool
     */
    private function checkRequirements(): bool
    {
        $requirements_met = true;

        // Check PHP version
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            \add_action('admin_notices', function () {
                $this->displayRequirementError('PHP', self::MIN_PHP_VERSION);
            });
            $requirements_met = false;
        }

        // Check WordPress version
        if (version_compare($GLOBALS['wp_version'], self::MIN_WP_VERSION, '<')) {
            add_action('admin_notices', function () {
                $this->displayRequirementError('WordPress', self::MIN_WP_VERSION);
            });
            $requirements_met = false;
        }

        // Check if Meta Box plugin is active
        if (!class_exists('RWMB_Loader')) {
            add_action('admin_notices', function () {
                $this->displayMetaBoxMissingError();
            });
            $requirements_met = false;
        }

        // Enhanced check for Breakdance Builder
        $is_breakdance_active = false;
        if (class_exists('\Breakdance\Plugin') || function_exists('breakdance_loaded')) {
            $is_breakdance_active = true;
        } else {
            // Fallback check for plugin being active but not fully loaded
            if (!function_exists('is_plugin_active')) {
                include_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $is_breakdance_active = is_plugin_active('breakdance/plugin.php');
        }

        if (!$is_breakdance_active) {
            add_action('admin_notices', function () {
                $this->displayBreakdanceMissingError();
            });
            $requirements_met = false;
        }

        return $requirements_met;
    }

    /**
     * Initialize plugin components
     *
     * @return void
     */
    private function initializePlugin(): void
    {
        // Register text domain loading for init hook
        add_action('init', [$this, 'loadTextDomain']);

        // Initialize core components
        Core\Loader::getInstance();
        Core\Assets::getInstance();
        Core\MetaBoxIntegration::getInstance();

        // Initialize admin if in admin area
        if (is_admin()) {
            Admin\AdminManager::getInstance();
        }

        // Register Element Studio locations when Breakdance is loaded
        add_action('breakdance_loaded', [$this, 'registerElementLocations'], 9);
    }

    /**
     * Register plugin hooks
     *
     * @return void
     */
    private function registerHooks(): void
    {
        // Add settings link on plugin page
        add_filter('plugin_action_links_' . $this->plugin_basename, [$this, 'addPluginActionLinks']);

        // Register activation hook
        register_activation_hook(AIFB_BMB_FILE, [$this, 'activate']);

        // Register deactivation hook
        register_deactivation_hook(AIFB_BMB_FILE, [$this, 'deactivate']);
    }

    /**
     * Plugin activation
     *
     * @return void
     */
    public function activate(): void
    {
        // Create necessary database tables or options
        update_option('aifb_bmb_version', self::VERSION);

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     *
     * @return void
     */
    public function deactivate(): void
    {
        // Clean up if needed
        flush_rewrite_rules();
    }

    /**
     * Load plugin text domain
     *
     * @return void
     */
    public function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'aifb-breakdance-metabox',
            false,
            dirname($this->plugin_basename) . '/languages'
        );
    }

    /**
     * Add plugin action links
     *
     * @param array $links
     * @return array
     */
    public function addPluginActionLinks(array $links): array
    {
        $plugin_links = [
            '<a href="' . admin_url('admin.php?page=breakdance_settings') . '">' .
                __('Breakdance Settings', 'aifb-breakdance-metabox') . '</a>'
        ];

        return array_merge($plugin_links, $links);
    }

    /**
     * Register Element Studio locations
     *
     * @return void
     */
    public function registerElementLocations(): void
    {
        // Allineato con l'esempio di Breakdance su GitHub
        $locations = [
            'elements' => __('Custom Elements', 'aifb-breakdance-metabox'),
            'macros' => __('Custom Macros', 'aifb-breakdance-metabox'),
            'presets' => __('Custom Presets', 'aifb-breakdance-metabox')
        ];

        foreach ($locations as $dir => $label) {
            \Breakdance\ElementStudio\registerSaveLocation(
                $this->getRelativePath($dir),
                'AIFB\\BreakdanceMetaBox',
                $dir === 'elements' ? 'element' : $dir,
                $label,
                false
            );
        }
    }

    /**
     * Get relative path for Element Studio locations
     *
     * @param string $dir
     * @return string
     */
    private function getRelativePath(string $dir): string
    {
        return \Breakdance\Util\getDirectoryPathRelativeToPluginFolder(__DIR__) . '/' . $dir;
    }

    /**
     * Display requirement error
     *
     * @param string $requirement
     * @param string $version
     * @return void
     */
    private function displayRequirementError(string $requirement, string $version): void
    {
        $message = sprintf(
            /* translators: 1: Requirement name 2: Required version */
            \esc_html__('AIFB Breakdance MetaBox requires %1$s version %2$s or greater.', 'aifb-breakdance-metabox'),
            $requirement,
            $version
        );
        printf('<div class="notice notice-error"><p>%s</p></div>', $message);
    }

    /**
     * Display Meta Box missing error
     *
     * @return void
     */
    private function displayMetaBoxMissingError(): void
    {
        $message = esc_html__('AIFB Breakdance MetaBox requires Meta Box plugin to be installed and activated.', 'aifb-breakdance-metabox');
        $install_url = wp_nonce_url(
            self_admin_url('update.php?action=install-plugin&plugin=meta-box'),
            'install-plugin_meta-box'
        );

        printf(
            '<div class="notice notice-error"><p>%s <a href="%s">%s</a></p></div>',
            $message,
            esc_url($install_url),
            esc_html__('Install Meta Box', 'aifb-breakdance-metabox')
        );
    }
    /**
     * Display Breakdance missing error
     *
     * @return void
     */
    private function displayBreakdanceMissingError(): void
    {
        $message = esc_html__('AIFB Breakdance MetaBox requires Breakdance Builder to be installed and activated.', 'aifb-breakdance-metabox');
        $install_url = wp_nonce_url(
            self_admin_url('update.php?action=install-plugin&plugin=breakdance'),
            'install-plugin_breakdance'
        );

        printf(
            '<div class="notice notice-error"><p>%s <a href="%s">%s</a></p></div>',
            $message,
            esc_url($install_url),
            esc_html__('Install Breakdance', 'aifb-breakdance-metabox')
        );
    }
}

// Initialize plugin
add_action('plugins_loaded', function () {
    Plugin::getInstance();
});