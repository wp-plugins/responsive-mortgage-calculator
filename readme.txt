=== Plugin Name ===
Contributors: liddweaver
Donate link: http://liddweaver.com/donate/
Tags: mortgage, mortgage calculator, loan, realty, realtor, real estate, widget, responsive, jquery
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 2.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple responsive mortgage calculator widget and shortcode.

== Description ==

The Responsive Mortgage Calculator is a jQuery widget and shortcode that's designed to fit easily into any theme, on any device, at any size. Just what every "Real Estate Agent on the go" needs.

__Features__

The calculator allows your website visitors to estimate their mortgage entering:

* the total cost of the of the home,
* a down payment amount,
* an interested rate (fixed rate),
* the amortization period
* and they can select a payment period, either monthly, bi-weekly, or weekly.

The payment result is displayed below the form - very simply and very easy to follow. For the savvy user, a click on the information icon reveals more details…

__Redesign It__

There are settings to adjust the styling, a light and a dark theme, or you can remove the styling entirely and use your theme’s styling. The HTML is built with plenty of classes, so it’s easy to override the included stylesheet with your own CSS. 

__Plenty of Options__

* Set the interest rate compounding period for your region.
* Set your currency symbol and currency code.
* Hide the down payment field.
* Set a fixed payment period.
* Rename the input labels.
* Add your own CSS classes.

__Shortcode Attributes__

Use the shortcode on different pages with different field names by using shortcode attributes. They’re pretty obvious, but here’s an example:

`[mortgagecalculator totalamount=“Mortgage Amount”]`

or use the first letter of the original labels:

`[rmc ta=“Mortgage Amount]`

__Known Issues__

The widget and short code rely on the same jQuery script at the moment. The short code seems to take precedence over the widget, meaning the widget won’t function on the same page as the shortcode.

= DISCLAIMER =

The calculator is for demonstration purposes only and may not reflect actual numbers for your mortgage. Assumes constant interest rate throughout amortization period.

It's pretty darn good, but the bank has the final say...

== Installation ==

Install this plugin just like any other…

1. Upload the plugin folder `responsive-mortgage-calculator.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

= Widget Installation =

1. Access the `Widgets` page under the `Appearance` menu.
1. Drag the `Responsive Mortgage Calculator` into the Widget display area of your choice.
1. If you want, change the title and save it.

= Shortcode Usage =

You can insert the mortgage calculator into a page or post using the short code [mortgagecalculator] or [rmc].

== Screenshots ==

1. The mortgage calculator fits in the widgets area of your theme and blends right in. The form inputs are styled simply and unobtrusively. The ‘Calculate’ button takes it’s styling from your theme.
2. The mortgage payment amount is displayed below the ‘Calculate’ button. The circled ‘i’ - the ‘inspector’ icon for Mac fans - is clickable.
3. A longer summary of the mortgage details is displayed when the ‘inspector’ icon is clicked.

== Changelog ==

= 1.1 =

* Added shortcodes

= 1.1.1 =

* Made sure that JS and CSS are being included when the shortcode is used…
* Fixed a minor UI bug where the arrow background on the select box was too short.

= 1.1.2 =

* Fixed a bug that caused the form to display at the top of the page instead of where the shortcode was placed.

= 1.1.3 =

* Minor CSS to remove margins from the Payment Period select box and prevent the surrounding span from resizing.

= 2.0.0 =

* Massive code rewrite.
* Added options page.
* Widget users may need to reactivate the widget.

= 2.0.1 =

* Quick and dirty bug fixes.

= 2.0.2 =

* Fixed an error where the JS and CSS weren’t loading with the [rmc] shortcode

= 2.0.3 =

* Added generic currency symbol
* Added input for ISO currency code on the options page

= 2.1.0 =

* Added option for setting a fixed payment period
* Result now shows the payment period
* Added ability to set input and submit button labels using shortcode attributes
* Beginning internationalization - still needs PO and MO files

= 2.1.1 =

* Added missing files

= 2.1.2 =

* Completed internationalization
* Added front end French and Spanish translations - thanks to designium

= 2.1.3 =

* Script/style loading moved to ‘wp’ action and combined

= 2.1.4 =

* Added an option to set a default interest rate
* Accented characters can now be used for field labels