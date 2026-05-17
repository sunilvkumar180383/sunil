# Image Gallery Manager - WordPress Plugin

A complete WordPress plugin for managing and displaying images in a responsive grid with hover effects.

## Features

✅ **Backend Management**
- Upload and manage images from WordPress Media Library
- Add titles and descriptions to images
- Drag-and-drop sorting with visual feedback
- Delete images easily
- AJAX-powered interface

✅ **Frontend Display**
- Responsive 3-column grid (adjusts to 2 columns on tablets, 1 on mobile)
- Smooth hover effects with image zoom and overlay
- Display image titles and descriptions on hover
- Easy-to-use shortcode

✅ **Technical Features**
- Custom WordPress database tables
- Security nonces for AJAX requests
- Proper capability checks
- Sanitization of all inputs
- jQuery UI Sortable integration
- Responsive CSS Grid layout

## Installation

1. Extract the `image-gallery-plugin` folder to `/wp-content/plugins/`
2. Go to WordPress Admin → Plugins
3. Find "Image Gallery Manager" and click Activate
4. The database tables will be created automatically

## Usage

### Backend

1. Go to **Image Gallery** in the WordPress admin menu
2. Click **Select Images** button
3. Choose one or more images from your media library
4. Add a title and description (optional)
5. Click **Add to Gallery**
6. Drag images to reorder them
7. Click **Delete** to remove images

### Frontend

Add this shortcode to any page or post:

```
[image_gallery columns="3"]
```

#### Shortcode Parameters:

- `columns` - Number of columns (default: 3)
  - `[image_gallery columns="2"]` - 2 columns
  - `[image_gallery columns="4"]` - 4 columns

- `limit` - Limit number of images displayed (default: all)
  - `[image_gallery limit="12"]` - Show only 12 images

#### Examples:

```
[image_gallery columns="3" limit="9"]
[image_gallery columns="2"]
[image_gallery]
```

## Hover Effects

The gallery includes smooth hover effects:
- Image zoom (1.1x scale)
- Dark overlay fade-in
- Title and description slide-up animation
- Card lift effect with shadow

## Responsive Design

- **Desktop**: 3 columns
- **Tablet (768px)**: 2 columns
- **Mobile (480px)**: 1 column

## Database

The plugin creates a table: `wp_gallery_images`

Columns:
- `id` - Primary key
- `image_id` - WordPress attachment ID
- `attachment_url` - Image URL
- `title` - Image title
- `description` - Image description
- `sort_order` - Display order
- `status` - Active/Inactive status
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## Security

- All inputs are sanitized
- AJAX requests use WordPress nonces
- Capability checks ensure only administrators can manage gallery
- Proper escaping of output

## Support

For issues or feature requests, please contact the developer.

## License

GPL v2 or later
