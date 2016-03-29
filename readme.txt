=== sizeme-wordpress ===
Contributors: SizemeCom
Tags: sizeme, measurements
Requires at least: 3.0.1
Tested up to: 3.6
Stable tag: 4.5
WC requires at least: 4.0
WC tested up to: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SizeMe is a service where you can store your physical measurements and use them at clothes retailers for size
recommendations.

== Description ==
SizeMe is a service where you can store your physical measurements and use them
at clothes retailers to get size recommendations and personalized information on
how the item will fit you. So basically it's like magic.

[http://www.sizeme.com](http://www.sizeme.com/)

== Installation ==
To install and take into use the SizeMe Measurements plugin, follow the instructions below.

1. Upload "sizeme-wordpress" contents to the "/wp-content/plugins/plugin-name" directory.
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
4. Configure the plugin at `wp-admin/admin.php?page=wc-settings&tab=sizeme_measurements` (`WooCommerce -> Settings -> SizeMe Measurements`)

    ##General settings
    * Custom size selection: Whether to use the custom size selection buttons that SizeMe provides or not
    * Service status: The SizeMe service status
        * Test: Testing
        * On: Service is in use in production
        * Off: Service is off

    ##Attribute settings
    * Product size attributes: Select all your size attributes that you might use, e.g. Size, Shoe size etc.

    ##UI Options
    * These options are the HTML class names where you want the SizeMe Measurements plugin to be shown.
    The defaults here are suitable for the WooCommerce theme Storefront.
    You will need to adjust these values according to your theme, or if you want to place the SizeMe plugin in another HTML element.
5. Creating a product

    When creating a new product, or updating an old one, you will need to add the SizeMe Measurements attributes to the product.

    **NOTE:** Only a "Variable Product" can use the SizeMe Measurements attributes.

    Navigate to the "Product Data" section, and choose the "Attributes" tab.
    You MUST have only ONE (1) size attribute selected for the product. If you have multiple, the plugin won't work.
    Remember to check the check-box "Used for variations".

    Navigate to the "SizeMe" tab in the "Product Data" section.
    You MUST add the following SizeMe attributes for the product (these are general attributes for the whole product):

    * SizeMe Item Type (as defined in SizeMe for product types - Shirts, Shoes etc.)
    * SizeMe Item Layer
    * SizeMe Item Thickness
    * SizeMe Item Stretch

    To save the "SizeMe Item" attributes, you need to save the whole product by clicking on the "Publish" or "Update" button
    in the top right-hand side of the page.

    Now you should add the SizeMe attributes to your product variations:
    Navigate to the "Variations" tab in the "Product Data" section.

    Add or choose a variation, and fill in the necessary SizeMe attributes for the variation (e.g. for shoes):

    * Shoe inside width
    * Shoe inside length

    Hoodies:

    * Chest
    * Waist
    * Sleeve
    * Sleeve top width
    * Wrist width
    * Shoulder width
    * Front height
    * Hips
    * Hood height

    Click "Save changes" to save the updated values.

== Changelog ==

= 1.0.0 =
* Initial release.
