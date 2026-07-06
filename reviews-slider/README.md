# Reviews Slider WordPress Plugin

An elegant WordPress plugin to display customer reviews in a responsive slider carousel showing 3 reviews at a time.

## Features

✨ **Core Features:**
- Beautiful slider carousel displaying reviews
- Shows 3 reviews at a time (configurable 1-6 reviews)
- Responsive design (works on mobile, tablet, desktop)
- Star rating system (1-5 stars)
- Author information with custom images
- Smooth autoplay functionality
- Navigation arrows and pagination dots

📋 **Management Features:**
- Custom Post Type for reviews
- Review categories/taxonomy
- Admin meta boxes for review details
- Image upload for author photos
- Fully customizable settings page
- Shortcode support

🎨 **Customization:**
- Configure number of items to display
- Enable/disable autoplay
- Adjust autoplay speed
- Toggle navigation arrows
- Toggle pagination dots
- Responsive breakpoints for mobile/tablet

## Installation

1. Upload the `reviews-slider` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin panel
3. Go to **Reviews** in the WordPress admin menu

## Usage

### Basic Shortcode

Add this shortcode to any page or post:

```
[reviews_slider]
```

### Shortcode with Options

```
[reviews_slider category="testimonials" limit="10" orderby="date" order="DESC"]
```

### Shortcode Parameters

- **category** (string): Filter reviews by category slug. Default: empty (show all)
- **limit** (number): Maximum number of reviews to display. Default: -1 (all)
- **orderby** (string): Order by 'date', 'title', or 'ID'. Default: 'date'
- **order** (string): Sort order 'ASC' or 'DESC'. Default: 'DESC'

### Examples

```
[reviews_slider category="customers" limit="20"]
[reviews_slider orderby="title" order="ASC"]
[reviews_slider limit="5"]
```

## Adding Reviews

1. Go to **Reviews** → **Add New**
2. Fill in the review content in the editor
3. In the **Review Details** meta box, add:
   - Author Name
   - Author Title/Company
   - Star Rating (1-5)
   - Author Image
4. Optionally assign a category
5. Publish the review

## Settings

Go to **Reviews** → **Settings** to configure:

- **Items to Show**: Number of reviews displayed at once (1-6)
- **Autoplay**: Enable automatic slide rotation
- **Autoplay Speed**: Delay between slides in milliseconds
- **Show Arrows**: Display previous/next navigation
- **Show Dots**: Display pagination indicators

## File Structure

```
reviews-slider/
├── reviews-slider.php              # Main plugin file
├── includes/
│   ├── class-reviews-slider.php    # Main plugin class
│   ├── class-reviews-cpt.php       # Custom post type & meta boxes
│   ├── class-reviews-shortcode.php # Shortcode handler
│   └── class-reviews-admin.php     # Admin settings page
├── assets/
│   ├── css/
│   │   ├── reviews-slider.css      # Frontend styles
│   │   └── admin.css               # Admin styles
│   └── js/
│       ├── reviews-slider.js       # Frontend slider functionality
│       └── admin.js                # Admin scripts
├── languages/
│   └── reviews-slider.pot          # Translation template
└── README.md                        # This file
```

## Technologies Used

- **Slider Library**: Slick Carousel (https://kenwheeler.github.io/slick/)
- **Frontend**: jQuery, HTML5, CSS3
- **Backend**: WordPress REST API, Custom Post Types

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- jQuery (included with WordPress)

## Customization

### Custom CSS

You can override styles by adding custom CSS to your theme:

```css
.review-content {
    background: #f0f0f0;
}

.review-text {
    font-size: 16px;
}

.author-image {
    width: 60px;
    height: 60px;
}
```

### Hooks & Filters

Add custom functionality using WordPress hooks:

```php
// Hook into review query
add_filter( 'reviews_slider_query_args', function( $args ) {
    $args['posts_per_page'] = 5;
    return $args;
} );
```

## Troubleshooting

**Slider not showing?**
- Ensure at least one review has been published
- Check that the shortcode is placed on a public-facing page/post
- Verify jQuery is properly loaded

**Reviews not displaying?**
- Check that reviews are published (not in draft)
- Verify the correct category slug if using category filter
- Clear any caching plugins

**Images not showing?**
- Ensure the author image URL is valid
- Check that media uploads are working
- Verify file permissions on the uploads directory

## Support

For support, documentation, or to report issues, contact:
sunilvkumar@example.com

## License

This plugin is licensed under the GPL v2 or later. See LICENSE file for details.

## Changelog

### Version 1.0.0
- Initial release
- Full slider functionality
- Admin settings page
- Shortcode support
- Responsive design
- Multi-language support ready

## Credits

Developed by Sunil Kumar
Built with ❤️ for WordPress
