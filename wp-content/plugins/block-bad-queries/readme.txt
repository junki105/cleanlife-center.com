=== BBQ Firewall ===

Plugin Name: BBQ Firewall
Plugin URI: https://perishablepress.com/block-bad-queries/
Description: BBQ is a super fast firewall that automatically protects WordPress against a wide range of threats.
Tags: firewall, secure, security, malware, web application firewall, waf
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Contributors: specialk, aldolat, WpBlogHost, jameswilkes, juliobox, lernerconsult
Donate link: https://monzillamedia.com/donate.html
Requires at least: 4.1
Tested up to: 5.7
Stable tag: 20210211
Version: 20210211
Requires PHP: 5.6.20
Text Domain: block-bad-queries
Domain Path: /languages
License: GPLv2 or later

The fastest firewall plugin for WordPress.



== Description ==

> Install, activate, and done!
> Powerful protection from WP's __fastest__ firewall plugin.

[BBQ Firewall](https://perishablepress.com/block-bad-queries/) is a lightweight, super-fast plugin that protects your site against a wide range of threats. BBQ checks all incoming traffic and quietly blocks bad requests containing nasty stuff like `eval(`, `base64_`, and excessively long request-strings. This is a simple yet solid solution for sites that are unable to use a [strong Apache/.htaccess firewall](https://perishablepress.com/7g-firewall/).

> Adds a strong firewall to ANY WordPress site
> Works with all WordPress plugins and themes


**Powerful Protection**

BBQ protects your site against many threats:

* SQL injection attacks
* Executable file uploads
* Directory traversal attacks
* Unsafe character requests
* Excessively long requests
* PHP remote/file execution
* XSS, XXE, and related attacks
* Protects against bad bots
* Protects against bad referrers
* Plus many other bad requests

> Works great with [Blackhole for Bad Bots](https://wordpress.org/plugins/blackhole-bad-bots/)


**Awesome Features**

BBQ provides all the best firewall features:

* Rated [5 stars](https://wordpress.org/plugins/block-bad-queries/#reviews) at WordPress.org
* 100% plug-&amp;-play, zero configuration
* 100% focused on security and performance
* Blocks a wide range of malicious URL requests
* Fastest Web Application Firewall (WAF) for WordPress
* Based on the [6G](https://perishablepress.com/6g/)/[7G Firewall](https://perishablepress.com/7g-firewall/)
* Scans all incoming traffic and blocks bad requests
* Scans all types of requests: GET, POST, PUT, DELETE, etc.
* Protects against known bad bots and referrers
* Works silently behind the scenes to protect your site
* Hassle-free security plugin that's easy to use
* Thoroughly tested, error-free performance
* Extremely low rate of false positives
* Compatible with other security plugins
* Regularly updated and "future proof"
* Lightweight, fast and flexible

> For advanced protection and features, check out [BBQ Pro &raquo;](https://plugin-planet.com/bbq-pro/)


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

> BBQ = Block Bad Queries



== Installation ==

**Installing BBQ**

1. Install, activate, done.

Once active, BBQ automatically protects your site against threats. Quietly, behind the scenes. For more control and stronger protection, [check out BBQ Pro &raquo;](https://plugin-planet.com/bbq-pro/)

[More info on installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)


**Customizing**

* To allow patterns otherwise blocked by BBQ, check out the [BBQ Whitelist plugin](https://perishablepress.com/bbq-whitelist-blacklist/#bbq-whitelist)
* To block patterns otherwise allowed by BBQ, check out the [BBQ Blacklist plugin](https://perishablepress.com/bbq-whitelist-blacklist/#bbq-blacklist)
* To customize long-request blocking, pattern-match logging, and response headers, check out the [BBQ Customize plugin](https://perishablepress.com/customize-bbq-firewall/)

Note that the [Pro version of BBQ](https://plugin-planet.com/bbq-pro/) makes it possible to customize patterns and everything else directly via the plugin settings, with a click. 


**Uninstalling**

This plugin cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.


**Like the plugin?**

If you like BBQ, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/block-bad-queries/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



== Upgrade Notice ==

To upgrade BBQ, remove old version and replace with new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically. Nothing else needs done.

Note: uninstalling/deleting the plugin via the WP Plugins screen results in the removal of all settings and email data from the WP database.



== Screenshots ==

There are no screenshots for BBQ! Everything is done behind the scenes.

The free version of BBQ is strictly plug-n-play, set-it-and-forget-it, with no settings to configure whatsoever. Just install, activate, and enjoy better security and robust protection against malicious requests.

The Pro version of BBQ is just as fast and simple to use, but is much more powerful and includes settings to customize and fine-tune your firewall. [Check out screenshots of BBQ Pro](https://plugin-planet.com/bbq-pro/#screenshots) (click the three grey buttons near the top of the page).



== Frequently Asked Questions ==


**How to test that the plugin is working?**

To test that the plugin is working, you can request any of the blocked patterns. For example, visit your site's homepage and enter the following URL:

	https://example.com/eval(

Replace `example.com` with your site's actual domain. If BBQ is active, the request for that URL will be blocked (with a "403 Forbidden" status). This means the plugin is working properly. You can test other patterns as well. To view all the patterns blocked by BBQ, look at the function `bbq_core()` located in `block-bad-queries.php`.


**What other security plugins do you recommend?**

I recently recorded a video tutorial series for Lynda.com on [how to secure WordPress sites](https://m0n.co/securewp). That's a good place to learn more about the best techniques and WP plugins for protecting your site against threats.


**Do I need to do anything else for BBQ to work?**

Nope, just install and relax knowing that BBQ is protecting your site from bad URL requests.


**I don't see any Settings whatsoever? Where is the settings?**

No settings needed for BBQ! Everything is done automatically behind the scenes. Zero configuration required. The free version of BBQ is strictly plug-n-play, set-it-and-forget-it, with no settings to configure whatsoever. Just install, activate, and enjoy better security and robust protection against malicious requests. The Pro version of BBQ is just as fast and simple to use, but is much more powerful and includes robust settings to customize and fine-tune your firewall.


**Is BBQ free version compatible with Wordfence?**

Does it makes sense to use both? Yes BBQ free and BBQ Pro are both compatible with any plugin written according to the WP API. And yes, there is benefit to using BBQ with any other security plugin, including Wordfence. They protect against different threats, so using both means you are extra secure.


**Does BBQ make changes to my .htaccess file?**

Absolutely not. Unlike other security/firewall plugins, neither BBQ (free version) nor BBQ Pro make any changes to any .htaccess file.


**Does BBQ make any changes to my WP database?**

No, the free version of BBQ operates as each page is loaded; it does not make any changes whatsoever to the WP database.


**Does BBQ block malicious strings included in arrays?**

Yes, BBQ scans any arrays that are included in the URI request. If any matching patterns are found, the request is blocked.


**My PHP scanner/checker plugin says there is an error?**

For example, if your PHP/plugin scanner reports something like, "found `0x3c62723e` which is bad." Normally you would not want to find such bad strings of code, but there is an exception for security plugins. Think about it: in order to block some nasty string, BBQ must _know_ about it. So each bad string that is blocked by BBQ is included in the plugin "blacklist". That means, when some PHP scanner looks at BBQ and finds some known bad strings, it just means that the scanner has discovered BBQ's list of blocked terms. In other words, BBQ contains static strings of non-functional text, in order to match and block malicious requests to your site. I hope this makes sense, feel free to [contact me](https://perishablepress.com/contact/) if I may provide any further infos.


**Do I need WordPress to run BBQ?**

Nope! BBQ is available in the following flavors:

* [BBQ Free - WordPress Plugin](https://wordpress.org/plugins/block-bad-queries/)
* [BBQ Pro - WordPress Plugin](https://plugin-planet.com/bbq-pro/)
* [BBQ Standalone PHP Script](https://perishablepress.com/block-bad-queries/#bbq-php-script)

So you can check out the Standalone PHP Script for sites that are not running WordPress.


**Can I use BBQ and 6G/7G Firewall at the same time?**

__Full question:__ "Except most of the rules overlapping, is it counter productive (site slowing down for example, potential conflicts, bugs) or is there any risks using 6G/7G Firewall + BBQ at the same time?" 

__Answer:__ It's fine to run both BBQ and 6G/7G Firewall at the same time. Both firewalls are super fast, so they won't slow things down. In other words the two firewalls play well together. The only downside is that some of the rules will be redundant, but there should be no negative impact on performance. The upside is that you get extra protection when using both, as there are variations in the firewall rules and patterns, etc.


**Do you offer any other security plugins?**

Yes, check out [Blackhole for Bad Bots](https://wordpress.org/plugins/blackhole-bad-bots/) to protect your site against bad bots. I also have a [video course on WordPress security](https://m0n.co/securewp), for more plugin recommendations and lots of tips and tricks.


**My PHP checker found something?**

If you are using some PHP checker that's reporting an error or bad string in BBQ, it's a false positive and safe to ignore. Why? Because the PHP checker is finding the static strings/patterns that BBQ uses to identify and block bad requests. In other words, your PHP checker is finding a static string thinking it is live code. It's not. If possible, please take a moment to report this to the developers of your PHP checker. They should be happy to improve the accuracy and quality of their plugin. [More info](https://wordpress.org/support/topic/on-php-checker-results/).


**How to enable logging?**

BBQ can be configured to log the matching pattern for any blocked request. By default, BBQ will add a log entry in the site's default error log. To enable logging, use the free [customize plugin](https://perishablepress.com/customize-bbq-firewall/).


**Got a question?**

Send any questions or feedback via my [contact form](https://perishablepress.com/contact/).



== Support development of this plugin ==

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books:

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect WordPress to Google Analytics
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thank you! :)



== Changelog ==

If you like BBQ, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/block-bad-queries/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**2021/02/11**

* Removes `zune` pattern from user agents
* Removes `ninja` pattern from  user agents
* Tests on WordPress 5.7

**2020/12/09**

* Tweaks query string pattern for optimal matching
* Further tests on WordPress 5.6

**2020/12/08**

* Removes `order` pattern from Query String rules
* Removes `ahrefs` pattern from User Agent rules

**2020/11/23**

* Removes `python` from the User Agent rules
* Adds filter for URI long-request blocking
* Adds filter for enabling logging of blocked requests
* Releases [customize plugin](https://perishablepress.com/customize-bbq-firewall/) to change default functionality
* Further tests on WordPress 5.6

**2020/11/16**

* Improves XSS protection
* Improves logic of `bbq_core()`
* Integrates 7G patterns to firewall rules
* Removes some redundant firewall patterns
* Adds protection against excessive characters
* Adds logging functionality (disabled by default)
* Adds filter hooks to customize blocked response
* Replaces `guangxiymcd` with `www\.(.*)\.cn`
* Changes plugin name to "BBQ Firewall"
* Updates default translation template
* Updates/refines readme.txt
* Tests on PHP 7.4 and 8.0
* Tests on WordPress 5.6

**2020/08/11**

* Replaces `guangxiymcd` with wildcard match `www.(.*).cn`
* Refines readme/documentation
* Tests on WordPress 5.5

**2020/07/06**

* Adds `guangxiymcd` to Request URI and Query String patterns
* Tests on WordPress 5.4 + 5.5 (alpha)

**2020/03/19**

* Tests on WordPress 5.4

**2019/11/09**

* Changes to `plugins_url()` for `BBQ_URL` constant
* Tests on WordPress 5.3

**2019/09/02**

* Updates some links to https
* Tests on WordPress 5.3 (alpha)

**2019/05/01**

* Bumps [minimum PHP version](https://codex.wordpress.org/Template:Server_requirements) to 5.6.20
* Adds activation check if BBQ Pro is active
* Updates default translation template
* Tests on WordPress 5.2

**2019/03/11**

* Improves function `bbq_action_links()`
* Refines plugin settings screen UI
* Generates new default translation template
* Tests on WordPress 5.1 and 5.2 (alpha)

**2019/02/20**

* Tests on WordPress 5.1

**2018/11/17**

* Adds homepage link to Plugins screen
* Updates default translation template
* Tests on WordPress 5.0

**2018/08/21**

* Removes `.tar` from Request URI patterns
* Adds `rel="noopener noreferrer"` to all [blank-target links](https://perishablepress.com/wordpress-blank-target-vulnerability/)
* Updates GDPR blurb and donate link
* Regenerates default translation template
* Further tests on WP 4.9 and 5.0 (alpha)

**2018/05/11**

* Adds `xrumer` to blocked query strings and request URIs
* Adds `indoxploi` to blocked query strings and request URIs
* Generates new translation template
* Tests on WordPress 5.0

**2017/11/01**

* Updates readme.txt :)
* Tests on WordPress 4.9

**2017/10/19**

* Changes `\/\.tar` to `\.tar` in Request patterns
* Changes `\/\.bash` to `\.bash` in Request patterns
* Adds new User Agent patterns: `shellshock`, `md5sum`, `\/bin\/bash`
* Adds new Request patterns: `@@`, `@eval`, `\/file\:`, `\/php\:`, `\.cmd`, `\.bat`, `\.htacc`, `\.htpas`, `\.pass`, `usr\/bin\/perl`, `var\/lib\/php`, `wp-config\.php`
* Adds new Query String patterns: `@@`, `\(0x`, `0x3c62723e`, `\(\)\}`, `\:\;\}\;`, `\;\!--\=`, `@eval`, `eval\(`, `base64_`, `UNION(.*)SELECT`, `\/config\.`, `\/wwwroot`, `\/makefile`, `\$_session`, `\$_request`, `\$_env`, `\$_server`, `\$_post`, `\$_get`, `phpinfo\(`, `shell_exec\(`, `file_get_contents`, `allow_url_include`, `disable_functions`, `auto_prepend_file`, `open_basedir`, `(benchmark|sleep)(\s|%20)*\(`
* Tests on WordPress 4.9

**2017/07/30**

* Changed menu item name to "BBQ Firewall"
* Tests on WordPress 4.9 (alpha)

**2017/03/22**

* Adds plugin settings page
* Adds French translation (thanks to Bouzin)
* Generates new default translation template
* Tests on WordPress version 4.8

**2016/11/14**

* Replaces `esc_html` with `esc_attr` for link title attributes
* Changes stable tag from trunk to latest version
* Adds `&raquo;` to rate this plugin link
* Updates URL for rate this plugin link
* Moves "Go Pro" link to action links
* Renames action/meta link functions
* Updates default translation template
* Tests on WordPress version 4.7 (beta)

**2016/08/10**

* Added translation support
* Added plugin icons and larger banner
* General fine-tuning and testing
* Tested on WordPress 4.6

**2016/03/28**

* Removed `\:\/\/` from Request URI and Query String patterns (see [this thread](https://wordpress.org/support/topic/redirection-blocked))
* Added `(benchmark|sleep)(\s|%20)*\(` to Request URI patterns (thanks to [smitka](https://wordpress.org/support/topic/idea-better-sqli-filter))
* Tested on WordPress 3.5 beta

**2015/11/07**

* Added `\.php\([0-9]+\)`, `__hdhdhd.php` to URI patterns (Thanks to [George Lerner](https://www.glerner.com/))
* Added `acapbot`, `semalt` to User Agent patterns (Thanks to [George Lerner](https://www.glerner.com/))
* Replaced `UNION.*SELECT` with `UNION(.*)SELECT` in Request URI patterns
* Added `morfeus`, `snoopy` to User Agent patterns
* Refactored redirect/exit functionality
* Renamed `rate_bbq()` to `bbq_links()`
* Tested with WordPress 4.4 beta

**2015/08/08**

* Tested on WordPress 4.3
* Updated minimum version requirement
* Highlighted Pro link on Plugins screen

**2015/06/24**

* Replaced `UNION\+SELECT` with `UNION.*SELECT`
* Added `wp-config.php` to query-string patterns
* Added plugin link to [BBQ Pro](https://plugin-planet.com/bbq-pro/)
* Testing on WP 4.3 (alpha)

**2015/05/07**

* Tested with WP 4.2 and 4.3 (alpha)
* Replaced some `http` with `https` in readme.txt

**2015/03/14**

* introduce `bbq_core()`
* tested on latest WP
* tightened up code

**2014/09/22**

* tested on latest version of WordPress (4.0)
* retested on Multisite
* increased minimum version requirement to WP 3.7

**2014/03/05**

* Bugfix: added conditional checks for empty variables

**2014/01/23**

* tested on latest version of WordPress (3.8)
* added link to rate plugin

**2013/11/03**

* removed `?>` from script
* added optional line for blocking long URLs
* added line to prevent direct access to BBQ script
* added `\;Nt\.`, `\=Nt\.`, `\,Nt\.` to request URI items
* tested on latest version of WordPress (3.7)

**2013/07/07**

* replaced `Nt\.` with `\/Nt\.` (resolves comment editing/approval issue)

**2013/07/05**

* removed `https\:` (from previous version)
* replaced `\/https\/` with `\/https\:`
* replaced `\/http\/` with `\/http\:`
* replaced `\/ftp\/` with `\/ftp\:`

**2013/07/04**

* removed block for `jakarta` in user-agents
* removed `union` from query strings
* added to request-URI: `\%2Flocalhost`, `Nt\.`, `https\:`, `\.exec\(`, `\)\.html\(`, `\{x\.html\(`, `\(function\(`
* resolved PHP Notice "Undefined Index" via `isset()`

**2013/01/03**

* removed block for `CONCAT` in request-URI
* removed block for `environ` in query-string
* removed block for `%3C` and `%3E` in query-string
* removed block for `%22` and `%27` in query-string
* removed block for `[` and `]` in query-string (to allow unsafe characters used in WordPress)
* removed block for `?` in query-string (to allow unsafe character used in WordPress)
* removed block for `:` in query-string (to allow unsafe character used by Google)
* removed block for `libwww` in user-agents (to allow access to Lynx browser)

**2012/11/08**

* Removed `:` match from query string (Google disregards encoding)
* Removed `scanner` from query string from query string match
* Streamlined source code for better performance (thanks to juliobox)

**Older versions**

* 2012/10/27 - Disabled check for long strings, disabled check for scanner
* 2012/10/26 - Rebuilt plugin using 5G/6G technology
* 2011/02/21 - Updated readme.txt file
* 2009/12/30 - Added check for admin users
* 2009/12/30 - Additional request strings added
