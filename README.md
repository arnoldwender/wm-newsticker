# WM Newsticker

A lightweight Gutenberg block for creating animated news tickers in WordPress.

![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)
![License](https://img.shields.io/badge/License-GPL%20v2-green)
![Version](https://img.shields.io/badge/Version-1.4.4-orange)

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

## Changelog

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
