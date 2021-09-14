=== Asset CleanUp Pro: Page Speed Booster ===
Contributors: gabelivan
Tags: minify css, minify javascript, defer css javascript, page speed, dequeue, performance
Requires at least: 4.5
Tested up to: 5.7.1
Stable tag: 1.1.9.3
License: Commercial

There are often times when you are using a theme and a number of plugins which are enabled and run on the same page. However, you don’t need to use all of them and to improve the speed of your website and make the HTML source code cleaner (convenient for debugging purposes), it’s better to prevent those styles and scripts from loading.

== Changelog ==
= <strong>1.1.9.3</strong> - 25 April 2021
* Option to manage critical CSS (in "CSS & JS Manager" &#187; "Manage Critical CSS") from the Dashboard (add/update/delete), while keeping the option to use the "wpacu_critical_css" hook for custom/singular pages
* Improvement: Make sure "&display=" is added (if enabled) to Google Fonts links if their URL is changed to fit in JSON formats or JavaScript variables
* Fix: Make sure managing CSS/JS for taxonomies from the Dashboard (e.g. when editing a category) works 100%
* Fix: Clearing load exceptions from "Overview" didn't work for all pages of a certain post type

= <strong>1.1.9.2</strong> - 16 April 2021
* Divi builder edit mode: Allow Asset CleanUp Pro to trigger plugin & CSS/JS unload rules when the page editor is on to make the editor load faster via define('WPACU_LOAD_ON_DIVI_BUILDER_EDIT', true); that can be set in wp-config.php / read more: https://www.assetcleanup.com/docs/?p=1260
* Cache Enabler (compatibility with older versions): Make sure the deprecated "cache_enabler_before_store" hook is in use
* Unload "photoswipe" fix: If WooCommerce's PhotoSwipe was unloaded, empty dots were printed at the bottom of the page from unused/unneeded HTML (hide it by marking the DIV with the "pswp" class as hidden)
* Improvement: Only use 'type="text/css"' when it's needed (e.g. an older theme is used that doesn't support HTML5)
* Improvement: Make SweetAlert2 independent (styling, functionality) from other SweetAlert scripts that might be loaded from other plugins/themes (e.g. "WooCommerce Quickbooks Connector" export in an edit product page was not working)
* Fix: Better detection for the homepage (e.g. the latest posts page was mistaken with the homepage in the front-end view of the CSS/JS manager)
* Fix: Better detection for the singular page; Make sure the latest posts page such as the "Blog" one is also checked)
* Fix: Make sure the license deactivation works even if the license is not valid so that it could be replaced with a valid one (e.g. a null version was initially used)

= <strong>1.1.9.1</strong> - 1 April 2021
* Minify CSS/JS improvement: From now on, the minification can be either applied to files, inline JS code, or both (before, the files minification had to be enabled to files first and then to inline JS code; sometimes, users just wanted to minify inline code and leave the files untouched)
* Fix: On some WordPress installations, the plugin's menu icon from the Dashboard's sidebar was not showing properly (the height was too large)
* Fix: If there are too many assets/plugins unloaded, when showing up in the top admin bar menu, the list was not scrollable (e.g. only 20 out of 40 assets were shown because the height of the browser's window wasn't large enough which can not be expanded on smaller devices)
* Fix: If the current theme supports HTML5, the 'type="text/javascript"' attribute is not added any more to altered SCRIPT tags by Asset CleanUp, thus avoiding any errors from W3C validators
* Fix: When "Move All <SCRIPT> tags From HEAD to BODY" was enabled, all SCRIPT tags got moved including those with the "type" attribute having values such as "application/ld+json" (these ones should stay within the HEAD tag)

= <strong>1.1.9.0</strong> - 16 March 2021
* The layout of a CSS/JS area is changed on the make exception area & a new option was added to make an exception from any unload rule on pages belonging to a specific post type (e.g. unload site-wide, but keep the asset loaded on all WooCommerce 'product' pages)
* Oxygen plugin edit mode: Allow Asset CleanUp Pro to trigger plugin & CSS/JS unload rules when the page editor is on to make the editor load faster via define('WPACU_LOAD_ON_OXYGEN_BUILDER_EDIT', true); that can be set in wp-config.php / read more: https://www.assetcleanup.com/docs/?p=1200
* In specific DIVI powered websites, the "PageSpeed" parameter is appended to the URL from the client-side, thus make sure to only check for "et_fb" when detecting if the DIVI builder is on to avoid loading Asset CleanUp Pro there
* Fix: Make sure that for languages such as Arabic where the Dashboard's menu is shown on the right side, the plugin's icon is not misaligned
* Fix: When "Update" button is clicked on edit post/page (Gutenberg mode), while there's no CSS/JS list fetched ("Fetch the assets on a button click" is on), make sure the list is not fetched after the page is saved (it's only refreshed if it was loaded in the first place)

= <strong>1.1.8.9</strong> - 6 March 2021
* Fix: Make sure WP Rocket is fully triggered when the assets are fetched via Asset CleanUp, as the "Uncode" theme is calling get_rocket_option() without checking if the function exists
* Fix: Added nonce checks to AJAX calls made by Asset CleanUp for extra security

= <strong>1.1.8.8</strong> - 4 February 2021
* Improved the caching mechanism: The most recently created files are never deleted in case HTML pages that weren't cleared for weeks or more would still load them successfully; Once "ver" is updated, then the now old file will be cleared in a couple of days (e.g. at least one day + the number of days set in "Settings" -> "Plugin Usage Preferences" -> "Clear previously cached CSS/JS files older than (x) days")
* Set a higher priority of the order in which the plugin's menu shows up in the top admin bar to make room for the notice related to the unloaded assets; Changed the notification icon from the menu (from exclamation to filter sign)
* Make sure the textarea for RegEx rules within CSS/JS Manager is adaptive based on its content (for easier reading of all the rules)
* Cleanup the value (strip any empty extra lines) from the RegEx textarea when it's updated for any unload/load exception rule to avoid invalid RegEx rules & make sure the delimiters are automatically added to the rules in case they were missed
* CSS Minifier Update: Better detection and minification for CSS related to math functions such as min(), max(), calc() and clamp(); Fix broken CSS (rare situations) that uses nested calc()
* Combine JS Update: Make sure the inline "translations" associated with a JS file is appended to the combined JS files, as this would also avoid possible errors such as "Uncaught ReferenceError: wp is not defined"
* Make sure preg_qoute() is used in CleanUp.php when clearing LINK/SCRIPT tags to avoid any errors such as unknown modifier
* Make sure jQuery Chosen is not beautifying the SELECT drop-down if "Input Fields Style" is set to "Standard" in the plugin's settings, so that anyone using a screen reader software (e.g. people with disabilities) will not have any problems using the drop-down
* Fallback: Added Internet Explorer compatibility (11 and below) for the deferred CSS that is loaded from the BODY
* Improved the way the file paths from "Inline CSS" and "Inline JS" areas are matched to make sure regular expressions can also be used for a match, not just the relative path to the file
* The super admin will always be able to access the plugin's settings for security reasons
* Fix: Make sure the unloading feature works for the WooCommerce Shop Page and it's not taken as a product archive page since it's connected to a page ID
* Fix: PHP Warning - array_merge() - Expected parameter 1 to be an array, null given - within the method alterWpStylesScriptsObj()
* Fix: Sometimes, due to the fact there were no line breaks on specific code shown in the hardcoded list, the left-side meta box had its width increased so much that it was hiding or partially showing the right-side meta boxes area that was only visible by using "View" -> "Zoom Out" in Google Chrome
* Fix: Hide the following area from the edit taxonomy page if the user is not an admin to avoid any confusion: "Asset CleanUp Pro: CSS & JavaScript Manager"

= <strong>1.1.8.7</strong> - 15 January 2021
* Improvement: Make it more clear where the admin is applying the plugin unload rules (frontend or /wp-admin/) in "Plugins Manager" by renaming the text related to the rules as well as the submit button
* Improvement: Alert the admin in case he/she might be in the wrong tab for plugin unload in "Plugins Manager" when the "wp-admin" string is added to the RegEx rules and the admin is within the "IN FRONTEND VIEW (your visitors)" tab
* Make sure only Asset CleanUp Pro plugin is loading when its own AJAX calls are made to /wp-admin/admin-ajax.php for faster processing (no point of loading other plugins) except the request when the caching is cleared (e.g. due to WP hooks that are used by other performance plugins)
* For easier debugging, the top admin bar menu now has the list of all the unloaded plugins and CSS/JS files within the current viewed page
* Prevent Asset CleanUp Pro from triggering when REST /wp-json/ calls are made due to conflicts with other plugins (e.g. Thrive Ovation for testimonials)
* Added a note below the textarea where the RegEx rule can be added (for unloading & load exceptions) that multiple RegExes are allowed one per line to make the admin aware of this option
* If an unload exception is chosen (after an existing unload rule has already been chosen), mark it with green font to easily distinguish it when going through the CSS/JS manager
* Cache Enabler: Clear plugin's caching right after Asset CleanUp Pro's caching is cleared to avoid references in the old cached HTML pages to files from Asset CleanUp Pro that might be missing or not relevant anymore
* Cache Enabler: Fix - PHP Deprecated: "cache_enabler_before_store" (the new filter is "cache_enabler_page_contents_before_store")
* Fix: Sometimes, admins are mistakenly moving the CSS/JS manager to the right side of the edit post/page area; It gets moved back where it belongs within the edit post/page area
* Fix: Update for 'Compatibility with "Wordpress Admin Theme - WPShapere" plugin' - make sure it applies to any admin page, not just the options page from WPShapere

= <strong>1.1.8.6</strong> - 4 January 2021
* New Feature: Unload plugins within the Dashboard /wp-admin/ (useful for pages that are too slow and, in rare cases, to fix any conflicts between two plugins loaded on the same admin page)
* Fix: Inline CSS for specified files was not working anymore if the CSS file was cached
* Added option to prevent CSS/JS from being optimized on page load by Asset CleanUp Pro via query string for debugging purposes: (/?wpacu_no_optimize_css /?wpacu_no_inline_css /?wpacu_no_optimize_js)

= <strong>1.1.8.5</strong> - 18 December 2020
* Replaced jQuery deprecated code with a new one (e.g. reported by "Enable jQuery Migrate Helper" plugin)
* Download file based on the browser's screen size feature addition: Show the option also for CSS files that are "parents" and have "children" under them, alerting the admin to be careful when a rule is set for the file as it could affect the way its "children" are loaded
* Debugging option: If the admin uses /?wpacu_only_load_plugins=[list_here_comma_separated] while he/she's logged-in, then Asset CleanUp's MU plugin file will only load the mentioned plugins (all the other active plugins will not load at all on the targeted page)

