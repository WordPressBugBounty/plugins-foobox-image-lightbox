=== Lightbox & Modal Popup WordPress Plugin - FooBox ===
Contributors: bradvin, fooplugins
Donate link: https://fooplugins.com
Tags: lightbox,modal,popup,images,gallery
Requires at least: 5.8
Requires PHP: 5.4
Tested up to: 7.0
Stable tag: 2.8.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A responsive image lightbox for WordPress galleries, WordPress attachments & FooGallery

== Description ==

FooBox adds a lightbox to your WordPress site, so images are loaded in a modal or popup instead of redirecting your visitors away from your site.

Some people often refer to a lightbox as an "Image Zoom" effect. It's really just a modal overlay or popup that showcases your images.

FooBox was the first lightbox to take responsive layout seriously. Not only does it scale images to look better on phones, but it rearranges it's button controls to look great in both portrait or landscape orientation.

Add a modal popup to your website images with no setup. FooBox will automatically add modals to WordPress galleries, WordPress images with captions, and attachment images.

**NEW: Auto Link Existing Images**

Have older posts with images that do not open in FooBox? That is probably because those images were inserted without linking to the full-size media file.
Enable Auto Link Existing Images and FooBox will automatically wrap unlinked WordPress attachment images on the frontend, so visitors can open them in the lightbox without you editing old content.

