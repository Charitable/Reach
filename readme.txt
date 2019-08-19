=== Reach WordPress Theme ===
Contributors: WPCharitable, Studio164a
Donate link: https://www.wpcharitable.com
Tags: blue, light, two-columns, right-sidebar, fluid-layout, responsive-layout, custom-colors, custom-menu, featured-images, full-width-template, post-formats, sticky-post, theme-options, threaded-comments, translation-ready
Requires at least: 4.2
Tested up to: 5.2.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Reach is the perfect theme for charities & non-profits. Designed and developed by the creators of Charitable, a beautiful WordPress donation plugin, Reach is focused on helping organisations create an attractive website with a strong focus on fundraising campaigns.

-------------------------------------------------------
Getting started with Reach WordPress Theme
-------------------------------------------------------

= Installation =

1. Sign into your WordPress dashboard, go to Appearance > Themes.
2. Click Add New.
3. Search for "Reach".
4. Click Install.
5. After WordPress installs the theme, click Activate.
6. You've successfully installed your new theme!

We strongly recommend installing the Reach child theme if you think you will make any modifications to your theme. You can download the child theme from https://s3-us-west-1.amazonaws.com/charitable-bucket/wp-content/uploads/2016/02/reach-child-theme-1.0.0.zip

To install the child theme, first make sure you have installed Reach. Once you have done that, go to Appearance > Themes and follow these steps:

1. Click Add New.
2. Click "Upload Theme".
3. Upload the Reach child theme zip file.
4. Click Install Now.
5. After WordPress installs the theme, click Activate.

----------

= Using the Theme Customizer =

You can change Reach's theme options via the Customizer.

* To access the theme customizer, click Appearance → Customize in the WordPress admin menu.
* Footer: Upload your own image to display in the footer. If it's a horizontally repeating pattern, set it to Tile Horizontally. You can also add a tagline to display on the right side of your footer.
* Social Networks: Add your social media URLs. Leave blank any networks that you don't use.
* When you’re finished making changes, click Save & Publish to save the settings. Check out your site to confirm your changes.

----------

= Menu Setup =

You’ll need to create at least one new menu for the header.

* WordPress menus can be found under Appearence → Menus.
* If you don’t have a menu already, click create a new menu to create one.
* On the left hand side of the Menu page, select the pages to add to your menu and click Add to Menu. Drag the pages around and arrange them any way you’d like. Create a drop menu by dragging menu items under and to the right of another menu item.
* Now that you have the menu created, you need to assign it to the Primary Navigation location in the Theme Locations section.
* Save the menu when finished.

== Frequently Asked Questions ==

= I need help! What should I do? =

Get in touch via our contact form at https://www.wpcharitable.com/support/

= Is there more documentation? =

There sure is! You can find more documentation about Reach on the Reach demo site at http://demo.wpcharitable.com/reach/documentation/

= License Info =
Font Awesome - ​http://fontawesome.io
License: SIL OFL 1.1, CSS: MIT License (http://fontawesome.io/license)
Copyright: @davegandy

FitVids, Copyright 2013 Chris Coyier
License: WTFPL license (http://sam.zoy.org/wtfpl/)
Source: http://fitvidsjs.com/

RRSSB - http://kurtnoble.com/labs/rrssb/
License: MIT License (https://github.com/kni-labs/rrssb/blob/master/LICENSE.md)
Copyright: 2014-2015 Daniel Box and Joshua Tuscan

Countdown for jQuery - http://keith-wood.name/countdown.html
License: MIT license (http://keith-wood.name/licence.html)

leanModal - http://leanmodal.finelysliced.com.au/
License: MIT and GPL licenses

Raphaël - http://raphaeljs.com
License: MIT license (http://raphaeljs.com/license.html)
Copyright © 2008-2012 Dmitry Baranovskiy (http://raphaeljs.com)
Copyright © 2008-2012 Sencha Labs (http://sencha.com)

== Change Log ==

= 1.1.3 - 19/08/2019 =
* Fixed responsive styling for custom campaign grids. [#72](https://github.com/Charitable/Charitable/issues/72)

= 1.1.2 - 10/05/2019 =
* Fixed issue with images in campaign grids getting stretched out of proportion.

= 1.1.1 - 09/05/2019 =
* Fixed problem with multiple textdomains.

= 1.1.0 - 09/05/2019 =
* Added support for different numbers of columns for campaign grids. [#47](https://github.com/Charitable/Reach/issues/47)
* Improved styling for login form on mobile. [#40](https://github.com/Charitable/Reach/issues/40)
* Fixed behaviour of anonymous donations checkbox in modal donation forms. [#46](https://github.com/Charitable/Reach/issues/46)
* Tidied up layout of credit card fields in the donation form on small screens. [#57](https://github.com/Charitable/Reach/issues/57)
* Tightened layout of the donation form on mobile, reducing padding around the sides. [#56](https://github.com/Charitable/Reach/issues/56)
* Fixed placement of mobile menu icon and social media icons. [#45](https://github.com/Charitable/Reach/issues/45)
* Fixed bug when no menu is assigned to the User Dashboard location. [#52](https://github.com/Charitable/Reach/issues/52)
* Improved alignment of the campaign stats in Boxed layout. [#59](https://github.com/Charitable/Reach/issues/59)
* Reduced size of campaign image in Boxed layout on large screens. [#51](https://github.com/Charitable/Reach/issues/51)

= 1.0.12 - 21/06/2017 =
* Switched to using `date_i18n` instead of `date` to ensure months are translated where available.
* Removed unused templates.

= 1.0.11 - 3/05/2017 =
* Fixed styling issue on header above donation amounts on small screens.

= 1.0.10 - 27/02/2017 =
* Fixed the width of the donation form on campaign pages when no widgets are activated. [#37](https://github.com/Charitable/Reach/issues/37)
* Fixed the width of the banner on 404 pages. [#35](https://github.com/Charitable/Reach/issues/35)

= 1.0.9 - 13/09/2016 =
* Fixed an issue that resulted in 'undefined' being displayed at the bottom of the mobile menu when Charitable is not activated. [#34](https://github.com/Charitable/Reach/issues/34)

= 1.0.8 - 31/08/2016 =
* Improved the styling of links within the top content area in the homepage template, to ensure that links are visible (cannot have blue links on a blue background!) and links within sliders like Slider Revolution are visible. [#32](https://github.com/Charitable/Reach/issues/32) and [#33](https://github.com/Charitable/Reach/issues/33)
* Fixed a fatal error that occured when removing either the body background image or the blog and page banners.

= 1.0.7 - 26/08/2016 =
* Improved styling of the campaign donation widget, which was broken with version 1.4 of Charitable.
* Display extra campaign media placement setting when Charitable Videos is enabled.

= 1.0.6 - 25/08/2016 =
* First official release on WordPress.org

= 1.0.2 - 16/05/2016 =
* Fixed HTML error in comments area.
* Improved the way the layout is handled to prevent the menu and site branding areas from overlapping with each other.
* Removed deprecated functions (left over from the Franklin theme).
* Added templates for campaign category & tag archives.
* Removed empty campaign-share-modal.php template file.
* Removed unused audio.js file.

= 1.0.1 - 21/04/2016 =
* Auto calculate the layout of the header using a little touch of Javascript.
* Expanded readme with more detailed information, particularly about licensing.
* Fixed Customizer issues.

= 1.0.0 - 13/04/2016 =
* Initial release.