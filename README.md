=== Frontend WooCommerce Product Submission ===
Contributors: MuhammadAsim
Tags: WooCommerce, frontend submission, product submission, add product, multiple images
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 7.0
Stable tag: 1.4
License: MIT
License URI: https://opensource.org/licenses/MIT

A simple plugin that allows WooCommerce product submissions from the frontend, enabling users to add products with descriptions, prices, and multiple images.

== Description ==

This plugin enables frontend product submissions for WooCommerce. Users can submit products with:
* Product name
* Description
* Regular and sale prices
* Multiple images (one primary image and additional gallery images)

It’s perfect for websites that want to allow users or vendors to submit products directly without accessing the WordPress dashboard.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/frontend-woocommerce-product-submission` directory, or install through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Ensure WooCommerce is installed and activated.

== Usage ==

To display the product submission form on any page or post, add the following shortcode:

`[frontend_product_form]`

This shortcode will render a form that allows users to submit products with the following fields:
* Product Name
* Description
* Regular Price
* Sale Price (optional)
* Product Images (multiple images can be uploaded)

== Author Information ==

* **Author**: Muhammad Asim
* **Website**: [https://workwithasim.com](https://workwithasim.com)



== Frequently Asked Questions ==

= Do I need WooCommerce to use this plugin? =
Yes, this plugin requires WooCommerce to be installed and activated.

= Can I add custom fields to the submission form? =
Currently, the plugin supports only basic product fields. You may customize it by adding fields in the code.

= Where do submitted products appear? =
Submitted products are added to WooCommerce as simple products with the status "Published."

== License ==

This plugin is licensed under the MIT License. You are free to use, modify, and distribute it under the conditions of the MIT License.