= <strong>1.1.8.4</strong> - 1 December 2020
* New Setting: Restrict access for administrators on the "CSS & JS Manager" area, thus decluttering the posts/pages whenever they edit them; Very useful if there are admins (e.g. store managers that don't have to mix with Asset CleanUp's assets list for various reasons) that should not view the meta boxes from edit post/page, the CSS/JS list from the front-end view (if enabled), etc. ("Settings" -> "Plugin Usage Preferences" -> "Allow managing assets to:")
* Improvement: Extra checks are made to detect if the page is an AMP one and if it is, no changes would be made to the HTML source (e.g. no SCRIPT tags in the HEAD section of the page)

= <strong>1.1.8.3</strong> - 22 November 2020
* Changed the "Plugins Manager" area to have the same feeling as the "CSS & JS Manager"; Removed the "Always load it (default)" option as it's redundant since all the plugins are loaded by default unless there are unload rules set there; The load exceptions are now showing in green font to stand out in case they overwrite any unload rule.
* Added extra alters to notify the admin in case something is not right with some of the rules set. These include: adding the full URI to the RegEx input areas when only the URI (relative path) is needed; Enabling both "Unload it if the user is logged in" and "Always load it if the user is logged in" which ends up in the cancellation of each other
* Added "Unload it if the user is logged in" option to "Plugins Manager" (e.g. you have a plugin that has Google Analytics and you want to trigger it only for your guests, not for yourself)
* Added debugging option to load all plugins (no filtered list in case there are any rules in "Plugins Manager"): /?wpacu_no_plugin_unload
* Make sure all the "if kept loaded" areas are blurred if any unload rule is chosen as those areas become irrelevant
* New option in "Settings" -> "Combine loaded JS (JavaScript) into fewer files" -> "Wrap each JavaScript file included in the combined group in its own try {} catch(e) {} statement in case it has an error and it would affect the execution of the other included files"
* Clear caching once a day via WP Cron in the case over 24 hours have passed since the last clearance (e.g. in case the admin hasn't cleared the caching in a long time or hasn't touched the Dashboard for days)
* Deactivate the appending of the inline CSS/JS code (extra, before or after) to the combined CSS/JS files if all the files' size is over 700 MB as this often suggest the inline code is not unique (e.g. having WordPress nonces that often change)
* Check if a directory is empty before using rmdir() to avoid certain debugging plugins to report errors (even though they are harmless)
* Make sure the following work fine if the plugin is marked as unloaded site-wide with exceptions: upload file within the front-end, download attachments from the Dashboard
* Fix: Basic preloading was not taking place anymore
* Fix: "PHP Warning: in_array() expects parameter 2 to be array, boolean given" is generating if the current media query load list is empty
* Fix: Make sure /wp-content/cache/asset-cleanup/(css|js) directories are re-created if necessary, in case they were removed (e.g. for being empty or by mistake)
* Fix: The list of the hardcoded assets wasn't wrapped correctly and not contracted properly on request
* Fix: The AJAX call meant for fetching the hardcoded list in the front-end view was also triggering within the Dashboard outside the plugin's pages and used extra resources that were not necessary
* Fix: Prevent the meta boxes from showing up in the edit post/page area (thus, decluttering the edit area) if the user's role is not "administrator" (e.g. it was showing it to editors without any CSS/JS to manage which was often confusing)

= <strong>1.1.8.2</strong> - 16 October 2020
* New Feature: Instruct the browser to download a CSS/JS file only when a certain media query matches (e.g. you might have a certain CSS file that is needed only in the desktop view, but not on mobile view)
* Prevent the plugin from triggering when Lumise plugin is used in edit mode
* Improvement: In the front-end view (when "Manage in the front-end" is enabled), the hardcoded assets are retrieved via an AJAX call for higher accuracy especially when certain plugins are using various techniques to list their assets (e.g. "Smart Slider 3")
* Oxygen Builder Fix: Make sure the file /wp-content/uploads/css/universal.css is taken into consideration for minification as it's among the files that aren't minified by default

= <strong>1.1.8.1</strong> - 29 September 2020
* Combine Google Fonts: The plugin checks if the option is enabled before using specific functions related to it, thus reducing the usage of more resources (on some shared hosting packages, the page returned 503 error)
* Combine JS: Skip adding inline JS with WordPress Nonces as they are not unique and add up to the disk space (better accuracy in detecting them)
* Added notification to the plugin's top right area if "Test Mode" is enabled (for extra awareness)
* Fix: In some environments, the plugin's custom functions to detect if the user is logged-in were triggering errors
* Fix: Do not alter the "ver" value to the default WordPress version (as it used to be) as some scripts should be loaded without query strings especially if "ver" was set to null on purpose

