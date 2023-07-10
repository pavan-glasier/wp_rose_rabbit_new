=== WooCommerce GST Plugin ===
Contributors: starkinfo
Tags: gst, woocommerce, addon, woocommerce addon, gst tax, woocommerce tax, indian gst tax,HSN, HSN code, HSN code woocommerce  
Requires at least: 4.0
Requires PHP : 5.6
Tested up to: 5.6
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is for GST tax setting. It set all tax including Tax slabs setting for CGST, SGST and IGST automatically.

== Description ==

Using WooCommerce GST Plugin, you are able to manage the GST tax for your store. You can specify the GSTIN number on GST settings tab in woocommerce setting. You can also choose your site content single or multiple type product and according that you can choose tax slabs. Plugin also provide 'HSN Code' meta field for product unique code in general setting of product. 

A few features:

* Admin can see the invoices for the orders.
* Admin can configure GST settings.
* Admin can enter their GSTIN code and that will be shown on invoice.
* Meta field for 'HSN Code'.
* Admin can choose required tax slabs.
* Generates tax slabs with CGST, SGST and IGST automatically.

<a href="https://www.starkdigital.net/how-to-configure-gst-taxation-in-woocommerce"><strong>Visit plugin site</strong></a>

== Installation ==

1. Upload `woocommerce-gst-plugin` zip to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure WooCommerce -> GST Settings


== Frequently asked questions ==

= How do I remove tax slab? =
1. Click on GST settings Tab in woocommerce setting.
2. Remove unwanted tax slab from 'Select Multiple Tax Slabs'.


== Screenshots ==

1. GST Setting Option
2. Tax Slab
3. Additional Tax Classes 


== Changelog ==
= 2.1.2 =
* Compatibility:
* Compatible with WordPress latest version 5.8
* Compatible with woocommerce latest version 5.6.0
* Bug fixes

= 2.1.1 =
* Compatibility:
* Compatible with WordPress latest version 5.8
* Compatible with woocommerce latest version 5.5.2
* Feature:
* Attach order PDF invoice to email.


= 2.1.0 =
* Compatibility:
* Compatible with WordPress latest version 5.7.2
* Compatible with woocommerce latest version 5.5.1
* Bug:
* Template 3 bug fixing.
* Woocommerce settings under GST settings Store location state editable now.
* Bug fixed related bulk download resolved.
* Bug fix related to the product price.

= 2.0 = 
* Bulk Download Invoices.
* New Invoice Template (Template 3).
* Added UTGST support for five Union Territories of India.
* Added support for changing invoice number after order is placed.
* Added Support for adding invoice prefix & setup next invoice number manually.
* Bug: Fixed Auto-create Tax Slabs issue.

= 1.5.3 = 
* Compatible with WooCommerce latest tax update
* Minor bug fixing.

= 1.5.2 = 
* Show pdf invoice button on processing and completed order only.

= 1.5.1 = 
* Licence activation bug fixing.

= 1.5 =
* Ability for admin to add/edit customer GST number for order.
* Show PDF invoice on my account page for customer to download.
* Ability to change product tax display on PDF inovice.
* Ability for admin to download invoice from Order page.

= 1.4 =
* Added the itemised tax on email and invoice.

= 1.3 =
* Minor bug fixes related to licence activation.

= 1.2 =
* Added support for order invoices.

= 1.1 =
* Resolved the conflict with other plugin.

== Upgrade Notice == 

* This is the major release for "WooCommerce GST Plugin". Please update with latest version to avoid conflict with other plugin.