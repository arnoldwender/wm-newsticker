# WM Newsticker

A lightweight Gutenberg block for creating animated news tickers in WordPress.

![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)
![License](https://img.shields.io/badge/License-GPL%20v2-green)
![Version](https://img.shields.io/badge/Version-1.4.6-orange)

## Features

### Animation Styles
- **Scroll (Marquee)** - Continuous horizontal/vertical scrolling
- **Fade** - Smooth fade in/out transitions
- **Slide** - Items slide in from any direction
- **Typing** - Typewriter effect with blinking cursor

### Content Sources
- Manual announcements with custom text and links
- Dynamic content from WordPress posts
- Filter by category or tag
- Custom post type support

### Customization
- 5 color controls (background, text, label, label text, border)
- Spacing controls (padding, margin)
- Border radius, width, style
- Box shadow presets
- Speed, font size, height adjustments

### User Controls (Optional)
- Play/Pause button
- Previous/Next navigation
- Progress indicator bar

### Additional Features
- RTL (Right-to-Left) language support
- Multilingual ready (EN, DE, ES included)
- Responsive design
- No jQuery dependency
- Lightweight (~3KB CSS, ~1KB JS)

## Installation

### From WordPress Admin
1. Download the latest release ZIP
2. Go to Plugins → Add New → Upload Plugin
3. Upload the ZIP file and activate

### Manual Installation
1. Download and extract to `/wp-content/plugins/wm-newsticker/`
2. Activate through the Plugins menu

## Usage

1. Edit a post or page in Gutenberg
2. Add the "Newsticker" block
3. Configure settings in the sidebar:
   - Choose animation type and direction
   - Add announcements or select posts as source
   - Customize colors and spacing
4. Publish

## Development

```bash
# Clone the repo
git clone https://github.com/arnoldwender/wm-newsticker.git
cd wm-newsticker

# Install dependencies
npm install

# Build for production
npm run build

# Development with watch
npm run start
```

## File Structure

```
wm-newsticker/
├── build/              # Compiled JS/CSS
├── languages/          # Translation files (DE, ES)
├── src/
│   ├── index.js        # Block editor component
│   ├── frontend.js     # Frontend animation script
│   ├── editor.scss     # Editor styles
│   └── style.scss      # Frontend styles
├── package.json
├── readme.txt          # WordPress.org readme
└── wm-newsticker.php   # Main plugin file
```

## Requirements

- WordPress 5.8+
- PHP 7.4+
- Gutenberg editor enabled

## Security

This plugin follows WordPress security best practices:

- All output escaped with `esc_html()`, `esc_attr()`, `esc_url()`
- All input sanitized with `sanitize_text_field()`, `sanitize_key()`, `absint()`
- Color values validated with strict regex (hex, rgb/rgba, CSS variables)
- Enum attributes validated with allowlists (`in_array` strict mode)
- Numeric attributes clamped within safe ranges
- REST API endpoints protected with `permission_callback`
- Post type queries restricted to registered public types only
- Block attributes constrained with JSON Schema (`enum`, `minimum`, `maximum`)
- Singleton pattern hardened against cloning and unserialization
- Frontend JS uses safe DOM APIs only (no `innerHTML`)
- Editor JS uses `AbortController`, `addQueryArgs`, and `decodeEntities`
- `prefers-reduced-motion` respected for all animation types

To report a vulnerability, see [SECURITY.md](SECURITY.md).

## Changelog

### 1.4.7
- Security: Fixed CSS injection via incomplete regex in color sanitization
- Security: Fixed path traversal in REST API path construction
- Security: Added public post type validation to prevent unauthorized content exposure
- Security: Hardened singleton pattern with `__clone()`/`__wakeup()` protection
- Security: Added `defined()` guards on plugin constants
- Security: Added field allowlist in editor to prevent prototype pollution
- Security: Added `AbortController` to prevent race conditions on API fetches
- Accessibility: ARIA labels now update dynamically on play/pause toggle
- Accessibility: `prefers-reduced-motion` now respected for JS-driven animations
- Fix: Hover no longer overrides manual pause state
- Fix: Play/pause icons now update correctly on hover in scroll ticker
- Fix: Timezone consistency using `wp_date()` and `current_time()`
- Fix: HTML entities in post titles now decoded correctly in editor preview
- Fix: Editor font size and height ranges now match server-side limits
- Hardening: Added `enum` constraints on all string-enum attributes in block.json
- Hardening: Added `minimum`/`maximum` on numeric attributes in block.json
- Hardening: Added `items`/`properties` schemas on array/object attributes in block.json
- Hardening: All dynamic class attributes now use `esc_attr()` (WPCS compliance)
- Hardening: Support for 4/8-digit hex colors and named colors (transparent, inherit)

### 1.4.6
- Version bump with dependency updates

### 1.4.4
- Cleaned up code for WordPress.org submission

### 1.4.0
- Added spacing and border controls
- Redesigned color picker UI

### 1.3.0
- Added dynamic content from posts
- Category/tag filtering

### 1.2.0
- Added user controls (play/pause, prev/next)
- Progress indicator

### 1.1.0
- Added fade, slide, typing animations
- RTL support

### 1.0.0
- Initial release

## License

GPL v2 or later - see [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html)