= <strong>1.1.8.0</strong> - 21 September 2020
* Made sure a directory exists before attempting to delete it (for old directories) to avoid any error reports (harmless, but annoying) from plugins such as "Fatal Error Notify Pro"
* Updated notification related to HTTP/2 from Combine CSS/JS tabs within "Settings"
* If the total files from the caching directory generated by the combined CSS/JS files occupy over 1 GB in disk space, deactivate automatically the appending of the inline CSS/JS code associated with the tags to the generated combined CSS/JS files as that's usually the culprit for having so many redundant files in the caching directory, leading to unnecessary disk space
* Older caching files are by default set to be cleared after 4 days (the new default value) instead of 7
* Updated "Help" page
* Show more information about the caching directory in "Tools" -> "Storage Info" (each directory with CSS/JS files is shown along with the total size of the assets within it)
* WP Rocket 3.7+ compatibility fix: "Minify HTML" is removed (read more: https://github.com/wp-media/wp-rocket/issues/2682), thus, make sure this gets verified (for compatibility reasons) as well in Asset CleanUp
* Shorten the file name of the combined CSS/JS to avoid possible duplicates
* Check if Cloudflare is used and notify the user about whether it's needed to enable "CDN: Rewrite assets URLs" (read more: https://assetcleanup.com/docs/?p=957)

= <strong>1.1.7.9</strong> - 5 September 2020
* Improvement: Save resources and do not check anything for optimization when the feed URL (e.g. /feed/) is loaded (the plugin should be inactive for these kinds of requests)
* Improvement: Do not trigger the plugin when WooCommerce makes AJAX calls (no point in using extra resources from Asset CleanUp)
* Improvement: When Google Fonts are marked for removal, nullify other related settings, leading to the usage of fewer resources
* The strings "/cart/" and "/checkout/" are added to the exclusion list where Asset CleanUp Pro is not triggered if the pattern is matched (read more: https://assetcleanup.com/docs/?p=488); These kinds of pages usually do not need optimization and if the admin decides to do some, he/she can remove the exclusion
* Fix (writing files to cache directory): If the handle name contained forward-slash (/), make sure that the final file name gets sanitized (including slash removal) to avoid errors related to file_put_contents() such as trying to write to directories that are non-existent
* Fix (unnecessary cached files): The plugin was caching CSS/JS files that did not need to be cached (e.g. already minified JS), leading to unnecessary extra disk space
* The Pro version won't deactivate the Lite one automatically (if it's enabled) as it can be kept active for full compatibility with plugins such as "WP Cloudflare Super Page Cache"
* "WP-Optimize" minify is not triggering anymore when /?wpacu_clean_load is used for debugging purposes (viewing all files loading from their original location)
* Do not strip inline CSS/JS associated with the handle if the original file is empty as there's a high chance the inline code is needed

= <strong>1.1.7.8</strong> - 27 August 2020
* CSS/JS unloading and other optimization options are now available for `Custom Post Type Archive Pages` (not just the singular pages belonging to the custom post type)
* Fix: Sometimes, "Fatal error: Cannot use object of type WP_Error as array" PHP error is logged when the assets are retrieved via "WP Remote Post"

= <strong>1.1.7.7</strong> - 19 August 2020
* Make the plugin's user aware about jQuery Migrate not loading starting from WordPress 5.5 (a notice is showing in "Settings" -> "Site-Wide Common Unloads" if the WP version >= 5.5)
* Add alerts for WooCommerce assets when the user is about to unload them to make sure he/she is aware of the consequences (e.g. "js-cookie", "wc-cart-fragments")
* Oxygen plugin compatibility: Make sure the page loads fine when "Manage in the front-end view" is enabled and the admin is logged-in (e.g. ob_flush() is used to print missing content)
* Do not unload plugins (any unload rule from the "Plugins Manager" area) on front-end pages if an AJAX request was made (e.g. some plugins such as WooCommerce & Gravity Forms are using index.php?[query_string_here] and we won't want to block these calls as they are obviously made for a reason)
* Added action hooks before ("wpacu_clear_cache_before") & after ("wpacu_clear_cache_after") the plugin's CSS/JS caching is cleared
* Do not deactivate the plugin automatically on Dashboard view if the PHP version is below 5.6 and when the plugin is activated, prevent its activation when the PHP version is below 5.6 printing an error message
* Yoast SEO Compatibility Fix: Prevent the plugin from minifying SCRIPT tags if the type is different than "text/javascript", avoiding errors with plugins such as Yoast SEO (type: application/ld+json)
* WordPress 5.5 & "Enable jQuery Migrate Helper" Fix - /assets/script.min.js: jQuery.fn.load() is deprecated
* When the CSS/JS list is fetched using the "Direct" way of fetching the assets ("Manage in the Dashboard"), there are two calls made; Now the progress is shown for each of the calls for easier debugging in case the assets' list is not retrieved successfully
* Improvement: Do not defer the plugin's own script file as sometimes its functions do not work (e.g. if there are JS errors from other plugins); It's better to have it loaded as render-blocking (small file anyway), as soon as possible
* Improvement: Do not leave extra space in the LINK & SCRIPT tags (it makes things easier when debugging the HTML source that might have been altered by the plugin)
* Fix: Avoid triggering the plugin if the request is an API one starting with "/wp-json/wc/" (excluding the site's base URL), WooCommerce related (REST requests)
* Fix: PHP Notice: Array to string conversion (CombineCss.php on line 503)
* Fix: PHP Notice: Undefined index: is_frontend_view (view-by-location.php on line 311)
* Fix: Fix: When assets are fetched, the list of CSS/JS wasn't showing up (AJAX call error) if the page URL that is called (from which the assets are fetched) is loaded with HTTP protocol while the Dashboard (the URL from which the AJAX call is made) is accessed via the HTTPS protocol - Error: "This request has been blocked; the content must be served over HTTPS."
* Fix: "PHP Warning: Use of undefined constant CURL_HTTP_VERSION_2_0" (triggered only for the admin to check if the server supports the HTTP/2 protocol); Show the verify link for HTTP/2 protocol if the automatic detection is not working
* Fix: "PHP Notice: Undefined variable: pluginListContracted in (...)/templates/meta-box-loaded-assets/view-by-location.php"
* Security Fix: Sanitize values from BulkChanges.php to prevent the execution of arbitrary code (e.g. JavaScript code)

= <strong>1.1.7.6</strong> - 28 July 2020
* Fix: CombineJs.php - PHP Notice: Array to string conversion (it happened when there were more than one inline JS code associated with a handle)
* Fix: CombineJs.php - Prevent PHP notice errors from showing up
* Security Fix: Sanitize value from $_REQUEST['wpacu_selected_sub_tab_area'] to prevent execution of arbitrary code (e.g. JavaScript code)
* Security Fix: Sanitize $postId (make sure it's only an integer) from the "duplicate_post_meta_keys_filter" filter to avoid any SQL injection attack

= <strong>1.1.7.5</strong> - 23 July 2020
* The caching of a file is re-built based on the filemtime() value as developers often forget to update the value of the "ver" (/?ver=) after updating a CSS/JS file's content
* When listing the loaded stylesheets (LINK tags), make sure to print the "media" attribute if it's different than "all" so the admin will be aware if that particular CSS is meant for mobile or other devices (e.g. to save time from going through the HTML source code and check it out there)
* Files loaded from "/wp-content/bs-booster-cache/" are not minified/combined (as they are already minified) to avoid getting a large caching directory (often having lots of GB)
* Prevent certain JavaScript code containing random strings such as nonces (e.g. CDATA one) from being added to the combined JS files to avoid the plugin generating lots of JS combined files that would increase the total disk space by writing to the caching directory
* Fix: "Combine CSS" was not working, unless "Combine JS" was also enabled

= <strong>1.1.7.4</strong> - 21 June 2020
* Update for the feature to check if the asset's content (CSS/JS) is already minified; Limit the number of database entries to 100 in case there are too many assets that might be having dynamic content
* Caching dynamically loaded assets is no longer enabled by default as it seems to be causing issues with some themes/plugins
* Added "Read more" links for some of the handles that have special documentation written about them (e.g. "How to unload Swiper in Elementor" or "How to check if Gutenberg Blocks CSS file is needed or not")
* Autoptimize compatibility: If "Minify HTML" is enabled in Autoptimize, make sure any changes to the HTML source that should be applied by Asset CleanUp, are done before the HTML minification
* Fix: If 'Hide "Asset CleanUp Pro: CSS & JavaScript Manager" meta box' is checked, make sure it also takes effect on taxonomy (e.g. 'category') edit page
* At least PHP 5.6 is now required (anything below that and the plugin won't activate). Official support for PHP 5.6 ended on December 31st, 2018. Unless you really have to use it (e.g. old code that won't work with newer PHP version), it's strongly recommended that you update to PHP 7+ as it's much faster & uses fewer resources than older PHP versions, not to mention the improvement in terms of security vulnerabilities & bugs - Read more about unsupported PHP branches: https://www.php.net/eol.php

= <strong>1.1.7.3</strong> - 1 June 2020
* Combine CSS/JS "Apply combination only for logged-in administrator (for debugging purposes)" is no longer available and has been replaced with two options: "Apply it only for guest visitors (default)" & "Apply it for all visitors (not recommended)"
* "Overview" new options: 1) Added option to remove all load exceptions for a handle in "Overview" page when the load exceptions are not tied to any bulk unload rule; 2) Clear redundant unload rules if the site-wide rule is already applied
* WP Rocket compatibility: Make sure HTML changes made by Asset CleanUp Pro are always applied (via "rocket_buffer" filter hook) before WP Rocket saves the HTML content to the cached file
* Fix: Make sure the plugin's own style is properly loaded asynchronously in Firefox in any of the plugin's configuration (this was causing the CSS/JS manager to be unstyled in Mozilla Firefox)

= <strong>1.1.7.2</strong> - 18 May 2020
* Critical CSS can be implemented conditionally via "wpacu_critical_css" filter / Read more: https://assetcleanup.com/docs/?p=608 / This is very helpful in completely preventing render-blocking CSS from loading in a page, thus improving the user experience & the page score in tools such as Google PageSpeed Insights
* Compatibility with Ronneby Theme: Alter the style/script tag later (e.g. by appending plugin markers) after plugins such as "Ronneby Core" alter it (in this case it prevents the URLs from the LINK tags to be stripped)
* When listing dependencies in the CSS JS managing list (e.g. the "children" of a "parent"), show the unloaded ones in the red font; Dependency handles are linked as anchors for easier navigation between them
* Fix: When listing plugins in "Plugins" page and Asset CleanUp Pro is eligible for an update, change the "Update" link and explain to the admin that the page will be reloaded to make sure the connection to the remote server is made and no "Plugin update failed" messages are shown anymore as it happened in some hosting environments
* Fix: Prevent any undefined constant "LOGGED_IN_COOKIE" errors (in case it's not set, as it happens in some WordPress setups) in case rules for logged-in users are set in "Plugins Manager"

= <strong>1.1.7.1</strong> - 10 May 2020
* New option: Hide "Asset CleanUp Pro" menu from the Dashboard (left sidebar) for any reason (e.g. have a cleaner sidebar menu area because of too many elements added up or you do not want it to be too obtrusive to the client for which you’ve done some optimization)
* If a script has "children" and it's about to be asynched or deferred, then a confirmation message about potential issues will show up
* If an asset is already minified, then its SHA1 value will be stored in the database for later reference to avoid minifying it (and use extra resources) for comparison in a future minify process
* Fix: Properly verify assets' SRC that are starting with ../ (very rare cases) to avoid errors such as the unreachable one; Higher accuracy in detecting the hostname in case the plugin is used on staging environments such as the SiteGround's one
* Fix: Gave up the inclusion of /wp-includes/pluggable.php everywhere which generated conflicts with other plugins such as "Post SMTP Mailer/Email Log" (wp_mail() overwritten) and went for a custom solution instead
* Fix: In very rare cases get_option('active_plugins', array()) is returning duplicated values (e.g. altered via a hook by a different plugin)
* Fix: Fix: Make sure load exceptions for taxonomy, author, search results, date & 404 pages are properly applied: for guests & the admin in any situation

= <strong>1.1.7.0</strong> - 29 April 2020
* Once a page is updated, the plugin preloads that page for both the admin and the guest visitor, making sure any new changes would take effect, saving the admin's time and making sure any first visitor coming to that page will access it faster, not having to wait for the rebuilding of the cache which would increase the TTFB (time to the first byte)
* If the attribute "data-wpacu-skip" is applied to any CSS/JS, then no alteration (e.g. no minify and no addition to any combine list) will be applied to that file (apart from the actual unload or attributes such as async/defer)
* Combined CSS/JS files are all stored in /wp-content/cache/asset-cleanup/(css|js)/ to avoid duplicated files that used to be stored in "logged-in" directory which is no longer created; This could reduce the total disk space considerably, especially when the same CSS/JS is created for both guests & logged-in users
* If the hardcoded asset was already stripped & the HTML source is updated, then do not proceed with further replacements of alternative values to save resources
* Store the assets info (for later reference in "Overview"/"Bulk changes" page with the relative "src"; In case the data is later imported from Staging to Live, it won't show the staging URLs on the live website as it could be confusing to the admin even though it's not affecting the functionality of the Live website
* Compatibility Fix: When "Minify HTML" is enabled in WP Rocket, some hardcoded assets that have comments and extra space around them are not stripped when marked for stripping
* Notify the admin that unloading 'jquery-migrate' won't unload its 'jquery' "child" as well, as the unloading of jQuery Migrate is done differently than other handles, in order to avoid unloading jQuery library
* Fix: If "Asynchronous via Web Font Loader (webfont.js)" was chosen for "Combine Multiple Requests Into Fewer Ones", the font weights weren't added to the final generated SCRIPT tag
* Fix: Sometimes, the parameter passed to "CleanUp::removeMetaGenerators" is empty and it returns a loadHTML() error for empty input
* Fix: Avoid Array to String Conversion Error in pages such as "Overview"
* Fix: Prevent any notice errors about undefined $GLOBALS for the 'wpacu_filtered_plugins' index
* Fix: Make sure when the handle information is saved, there are no PHP notice errors if the 'src' index is missing as some handles do not have an "src" (e.g. 'woocommerce-inline' handle)

= <strong>1.1.6.9</strong> - 20 April 2020
* 'Remove All "generator" meta tags?' improvement: Higher accuracy in stripping META tag generators if the option is enabled in case some of their attributes have no quotes around them (rare cases)
* If 'Remove "REST API" link tag?' is enabled, the <em>/wp-json/</em> reference is also removed from the "Response headers" when accessing the page via remove_action()
* Compatibility with extra page builders: "X" & "PRO" themes (Theme.co), "WP Page Builder" & "Page Builder: Live Composer" plugins: whenever their editor is ON, no unloads or any other changes to the HTML source (including minification) are performed to make sure the editor is loading its files and works smoothly
* Compatibility with "Redis Object Cache" plugin: The global variable <em>$wp_object_cache</em> is no longer used and it's replaced with a custom solution
* Compatibility with "404page – your smart custom 404 error page" plugin and similar plugins that are making pages as 404 customizable ones
* For debugging purposes, the admin can use /?wpacu_no_cache to view how the website would load without the CSS/JS cache applied
* Fix: Avoid deprecated error notice when a non-static function was called as being static
* Fix: Avoid "Warning: DOMDocument::loadHTML(): Empty string supplied as input" in some situations when the HTML source is parsed
* Fix: "style>" was showing up at the top of the page when Inline CSS was enabled when the fetched file (for inlining) was empty or only had comments in it

= <strong>1.1.6.8</strong> - 15 April 2020
* For maximum compatibility, any inline CSS/JS code associated with a handle (e.g. added via wp_add_inline_style() & wp_add_inline_script()) is automatically appended to any file that is added to a combined CSS/JS file
* Added more elements to the debug area (accessed via /?wpacu_debug to show how much time it takes to load them); Also, the time calculating to dequeue CSS/JS handles wasn't accurate, this has been fixed
* Prevent certain DOMDocument calls (which can be slow on large HTML documents) when they are not necessary (e.g. when preloading CSS stylesheets and the RegEx which is faster can do the same task with the same accuracy)
* If Minify STYLE/SCRIPT (inline) tags are enabled, then make that content larger than 40KB are cached as so far the minify was done on every page load and for very large inline tags, it would have used more resources and time to render the HTML output
* In some cases, the PHP function strtr() has proven to be faster than str_replace() to make replacements, thus it has been applied to some methods that are dealing with the alteration of the HTML source
* Fix: In some situations, the fetching of the CSS/JS list got stuck without loading anything due to a fetching speed filter that disabled all other plugins apart from Asset CleanUp Pro to load on the last AJAX call

= <strong>1.1.6.7</strong> - 7 April 2020
* Added contract / expand asset row for hardcoded assets so the admin could contract them if they take too much space and some of them will likely never be unloaded in any page
* Clear plugin's cache via AJAX after "Settings" is updated within the Dashboard (this is more effective then clearing it when the page reloads as it could take some time to clear the cache if there are lots of files stored there)
* Trigger certain actions (to save database & disk space) when the plugin is deactivated: Clear all its transients from the database & Remove the caching directory if it doesn't have any more CSS/JS; If all the plugin's changes were cleared via "Tools" -> "Reset", then deactivating the plugin will completely clear any of its traces
* The plugin's own files that are needed for the plugin's functionality (they are only loading for the logged-in admin), are loaded asynchronously (CSS) and deferred (JS) to ensure the admin doesn't load them as render-blocking especially when managing the pages in the front-end view
* The combined CSS tags can now be altered for any reason via add_filter() through the 'wpacu_combined_css_tag' tag name, just like the combined JS tags are via 'wpacu_combined_js_tag'
* While the CSS/JS assets are fetched prevent extra performance plugins from triggering their optimisation for CSS/JS/HTML as the action is irrelevant and uses resources during the fetching of the assets for the admin

= <strong>1.1.6.6</strong> - 4 April 2020
* Backend Improvement: Prevent all front-end optimisation code from triggering while the CSS/JS is fetched, thus saving resources (this also leads to a faster fetching of the CSS/JS list)
* Backend Improvement: Optimised the plugin to use less calls (there were redundant ones) to the minify CSS/JS library for hardcoded assets, reducing considerably the resources used and avoiding 500 Internal Errors when fetching the CSS/JS list for management (sometimes timeout errors are generated in hosts with less resources allocated, such as the memory)
* If plugin is updated in the "Plugins" page and the update fails, the admin is alerted by the potential reasons and is advised to take further action (e.g. license is not active for the website or there's a timeout in doing the update for any reason)

= <strong>1.1.6.5</strong> - 1 April 2020
* New Feature in the CSS/JS Manager: The handle rows can be contracted/expanded (their status is saved when the form is submitted); This is useful to make the whole area smaller (less scrolling) as there will likely be CSS/JS file that you know you will never edit for a long time (if ever) and it's better to have them contracted
* Show confirmation message when unloading specific files that are very likely needed such as jQuery, Backbone & Underscore libraries
* Alert the admin when there are unload rules from inactive plugins on pages such as "Bulk Changes" & "Overview"
* Keep Dashicons loaded if the toolbar (top admin bar) is shown
* Do not load own plugin's (Asset CleanUp Pro) CSS/JS files in the front-end when the admin is logged-in and managing assets in the front-end is disabled (keep things tidy: no point in having extra HTTP requests loaded, even if load just for the admin)
* Backend Improvement: When a plugin page (e.g. "Settings") is visited within the Dashboard, trigger a maintenance script that will remove inactive handle data information (from handles without any rule attached to it, often from deleted plugins no longer used) from the "wpassetcleanup_global_data" option value (from `options` table), thus making it lighter
* Backend Improvement: In many hosting environments, the total number of fields submitted is maximum 1000 (set by default in php.ini); The total number of fields that were sent have been reduced (e.g. hardcoded assets information) as they are only enabled via JavaScript whenever they are relevant to make sure there are less fields sent (to void partial submit and missing data as a result in case the admin has difficulties increase the default 1000 in php.ini)
* Backend Improvement: Do not automatically store hardcoded assets info when the CSS/JS manager list is loaded; Instead, store it IF there's a rule attached to it in order to make the contents of the "wpassetcleanup_global_data" option smaller in size (for a lighter database & faster MySQL queries)
* Fix: Added missing load exception rules in the "Overview" page for 404 Not Found, Search Results and Date archive pages
* Fix: When generating combined JS files, local file starting with // (no protocol added to them) were not added to the combined JS content
* Fix: Higher accuracy in stripping 'before' and 'after' associated inline SCRIPT after adding the content to the JS combined file (sometimes, these associated inline tags were left unstripped)

= <strong>1.1.6.4</strong> - 24 March 2020
* In "Settings" page, a check is automatically made (in "Optimize CSS" & "Optimize JavaScript") to determine if the website is delivered through the HTTP/2 network protocol, thus encouraging the admin to avoid combining CSS/JS unless it's really necessary
* Save resources by skipping certain SCRIPT tags if Inline JS is enabled for files below a specific size in KB (a confirmation window was also added up when the option is enabled to remind the admin that using it could break things if not used carefully)
* Fix: Added missing update functions that failed specific things to update from the edit taxonomy page (e.g. a category) within the Dashboard

= <strong>1.1.6.3</strong> - 16 March 2020
* Improved the speed of the generated & printed combined CSS/JS assets by ~30ms (depending on the hosting package and the PHP version used) when the HTML source is altered by avoiding extra useless verifications of the HTML output
* Improved combine CSS/JS feature: If the inline content (CSS/JS) that is associated with a handle (added via wp_add_inline_style() or wp_add_inline_script()) changes and the caching is cleared, make sure a new JS combined file is generated. Before, the caching had to be cleared in the browser as well, leading to old JS loaded in some situations
* When managing the assets, make sure the checkboxes from the load exception area are always disabled if there's no unload rule set, thus avoiding any user error to mistakenly add useless load exceptions to an already loading asset, also avoiding any confusing and get a cleaner list in the "Overview" area
* When managing the assets, make sure to show "before" and "after" content (so the user is aware how is that inline tag generated) associated with a handle too (not just the "data" one added via wp_localize_script())
* "Overview" page update: If there's any "Load it for the logged-in user" exception for a handle, show it
* Fix: Avoid creating redundant CSS files when minify inlined tags is enabled, leading to a large number of files in the caching

= <strong>1.1.6.2</strong> - 8 March 2020
* Caching: Expired CSS/JS files are cleared differently (in time after visiting various pages) to save resources and errors related to the PHP memory (e.g. shared hosting packages often have limitations in terms of the server's CPU & memory usage)
* Make sure the combined CSS/JS file is valid before its tag is generated in the HTML output (in rare cases, the cached CSS/JS files get deleted either by mistake when developers are cleaning up the caching directory OR they weren't properly created in the first place)

= <strong>1.1.6.1</strong> - 3 March 2020
* New ways of sorting the CSS/JS management list: By handles with rules & without rules / By the files size in descending order (from largest to smallest)
* Compatibility with "Smart Slider 3" plugin (and similar plugins that load the assets the same way): STYLE/LINK/SCRIPT are all showing up in the hardcoded assets list
* Higher accuracy in detecting hardcoded scripts that are loaded via output buffering levels
* Compatibility fix to avoid PHP warning error when "Smart Slider 3" & "WP Rocket" are used and the CSS/JS assets are fetched
* Improvement: Strip empty STYLE/SCRIPT tags if, after optimization, their content is empty (e.g. the CSS was minified as it had only comments in it)
* Improvement: In case fetching the assets will result in an error, filter the HTML output from certain tags that could mess the whole Dashboard layout
* Improvement (to save resources): Do not trigger Composer's autoloader while the assets are fetched
* Improvement (to save resources): Do not fetch all the hardcoded assets for the guest visitors if there are no hardcoded assets marked for unload
* Improvement (to save resources): Added disk caching mechanism for inline CSS/JS optimized content to avoid going on every page load through minify and other alterations that could add up to the TTFB (time to the first byte)
* Fix: If 'Ignore dependency rule and keep the "children" loaded' would have been checked for a JavaScript handle, the script would be unloaded on all pages disregarding whether it was marked for unload or not
* Fix: When minification is applied for inlined CSS, do not touch the background URLs as sometimes it leads to issues

= <strong>1.1.6.0</strong> - 25 February 2020
* New Feature: Manage hardcoded CSS/JS (non-enqueued using the WordPress functions)
* Improvement: Save resources and prevent any optimizations from triggering while the assets are fetched (no HTML alteration via "wp_loaded" action hook is needed) for the admin
* Improvement: Do not inline CSS/JS files that are within conditional comments (Internet Explorer)
* Fix: SCRIPT tags within conditional comments (Internet Explorer) were moved from HEAD to BODY (if the option was chosen) without their conditional tags (keep them within the HEAD)

= <strong>1.1.5.9</strong> - 21 February 2020
* Make the admin aware in case a certain CSS/JS asset is loaded within Internet Explorer conditional comments (Read more: https://www.sitepoint.com/internet-explorer-conditional-comments/)
* "display=" is now applied to Google Fonts final URL, generated via WebFontConfig within inline SCRIPT tags
* Licensing: Auto activate site if the license is Unlimited (e.h. for the admin's convenience in case a move from Staging to Live was done and the license wasn't activated on Live)
* Licensing: Show renewal link if the license is expired; If the license is not active, show its status (in red background) near the 'License' text near the sidebar menu
* Allow CSS/JS management for privately published pages
* Fix: Make sure the path to /wp-includes/ (or other internal directories) is the right one when the blog URL is like mysite.com/blog/
* Added "Debugging" tab to "Tools" page
* From now on, "disk" is the default method for storing the cached information of the assets

= <strong>1.1.5.8</strong> - 8 February 2020
* Storage info for cache directory shows the total size/number of all files (not just CSS/JS ones)
* Removed plugin's meta boxes when a block ("oxy_user_library" post type) is edited in Oxygen Builder plugin (as the meta boxes are not relevant there)
* Any plugins that are unloaded in "Plugins Manager" are listed in "Asset CleanUp Pro: CSS & JavaScript Manager" (beginning of the list), reminding the user why none of the plugins' assets (if any) are listed for management
* If the admin edits a page (post, page, taxonomy, homepage) from the Dashboard, notify him/her if the page's URI is matched by any of the rules from "Do not load the plugin on certain pages"
* "License" page update: on page load it automatically retrieves the total number of activations and the total available ones (e.g. if you have 2 active websites and a Plus license, it would show 2/3 activations)
* Proceed with the combine CSS/JS (if enabled) when there are common query strings in the URL (e.g. Google Analytics campaigns, Facebook clicks)
* Compressed images for a lighter plugin
* FileSystem is always using the "direct" method for altering CSS/JS files, thus avoiding (e.g. by mistake via a different plugin using the same WordPress FileSystem class) any reading/writing error for the cached files
* When fetching assets, make sure some plugins such as Fast Velocity Minify (that could interfere with the HTML output) are deactivated
* When updating a post/page/homepage, the caching is now cleared after the page is updated via an AJAX call (asynchronously) thus reducing the memory usage and the time spent until the page reloads
* "Plugin Manager": List plugins with rules first (for easier reading)
* If a plugin that has unload rules is not active (or deleted), do not show it as unloaded in the CSS/JS management list as only active plugins are verified for any unload rules
* On page request (within the Dashboard), /?wpacu_get_cache_dir_size will retrieve information about the cache directory (all its files and their sizes get printed)
* Debugging feature: If /?wpacu_clean_load is used, it will show the unoptimized version of the page (great for locating specific files that were perhaps combined and cached by various plugins)
* Debugging feature: If /?wpacu_debug is used, it will print a list of options to deactivate on page request (for the logged-in admin)
* Debugging feature: If /?wpacu_no_async and/or /?wpacu_no_defer is used, it will prevent any SCRIPTS with "src" to have async/defer attributes applied
* Debugging feature: Allow the option to deactivate any HTML source alteration ("wp_loaded" action hook) via page request: /?wpacu_no_html_changes
* Optimize CSS Improvement: Avoid any errors in case "circular reference" is detected (via @import)
* Fix: CSS/JS URLs starting with // were giving unreachable error when checked if they are valid or not
* Fix: Prevent errors in some BuddyPress pages as $post->post_type is undefined
* Fix (plugin compatibility): Avoid call_user_func_array() PHP error if SiteGround's "Remove Query String From Static Resources" is enabled
* Fix: Make sure "Do not load the plugin on certain pages" takes effect for any "Plugins Manager" rule as well

= <strong>1.1.5.7</strong> - 5 January 2020
* New Feature (located in "Settings" - "Plugin Usage Preferences" - "Do not load the plugin on certain pages"): Useful if you wish to deactivate any Asset CleanUp Pro rules on specific pages (e.g. non-cached pages such as /basket/ or a page where there are any issues if the plugin is activated)
* Improvement: Do not trigger Asset CleanUp Pro if TranslatePress Multilingual plugin is in edit mode (front-end view)
* Improvement: Only trigger Asset CleanUp Pro when plugin related AJAX calls are made via admin-ajax.php for a faster response timing
* Bug Fix: Avoid reporting any DOMDocument errors as they are irrelevant

= <strong>1.1.5.6</strong> - 28 December 2019
* New Feature: Load exception in "Plugins Manager" - "Always load it if the user is logged in" (e.g. you might want a plugin unloaded for guest users, but loaded for the logged in users, such as the administrators)
* Improvement: Sometimes, specific plugins are used to alter the HTML source (e.g. features such as minify HTML); Make sure no META tags are left in the BODY tag as it would give validation errors in https://validator.w3.org/
* Bug Fix: Make sure "Load it on this page" always stays checked after the assets management list is updated
* Bug Fix: When saving plugins' unload/load exception rules, make sure the slashes are stripped from any RegEx in case certain characters are used in the RegEx

= <strong>1.1.5.5</strong> - 17 December 2019
* New Feature: Make exception (from any unload rule) and load an asset if the user is logged in
* Improvement: Updated RegEx verification to avoid printing PHP errors in the front-end view (sometimes messing the layout) if the RegEx input is not valid
* Improvement: RegEx Input is turned into a Textarea making the area expandable (in case the RegEx rule is long) and allows more than one RegEx rule to be added if it's easier (one rule per line)

= <strong>1.1.5.4</strong> - 14 December 2019
* Fix PHP warning errors within the "Overview" admin page (Asset CleanUp Pro's menu) within the Dashboard
* Adjust the text below "CSS & JS Manager", "Plugins Manager", "Bulk Changes" to stay on the same line

= <strong>1.1.5.3</strong> - 13 December 2019
* New Feature: Initial release of "Plugins Manager" which allows unloading the plugins (not just their CSS/JS files) which would be like having the plugin deactivated for the specified rule; This comes with an MU plugin (for early triggering, before all the other plugins) called "Asset CleanUp Pro: Plugin Filtering" which is dependant on Asset CleanUp Pro (if deactivated, its MU plugin will get deleted)
* Improvement: Added the total number of handles for stylesheets and scripts in "Overview" page
* Bug Fix: In rare cases, the version of a CSS/JS could be an array, not just a float/integer number; Prevent notices from showing up when 'ver' is an array and make sure the proper query string is passed to the link/path of the source file

= <strong>1.1.5.2</strong> - 24 November 2019
* Improvement: Added "Overview" page which has the list of all the changes made to a specific CSS/JS file (handle), offering a much easier way to understand the changes made and do any debugging
* UI Improvement: The height of the CSS/JS asset row (when managing the list) is smaller, depending on the settings, making it easier to do scrolling
* UI Improvement: Adjust the total height of the "Note" textarea based on the content added, thus reducing the spacing between assets for easier scrolling/management
* Code Improvement: Split a few large files into multiple ones for easier management
* Backend Performance Improvement: Prevent Asset CleanUp Pro's (own) CSS/JS from loading in edit post/page when the files aren't needed (e.g. no meta boxes are showing up because they were hidden)
* Bug Fix: Do not alter any Google Fonts links if there is no "family=" within it ("Smart Slider 3" fix)
* Bug Fix: When a bulk unload is chosen for a category/date/404 (any page manageable in the Pro version), make sure the load exception area is showing up

= <strong>1.1.5.1</strong> - 18 November 2019
* All "RegEx Load Exceptions" can also be managed in "Bulk Changes" (the same way as "RegEx Unloads")
* Debugging Improvement: When /?wpacu_show_handle_names is used, it will print the handle name as a "data" attribute tag within the LINK/SCRIPT; Great for debugging and to find out if any of the assets are hardcoded
* Added information about the handles (source, version) in "Bulk Changes" for easier management
* Extra compatibility with AMP pages: Do not move from HEAD to BODY any SCRIPT tags containing //cdn.ampproject.org/
* Improvement: Once the Assets List is loaded for management, verifications would be made to check if the files exist or not returning errors (e.g 404 Not Found); any that return errors gets highlighted with a notification (great to spot any deleted files or external resources that are pointing to bad requests)
* Polished plugin's CSS for WP 5.3
* Plugin Compatibility: Make sure Asset CleanUp Pro's combine CSS/JS works if "HTML Minify" is enabled in W3 Total Cache
* Improvement: When saving the RegExes for unload & load exceptions and verifying if a pattern matches the current requested URI, the T-Regx library is used that also fixes any invalid regular expression patterns (e.g. if no delimiters were added, they will be added automatically)
* Bug Fix: When a category is saved, an error was triggering for calling a method in the wrong class
* Bug Fix: Bug Fixes: Make sure the regex load/unloads get saved when applying the changes from an edit taxonomy (e.g. category) page
* Bug Fix: Make sure the load it on this page & regex checkboxes always stay checked (only an issue within the Dashboard)
* PHP 7.4 Compatibility Fix: Removed deprecated errors for "Array and string offset access syntax with curly braces is deprecated"

= <strong>1.1.5.0</strong> - 7 November 2019
* Compatibility with "AMP (Official AMP Plugin for WordPress)" and "AMP for WP – Accelerated Mobile Pages" plugins: If the page is of AMP type, no Asset CleanUp settings/rules will be triggered to avoid validation errors; Moreover, NOSCRIPT tags added by Asset CleanUp are moved to the BODY tag (they are no longer stored in the HEAD tag) to avoid further validation errors in case other AMP plugins/scripts are used and Asset CleanUp Pro doesn't detect them
* Combine CSS Improvement: Stylesheets that are asynchronously loaded are also combined into fewer files (e.g. if 10 CSS files from HEAD are async preloaded, they will be combined into one async preloaded file) to reduce the number of HTTP requests
* New Unload Feature: Unload CSS/JS for URLs with request URI matching a specific RegEx
* New Feature: Skip "Test Mode" on page request for debugging purposes via /?wpacu_skip_test_mode - e.g. useful when you have to check a website and you don't have admin access and "Test Mode" is enabled (you can check if anything is broken there while the page loads fine for other visitors)
* Bug Fix: If "Test Mode" was enabled, "async" and "defer" rules applied per page for JS files weren't ignored
* Improvement: No matter what type of layout to show the assets list is chosen from "Assets List Layout:", it will show the total number of CSS/JS for each group (e.g. total files from the theme, total files from all the active plugins, etc.)
* Improvement: Option to choose how the caching information (asset details including its location in the caching directory) is retrieved in "Plugin Usage Preferences" (useful to reduce database queries in case one has a large database that is slow in retrieving information)

= <strong>1.1.4.9</strong> - 28 October 2019
* New Feature: If in CSS/JS is loaded everywhere (or for instance on a custom post type), you can make an exception and load it if the URL (precisely the request URI) matches a specific RegEx (read more: https://assetcleanup.com/docs/?p=21#wpacu-method-2)
* Improvement: When assets are fetched to show in the load manager, prevent WP Rocket from running as well as Query Monitor from outputting information
* "Duplicate Post" compatibility fix: Make sure Asset CleanUp's meta values are taken into account when a post is cloned
* Bug Fix: Hide any PHP notice errors (reported in the error log in some environments) for cached CSS/JS as the 3rd parameter ("src" as it is) wasn't added to the returned array

= <strong>1.1.4.8</strong> - 20 October 2019
* Improvement: CSS/JS URLs that start with "/" (relative) or "//" (some themes/plugins strip the protocol when enqueuing them) are checked and if they are from the same domain, they will be optimized
* "Smart Slider 3" plugin compatibility: Make sure the plugin's JavaScript files that are not enqueued (but appended to the HTML source via output buffering) get optimized (e.g. combined)
* Added more tutorials to "Getting Started" -> "Video Tutorials"
* Changed default value for "Move Scripts to BODY" exceptions for AMP pages compatibility

= <strong>1.1.4.7</strong> - 14 October 2019
* New Feature in "Optimize JavaScript": Move All SCRIPT tags from HEAD to BODY
* New Feature in "Optimize JavaScript": Move jQuery inline code after the jQuery library is called
* Combine Google Fonts Requests Improvement: If the LINKs already have extra commas in the font weights, they will be stripped properly and all the font weights arranged in alphabetical order in the resulting LINK tag
* Improvement: Prevent irrelevant notice errors from being recorded in some error log server files to avoid confusion about the functionality

= <strong>1.1.4.6</strong> - 6 October 2019
* Compatibility with "Cache Enabler" plugin: Make sure the saved HTML files have all the changes made by Asset CleanUp Pro
* Inline JS automatically is no longer enabled by default; Added a notice about what it means to inline JS files to reminder the user to be extra careful
* Make "Update" button area (for assets management) sticky on certain pages (to avoid scrolling too much before deciding to perform the update)
* Optimize hardcoded assets that are starting with a relative path (e.g. /wp-content/ without the site URL)
* Cache Dynamic Assets Improvement (also checks for www.domain.com?query without /)
* Improvement: If 'Ignore dependency rule and keep the "children" loaded' is used and the the tag (LINK or SCRIPT) has inline code (e.g. before/after the tag) associated with it (e.g. added via wp_add_inline_script() or wp_add_inline_style()), make sure that code is also stripped along with the tag
* Bug Fix: If 'Ignore dependency rule and keep the "children" loaded' was checked, it would have stripped the tag from the HTML source even if no unload rule was set (e.g. forgotten to be set by the admin)
* Bug Fix: If Combine CSS is enabled, make sure that moved CSS from HEAD to BODY is combined and deferred separately from other CSS from the BODY

= <strong>1.1.4.5</strong> - 27 September 2019
* Inline automatically CSS/JS smaller then (specific size) KB (if option is enabled)
* Inline CSS/JS Improvement: Inline dynamic loaded CSS/JS (if option is enabled)
* Improvement for "Google Font Remove": Added more patterns to detect Web Font Loader CDN requests
* WP Rocket Compatibility Fix: If the CSS/JS files' path get changed by "WP Rocket" (path contains "/wp-content/cache/busting/"), make sure they are getting unloaded by Asset CleanUp Pro if 'Ignore dependency rule and keep the "children" loaded' option is checked along with the unload rule

= <strong>1.1.4.4</strong> - 25 September 2019
* New Feature: Rewrite cached static assets URLs with the CDN ones if necessary (located in "Settings" -> "CDN: Rewrite assets URLs")
* Improvements: Strip Google Fonts references from JavaScript (.js) files (if the option is active)
* Append "display" parameter to Google Font URLs within JavaScript files (if any option for "font-display:" is chosen)
* Bug Fix: Make sure all values from "Site-Wide Common Unloads" show the correct status (enabled/disabled) in "System Info" from "Tools"

= <strong>1.1.4.3</strong> - 16 September 2019
* New Assets Management Feature: Until now, the list was loaded automatically on edit post, page, custom post type, and taxonomy. You can choose to fetch the list when clicking on a button. This is good when you rarely manage loaded CSS/JS and want to declutter the edit page on load and also save resources as AJAX calls to the front-end won't be made to retrieve the assets' list.
* New Feature: Cache Dynamic Loaded CSS & JavaScript to avoid loading the whole WP environment and save resources on each request (e.g. /?custom-css=value_here or /wp-content/plugins/plugin-name-here/js/generate-script-output.php?ver=1)
* Reduced the number of database queries to fetch cached information making the pages preload faster (when the caching is rebuilt) thus reducing the loading time especially if PHP 5.6 is still used (which is slower than PHP 7+ when it deals with database connections).
* Combine JS files improvement: If there are multiple files that have "defer" or "async" attribute set (or both) and they are not preloaded, then they will be grouped into fewer files; Before, only SCRIPT tags without these attributes were combined
* Improvement to reduce disk space: Make sure already minified (100%) static .js files aren't cached
* Google Fonts Optimization: Requests that are for icons (e.g. https://fonts.googleapis.com/icon?family=Material+Icons) are also combined to reduce HTTP requests
* "Optimize CSS Delivery" from WP Rocket works together with "Inline Chosen CSS Files" from Asset CleanUp Pro
* Prevent plugin from loading when Themify Builder (iFrame) is used
* Bug Fix: Sometimes, the position of an asset (HEAD or BODY) is reported incorrectly if it was enqueued in specific action hooks; Extra checks are made to fix that as sometimes developers do not use wp_enqueue_scripts() which is the proper hook to use when enqueuing items that are meant to appear on the front end
* Bug Fix: If CSS files get inlined, make sure @import without "url" is updated correctly in all situations
* Bug Fix: In rare cases, managing assets for the Homepage is not working properly. Reason: $post is overwritten by external plugins or the theme because the developers have forgotten to use wp_reset_postdata() and reset it to its initial value (which should be 0 in this case).

= <strong>1.1.4.2</strong> - 10 September 2019
* New Feature: Remove Google Font Requests (including link/font preloads, @import/@font-face from CSS files & STYLE tags, resource hints)
* Higher accuracy in detecting META tags with the "generator" name even if the "content" attribute contains unusual characters
* Minify/Combine CSS Improvement: Any @import found including a local CSS in another CSS file is fetched (and minified/optimized if necessary) and added to the parent file (this reduces HTTP requests, saving additional round-trip times to the overall page load) - Read more: https://gtmetrix.com/avoid-css-import.html
* Hardcoded CSS/JS (not enqueued the WordPress way) from the same domain (local) get minified/optimized
* Improved the UI for "License" page
* Bug Fix: If Google Fonts loading type is async (optional with preload) then make sure it's applied even if there's only one LINK request

= <strong>1.1.4.1</strong> - 2 September 2019
* New feature: Inline Chosen CSS/JS files (usually small ones) saving the overhead of fetching them resulting in fewer HTTP requests (more: https://varvy.com/pagespeed/inline-small-css.html / https://gtmetrix.com/inline-small-css.html)
* New Option to load Google Fonts: Asynchronous by preloading the CSS stylesheet
* Reduced redundant CSS/JS files cached for logged-in users, thus making clearing the caching faster and reducing the total disk space (sometimes, on certain hosting environments with lower memory limit clearing the whole caching resulted in "PHP Fatal error: Allowed memory size of (X) bytes exhausted")

= <strong>1.1.4.0</strong> - 27 August 2019
* Option to disable "Freemius Analytics & Insights?" in "Settings" -> "Plugin Usage Preferences" (good if you often deactivate the plugin for debugging reasons or you just don't like plugin feedback popups)
* Changed the vertical "Settings" menu by renaming "Minify CSS & JS Files" & "Combine CSS & JS Files" to "Optimize CSS" & Optimize JavaScript; Added the status of the minify/combine below the menu titles to easily check what optimizations were done
* Improved the way JS files are combined; If "Defer loading JavaScript combined files" is enabled in "Optimize JavaScript", make sure that any external script between the first and last combined JS tags has "defer" attribute applied to it to avoid any JS errors in case a "child" JS file is loaded before a combined "parent" one.
* Combine CSS/JS feature now has the option to aggregate the inline tag contents associated with the combined styles/scripts (e.g. inline added after the LINK tag via wp_add_inline_style() or CDATA, inline added before/after the SCRIPT tag via wp_add_inline_script())
* Option to minify inline content between from STYLE and SCRIPT (without any "src" attribute) tags
* Optimize minify CSS/JS feature to use less resource when dynamically generating the optimized (cached) files; Minification is performed via a new library (ref: https://www.minifier.org/)
* Option to choose between "Render-blocking" and "Asynchronous via Web Font Loader (webfont.js)" when loading the combined Google Font requests
* Bug Fix: Sometimes the dynamically created drop-down from "Hide all meta boxes for the following public post types" (in "Settings" -> "Plugin Usage Preferences") via jQuery Chosen plugin was returning an empty (0px in width) drop-down

= <strong>1.1.3.9</strong> - 15 August 2019
* Option to hide all meta boxes for specific post types (e.g. not queryable or do not have a public URL, making the assets list irrelevant)
* Option to overwrite current "font-display" CSS property with the chosen one from "Settings" - "Local Fonts" for local CSS files
* Bug Fix: In some servers, when preload feature is used and the HTML is not fully valid for DOMDocument, PHP errors were printing
* Extra compatibility with "Breeze – WordPress Cache Plugin"
* Do not trigger Asset CleanUp on Avada's Fusion Builder Live: Edit Mode

= <strong>1.1.3.8</strong> - 9 August 2019
* New Feature: Local Fonts Optimization; Option to add "font-display" CSS property to @font-face within local CSS files; Option to preload local font files (e.g. .woff, .ttf, .eot)
* New Feature: Option to preload Google font files (e.g. .woff)
* Extra Compatibility with the latest version of SG Optimiser
* Bug Fix: Excluding CSS/JS files from combination was not working effectively if Minify CSS/JS was also applied to the asset
* New Feature: Strip LINKs that are made to Google Fonts (fonts.googleapis.com) without any "family" value (e.g. some themes/plugins allow to input the font family but don't validate empty submits)

= <strong>1.1.3.7</strong> - 2 August 2019
* New Feature: Google Fonts Optimization: Combine multiple font requests into fewer requests; Option to add "font-display" CSS property (PageSpeed Insights Reference: "Ensure text remains visible during webfont load")

= <strong>1.1.3.6</strong> - 30 July 2019
* New Option To Conveniently Site-Wide Unload Gutenberg CSS Library Block in "Settings" -> "Site-Wide Common Unloads"
* Better way to clear cached files as the system doesn't just check the version number of the enqueued file, but also the contents of the file in case an update is made for a CSS/JS file on the server, and the developer(s) forgot to update the version number
* When CSS/JS caching is cleared, the previously cached assets older than (X) days (set in "Settings" -> "Plugin Usage Preferences") are deleted from the server to free up space
* New Information was added to "Tools" -> "Storage Info" about the total number of cached assets and their total size
* Prevent specific already minified CSS files (based on their handle name) from various plugins from being minified again by Asset CleanUp (to save resources)
* Bug Fix: When the asset's note was saved, any quotes from the text were saved with backslashes that kept increasing on every save action

= <strong>1.1.3.5</strong> - 25 July 2019 =
* Preload CSS/JS Compatibility Update: If "WP Fastest Cache" is enabled with "Minify CSS" or "Minify JS" option, Asset CleanUp Pro preloading works fine with the new (cached) URLs
* New Feature: Async CSS Loading via preloading for the desired assets (prevent render-blocking loading)
* New Option in "Assets List Layout": Sort assets by their preload status (preloaded or not)
* Bug Fix: Sometimes, the file writing permission constants were not loaded (e.g. FS_CHMOD_FILE)
* Bug Fix: Added extra checking to prevent a PHP warning related to a foreach() call on PluginUpdater.php
* Bug Fix: Some transients where left in the database after a "Reset Everything" was performed causing confusing regarding the total number of unloaded assets
* Prevent Asset CleanUp Pro from loading any of its rules when Gravity Forms are previewed

= <strong>1.1.3.4</strong> - 16 July 2019 =
* Defer CSS: Added support for "integrity" and "crossorigin" for dynamically created LINKs and added default "all" to "media" attribute if no value is set; Only load the dynamic deferred LINK after 'body' element has loaded (once DOM is ready)
* Code CleanUp: Removed blocks of code that weren't used
* Bux Fix: PHP Notice errors were printing on some hosts related to undefined array indexes
* Bug Fix: An error is shown if "Remove HTML Comments" is enabled because of an undefined constant
* Bug Fix: Assets' Positions weren't retrieved in "Bulk Changes" because of a PHP error

= <strong>1.1.3.3</strong> - 13 July 2019 =
* New Feature: Option to preload CSS/JS files by ticking "Preload (if kept loaded)" checkbox for the corresponding file (More info: https://developers.google.com/web/tools/lighthouse/audits/preload)
* Hide irrelevant Asset CleanUp MetaBoxes when custom post types from "Popup Maker" & "Popup Builder" plugins are edited
* Deferred CSS files (moved from HEAD to BODY), are inserted right after the BODY tag

= <strong>1.1.3.2</strong> - 5 July 2019 =
* Any stylesheet LINK tag within the BODY is automatically deferred by loading it via JavaScript (fallback is in place)
* Bug Fix: When pages were updated, jQuery Migrate and Comment Reply were loaded back (when they were marked for unloading)
* Bug Fix: Sometimes, WP Rocket caching was not fully cleared because of an Asset CleanUp hook that interfered with it

= <strong>1.1.3.1</strong> - 3 July 2019 =
* Option to unload on all pages (site-wide) the Dashicons for non-logged-in users
* Load it on this page (exception) is preserved if chosen before any bulk unload
* Better accuracy in getting the total unloaded assets
* Used transient to store total unloaded assets from the SQL query (it's slow on some servers)
* Improved "Plugin Review" notice to use fewer queries to determine if it will be shown or not
* On plugin activation, mark Checkout/Cart pages from WooCommerce & EDD to not apply plugin combine/minify options
* Fixed undefined error related to ignoring "children" option
* Improved "CSS/JS Load Manager" pages overview layout
* Disable oEmbeds Feature; Option to update "Assets List Layout" while managing the assets
* Added tip messages next to various handles
* Bug Fix: AJAX call for retrieving plugins' icons was not working

= <strong>1.1.3.0</strong> - 12 June 2019 =
* Implemented WP_FileSystem for dealing with writing and reading cached CSS/JS files
* Minify/Combine CSS/JS files option from Asset CleanUp will be unavailable if already applied in Fast Velocity Minify, SG Optimizer & Swift Performance Lite
* Bug Fix: CSS Combine was returning a 500 error in specific hosting servers although the page was loading successfully in the browser

= <strong>1.1.2.9</strong> - 6 June 2019 =
* Minify/Combine CSS/JS files option from Asset CleanUp will be unavailable if the same feature is used in other plugins (the list includes: Autoptimize, WP Rocket, WP Fastest Cache, W3 Total Cache, SG Optimizer) to save resources and potential conflicts
* Remove Shortlink - Addition: Clean it up from the HTTP header as well (not just within the HEAD section of the website)
* Do not trigger Asset CleanUp on Elementor & Divi Page Builders AJAX calls from the Edit Area (this is especially to save resources on some hosting environments such as the shared ones)
* Only trigger fetching plugin icons from WordPress.org in specific situations (save resources)

= <strong>1.1.2.8</strong> - 1 June 2019 =
* New Feature: Enable Minify CSS/JS on the fly when admin is logged in (for debugging purposes) - via /?wpacu_css_minify
* Updated "Tools" -> "System Info": Has database information related to the Asset CleanUp's entries
* Option to override "administrator" (default) role, in order to access plugin's pages
* Do not trigger Asset CleanUp Pro on REST Requests, WPBakery Page Builder Edit Mode, Brizy Page Builder Edit Mode
* Prevent "Could not read" file size errors in case files (.css, .js) have extra parameters added to them (rare cases)
* Avoid notice errors if some "SG Optimizer" features are enabled
* Minify CSS: Compatibility with "Simple Custom CSS" plugin
* Match sidebar and top bar menus; Allow unloading of CSS/JS on the fly (via URI request) for debugging purposes; Added coloured left border for assets that had their position changed to easily distinguish them
* New Feature: Ignore dependency rule and keep the "children" loaded
* New Feature: CSS/JS "Notes" (useful to remember why you have unloaded or decided to keep a specific file)
* Bug Fix: Posts' Metas (e.g. load exceptions) were not imported
* Bug Fix: Make sure specific elements from "Site-Wide Common Unloads" are properly imported / exported

= <strong>1.1.2.7</strong> - 4 May 2019 =
* "Import & Export" feature (for settings, load/unload rules and everything else)
* Move CSS/JS to BODY or HEAD - Better accuracy in detecting the location of the asset - Dependencies are not affected in any way
* Better CSS/JS minify: In rare cases, if cached files are forcefully deleted from the server (e.g. "/wp-content/cache/" directory is cleared completely) for any reason, or there are partial issues in writing the files to the cache, then the plugin will detect that and provide the original version of the file to avoid any broken front-end (ideally, cache should be cleared after cleaning operations are performed)

= <strong>1.1.2.6</strong> - 21 April 2019 =
* Bug Fix: Make sure that "Unload on this page" checkbox stays selected after page/post update

= <strong>1.1.2.5</strong> - 19 April 2019 =
* Bug Fix: array_key_first() didn't have a fallback for PHP 5 causing plugin admin pages to disappear
* Do not trigger Asset CleanUp if either of the following page builders is in edit mode: "Thrive Architect", "Page Builder by SiteOrigin" & "Beaver Builder"
* Code improvement; Hide meta boxes from Themify builder templates

= <strong>1.1.2.4</strong> - 10 April 2019 =
* Option to prevent plugin to trigger any of its settings & unload rules on request via "wpacu_no_load" query string
* Do not minify CSS/JS from /wp-content/uploads/ (e.g. files belonging to Elementor or Oxygen page builder plugins)
* Added more things to "System Info" including settings and browser information
* Apply relative URLs for combined CSS/JS script/stylesheet tags, if URL opened is via SSL and the WordPress site URL starts with http://
* Bug Fix: Clear CSS/JS cache was returning a blank white page
* Bug Fix: Minify JS - Exceptions weren't applied

= <strong>1.1.2.3</strong> - 1 April 2019 =
* "Bulk Unloaded" is renamed to "Bulk Changes" and has two tabs/pages added with the following features: 1) Remove site-wide "async/defer" for JS files - 2) Restore CSS/JS to their initial positions
* Handles from "Bulk Changes" are shown in alphabetical order
* Bug Fix: The CSS/JS position (HEAD or BODY) wasn't showing correctly on each row
* New Feature: Show plugin list if CSS/JS are sorted by location in 'contracted' mode for easier management
* New Feature: "Check / Uncheck All" for Each Plugin's Assets (when sorted by location is enabled)
* If a CSS/JS has "children" (handles that depend on it), a message will be shown making the admin aware about it
* Make sure no PHP notice errors are shown if there are no bulk CSS/JS files to manage
* Do not show Asset CleanUp meta boxes when editing Oxygen Builder templates ('ct_template' custom post type) as boxes are useless in this instance; this avoids any confusion &amp; declutters the edit template page

= <strong>1.1.2.2</strong> - 16 March 2019 =
* Bug Fix: 403 Forbidden error was returned when fetching assets within the Dashboard because of the wrong nonce check
* Option to show on request all the settings (no tabs) within "Settings” plugin's area by appending '&wpacu_show_all' to the URL like: /wp-admin/admin.php?page=wpassetcleanup_settings&wpacu_show_all

= <strong>1.1.2.1</strong> - 15 March 2019 =
* "Manage in the Front-end?": Add exceptions from printing the asset list when the URI contains specific strings (e.g. "et_fb=1" for Divi Visual Builder)
* Option to hide plugin's meta boxes on edit post/page area within the Dashboard
* Make sure no irrelevant errors are written excessively to the server's log printed via DOMDocument in case the HTML is not fully valid

= <strong>1.1.2</strong> - 12 March 2019 =
* New CSS/JS Manage Sorting Option: By HEAD and BODY locations
* Make no CSS file (that should be minified) is missed from minification such as the ones from BODY which are loaded later in the code
* Prevent PHP notice errors from showing up in the server's (e.g. Apache, Nginx) error log files
* New CleanUp option: Strip HTML Comments

= <strong>1.1.1.9</strong> - 7 March 2019 =
* Added option to update the $content and $priority of the Asset CleanUp meta boxes via "add_filter" via the following tags (for each meta box): wpacu_asset_list_meta_box_context, wpacu_asset_list_meta_box_priority, wpacu_page_options_meta_box_context, wpacu_page_options_meta_box_priority
* Bug Fix: Make sure Emojis are always disabled when specified in the Settings and there is no DNS prefetch to //s.w.org
* Bug Fix: Prevent breaking the JS if minified and contains strings such as /**/

= <strong>1.1.1.8</strong> - 1 March 2019 =
* Prevent AJAX calls from triggering to retrieve asset list when a new post/page is created as the CSS/JS files should only be fetched when after the post/page is published
* Improved the PHP code to use fewer resources on checking specific IF conditions
* Added introduction to the "Settings" area about how the plugin is working to give the WordPress admin user a clear understanding of what needs to be done to optimize the pages
* Bug Fix: Prevent CSS files containing "@import" from getting combined (they remain minified) to prevent breaking the layout
* Bug Fix: "Do not minify JS files on this page" checkbox from the side meta box (edit post/page area) wasn't kept as selected after "Update" button was used
* Bug Fix: Avoid PHP notice errors in case arrays that do not always have specific keys are checked

= <strong>1.1.1.7</strong> - 24 February 2019 =
* Added readme to the "Settings" area to remind website admins about the role of the plugin
* New Feature: The location of a CSS/JS file can be updated site-wide when managing the asset on any page (from HEAD to BODY and vice-versa); Useful when, for instance, you have CSS/JS code that is loading on the HEAD (render-blocking), but it's only needed later (e.g. popups, AJAX calls outputs etc.)
* Reduced the number of cached files on the /wp-content/cache/asset-cleanup/ directory for the combine CSS files; 404 Not Found (any URL) pages now have only one caching information file created
* Make sure CSS files containing "@import" are not combined to avoid breaking the pages' layout

= <strong>1.1.1.6</strong> - 21 February 2019 =
* Feature update: "Combine CSS loaded files" - now the files loaded within BODY tag are also combined to further reduce HTTP requests

= <strong>1.1.1.5</strong> - 18 February 2019 =
* Feature update: Added "Get File Size" link to get the size of an external CSS/JS file to avoid overloading the server with many AJAX requests in case there are many assets loaded from CDN locations (useful to avoid max_user_connections errors and 503 errors in some WP environments such as shared hosting accounts where any CPU/memory usage reduction matters)

= <strong>1.1.1.4</strong> - 13 February 2019 =
* New Feature: Defer loading JavaScript combined files from BODY tag
* Changed the way the JS files are combined resulting in fewer combination groups taking into account the HEAD and BODY HTML tag locations
* Offer the option to clear the CSS/JS caching even if CSS/JS Minify/Combine options were deactivated
* Bug Fix: Make sure MSIE conditional script tags are not combined into JS groups
* Bug Fix: Old links to the manage homepage page from the admin bar were updated with the new ones
* Bug Fix: On some WordPress setups, the path to the CSS background image URL after combination was updated incorrectly

= <strong>1.1.1.3</strong> - 11 February 2019 =
* New Features: Minify CSS & JavaScript files (remaining loaded ones after the useless ones were unloaded)
* Bug Fix: Make sure no 500 errors are returned on save settings or save post when the wrong caching directory is read

= <strong>1.1.1.2</strong> - 4 February 2019 =
* New Feature: "Asset CleanUp: Options" side meta box showing options to disable plugin functionality for posts, pages, and custom post types; Ideal to use with the "Preview" feature if you wish to see how a page loads/looks before publishing any changes

= <strong>1.1.1.1</strong> - 2 February 2019 =
* Fix: Make sure scripts with "async" and "defer" are excluded from any JS combination
* "Combine CSS files into one" feature update - CSS files having media="print" or media="only screen and (max-width: 768px)" (and so on) are not combined
* "Combine JS files into fewer ones" feature update - jQuery and jQuery Migrate are combined as a single group (not together with any other files); if only jQuery is loaded (without jQuery Migrate), it will not be added to any group and load independently

= <strong>1.1.1.0</strong> - 1 February 2019 =
* Bug Fix: Prevent fatal error from showing in PHP 5.4 when the plugin was updated
* Re-organised the plugin's links within the Dashboard to make it easier to navigate through

= <strong>1.1.0.9</strong> - 29 January 2019 =
* New Feature: Combine remaining loaded JavaScript files into fewer files, depending on the page's settings (for maximum compatibility and performance, the files are not combined into only one large file)
* The combined loaded files caching is now stored only on /wp-content/cache/asset-cleanup/ directory (no longer in the database as transients to avoid overloading the options table with too many entries)

= <strong>1.1.0.8</strong> - 22 January 2019 =
* New sorting by location (default) option in "Assets List Layout" setting; Cache transients are also cleared when resetting everything; Changed plugin's default settings ("Inline code associated with this handle" is contracted by default)

= <strong>1.1.0.7</strong> - 19 January 2019 =
* WooCommerce & WP Rocket Compatibility - Bug Fix: When both WooCommerce and WP Rocket are active and an administrator user is logged in and tries to place an order, the "Sorry, your session is expired." message is shown

= <strong>1.1.0.6</strong> - 16 January 2019 =
* Make sure that no CSS is combined if "Test Mode" is ON
* State that DOMDocument is required for "Combine Loaded CSS" feature

= <strong>1.1.0.5</strong> - 16 January 2019 =
* "Combined Loaded CSS" feature (concatenates all the remaining loaded stylesheets within the HEAD section of the page and saves them into one file) to reduce HTTP requests even further
* Improved "Getting Started" area
* Made "Settings" as the default page where you (the administrator user) is redirected when activating the plugin for the first time
* "Remove Query String from Static Resources" feature was removed as it wasn't keeping the version tag inside the file (causing possible outdated CSS &amp; JS to be loaded instead) and wasn't worth it any tiny increase in GTMetrix score as performance and proper functionality are more important

= <strong>1.1.0.4</strong> - 1 January 2019 =
* Added "System Info" to "Tools" page to fetch information about the WordPress environment in case something needs debugging
* Added "Getting Started" page to make things easier for anyone who doesn't understand how the plugin works

= <strong>1.1.0.3</strong> - 22 December 2018 =
* Bug Fix: "async" and "defer" attributes were not added to the script tag if "Manage in the Front-end?" option (in the "Settings" page) was not enabled

= <strong>1.1.0.2</strong> - 19 December 2018 =
* Make sure "ver" query string is stripped on request only for the front-end view; Avoid removing the license info from the database when resetting everything (unless the admin chooses to remove the license info too for a complete uninstall)
* Updated the way temporary data is stored (from $_SESSION to WordPress transient) for more effective use of server resources

= <strong>1.1.0.1</strong> - 14 December 2018 =
* Bug Fix: When settings are reset to their default values via "Tools", make sure 'jQuery Migrate' and 'Comment Reply' are loading again if added in the bulk (site-wide) unload list (as by default they were not unloaded)

= <strong>1.1.0.0</strong> - 14 December 2018 =
* Added "Tools" page which allows you to reset all settings or reset everything
* Bug Fix: Notice error was printing when there was no source file for specific handles that are loading inline code (e.g. 'woocommerce-inline')

= <strong>1.0.9.9</strong> - 12 December 2018 =
* Better support for WordPress 5.0 when updating a post/page within the Dashboard
* On new plugin installations, "Hide WordPress Core Files From The Assets List?" is enabled by default
* Renamed "rule" text with "attribute" when dealing with "async" and "defer" options to avoid any confusions

= <strong>1.0.9.8</strong> - 12 December 2018 =
* Bug Fix: Make sure "Remove rule" for post types (any kind) works correctly in all WP environments and WordPress 5.0 when removing it from the "Edit Page" area (Dashboard) and Front-end view mode

= <strong>1.0.9.7</strong> - 9 December 2018 =
* Option to remove RSS Feed link tags from thesection of the website* Option to hide WordPress core files from the management list to avoid applying settings to any of them by mistake (showing the core files for unloading, async or defer are mostly useful for advanced developers in particular situations)* Improved security of the pages by adding nonces everywhere there is an update button within the Dashboard related to the plugin* Added confirmation message on top of the list in front-end view after an update is made (to avoid confusion whether the settings were updated or not)* The height of an asset row (CSS or JavaScript) is now smaller as "Unload on this page" and bulk unloads (site-wide, by post type etc.) are placed on the same line if the screen width is large enough, convenient when going through a big list of assets

= <strong>1.0.9.6</strong> - 4 December 2018 =
* Added "Input Fields Style" option in plugin's "Settings" which would turn the fancy CSS3 iPhone-like checkboxes to standard HTML checkboxes (good for people with disabilities who use a screen reader software or personal preference)
* Added notification in the front-end view in case WP Rocket is enabled with "User Cache" enabled
* Option to have the "Inline code associated with the handle" contracted on request as it will reduce the length of the assets management page in case there are large blocks of text making it easier to scan through the assets list
* Tested the plugin for full compatibility with PHP 7.2 (5.3+ minimum required to use it)

= <strong>1.0.9.5</strong> - 29 November 2018 =
* Bug Fix: When "Remove Query Strings from CSS &amp; JS?" option was used, other needed query strings were removed besides "ver", most common in stylesheets such as Google APIs ones

= <strong>1.0.9.4</strong> - 28 November 2018 =
* Added the plugin's logo at the top of each Asset CleanUp Pro's page
* Added new menu icon (from the new logo) to the Dashboard's left plugin menu
* Bug Fix: If the new "All Styles &amp; Scripts" option is chosen from "Assets List Layout" plugin's setting, make sure that "Expanded" and "Contracted" states work in any situation (page load, manual click on the + and - areas)

= <strong>1.0.9.3</strong> - 27 November 2018 =
* Added option to expand &amp; contract "Styles" and "Scripts" management list and ability to choose the initial state on page load via plugin's "Settings" page
* Added extra view type layout (besides the only default one) which prints all assets as one list (Styles &amp; Scripts) * Fixed internal error showing in Apache's log related to the calculation of the file size

= <strong>1.0.9.2</strong> - 23 November 2018 =
* Added "Test Mode" option which will unload assets only if the user is logged in as administrator and has the capability of activating plugins.
* This is good for debugging in case one might worry that a CSS/JavaScript file could be unloaded by mistake and break the website for the regular (non-logged in) users.
* Once the page loads fine and all looks good, the "Test Mode" can be disabled so the visitors will load the lighter version of the page.

= <strong>1.0.9.1</strong> - 17 November 2018 =
* Updated code to avoid showing errors (and trigger a fatal error on activation) in case the PHP version is lower than 5.6 as the plugin is still guaranteed to work with PHP 5.3+

= <strong>1.0.9</strong> - 15 November 2018 =
* Bug Fix: PHP code change to properly detect the singular pages had the wrong condition set

= <strong>1.0.8.9</strong> - 14 November 2018 =
* Better accuracy in detecting the file location to retrieve its size (to avoid errors such as NaN bytes); It also works fine if the path starts with '//' (without any URL scheme such as 'http' or 'https')

= <strong>1.0.8.8</strong> - 13 November 2018 =
* Improved assets list styling to avoid overwriting by 3rd party CSS (from other themes and plugins)
* Added option to force license activation in case there are issues with the "Activate License" button

= <strong>1.0.8.7</strong> - 10 November 2018 =
* Assets can be managed by an administrator that has rights to activate plugins (before just "manage_options" capability was checked)
* Added option to remove all meta generator tags from the HEAD section
* Option to disable XML-RPC protocol support (partially for Pingbacks or completely)
* PHP code cleanup for using fewer resources

= <strong>1.0.8.6</strong> - 8 November 2018 =
* In case the assets can't be retrieved via AJAX calls within the Dashboard, the user will be notified about it and any response errors (e.g. 500 Internal Errors) would be printed for debugging purposes
* Make the user aware that there could be also CSS files loaded from the WordPress core that should be unloaded only if the user is comfortable with that

= <strong>1.0.8.5</strong> - 31 October 2018 =
* Bug Fix: "Everywhere" bulk unloads could not be removed from "Bulk Unloaded" page

= <strong>1.0.8.4</strong> - 31 October 2018 =
* Bug Fix: When inline CSS code was attached to a handle, it would trigger an error and prevent the assets from printing in the back-end view

= <strong>1.0.8.3</strong> - 21 October 2018 =
* Added "Feature Request" links for both sidebar and top menus
* Less Text on the Menus (useful for the top to keep it on one line for smaller screen sizes)
* Trigger specific PHP code only in the front-end (not within the Dashboard)

= <strong>1.0.8.2</strong> - 20 October 2018 =
* Bug Fix: Asset list wasn't retrieved within the Dashboard view as the AJAX call returned a 500 error response due to a PHP bug
* Bug Fix: The plugin's version was updated correctly to the latest one to make Dashboard plugin updates work as usual

= 1.0.8.1 - 19 October 2018 =
* Bug Fix: Prevent notice errors from showing on the WordPress login page (for aesthetic reasons, functionality remains the same)

= 1.0.8 - 13 October 2018 =
* Added cleanup options to remove unneeded elements from the HEAD section of the website including: "Really Simple Discovery (RSD)" link tag, "Windows Live Writer" link tag, "REST API" link tag, Pages/Posts "Shortlink" tag, "Post's Relational Links" tag, "WordPress version" meta generator* Renamed some text to make more relevance when unloading assets* Added "Remove Query Strings from CSS &amp; JS?"

= 1.0.7 - 6 October 2018 =
* Apply async &amp; defer attributes to the loaded scripts
* Extra confirmation required when unloading site-wide "jQuery Migrate" and "Comment Reply" from the plugin's settings (to avoid accidental unload)
* Bug Fix: Sometimes, specific scripts were showing up on Dashboard view, but not showing on Front-end view
* Bug Fix: Getting file size was generating errors sometimes due to the wrong path to the file

= <strong>1.0.6</strong> - 19 September 2018 =
* Removed "@" from printing within the output result when using AJAX calls to get the assets as a delimiter to avoid conflict with Cloudflare's email protection
* Replaced deprecated jQuery's live() with on() to avoid JavaScript error on the front-end in case jQuery Migrate is disabled

= <strong>1.0.5</strong> - 18 September 2018 =
* Added new top menu to easily access plugin's pages; Added "Pages Info" page which has explanations about the type of WordPress pages (e.g. post, page, tag etc.) that can have their assets managed through the plugin
* Added "Taxonomies", "Authors", "Search Results", "Dates" &amp; "404 Not Found" tabs to "Bulk Unloaded" page; Removed iCheck and replaced with pure CSS to make the plugin lighter

= <strong>1.0.4</strong> - 5 September 2018 =
Bug Fix: JS &amp; CSS files were not unloaded if “Manage in front-end view?” was not active (which is optional and only an admin preference)

= <strong>1.0.3</strong> - 3 September 2018 =
The premium plugin does not depend anymore on the Lite version so you don't need both plugins active. If you have Asset CleanUp Pro 1.0.3+, you can safely delete the Lite plugin (no worries, all the settings will be preserved)

= <strong>1.0.2</strong> - 4 August 2018 =
Initial Release