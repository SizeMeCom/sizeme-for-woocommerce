=== sizeme-for-woocommerce ===
Contributors: SizemeCom
Tags: sizeme, measurements, size guide, size recommendations
Requires at least: 3.8
Tested up to: 5.0
Stable tag: 2.0.0
WC requires at least: 4.0
WC tested up to: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SizeMe is a web store plugin that enables your consumers to input their measurements and get personalised fit recommendations based on actual product data.

== Description ==

SizeMe is a service for retailers who want to help buyers find better fitting clothes and shoes.  It uses an unique mathematical algorithm to store personal measurement data and recommend correct sizes.

It also provides a true-to-product size guide.  No more generic guides.

[https://www.sizeme.com](https://www.sizeme.com/)

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

    ##General settings
    * Custom size selection: Whether to use the custom size selection buttons that SizeMe provides or not
    * Service status: The SizeMe service status
        * Test: Testing
        * On: Service is in use in production
        * Off: Service is off

    ##Attribute settings
    * Product size attributes: Select all your size attributes that you might use, e.g. Size, Shoe size etc.

    ##UI Options
    * These options are the HTML class names where you want the SizeMe plugin to be shown.
    The defaults here are suitable for the WooCommerce theme Storefront.
    You will need to adjust these values according to your theme, or if you want to place the SizeMe plugin in another HTML element.
5. Creating a product

    When creating a new product, or updating an old one, you will need to add the SizeMe attributes to the product.

    **NOTE:** Only a "Variable Product" can use the SizeMe attributes.

    SizeMe hosts the product measurements in its own product database.  Contact [support@sizeme.com](mailto:support@sizeme.com) to upload your products.

== Changelog ==

= 2.0.2 =
* fixed bug in cart tracking

= 2.0.1 =
* added clientKey to sizeme_options

= 2.0.0 =
* Fairly complete rewrite for SizeMe v3.0
* Supports only the SizeMe product database for measurements

= 1.0.0 =
* Initial release.
