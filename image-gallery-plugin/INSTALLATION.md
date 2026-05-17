# Installation Guide - Image Gallery Manager

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher

## Step-by-Step Installation

### Method 1: Upload via FTP

1. Download the plugin folder
2. Extract the ZIP file
3. Upload the `image-gallery-plugin` folder to `/wp-content/plugins/` via FTP
4. Log in to WordPress Admin
5. Go to **Plugins**
6. Find **Image Gallery Manager**
7. Click **Activate**

### Method 2: Upload via WordPress Admin

1. Download the plugin ZIP file
2. Log in to WordPress Admin
3. Go to **Plugins → Add New**
4. Click **Upload Plugin**
5. Choose the ZIP file
6. Click **Install Now**
7. Click **Activate Plugin**

### Method 3: Manual Upload (Recommended)

1. Extract the ZIP file on your computer
2. Connect via FTP or File Manager
3. Navigate to `/wp-content/plugins/`
4. Upload the entire `image-gallery-plugin` folder
5. Activate from WordPress Admin → Plugins

## First Time Setup

1. After activation, go to **Image Gallery** in the admin menu
2. The database tables are created automatically
3. Start adding images!

## Troubleshooting

### Plugin not appearing in Plugins list
- Ensure the folder is named `image-gallery-plugin`
- Check file permissions (755 for folders, 644 for files)
- Verify PHP is running properly

### Can't upload images
- Check WordPress Media Library permissions
- Ensure your user role is Administrator
- Verify PHP upload size limits

### Database errors
- Check WordPress database user has CREATE TABLE permissions
- Verify MySQL version is 5.6+
- Check database connection settings

### Styles not loading
- Clear browser cache (Ctrl+Shift+Delete or Cmd+Shift+Delete)
- Check if CSS file paths are correct
- Verify folder permissions

## Uninstallation

1. Go to **Plugins** in WordPress Admin
2. Find **Image Gallery Manager**
3. Click **Deactivate**
4. Click **Delete**
5. Confirm deletion

Note: Deactivation does NOT delete images. The database table remains.

## Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all requirements are met
3. Check WordPress error logs
4. Contact your hosting provider if issues persist
