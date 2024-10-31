=== Nobesho ===
Contributors: nobesho
link: https://nobesho.com/
Tags: Market-Place, E-Commerce, Shop
Requires at least: 4.2
Tested up to: 5.7.2
Stable tag: 1.0
Requires PHP: 7.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This Plugin provides an interface for sending products and their conditions  to Nobesho's end point by Woocommerce webhook
 
== Description ==

This plugin belongs to Nobesho's sellers , it has the capability of synchronizing the Woocommerce's products to Nobesho's pannel.
Nobesho produces a secret key to creating the Wooocommerce Webhook ,wordpress admin should have the secret key that produced by Nobesho and use that in this plugin. 
this secret key will be use when Woocommerce Webhook have been created. 
secret key get used in order to authenticating the request while a product sends to nobesho by Webhook.

Requirements:
- SSL
- Wordpress version 4.2 or above
- Woocommerce version 4.0.0 or above

You should know that we're using our API to move your Woocommerce products, And this API has been made by our strong developers to help you better!
If you want to know more about the API, you can [check this out](https://nobesho.com/faq)!
By installing our plugin, you will agree with our [terms of use](https://nobesho.com/privacy-policy/).


== Installation ==

after activation you need Nobesho's secret key , you can get it from your Nobesho's pannel .
 and then copy paste the key in plugin's dashboard and finally click on Submit button to creating webhook by woocommerce . 
now you can send your products to your Nobesho's pannel and even your discounts on Woocommerce products or price and stock changing on product can cause the webhook fire and send updates to nobesho  


== Changelog ==

= 1.0 =
* Initial release