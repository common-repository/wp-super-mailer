=== WP SuperMailer ===
Contributors: batmoo, cvernon, jkudish
Tags: mailing, net-results, cakemail, newsletter
Requires at least: 3.0
Tested up to: 3.3
Stable tag: 1.4.3

WP SuperMailer gives you the power of external mailing services so that blog and custom posts can be subscribed to and mailings automatically sent.

== Description ==

The WP SuperMailer allows you to choose a mailing engine and to send out e-mails to your subscribers with every new blog post or custom post type entry you make.
The plugin currently supports Net Results (http://net-results.com) or Cakemail (http://cakemail.com) as mailing engines, but we're looking at adding more in the near future.

The plugin allows you to connect directly with your prefered mailing engine and chose predefined lists (that you have set-up with your mailing engine) to associate with each content type in the settings page of the plugin. It also allows you to use different mailing templates for each content type.

Plugin built by Joachim Kudish (http://jkudish.com) & Stresslimit Design (http://stresslimitdesign.com)

The WP SuperMailer is fully working, but still in it's infancy. It should be regarded as beta software. It's safe to use on a production site, but no guarantees can be made. We've only tested the plugin on a handful of sites so far. Please provide any feedback, bug reports and/or questions to tech@stresslimitdesign.com.

This plugin is licensed under the GPL.

# Requirements:

If you want to use Net-Results as your mailing engine, you will need PHP's SOAP extension installed on your server (more info here: http://php.net/manual/en/book.soap.php)

If you want to use Cakemail as your mailing engine, you will need PHP's Mcrypt extension installed on your server (more info here: http://php.net/manual/en/book.mcrypt.php)

The current APIs provided by these mailing providers rely on these two PHP extensions. We are currently looking at alternatives that wouldn't rely on these extensions.

The WP SuperMailer will warn you and disable either mailing engine if your server doesn't have the required extensions installed.

We are also currently looking at adding more mailing engines such as Mailchimp & CampaignMonitor.
If you have other suggestions or if you'd like to provide any feedback, bug reports and/or questions, please e-mail tech@stresslimitdesign.com.


== Installation ==

Just as with any other plugin:

1. Download and extract wp-supermailer.zip
2. Upload the wp-supermailer folder to the '/wp-content/plugins/' directory
3. Activate the SuperMailer through the 'Plugins' menu in WordPress
4. Fill in the required credentials and other information in the newly added WP SuperMailer settings page


== Frequently Asked Questions ==

= The Mailing Engine I want to use can't authenticate =

First make sure that your server supports the right mailing engine:

If you want to use Net-Results as your mailing engine, you will need PHP's SOAP extension installed on your server (more info here: http://php.net/manual/en/book.soap.php)

If you want to use Cakemail as your mailing engine, you will need PHP's Mcrypt extension installed on your server (more info here: http://php.net/manual/en/book.mcrypt.php)

The current APIs provided by these mailing providers rely on these two PHP extensions. We are currently looking at alternatives that wouldn't rely on these extensions.

The WP SuperMailer will warn you and disable either mailing engine if your server doesn't have the required extensions installed.

Secondly, check all of your credentials.

If that doesn't work, get in touch at tech@stresslimitdesign.com and we'll help you debug!


= How does the WP SuperMailer decide what template to use for the mailings? =

Similarly to how WordPress themes work, the WP SuperMailer has a template hierarchy. The plugin includes a default template that you can use, it's very simple and doesn't include any styles. However you can override this template by adding a new file in your active WordPress theme. Create a new folder wpsmlr_templates in your theme and add a new file called template.php in it. You can go into even more specifics by having different templates for each content type simply by having a template-posttype.php file

For example, if you wanted to have a template for your "news" content type and then have the same template for all the other content types, you would have the two following files:

* wp-content/themes/name-of-your-theme/wpsmlr_templates/template-news.php

* wp-content/themes/name-of-your-theme/wpsmlr_templates/template.php

We're looking at expanding this functionality even further in upcoming versions of the WP SuperMailer.


= Ok. Now that I know about the template hierarchy, what I can actually do with a template? =

Your template will need a basic html markup structure and you will need to include the following two tags wherever you feel they belong: {the_title} and {the_content}
We suggest you take a look at the existing template.php in the plugin's folder as an example.
Other than that, the sky's a limit. You can do anything you could regularly do with code. Just keep in mind that emails don't always work as web pages (example: most css doesn't work as expected).


= How do I create a new list for the WP SuperMailer to use? =

If you are using Net-Results, you can do so from the WP SuperMailer's settings page. If you are using Cakemail, you will have to do it from Cakemail's interface. We're looking into building that functionality right into the WP SuperMailer.

= How do I find my Net-Results Account ID? =

Log into your Net-Resutls account and go to the My Account tab. Look at your Net-Results™ Implementation Code, it will look something like:

   	<script src="https://nr7.us/apps/?p=1234"></script>

In this case your Account ID is 1234.


= How do I find my Cakemail Interface ID and Key? =

For now, you will need to manually contact the folks over at Cakemail and request and API Interface ID and Key. More information can be found here: http://dev.cakemail.com/page/cakeengine-api-getting

= Who made this plugin? =

A team of developers over at StressLimit Design (http://stresslimitdesign.com).

We started it at the #HackMTL event (more details here: http://nextmontreal.com/hackmtl-a-huge-success/)

= I'd like to donate, or help, or contribute, what can I do? =

Wow, that's generous! At this time, we're not looking for any donations. However, here's the best way you can help, try the plugin out, test it in different situations and if you'd like to provide any feedback, bug reports, questions, or even if you'd like to contribute some code please e-mail tech@stresslimitdesign.com.


== Screenshots ==

1. As you can see this is a pretty simple interface.

== Changelog ==

= 1.4.3 =
* fix bugs and typos
* remove extra whitespace
* add support for coauthors plugin

= 1.4.2 =
* various bug fixes

= 1.4.1 =
* Fix a bug with the template getter functions
* Removed old code

= 1.4 =
* Yup there's been many iterations of the plugin since the first version, but we never got to releasing them (for various internal reasons). We've also decided to use a more conventional numbering, thus this is version 1.4!
* Rewritten the whole admin interface to use core WordPress functions
* Removed the subscription widget which wasn't working right
* Updated Net-Results functions with their updated API
* Added ability to create lists
* Clean up the plugin as a whole and added documentation (still needs a bit more though)

= 0.1 =
* First release, enjoy!
