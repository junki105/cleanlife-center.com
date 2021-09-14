=== Mouseflow for Wordpress ===
Contributors: Mouseflow
Tags: mouseflow 
Requires at least: 4.5.0
Tested up to: 5.5.1
Stable tag: 5.5.1

Use Mouseflow directly from your Wordpress dashboard. Easy installation and use.

== Description ==

With Mouseflow for Wordpress you can access everything Mouseflow has to offer - directly from your Wordpress dashboard! Learn more about your visitors by viewing recordings of whole user sessions including mouse movements, clicks, scroll events and key strokes. The plugin makes it quick and easy to install the Mouseflow-tracking code on your Wordpress-site.

== Installation ==

Follow these steps to use the plugin:

1. Activate the plugin through the 'Plugins' menu in WordPress
2. Retrieve your tracking snippet from your website settings in Mouseflow (https://app.mouseflow.com/websites)
3. Navigate back to your Wordpress admin panel > Mouseflow plugin. 
4. Paste your Mouseflow script code in the box under Settings | Mouseflow

== Frequently Asked Questions ==

= Where do I get the tracking code? =

You get the tracking code by signing up on Mouseflow.com. You can easily create an account for free by following this link: https://mouseflow.com/sign-up/

= The code is not working =

1. Make sure you've inserted the script code in the settings page.
2. Check that you have the wp_footer() function in the blog template.
3. Check your blog's html source (Page / View source) and search for "mouseflow". In case the script found at the end of the <body> section, but still not working, you're probably running the site from a different domain than the one you entered on your mouseflow account. Make sure that the domains are matching. 
4. Read more here: https://mouseflow.groovehq.com/knowledge_base/topics/recording-status-is-not-installed
5. Get in touch: http://mouseflow.com/support/