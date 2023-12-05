=== SizeMe for WooCommerce ===
Contributors: sizeme
Tags: sizeme, measurements, sizeguide, size guide, size recommendations
Requires at least: 3.8
Tested up to: 6.4.1
Stable tag: 2.3.4
Requires PHP: 5.2.4
WC requires at least: 2.5
WC tested up to: 8.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SizeMe is a web store plugin that enables your consumers to input their measurements and get fit recommendations based on actual product data.

== Description ==

SizeMe is a web store plugin that enables your consumers to input their measurements and get personalized fit recommendations based on actual product data.  It is designed to help your users to choose better fitting clothes.

It also provides a true-to-product size guide.  No more generic guides.

https://www.youtube.com/watch?v=RRrMBtog75A

[https://www.sizeme.com](https://www.sizeme.com/)

The plugin is fully WPML compatible and also works with multiple attributes (such as size and color).


== Features ==

* Integrates seamlessly with your existing store
* Product-specific size guide
* Accurate size recommendations
* Increase sales and reduce returns

Currently, the plugin does not support custom-made clothing, only fixed sizes.

== Installation ==

To install and take into use the SizeMe for WooCommerce plugin, follow the instructions below.

1. Upload "sizeme-for-woocommerce" contents to the "/wp-content/plugins/plugin-name" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Configure Size attributes if you don't already have them:

    Go to `Products -> Attributes -> Add new`: label: Size, slug: size

    Click the cogwheel to add attribute values (these values are just for example):

    * label: XS, slug: extra-small
    * label: S, slug: small
    * label: M, slug: medium
    * label: L, slug: large
    * label: XL, slug: extra-large

    If you have any other size attributes, go ahead and add them as well, e.g. Shoe size, Hat size etc.
4. Configure the plugin at `wp-admin/admin.php?page=wc-settings&tab=sizeme_for_woocommerce` (`WooCommerce -> Settings -> SizeMe`)

    **General settings**
    * Custom size selection: Whether to use the custom size selection buttons that SizeMe provides or not
    * Service status: The SizeMe service status
        * Test: Testing
        * On: Service is in use in production
        * Off: Service is off

    **Attribute settings**
    * Product size attributes: Select all your size attributes that you might use, e.g. Size, Shoe size etc.

    **UI Options**
    * These options are the HTML class names where you want the SizeMe plugin to be shown.
    The defaults here are suitable for the WooCommerce theme Storefront.
    You will need to adjust these values according to your theme, or if you want to place the SizeMe plugin in another HTML element.
5. Creating a product

    The SizeMe functionality only activates with products that can be found in the SizeMe Product Database which is hosted by SizeMe.

    The product and its sizes are recognized with their SKU values.  Contact [support@sizeme.com](mailto:support@sizeme.com) to upload your products.

    The functionality can be tested using the SKU "SHIRT-FOTL-T-SHIRT" for the main product, and "SHIRT-FOTL-T-SHIRT-S", "SHIRT-FOTL-T-SHIRT-M" (and so on) for the single sizes.  See [the demo store](https://www.sizemedemo.com/wordpress/) for reference.

== Screenshots ==

1. Seamless integration to existing site.  Functionality can be visible directly or behind "Find my size" toggler.
2. Offer a true-to-product size guide
3. For size recommendations, we ask for physical measurements of the users and offer proper measuring instructions for this
4. Visualize the fit using the SizeMe Sizing Bar™ and easy-to-understand illustrations

== Changelog ==
= 2.3.4 =
* Added user interface option for showing flat or circular measurements in size guide

= 2.3.3 =
* Litespeed compatibility improved

= 2.3.2 =
* Code cleanup

= 2.3.1 =
* Added shortcode "sizeme_write_scripts" to include required scripts
  * This is for cases where the normal WC hooks are not launched for some reason

= 2.3.0 =
* Added metric to imperial measurement switch with corresponding options
* Added possibility to specify which products are for men to show correct measurement guide videos

= 2.2.3 =
* Discontinued sending unused email hash

= 2.2.2 =
* Improved cookie handling again

= 2.2.1 =
* Cookie handling improved

= 2.2.0 =
* Options (finally) prefixed to avoid plugin conflicts and some significant code cleanup too

= 2.1.2 =
* Added support for the clear size selection button

= 2.1.1 =
* Fixed tracking of ajax-based add to cart events.  Also improved SKU handling with simple products.

= 2.1.0 =
* Added support for the maximum recommendation distance setting.  Used if you don't want to recommend sizes that are too far from the optimal size.

= 2.0.9 =
* Fixed sizeme_product object if the product's attribute is not global (product-specific)

= 2.0.8 =
* Fixed sizeme_product object (used to communicate with our product database) creation when the product doesn't have proper SKU's

= 2.0.7 =
* Fixed javascript bug when no product variation was present

= 2.0.6 =
* Switch to dynamic loading of integration code for performance improvement

= 2.0.5 =
* Improved handling in empty or broken products

= 2.0.4 =
* Fixed bug in order tracking

= 2.0.3 =
* Added fix for selector change event triggering

= 2.0.2 =
* Fixed bug in cart tracking

= 2.0.1 =
* Added clientKey to sizeme_options

= 2.0.0 =
* Fairly complete rewrite for SizeMe v3.0
* Supports only the SizeMe product database for measurements

= 1.0.0 =
* Initial release.

== Upgrade Notice ==
= 2.2.0 =
Internal option naming changed.  Upgrade normally and it should work fine.

= 2.0.6 =
Minor performance-related update.  Upgrade normally.

== Additional Information ==

* SizeMe for WooCommerce is actively developed at [GitHub](https://github.com/SizeMeCom/sizeme-for-woocommerce)
* Demo store with WPML enabled can be found at [SizeMeDemo.com](https://www.sizemedemo.com/wordpress/)
