# AIFB Breakdance Elements Custom MetaBox

Advanced MetaBox integration for Breakdance Builder.

## Description

This plugin enhances Breakdance Builder by adding a custom element that allows you to display Meta Box fields in your Breakdance layouts. It properly handles both regular and clonable fields, providing a seamless integration between Meta Box and Breakdance.

### Features

- Display Meta Box fields in Breakdance layouts
- Support for various field types (URL, text, image, file, video)
- Proper handling of clonable fields
- Customizable styling options
- Fallback content for empty fields
- Internationalization support

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- Breakdance Builder
- Meta Box plugin

## Installation

1. Upload the plugin files to the `/wp-content/plugins/aifb-breakdance-elements-custom-metabox` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the new 'Meta Box Field' element in Breakdance Builder.

## Usage

1. Create a Meta Box field group and fields using the Meta Box plugin.
2. In Breakdance Builder, add the 'Meta Box Field' element to your layout.
3. Select the Meta Box field you want to display.
4. Configure the display options and styling as needed.

## Changelog

### 0.0.2

- Added internationalization support
- Improved error handling for dependencies
- Added direct link to Breakdance settings in plugin actions
- Added proper activation and deactivation hooks
- Enhanced code documentation
- Fixed compatibility issues with Breakdance Builder

### 0.0.1

- Initial development release

## Credits

- Developed by Edoardo Guzzi, AIFB
- Website: [https://plugins.aifb.ch](https://plugins.aifb.ch)

## License

GPL-3.0+
