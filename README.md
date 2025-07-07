# HOSTPN - WordPress Plugin for Accommodation Management

A comprehensive WordPress plugin for managing accommodations, guests, and travel parts with advanced search and filtering capabilities.

## 🏠 Features

### Core Functionality
- **Accommodation Management**: Create, edit, and manage accommodation listings
- **Guest Management**: Track and manage guest information
- **Travel Parts**: Organize travel components and itineraries
- **Advanced Search**: Real-time filtering and search functionality
- **Multi-language Support**: Built-in internationalization (i18n)

### User Interface
- **Modern UI**: Clean, responsive design with Material Design icons
- **Interactive Forms**: Dynamic form validation and user feedback
- **Search & Filter**: Real-time search across all content types
- **Modal Popups**: Smooth popup interface for editing and viewing
- **Drag & Drop**: Sortable lists and intuitive interactions

### Technical Features
- **Custom Post Types**: Structured data management
- **AJAX Integration**: Smooth, non-blocking user interactions
- **Security**: Nonce verification and input sanitization
- **Performance**: Optimized queries and caching
- **Extensible**: Hook-based architecture for customization

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- jQuery (included with WordPress)

## 🚀 Installation

### Method 1: Manual Installation
1. Download the plugin files
2. Upload to `/wp-content/plugins/hostpn/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin settings

### Method 2: WordPress Admin
1. Go to Plugins → Add New
2. Upload the plugin ZIP file
3. Activate the plugin
4. Configure settings

## ⚙️ Configuration

### Basic Setup
1. Navigate to the plugin settings page
2. Configure your accommodation types
3. Set up guest categories
4. Customize search and filter options

### Customization
The plugin uses WordPress hooks and filters for easy customization:

```php
// Add custom fields to accommodations
add_filter('hostpn_accommodation_fields', 'my_custom_fields');

// Modify search behavior
add_filter('hostpn_search_results', 'my_custom_search');
```

## 🎯 Usage

### Managing Accommodations
- Create new accommodation listings
- Add detailed descriptions and amenities
- Upload images and media
- Set availability and pricing

### Guest Management
- Register and track guests
- Manage guest preferences
- Track visit history
- Handle special requirements

### Search and Filter
- Real-time search across all content
- Filter by multiple criteria
- Sort by various parameters
- Export filtered results

## 🔧 Development

### File Structure
```
hostpn/
├── assets/
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   └── media/        # Images and media
├── includes/         # PHP classes and functions
├── languages/        # Translation files
├── templates/        # Template files
└── hostpn.php       # Main plugin file
```

### Key Classes
- `HOSTPN_Common`: Core functionality
- `HOSTPN_Forms`: Form handling and validation
- `HOSTPN_Post_Type_*`: Custom post type management
- `HOSTPN_Ajax`: AJAX request handling

### Adding Custom Features
```php
// Add custom post type
add_action('init', 'my_custom_post_type');

// Add custom AJAX handler
add_action('wp_ajax_my_custom_action', 'my_custom_handler');
```

## 🌐 Internationalization

The plugin supports multiple languages:
- English (default)
- Spanish (es_ES)
- Catalan (ca)
- Basque (eu)
- Galician (gl_ES)
- Italian (it_IT)
- Portuguese (pt_PT)

### Adding Translations
1. Copy the `.pot` file
2. Translate strings using Poedit
3. Save as `.po` and `.mo` files
4. Place in the `languages/` directory

## 🔒 Security

- Input sanitization and validation
- Nonce verification for forms
- SQL injection prevention
- XSS protection
- Capability checks

## 📊 Performance

- Optimized database queries
- Efficient caching strategies
- Lazy loading for large lists
- Minified assets for production

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### Coding Standards
- Follow WordPress coding standards
- Use proper documentation
- Include unit tests where possible
- Maintain backward compatibility

## 📝 Changelog

### Version 1.0.4
- Fixed search functionality for all CPTs
- Improved unified search system
- Enhanced performance and stability
- Added comprehensive error handling

### Version 1.0.3
- Initial public release
- Core functionality implementation
- Multi-language support
- Basic search and filter features

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 🆘 Support

- **Documentation**: [Wiki](https://github.com/your-username/HOSTPN/wiki)
- **Issues**: [GitHub Issues](https://github.com/your-username/HOSTPN/issues)
- **Discussions**: [GitHub Discussions](https://github.com/your-username/HOSTPN/discussions)

## 🙏 Acknowledgments

- WordPress community
- Material Design Icons
- jQuery and related libraries
- Contributors and testers

---

**Made with ❤️ for the WordPress community** 