# Typeform Embed for Elementor

A powerful WordPress plugin that seamlessly integrates Typeform into Elementor with advanced customization options and dynamic data capabilities.

## Features

- **Multiple Embed Types**: Widget (inline), Popup, Slider, Popover, and Side Tab
- **Dynamic Form Selection**: Use custom fields to dynamically load different Typeforms per page/product
- **DataLayer Integration**: Send Google Tag Manager dataLayer to Typeform as hidden fields
- **Full Customization**: Complete control over appearance, behavior, and styling
- **Responsive Design**: Mobile-friendly and auto-resize options
- **Advanced Options**: Lazy loading, session persistence, auto-close, and more

## Installation

1. Download the plugin ZIP file
2. Navigate to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the ZIP file
4. Click "Install Now" and then "Activate"

### Manual Installation

1. Upload the `typeform-embed-elementor` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The Typeform widget will appear in your Elementor widgets panel

## Usage

### Basic Setup

1. **Add Widget**: In Elementor, drag the "Typeform Embed" widget to your page
2. **Configure Form**: 
   - Set your Typeform ID or URL in the widget settings
   - Choose your preferred embed type (Widget, Popup, Slider, etc.)
3. **Customize**: Adjust appearance and behavior settings as needed

### Dynamic Forms with Custom Fields

Use different Typeforms for different pages or products:

1. Add a custom field (e.g., `typeform_id`) to your page/post/product
2. In the widget settings, set the "Custom Field Name" to match your field
3. Set a "Fallback Typeform ID" for pages without custom fields
4. The widget will automatically load the appropriate form

### DataLayer Integration (New!)

Send Google Tag Manager dataLayer information to your Typeform:

1. **In Typeform**: 
   - Add a hidden field (e.g., named `datalayer`)
   - Note the exact field name

2. **In Widget Settings**:
   - Enable "Send DataLayer to Typeform" under Advanced Settings
   - Enter the hidden field name (must match Typeform exactly)
   - The complete `window.dataLayer` array will be sent as JSON

3. **Use Cases**:
   - Track user journey and interactions
   - Pass e-commerce data
   - Send marketing campaign parameters
   - Include user preferences or session data

## Embed Types

### Widget (Inline)
Embeds the form directly in your page content
- Adjustable height
- Auto-resize option
- Lazy loading support

### Popup
Opens form in a modal overlay
- Custom trigger button
- Configurable close behavior
- Session persistence

### Slider
Slides in from the side of the screen
- Smooth animations
- Custom button styling
- Remember user progress

### Popover
Small popup attached to button
- Auto-positioning
- Compact design
- Auto-close after inactivity

### Side Tab
Fixed tab on screen edge
- Always accessible
- Customizable tab text
- Non-intrusive

## Advanced Features

### Styling Options
- Custom button colors and typography
- Border and shadow controls
- Icon support with positioning
- Full width button option
- Responsive design controls

### Behavior Settings
- **Opacity**: Control form transparency
- **Hide Headers/Footer**: Clean, minimal appearance
- **Disable Tracking**: Privacy-focused option
- **Auto Close**: Close after form submission
- **Keep Session**: Remember user progress
- **Close on ESC**: Keyboard navigation support

### Performance
- **Lazy Loading**: Load form only when needed
- **Auto Resize**: Adapt to content changes
- **Optimized Scripts**: Minimal performance impact

## Development

### Building from Source

```bash
# Install dependencies
npm install

# Development mode (with watch)
npm run dev

# Production build
npm run build
```

### File Structure

```
typeform-embed-elementor/
├── assets/
│   ├── css/          # Compiled styles
│   └── js/           # Compiled JavaScript
├── includes/
│   └── widgets/      # Elementor widget classes
├── src/              # Source JavaScript files
├── typeform-embed-elementor.php  # Main plugin file
└── webpack.config.js # Build configuration
```

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher
- PHP 7.0 or higher
- Modern browser with JavaScript enabled

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## WooCommerce Integration

For WooCommerce products:
1. Add a custom field to the product
2. Use the same widget on the product page
3. The form will automatically be linked to the product

## Support

For issues, questions, or feature requests, please create an issue in the GitHub repository.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This plugin is licensed under GPL v2 or later.

## Credits

- Built with [Typeform Embed SDK](https://developer.typeform.com/embed/)
- Integrated with [Elementor Page Builder](https://elementor.com/)

## Changelog

### Version 1.0.0
- Initial release
- Multiple embed types support
- Dynamic form selection via custom fields
- DataLayer integration
- Full Elementor styling controls
- Advanced behavioral options