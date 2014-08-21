=== Donate Plus ===
Contributors: devbit
Donate link: http://devbits.ca
Tags: donate, donation, recognition, paypal
Requires at least: 2.6
Tested up to: 2.8.5
Stable tag: 1.6

Donation form. Recognition wall.  Donation total tracker. PayPal integration. 

== Description ==

This plugin will allow you to place the shortcode `[donateplus]` on a WordPress page and accept donations.  The form includes the option to be recognized on your website after the donation is received.  The Recognition wall can be placed on any WordPress page using the shortcode `[donorwall]`.  You can display your running donation total using the shortcode `[donatetotal]`. The entire plugin is integrated with PayPal IPN so it will receive notification once a donation payment has been processed and put the donor information into your website (if they opted to be displayed).  The donor can promote his name and website, along with some comments on the Recognition Wall.  This should hopefully encourage donaters who like recognition to be more likely to contribute to you or your cause.

Includes Sidebar Widgets and Recurring Donations

*NEW: Manage your Donations*

== Installation ==

1. Upload the `donate_plus` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set the options in the Settings Panel
1. Put the shortcode `[donateplus]` on your donation page to display the donate form.
1. Put the shortcode `[donatewall]` on your donation page or a seperate page to display the Donation Recognition Wall.
1. Use the shortcode `[donortotal]` to display your running total of donations to date.
1. Add the Instant Payment Notification URL to your PayPal Profile IPN settings.

== Frequently Asked Questions ==

= Why don't the donations appear on my Recognition Wall =
You may need to add the Instant Payment Notification URL to your PayPal Profile IPN Settings.  This URL can be found on the Donate Plus Settings Panel at the bottom.  Login in to PayPal and go to your Profile Summary, the click on the Instant Payment Notification link under Selling Preferences and Turn on IPN and set the Notification URL from your Donate Plus Settings Panel.  You can also view your IPN History from this page to see if there are other issues.

= What are shortcodes? =

A shortcode is a WordPress-specific code that lets you do nifty things with very little effort. Shortcodes can embed files or create objects that would normally require lots of complicated, ugly code in just one line. Shortcode = shortcut.  To use a shortcode, simple enter it on the page or post in your WordPress blog as described below and it will be replaced on the live page with the additional functionality.

= What shortcodes does Donate Plus use? =

`[donateplus]`
This shortcode will display the Donate Plus donation form

`[donorwall]`
This shortcode will display the Donor Recognition Wall. Optional attribute: title is wrapped within a `<h2>` tag. Usage is `[donorwall title='Donor Recognition Wall']`

`[donatetotal]`
This shortcode will display the total donations received. Optional attributes: prefix is the currency symbol (ie. $), suffix is the currency code (ie. USD), type is the english description (ie. U.S. Dollar). Usage is `[donatetotal prefix='true', suffix='true', type='false']`

= What kind of PayPal account will I need? = 
You will need a Premier or Business account.  Personal accounts are primarily for sending payments and may not include the PayPal IPN features this plugin requires.

== Screenshots ==

1. Settings Panel
2. Manage Donations
3. Example of Donation Form
4. Example of Recognition Wall

== Changelog ==

**Oct 25, 2009 - v1.6**

* Added Testing options
* Added Donation Management
* Added Menu Icon
* Added IPN URL information

**Jan 26, 2009 - v1.5.4/1.5.5**

* Added missed localisation tags
* Fixed Recognition Wall date/time to show

**Jan 25, 2009 - v1.5.3**

* Fixed MAJOR bug with option for displaying user info, was incorrectly set to always show wall info even when not checked.

**Jan 25, 2009 - v1.5.2**

* Fixed PayPal error when not using recurring donations

**Jan 25, 2009 - v1.5.1**

* Integrated Widgets into main plugin to fix version control issue.  No need to seperately activate widgets.

**Jan 24, 2009 - v1.5**

* Fixed bugs with recurring donations
* Added button image choices
* Allow donors to hide donation amount, but still appear on wall
* Donors can choose the period of donations rather than having it preset. Settings allow selective recurrance options.
* Limit the amount of Donors showing. Pagination coming soon.

**Jan. 23, 2009 - v1.4**

* Added Sidebar Widget Plugin as an alternative to shortcodes.

**Jan. 20, 2009 - v1.3**

* Fixed Donor Wall to allow disabling
* Added Recurring Donation support

**Dec. 7, 2008 - v1.2**

* Altered Paypal IPN script to use `mc_amount` variable instead of `payment_amount`
* Fixed {wall} url replacement - was putting link ID, not actual link in the Thank You email.

**Dec. 7, 2008 - v1.1**

* Replaced testing url in form back to PayPal url.
