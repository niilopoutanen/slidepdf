[![Banner](https://raw.githubusercontent.com/niilopoutanen/slidepdf/refs/heads/main/assets/banner-1544x500.png)](https://wordpress.org/plugins/slidepdf/)

<h1>
  <span style="display: inline-flex; align-items: center;">
    <img src="https://github.com/niilopoutanen/slidepdf/blob/main/static/icon.png?raw=true" 
         alt="Banner" 
         width="70" 
         style="margin-right: 10px; border-radius: 10px;">
    SlidePDF
  </span>
</h1>

WordPress plugin for embedding PDFs onto your site in a slider view.

<a href="https://wordpress.org/plugins/slidepdf/">
  <img src="https://raw.githubusercontent.com/niilopoutanen/slidepdf/refs/heads/main/static/wp-banner.svg" alt="Wordpress plugin page" height="50">
</a>


## Usage

### Shortcode
* `[slidepdf src="https://example.com/file.pdf"]`
  * Loads PDF in a slider view using SwiperJS.

* `[slidepdf src="https://example.com/file.pdf" page=3]`
  * Loads only a single page, not the whole PDF. SwiperJS will not be loaded onto the page, saving resources.


### Elementor
Search SlidePDF from widgets gallery. The customization options in the Elementor widget will override the global ones set in admin dashboard.



## Depedencies

* [SwiperJS](http://swiperjs.com/)
  * Basic JS bundle
  * CSS for pagination & minified base
* [PDFJS](https://mozilla.github.io/pdf.js/) by Mozilla
  * Base JS & worker
  * WASM files for JPEG support