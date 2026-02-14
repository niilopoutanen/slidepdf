=== SlidePDF ===

Contributors: niilopoutanen
Tags: pdf, pdf-viewer, slider, embed, document
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Tested up to: 6.9
Stable tag: 0.2.4

Embed PDFs onto your site in a slide view.

== Description ==

SlidePDF renders PDFs in the browser and shows them in a swipeable slider. Single-page view is also supported for linking to a specific page.

* Shortcode: `[slidepdf src="https://example.com/file.pdf"]`. Add `page="3"` for a single page.
* Elementor: add the SlidePDF widget and pick a PDF (Media Library or URL).
* Global behaviour and colours are set under Tools → SlidePDF. The Elementor widget can override specific configurations or styles.

Uses the plugin’s own Swiper bundle and PDF.js so it doesn’t depend on Elementor’s scripts.

== Frequently Asked Questions ==

= How do I change how many slides are visible? =

In Elementor, use the widget’s “Slides per view” control. For the shortcode, set the default under Tools → SlidePDF (same page where you set transition speed, loop, colours, etc.).

= What is used for the slider and PDF rendering? =

Swiper JS (slider) and Mozilla’s PDF.js (rendering). Both are bundled with the plugin.

= Where are the plugin settings? =

Tools → SlidePDF.

== Changelog ==

= 0.2.4 =
* Swiper: use only the plugin’s bundled Swiper 

= 0.2.3 =
* Bug fixes with missin WASM support

= 0.2.2 =
* Minor translation fixes

= 0.2.1 =
* Translation system and Finnish strings

= 0.2.0 =
* Initial release