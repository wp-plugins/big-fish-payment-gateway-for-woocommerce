=== BIG FISH Payment Gateway for WooCommerce ===
Contributors: BIG FISH Kft.
Tags: Payment,credit card, internet payment, online payment
Requires at least: 4.1.1
Tested up to: 4.1.1
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

BIG FISH Payment Gateway is available now for webshops as a WooCommerce module.

== Description ==

BIG FISH Payment Gateway system provides more different payment solutions for webshops, where all the payment methods of the webshop can be managed in one place. The appearance of the user side of our module is simple and clear, the task of the customer is only to choose the most convenient payment method and to provide transaction data on the landing page of the bank / payment service provider reached through the module's safe gateway.

Configuration of the module is very simple, anyone is able to treat the interface. Administrators can find payment method settings and general settings by clicking on WooCommerce / Settings / Checkout and then on the name of the relevant payment method or the module.

At general settings of the module administrators can set up or modify the name of the webshop in the BIG FISH Payment Gateway system ("Store name") and the API key ("API key") that is required for the authentication. If administrators want to shift the module to test or live operation, they can do it by switching "Test mode" ("Yes" or "No"). In the list of available banks or payment service providers ("Available providers") administrators can see all of our partners whose solutions can be implemented into your webshop by our module.

Each payment methods of the merchant's BIG FISH Payment Gateway account can be configurated in the module separatedly.Administrator can set up each payment methods to test or live status and provide the names appeared on the page of the payment method ("Display name"). If the payment method supports both immediate and delayed payments "two steps payments" this option ("Authorisation") can be configurated in the module ("Immediate" or "Later"). In case of setting two steps payment method you always need to provide "Encrypt public key".

BIG FISH Payment Gateway module can solve rapid exchange of information between the webshop and the banks / payment service providers, so administrator can see the system messages related to a transaction of a specific order in real time (in "Order notes" boksz) and the status of the payment of each orders. The status of orders (transactions) can be:

* started ("Pending payment"),
* in progress ("Processing),
* on hold ("On hold"),
* finished/authorised ("Completed"),
* cancelled ("Cancelled"),
* refunded ("Refunded") and
* Failed ("Failed).

Approval of two steps payment transactions can be set by switching the status of the payment to "Completed" or "Cancelled".

== Installation ==

= Automatic installation =
1. Log in WordPress Admin site.
2. Choose "Plugins > Add New" option from menu on the left side of the page.
3. To "Search Plugins" box please write "BIG FISH Payment Gateway for WooCommerce" and press Enter.
4. There you will find "BIG FISH Payment Gateway for WooCommerce" plugin. Click on "Install Now" for installation.

= Manual installation =
1. Load BIG FISH Payment Gateway plugin in zip format.
2. Log in WordPress Admin site.
3. Choose "Plugins > Add New" option from menu on the left side.
4. Click on "Upload Plugin" and choose BIG FISH Payment Gateway plugin zip file.
5. Then click on "OK" button and press "Install Now".

= Note =
* After successful installation please activate the plugin by pressing "Activate Plugin" button.
* Please select "WooCommerce > Settings" option from menu on the left side and click on "Checkout" tab.
* You can configurate plugin at "BIG FISH Payment Gateway" option.

== Frequently Asked Questions ==

= What are the advantages of choosing Payment Gateway solution? =
Payment Gateway provides technical solution to webshops for connecting to more banks / payment service providers easily, so you do not need to integrate any or all of them each, performing very complex developments. This way the webshop's operator can save time and money. Later it is very easy to connect more new banks /payment service providers or change any of them.

= Is it enough to make an agreement with BIG FISH or do I need to do it with the banks / payment service providers as well? =
You need to sigh an agreement both with the chosen banks / payment service providers and with BIG FISH. Agreement with the banks / psp-s is completely independent from us. Then you will need to pay their fees and BIG FISH fees as well.

= Can you help us to contact banks? =
Off course, we can provide contact details to any of them.

= Do we need to pay only BIG FISH monthly fees for the transactions or chosen banks will debit us with any other fees too? =
For transactions you will have to pay both to us and to the banks / payment service providers. Bank commissions are usually between 1,2-1,8%, but there are no general rules, they offer special prices to each merchants.

= Is it possible to change monthly fee package later any time? =
Yes, at the end of any settlement periods.

== Screenshots ==

1. Appearance of online payment methods available for the customers in the webshop. You can choose.
2. Possibilities of  BIG FISH Payment Gateway WordPress module configuration.
3. Example of the possible configurations of a particular payment method (OTP credit card payment - two participants)
4. Order details: changing of order status and order notes box.


== Changelog ==

= 1.0.2 =
Success return url fixed

= 1.0.1 =
Checkout success link fixed

= 1.0.0 =
First edition.
**Wordpress WooCommerce plugin minimum 2.3.6 version is needed.**

Available Providers:
* Barion
* Borgun
* CIB Bank
* Escalion
* FHB Bank
* K&H Bank
* K&H SZÉP Card
* MasterCard Mobile
* MasterPass
* MKB SZÉP Card
* OTP Bank
* OTP Bank (two participants)
* OTP Multipont
* OTP SZÉP Card
* OTPay
* PayPal
* PayU
* PayU Cash
* PayU Wire
* SMS
* Sofort Banking
* UniCredit Bank
* Wirecard QPAY