Works with most image gallery plugins, but works best with our [FooGallery Gallery WordPress Plugin](https://fooplugins.com/foogallery-wordpress-gallery-plugin/).

**FULL GUTENBERG SUPPORT**

FooBox adds native block-editor controls to core Button, Image, Gallery, and Group blocks:

* Image blocks support media linking, captions, style, theme, gallery grouping, maximum dimensions, large-image panning, and opening effects in FooBox Pro.
* Gallery blocks share style, theme, modern gallery grouping, and FooBox Pro opening effects with their child Image blocks.
* Button blocks can open images and auto-detected image URLs. FooBox Pro adds video, iframe, and hidden HTML targets, plus video-poster controls.
* Group blocks can become hidden inline content that a FooBox Pro Button opens in a modal.
* An opt-in compatibility setting lets FooBox take over Image and Gallery blocks using WordPress's own "Open in lightbox" option.

FooBox Pro is required for HTML, iframe, and video content handlers and for all opening effects. These controls remain available in the block editor so content can be configured before upgrading without being rebuilt.

**FooBox Image Lightbox Features:**

*	Responsive lightbox design
*	Modern lightbox design
*	Zero configuration!
*	Works with WordPress galleries
*	Works with WordPress captioned images
*	Auto links older unlinked WordPress attachment images
*	Control when to exclude / include FooBox JS &amp; CSS assets

**Includes a 7-day free trial of FooBox Pro Lightbox!**

You can try the PRO version for free for 7 days.

**[FooBox PRO](https://fooplugins.com/foobox/?utm_source=fooboxfreeplugin&utm_medium=fooboxfreeprolink&utm_campaign=foobox_free_wprepo) Features:**

*	Social sharing (10+ networks)
*	Video lightbox support
*	HTML lightbox support
*	iFrame support
*	Deeplinking
*	Fullscreen and slideshow modes
*	Glass lightbox theme (including custom color pickers)
*	Metro lightbox theme
*	Flat lightbox theme
*	5 color schemes, 12 button icons and 11 loader icons
*	12 animation effects
*	85+ settings to customize

**[FooBox PRO](https://fooplugins.com/foobox/?utm_source=fooboxfreeplugin&utm_medium=fooboxfreeprolink&utm_campaign=foobox_free_wprepo) Works With:**

*	[The Best Image Gallery Plugin for WordPress](https://fooplugins.com/foogallery-wordpress-gallery-plugin/)
*	NextGen
*	[Justified Image Grid](https://codecanyon.net/item/justified-image-grid-premium-wordpress-gallery/2594251)
*   Envira Gallery
*	WooCommerce product images (Works with WooCommerce v3+)
*	JetPack Tiled Gallery
*	AutOptimize

Check out the [full feature comparison](https://fooplugins.com/foobox-feature-comparison/?utm_source=fooboxfreeplugin&utm_medium=fooboxcomparelink&utm_campaign=foobox_free_wprepo).

**Complete FooBox Asset Control**

By default, FooBox lightbox includes javascript and stylesheet assets into all your pages. We do this, because we do not know if the page content contains media or not.
If you want more control over when FooBox assets are included, you can now exclude the assets by default, by enabling a setting. Then on each page, you can choose to include them when required.
Alternatively, you can leave the setting disabled, and then choose to exclude the FooBox assets from particular pages. A new metabox is now available when editing your pages or posts.
This new feature was only available in the PRO version beforehand, but we feel control over your website performance is something you should not have to pay for. Enjoy!


**Translations**

* [Serbo-Croatian by Borisa Djuraskovic](https://www.webhostinghub.com/)

== Installation ==

1. Upload `foobox-free` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. A modal lightbox will automatically be added to your linked images and galleries

== Block Editor Usage ==

FooBox adds a "FooBox" panel to supported core blocks in the WordPress block editor. Select a Button, Image, Gallery, or Group block, then open the block inspector sidebar to configure FooBox for that block.

For a quick reference inside WordPress, open FooBox > Getting Started > Block Editor. This help tab includes the common Image, Gallery, Button, and hidden-content workflows, plus a control reference and troubleshooting tips.

= Images =

1. Add or select an Image block.
2. Open the FooBox panel and enable FooBox.
3. Use "Link image to media file" if the image is not already linked to the full media file.
4. Optionally set a style, theme, gallery group, caption title, caption description, max size, or large-image panning. Opening effects require FooBox Pro.

= Galleries =

1. Add or select a Gallery block.
2. Open the FooBox panel and enable FooBox.
3. Use "Link gallery images to media files" so each image opens in FooBox.
4. Set an optional gallery group, style, or theme. FooBox mirrors gallery settings to the child Image blocks and groups them without relying on legacy `rel` attributes. Opening effects require FooBox Pro.

= Buttons =

1. Add or select a Button block.
2. Open the FooBox panel and enable FooBox.
3. Choose auto-detect or image for image targets. HTML content, iframe, and video targets require FooBox Pro.
4. Enter a Lightbox URL or target. Use "Select from media library" to choose an image or media file and fill the URL automatically.
5. Optionally set width, height, captions, style, theme, or gallery group. Opening effects and video poster images require FooBox Pro.
6. With FooBox Pro, hidden inline content can point to a target id such as `#demo-hidden-form`.

= Hidden HTML Content (FooBox Pro) =

Opening a Group block as hidden inline content requires FooBox Pro.

1. Add a Group block and build the content inside it using normal blocks.
2. Select the Group block and enable "Use as hidden FooBox content".
3. Set an HTML id or generate one from the FooBox panel.
4. Leave "Hide on front end" enabled when the content should only appear inside FooBox.
5. Create a Button block, enable FooBox, set the content type to HTML content, and point the Lightbox URL or target field to the Group id.

= WordPress Lightbox Compatibility =

FooBox can take over Image and Gallery blocks that use WordPress's own "Open in lightbox" option. Enable this under FooBox Settings > General > Compatibility > WordPress Lightbox. This setting is off by default for upgrade safety.

The Compatibility settings also include "Default Image Link" for setting WordPress's default image link behavior and "Auto Link Existing Images" for older attachment images that were inserted without media-file links.

== Frequently Asked Questions ==

= What is a WordPress gallery lightbox? =

A WordPress gallery lightbox displays images in a modal overlay above the page when clicked, allowing visitors to view larger images and navigate through a gallery without leaving the page.

= Does WordPress have a built-in lightbox? =

Yes. WordPress 6.4+ includes a basic Expand on Click feature for galleries. It provides a simple overlay but lacks advanced features like navigation controls, styling options, and social sharing.

= What is FooBox? =

FooBox is a WordPress lightbox plugin that adds customizable, responsive overlays for images, with zero configuration.

= Is FooBox mobile-friendly? =

Yes. FooBox supports responsive layouts, swipe gestures, and touch-optimized controls for mobile and tablet devices.

= Can FooBox be customized? =

Yes. FooBox PRO includes themes, color controls, icons, animations, caption display options, slideshow mode, fullscreen viewing, and deep-linking.

= Does FooBox slow down a website? =

No. FooBox is performance-optimized and loads assets only when needed. When combined with optimized images, it does not negatively impact page speed.

= FooBox is not working. I do not see a lightbox =

Make sure your images/galleries are set to Link To the Media File (within Gutenberg).
In the class editor, make sure your images/galleries are linking to the media file.
FooBox scans for images or thumbs that are pointing to the full-size version of the image. If the image is not linking to the full size image, then FooBox cannot work on that image.
You can tell if an image links to a full-size version when you can click on the image and the full size version opens in the browser.

= Can FooBox work with older images that were inserted without links? =

Yes. Enable the Auto Link Existing Images setting under FooBox Settings > Compatibility. FooBox will try to detect unlinked WordPress attachment images on the frontend and wrap them with a media-file link automatically, so they can open in FooBox without editing each old post or page.

For new content, we still recommend setting WordPress images to Link To > Media File. The Default Image Link setting in FooBox can help make that the default for future inserts.

= FooBox is not working. There is an error in the console "Uncaught ReferenceError: FooBox is not defined" =

Some plugins or themes defer javascript in the page, which causes the FooBox initialization code to run BEFORE the FooBox main script is loaded. This has been fixed in version 1.2.24. Please upgrade.

= My theme has a built-in lightbox and it shows under FooBox. What can I do? =

There is a setting to try and disable hard coded lightboxes, but this is not a sure-fire solution for every scenario. If that setting does not work for you, you might need to deregister certain javascript files, or uncomment certain lines of code in your theme to remove it's lightbox.

== Screenshots ==

1. Frontend example
2. Phone example

== Changelog ==

= 2.8.4 =
* Updated 22 Jul 2026
* New : Added a Block Editor help tab to the FooBox Getting Started admin pages.

= 2.8.3 =
* Updated 21 Jul 2026
* Fix : Kept FooBox controls visible in the WordPress 7 block inspector's Content tab.
* Fix : Applied each Gutenberg Gallery block's selected FooBox style and opening effect to its lightbox instance.

= 2.8.2 =
* Updated 21 Jul 2026
* Fix : Improved handling of encoded markup in caption titles and descriptions while preserving supported HTML formatting.

= 2.8.1 =
* Updated 20 Jul 2026
* Update : updated to Freemius SDK v2.13.4
* Docs : clarified which Gutenberg content handlers and opening effects require FooBox Pro.
* Fix : Sanitized WordPress caption titles and descriptions before rendering.

= 2.8.0 =
* Updated 25 Jun 2026
* New : Added FooBox block editor controls for Button, Image, Gallery, and Group blocks.
* New : Added Button block support for image and auto-detected lightbox targets, plus FooBox Pro HTML content, iframe, and video targets.
* New : Added a media-library selector for the Button block "Lightbox URL or target" field.
* New : Added FooBox Pro Group block support for hidden inline FooBox content, generated HTML ids, and frontend hiding.
* New : Added Image block controls for media linking, captions, style, theme, FooBox Pro opening effects, gallery grouping, sizing, and large-image panning.
* New : Added Gallery block controls that mirror parent style, theme, FooBox Pro effect, and group settings to child Image blocks.
* New : Added an opt-in WordPress Lightbox compatibility setting so FooBox can take over core Image and Gallery blocks using WordPress's "Open in lightbox" option.
* Update : Added `data-foobox-group` grouping support for block-editor galleries without relying on `rel` attributes.
* Update : Added Block Editor Usage guidance to the readme and expanded the usage documentation.

= 2.7.44 =
* Updated 27 May 2026
* Fix : Fixed a PHP warning in the WP Rocket compatibility layer when inline defer exclusions are passed as an array.
* Fix : Prevented Google Analytics tracking handlers from throwing JavaScript errors when a FooBox item URL is missing.

= 2.7.43 =
* Updated 14 May 2026
* New : Added an Auto Link Existing Images setting to automatically wrap unlinked WordPress attachment images so older posts can open in FooBox.
* New : Added Admin notice on FooBox Settings page to ask if you want to enable Auto Linking for old content.

= 2.7.42 =
* Updated 19 Jan 2025
* Update : Made sure all plugin check warnings were corrected.
* Update : Updated getting started admin page to include links to homepage and docs.
* Update : Freemius SDK update 2.13.0

= 2.7.38 =
* Updated 15 Sept 2025
* Update : Updated the settings page - moved promos and rate widget under Thanks tab (free only)
* Update : Freemius SDK update 2.12.2

= 2.7.37 =
* Updated 14 Sept 2025
* New : Added setting and admin notice for "Default Image Link", which allows users to set the default link for images that do not have a link set.

= 2.7.36 =
* Updated 08 Aug 2025
* Update : Updated to latest FooBox client JS & CSS 2.5.3
* Update : Freemius SDK update 2.12.1
* Fix : Fixed bug where lightbox buttons were showing as non crawable in lighthouse.

= 2.7.35 =
* Updated 03 July 2025
* Update : Updated to latest FooBox client JS & CSS 2.5.2
* Update : Freemius SDK update 2.12.0
* Fix : Fixed bug where double escaped HTML was not being parsed correctly.

= 2.7.34 =
* Updated 27 Jan 2025
* Update : Freemius SDK update 2.11.0
* Fix : Fixed bug where custom JS from settings was being double escaped.

= 2.7.33 =
* Updated 10 Nov 2024
* Update : Freemius SDK update 2.9.0

= 2.7.32 =
* Updated 4 Aug 2024
* Update : Freemius SDK update 2.7.3
* Update : Updated to latest FooBox client JS & CSS 2.4.13
* Fix : Fixed parsing of HTML captions, so that it strips harmful scripts.

= 2.7.29 =
* Updated 1 June 2024

= 2.7.30 =
* Updated 8 July 2024

= 2.7.29 =
* Updated 1 June 2024

= 2.7.28 =
* Update 4 May 2024
* Update : Freemius SDK update 2.7.2
* Fix : enhanced sanitization for settings.

= 2.7.27 =
* Updated 1 July 2023
* Update : Freemius SDK to 2.5.10

= 2.7.26 =
* Updated 10 April 2023
* Update : Freemius SDK to 2.5.6

= 2.7.25 =
* Updated 01 March 2023
* Update : Freemius SDK to 2.5.3
* Update : Updated to latest FooBox client JS & CSS 2.4.13

= 2.7.24 =
* Updated 03 March 2022
* Fix : Updated request handling in wp-admin
* Update : Freemius SDK to 2.4.3

= 2.7.22 =
* Updated 15 Jan 2022

= 2.7.20 =
* Updated 5 Sep 2021

= 2.7.20 =
* Updated 08/09/2021
* Update : Freemius SDK (develop)
* Fix : fixed conflict with PHP 8 and Russian translation (within Freemius SDK)
* New : added "REL Grouping" setting under Advanced to be able to change the grouping of images using the rel attribute
* New : added "Custom Excludes" setting under Advanced to add custom excludes so that FooBox does not bind to certain elements
* New : added "Specific CSS classes" setting under General to allow specifying custom selectors that FooBox can bind to

= 2.7.19 =
* Updated 27/12/2020
* New : added compatibility with Elementor custom captions
* New : added compatibility with WP Rocket to always exclude FooBox inline script to avoid javascript errors
* Update : Updated to latest FooBox client JS & CSS 2.4.6

= 2.7.17 =
* Updated 19/12/2020
* Fix : fix for loader appearing after the modal is closed in come instances
* Fix : additional jQuery 3+ compatibility - removed all deprecation warnings
* Update : Updated to latest FooBox client JS & CSS 2.4.5

= 2.7.16 =
* Updated 01/12/2020
* Fix : fixed settings page CSS due to conflict with some themes
* Fix : Updated deprecated jQuery warnings in prep for WP 5.6
* Update : Updated to latest FooBox client JS & CSS 2.4.4
* Update : Freemius SDK 2.4.1

= 2.7.15 =
* Updated 20/10/2020
* Update getting started page to include a CTA to open FooBox demo
* Added new setting to force the FooBox trial notice admin banner to never show
* Updated settings page with ratings CTA and included details of other plugins

= 2.7.14 =
* Updated 20/08/2020
* Fix : fixed bug where FooBox was ignoring FooGallery filters
* Update : Updated to latest FooBox client JS & CSS 2.4.2

= 2.7.13 =
* Updated 06/08/2020
* New : Speed improvements (replaced font with SVG images)
* New : Dropped support for IE7
* Update : Freemius SDK 2.4.0.1
* Update : Updated to latest FooBox client JS & CSS 2.4.0

= 2.7.11 =
*	Update : Freemius SDK 2.3.2
*	Update : Updated to latest FooBox client JS & CSS

= 2.7.8 =
*	Fix : turned off font preload by default
*	Fix : scrollbar bug in iOS13
*	Update : Updated to latest FooBox client JS & CSS

= 2.7.7 =
* Fix : Slideshow was stopping after the Lightbox is closed
* Update : Updated to latest FooBox client JS & CSS

= 2.7.6 =
* Update : Updated to latest FooBox client JS & CSS

= 2.7.5 =
* Fix : Fixed admin bug with certain galleries
* Update : Updated to latest FooBox client JS & CSS

= 2.7.3 =
* Fix : Fixed get_blog_list error
* Update : Freemius SDK 2.3.0

= 2.7.1 =
* Fix : Fixed swipe issues in Chrome
* Update : Updated to latest FooBox client JS & CSS

= 2.7.0 =
* New : Reworked how FooBox loads, to work better with optimization plugins (Autoptimize / WP Rocket)
* Remove : Removed support for Google+ in social sharing
* Update : Updated to handle Chrome's new allow attribute in videos
* Update : Updated to latest FooBox client JS & CSS

= 2.6.5 =
* New : Added support for SVG images
* New : Added support for WebP images
* Fix : Fixed scroll blocking violation warnings in dev tools
* Update : Updated to latest FooBox client JS & CSS

= 2.6.4 =
* Fix : Improved plugin request validation and data handling
* Fix : Included font-display for improved pagespeed score
* Update : Freemius SDK 2.2.4

= 2.6.3 =
* Added support for loading FooGallery galleries within a FooBox
* Fixed layout bugs with certain notched iPhones
* Updated to latest client JS & CSS fixing a few bugs
* Updated to latest Freemius SDK 2.2.3

= 2.6.0 =
* Added support for the Gutenberg gallery and image blocks
* Updated to latest Freemius SDK 2.1.1
* Fixed bug with horizontal scrollbar
* Fixed bug with paging + filtering in FooGallery
* Forced Youtube videos to open using youtube-nocookie.com
* Updated to latest client JS & CSS fixing a few bugs

= 2.5.3 =
* Updated to latest Freemius SDK 2.0.1

= 2.5.2 =
* Updated to latest client JS & CSS fixing a few bugs
* Removed deprecated functions for PHP 7.2 compatibility
* Updated to latest Freemius SDK 1.2.4

= 2.5.1 =
* Dropped support for IE7 and IE8 (removing CSS validation errors)
* Updated to latest client JS & CSS fixing a few bugs

= 2.5.0 =
* Added new setting to exclude FooBox assets by default
* Added metabox on all public post types to include/exclude FooBox assets
* Major version bump to avoid confusion with FooBox PRO 2.4.0.0

= 1.2.34 =
* Fix : lightbox was not working with FooGallery paging
* Updated to latest client CSS
* Updated to latest Freemius SDK 1.2.1.10
* Removed FooGallery admin notices

= 1.2.27 =
* Fix : default caption state was disabled when no settings were saved.
* Updated to latest Freemius SDK 1.2.1.7.1

= 1.2.26 =
* Fix : disappearing captions. Renamed "Show Captions" setting to "Hide Captions" and default to disabled.

= 1.2.25 =
* Added setting to disable captions
* Added setting to change image counter text

= 1.2.24 =
* Added better browser support for defer javascript loading added in 1.2.23

= 1.2.23 =
* Added support for plugins that defer javascript loading, e.g. AutOptimize

= 1.2.22 =
* Updated to latest JS and CSS fixing multiple issues and bugs

= 1.1.11 =
* Updated to latest JS and CSS fixing some bugs
* Updated to latest Freemius 1.2.1.5 SDK
* Free trial for PRO now included in getting started page

= 1.1.10 =
* Fix deactivation issue when PRO is activated

= 1.1.9 =
* New setting for dropping IE7 support (for valid CSS)
* Fix for when multiple jQuery versions loaded on page!
* Fix for not including scripts for setting 'disable other lightboxes'

= 1.1.8 =
* IMPORTANT : clear your site cache when updating - if you use a caching plugin.
* Added clear cache message to getting started page
* Removed duplicate settings page
* Updated opt-in message
* Fix : loosing scroll position when scrollbars are hidden

= 1.1.7 =
* Integrated Freemius tracking and upgrade system
* Moved FooBox into top-level menu item
* Complete overhaul of Getting Started page, including demo
* Updated to use latest FooBox JS and CSS

= 1.0.14 =
* Hide foo admin notice on mobile devices
* More CSS tweaks for admin on smaller screen sizes

= 1.0.13 =
* Updated settings page to be responsive
* Tested with WP 4.6

= 1.0.12 =
* Updated to use latest FooBox JS and CSS
* Removed discount for FooBox PRO

= 1.0.11 =
* Updated to use latest FooBox JS and CSS
* Updated settings to include demo tab
* Updated admin screens be responsive on phones

= 1.0.10 =
* Updated to use latest FooBox JS fixing few bugs
* Smarter admin warnings when using with FooGallery

= 1.0.9 =
* Updated to use latest FooBox JS fixing few bugs
* Reorder selectors so FooGallery can take preference in some cases

= 1.0.8 =
* Updated to use latest FooBox JS
* Added new Getting Started landing page on activation
* Added support for wp.org language packs
* Better FooGallery integration

= 1.0.7 =
* Updated to latest version of javascript and CSS files
* Plays better with FooBox PRO now

= 1.0.6 =
* Fixed navbar issues in Chrome on IOS

= 1.0.5 =
* Fixed URL query handling with add_query_arg on the admin plugins page

= 1.0.4 =
* Improved FooGallery support
* Added keyboard navigation support!
* 50% offer included for PRO version

= 1.0.3 =
* Added FooGallery support
* Added .nolightbox to exclusions
* Added .pot translation file
* Added Bottomless design banner to "FooBot Says..." tab

= 1.0.2.1 =
* Fixed jQuery dependency issue with themes that do not load jQuery by default

= 1.0.2 =
* Added setting "Show Captions On Hover"
* Added "FooBot Says..." tab on settings page

= 1.0.1 =
* first version!
