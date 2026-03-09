=== Fade Slider ===
Contributors: anand000
Tags: Slider, Carousel, Image Carousel, Bootstrap Slider, Fade Slider, Slide Animation, Responsive Carousel, WordPress Slider Plugin, Touch Navigation, Image Gallery Slider
Tested up to: 6.9
Requires: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Professional Bootstrap carousel slider for WordPress. Beautiful fade and slide animations, touch support, responsive design, and easy shortcode integration.

== Description ==

**Fade Slider** is a powerful Bootstrap-based carousel and image slider plugin for WordPress. Create stunning, responsive image carousels with smooth fade and slide animations. Perfect for showcasing products, portfolios, testimonials, and image galleries with full touch and keyboard navigation support.

Fade Slider combines the reliability of Bootstrap with modern responsive design to deliver a high-performance slider that works perfectly on mobile, tablet, and desktop devices. No coding required - manage everything from the WordPress admin panel.

= Key Features =

* **Bootstrap Carousel** - Built on the trusted Bootstrap framework for reliability and compatibility
* Fade and Slide **animation modes** with smooth CSS transitions
* **Fully responsive design** - Automatically adapts to mobile, tablet, and desktop screens
* **Touch navigation support** - Swipe gestures for mobile and tablet users
* **Keyboard navigation** - Arrow keys and accessibility features included
* Adaptive height scaling - Auto-adjusts to 70% on tablet, 60% on mobile
* Customizable autoplay interval and pause-on-hover behavior
* **Image slider with multiple media options** - Images, titles, descriptions, and clickable URLs
* Navigation indicators with dot navigation and arrow controls
* **Mobile-optimized captions** - Show/hide descriptions on small devices
* Drag and drop slide reordering with visual feedback and auto-save
* Easy shortcode integration - No coding required
* Zero complex setup - Drag, drop, and manage slides easily in WordPress admin

= Custom Options =

	1. Install and activate the plugin
	2. Create a new slider in the admin panel (Fade Slider menu)
	3. Configure animation type (Fade or Slide)
	4. Set carousel interval (delay between slides in milliseconds)
	5. Add slides with images, titles, descriptions, and URLs
	6. Set slider height/width (auto-scales to 70% on tablet, 60% on mobile, 50% on small phones)
	7. Choose to show/hide captions on small devices
	8. **Drag and drop slides to reorder** - Simply click and drag a slide thumbnail to change its display order. Visual feedback shows where the slide will be placed
	9. Add shortcode to page/post: [display_fade_slider id=SLIDER_ID]
	10. Or use template: <?php fade_slider_template('[display_fade_slider id=SLIDER_ID]'); ?>

= Shortcode Usage =

	[display_fade_slider id=1]

= Configuration Options =

* Animation: Choose between Fade or Slide effects
* Interval: Set auto-play delay (milliseconds) or turn off with 'off'
* Hover Pause: Pause slider on mouse hover
* Show Indicators: Display bottom dot navigation
* Show Arrows: Display prev/next buttons
* Description Responsive: Hide captions on mobile devices (only shows on desktop/tablet)
* Slider Height and Width: Auto-responsive scaling

= Frequently Asked Questions =

1. What makes Fade Slider different from other carousel plugins?

	Fade Slider is built on Bootstrap - the world's most popular CSS framework. This ensures compatibility, reliability, and professional styling out of the box. It's easier to use than vanilla JS sliders and more dependable than untested alternatives.

2. Is this Bootstrap carousel plugin easy to use?

	Yes. The plugin is designed for WordPress users of all skill levels. Simply install, create a slider in the admin panel, drag and drop your images, and add the shortcode to any page. No coding knowledge required.

3. Does it work on mobile devices?

	Yes. The plugin is fully responsive with adaptive height scaling:
	- Desktop (≥1200px): Full configured dimensions
	- Tablet (768px-1199px): 70% of viewport height
	- Mobile (≤767px): 50-60% of viewport height
	- Small phones (≤375px): 40% of viewport height

4. Can I customize animations?

	Yes. Two animation modes are available: Fade (opacity transition) and Slide (horizontal transition).

5. Is there keyboard/touch support?

	Yes. The carousel supports touch gestures, arrow keys for navigation, and full accessibility features.

6. How do I hide captions on mobile?

	In slider settings, select "Hide" for "Description Responsive" option. Captions will only show on tablets and desktops (≥768px).

7. How do I reorder slides?

	You can easily drag and drop slides to change their display order. Click and hold on a slide thumbnail, then drag it to the desired position. The slide order is automatically saved when you release it. Visual highlighting shows where the slide will be positioned.

8. Can I edit slide images after adding them?

	Yes. Click the edit icon (pencil) on any slide thumbnail to change the image. Click the trash icon to remove a slide entirely.

== Installation ==

1. Install the Bootstrap Carousel Slider plugin directly from WordPress, or upload plugin files to `/wp-content/plugins/fade-slider/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to **"Fade Slider"** in the WordPress admin menu to access the slider builder
4. Create a new carousel and start adding images
4. Create a new slider and configure your options
5. Add slides with images and optional titles/descriptions
6. Use the shortcode [display_fade_slider id=SLIDER_ID] in pages or posts

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png


== Changelog ==

= 1.0 =

* New: Fade Slider plugin

= 1.1 =

* Update Admin options and admin UI

= 2.0 =

* Update bootstrap version v3x to v3.3.7x
* Add multisite options
* Add intervel and nav controls
* Support Non Bootsrrap themes
* Multi slide options added.
* Slide title, description and url fields added
* Light weight plugin

= 2.1 =

* Admin UI changed
* Update metabox issues
* Silder Width and Height option added
* Slider description have option to show responsive

= 2.3 =

* Add edit slide option.
* Add re-order slides option.
* Add regenerate slide sizes.

= 2.5 =

* Update bootstrap 4.3.1.
* Fixed slide dimention issue.

= 2.6 =

* Major: Update to Bootstrap 5.3.3 with native carousel API
* Fix: Fade and slide animations now working properly
* New: Smooth CSS transitions with proper transform/opacity handling
* New: Responsive height scaling (70% tablet, 60% mobile, 50% small phone)
* New: Auto-responsive caption display with font scaling
* New: Enhanced mobile controls (larger touch targets)
* New: Indicator dots responsive sizing
* New: Bootstrap duplicate detection to prevent conflicts
* New: Drag and drop slide reordering with visual feedback
* New: Auto-save slide order when reordered
* Improved: Better touch and keyboard navigation support
* Improved: Mobile-first responsive design
* Improved: Caption visibility control on small devices

== Upgrade Notice ==

Important: Version 2.6 introduces major improvements with a Bootstrap-free implementation using pure CSS and vanilla JavaScript. Bootstrap is no longer required. All existing sliders will continue to work with automatic responsive scaling and improved performance. This update provides better compatibility with all themes and eliminates external dependencies.