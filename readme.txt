=== WM Newsticker ===
Contributors: arnoldwender
Tags: news ticker, marquee, announcements, breaking news, gutenberg block
Requires at least: 5.8
Tested up to: 6.9
Stable tag: 1.4.4
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Animated news ticker block with scroll, fade, slide and typing animations. Dynamic posts support.

== Description ==

A Gutenberg block for creating animated news tickers. Supports multiple animation styles (scroll, fade, slide, typing), dynamic content from posts, RTL languages, and full customization.

= Features =

**Animation Styles**

* **Scroll (Marquee)** - Classic continuous horizontal/vertical scrolling
* **Fade** - Smooth fade in/out transitions
* **Slide** - Items slide in from any direction
* **Typing** - Typewriter effect with blinking cursor

**Dynamic Content Sources**

* Manual announcements with custom text and links
* Automatic display of recent WordPress posts
* Filter by category or tag
* Custom post type support
* Show post dates (relative or absolute format)

**Full Customization**

* 5 customizable colors (background, text, label, label text, border)
* Complete spacing controls (padding, margin)
* Border radius for rounded corners
* Border width, style, and color
* Box shadow presets (small, medium, large, inset)
* Adjustable speed, font size, and height
* RTL (Right-to-Left) language support

**User Controls (Optional)**

* Play/Pause button
* Previous/Next navigation
* Progress indicator bar
* Position controls left or right

**Multilingual Ready**

* English, German (Deutsch), and Spanish (Español) included
* Translation-ready for additional languages
* Compatible with WPML and Polylang

= 🎯 Perfect For =

* Breaking news and alerts
* Stock tickers and financial updates
* Event announcements
* Product promotions and sales
* Sports scores and updates
* Social media feed highlights
* Company announcements
* Emergency notifications

= 🚀 Performance =

* Lightweight (~3KB CSS, ~1KB JS per ticker)
* No jQuery dependency
* CSS-based animations (GPU accelerated)
* No external API calls
* Server-side rendering

= 🔒 Security =

* All inputs properly sanitized and escaped
* No database queries (uses WordPress APIs)
* Follows WordPress coding standards
* Regular security audits

== Installation ==

1. Upload the `wm-newsticker` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create or edit a post/page and add the "Newsticker" block
4. Configure your ticker settings in the block sidebar
5. Publish and enjoy!

== Frequently Asked Questions ==

= Does this plugin work with the Classic Editor? =

No, WM Newsticker is a Gutenberg block and requires the WordPress Block Editor. If you're using the Classic Editor, you'll need to switch to Gutenberg or use a different plugin.

= Can I have multiple tickers on the same page? =

Yes! You can add as many Newsticker blocks as you need. Each ticker operates independently with its own settings.

= Does it work with custom post types? =

Yes, when using the "Recent posts" content source, you can select any public custom post type that has REST API support enabled.

= Is it compatible with page builders? =

WM Newsticker works with any page builder that supports Gutenberg blocks, including Elementor (with Gutenberg widget), Divi, Beaver Builder, and others.

= Can I change the animation direction? =

Yes, you can choose from four directions: Left, Right, Up, or Down. This works with all animation types.

= How do I make the ticker full-width? =

Select the block and use the alignment options in the toolbar to choose "Wide width" or "Full width" alignment.

= Does it support RTL languages? =

Yes! Enable the RTL toggle in the Animation panel for proper right-to-left text display and animation direction.

= Can I pause the ticker on hover? =

Yes, the "Pause on hover" option is enabled by default. You can disable it in the Animation settings.

= How do I show recent posts instead of manual text? =

In the "Content Source" panel, change the source from "Manual announcements" to "Recent posts". Then configure the post type, number of posts, and optional filters.

= Can I filter posts by category? =

Yes, when using posts as the content source, you can filter by a specific category or tag.

== Screenshots ==

1. Newsticker block in the editor with live preview
2. Animation settings panel with type and direction options
3. User controls configuration (play/pause, navigation)
4. Content source selection - manual or dynamic posts
5. Colors panel with compact color pickers
6. Spacing and borders customization
7. Frontend display with scroll animation
8. Frontend display with typing animation
9. Mobile responsive design

== Changelog ==

= 1.4.4 =
* Cleaned up promotional content
* Updated author information
* Simplified readme

= 1.4.3 =
* Fixed: Plugin URI and Author URI are now different (WordPress.org requirement)
* Fixed: Description changed to English

= 1.4.2 =
* Fixed: Plugin name in readme.txt now matches plugin header
* Fixed: Updated "Tested up to" to WordPress 6.9
* Fixed: Short description shortened to under 150 characters

= 1.4.1 =
* Fixed: Plugin Check compatibility - removed deprecated load_plugin_textdomain
* Fixed: Proper escaping for all output attributes
* Fixed: Using wp_print_inline_script_tag for inline scripts
* Removed: README.md (WordPress.org uses readme.txt)

= 1.4.0 =
* New: Complete spacing controls (padding, margin)
* New: Border radius customization
* New: Border width, style, and color options
* New: Box shadow presets
* Improved: Redesigned color controls with compact pickers
* Improved: Better color input with hex field

= 1.3.0 =
* New: Dynamic content from WordPress posts
* New: Filter posts by category or tag
* New: Custom post type support
* New: Show post dates (relative/absolute)
* New: Order posts by date, title, modified, random, or comments

= 1.2.0 =
* New: Optional user controls (play/pause, prev/next)
* New: Progress indicator bar
* New: Controls position option (left/right)

= 1.1.0 =
* New: Fade animation type
* New: Slide animation type
* New: Typing (typewriter) animation
* New: Directional controls (left, right, up, down)
* New: RTL language support

= 1.0.0 =
* Initial release
* Scroll (marquee) animation
* Manual announcements with links
* Customizable colors and styling
* Multilingual support (EN, DE, ES)

== Upgrade Notice ==

= 1.4.0 =
Major update with full spacing and border customization. Redesigned color controls for better usability.

= 1.3.0 =
New dynamic content feature! Display your latest posts automatically in the ticker.

== Additional Information ==

= Requirements =

* WordPress 5.8 or higher
* PHP 7.4 or higher
* Gutenberg editor enabled

= Support =

For support, feature requests, or bug reports, use the WordPress.org support forum or open an issue on GitHub.

= Privacy =

This plugin does not collect any user data, does not use cookies, and does not connect to external services.
