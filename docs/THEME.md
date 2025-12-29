# Theme Decision

## Decision
Use a lightweight **block theme** to keep the site fast, accessible, and compatible with the WordPress Site Editor.

**Primary recommendation:**
- **Twenty Twenty-Four** (core WordPress block theme)

**Alternative:**
- **GeneratePress** (lightweight theme with solid Gutenberg support)

## Page Builder Policy
- **No Elementor or WPBakery.**
- **Gutenberg blocks only.**

## Required Theme Capabilities
A chosen theme must support:
- **Navigation menus** (header primary navigation).
- **Footer composition** via **Footer widget areas** or the **Site Editor** (block templates/parts).
- **Block templates & template parts** for layout control in the Site Editor.
- **Responsive typography and spacing** out of the box.
- **Accessibility-ready** markup and keyboard-friendly navigation.

## Child Theme (If Needed)
Create a child theme only if custom templates or styles cannot be handled via the Site Editor.

**Steps:**
1. Create a new folder in `wp-content/themes/`, e.g. `yourtheme-child/`.
2. Add a `style.css` with a header that declares the parent theme:
   ```css
   /*
   Theme Name: YourTheme Child
   Template: yourtheme
   */
   ```
3. Add a `functions.php` to enqueue the child stylesheet:
   ```php
   <?php
   add_action('wp_enqueue_scripts', function () {
       wp_enqueue_style(
           'yourtheme-child',
           get_stylesheet_uri(),
           ['yourtheme-style'],
           wp_get_theme()->get('Version')
       );
   });
   ```
4. Activate the child theme in **Appearance → Themes**.
5. Keep overrides minimal and prefer block templates/parts where possible.
