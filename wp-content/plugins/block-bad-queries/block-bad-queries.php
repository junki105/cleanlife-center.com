<?php
/*
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
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2021 Monzilla Media. All rights reserved.
*/

if (!defined('ABSPATH')) die();

if (!defined('BBQ_VERSION')) define('BBQ_VERSION', '20210211');
if (!defined('BBQ_FILE'))    define('BBQ_FILE', plugin_basename(__FILE__));
if (!defined('BBQ_DIR'))     define('BBQ_DIR',  plugin_dir_path(__FILE__));
if (!defined('BBQ_URL'))     define('BBQ_URL',  plugins_url('/block-bad-queries/'));

function bbq_core() {
	
	$request_uri_array  = apply_filters('request_uri_items', array('\s', '<', '>', '\^', '`', '@@', '\?\?', '\/&&', '\\', '\/=', '\/:\/', '\/\/\/', '\.\.\.', '\/\*(.*)\*\/', '\+\+\+', '\{0\}', '0x00', '%00', '\(\/\(', '(\/|;|=|,)nt\.', '@eval', 'eval\(', 'union(.*)select', '\(null\)', 'base64_', '(\/|%2f)localhost', '(\/|%2f)pingserver', 'wp-config\.php', '(\/|\.)(s?ftp-?)?conf(ig)?(uration)?\.', '\/wwwroot', '\/makefile', 'crossdomain\.', 'self\/environ', 'usr\/bin\/perl', 'var\/lib\/php', 'etc\/passwd', '\/https:', '\/http:', '\/ftp:', '\/file:', '\/php:', '\/cgi\/', '\.asp', '\.bak', '\.bash', '\.bat', '\.cfg', '\.cgi', '\.cmd', '\.conf', '\.db', '\.dll', '\.ds_store', '\.exe', '\/\.git', '\.hta', '\.htp', '\.inc', '\.init?', '\.jsp', '\.mysql', '\.pass', '\.pwd', '\.sql', '\/\.svn', '\.exec\(', '\)\.html\(', '\{x\.html\(', '\.php\([0-9]+\)', '(benchmark|sleep)(\s|%20)*\(', '\/(db|mysql)-?admin', '\/document_root', '\/error_log', 'indoxploi', '\/sqlpatch', 'xrumer', 'www\.(.*)\.cn', '%3Cscript', '\/vbforum(\/)?', '\/vbulletin(\/)?', '\{\$itemURL\}', '(\/bin\/)(cc|chmod|chsh|cpp|echo|id|kill|mail|nasm|perl|ping|ps|python|tclsh)(\/)?$', '((curl_|shell_)?exec|(f|p)open|function|fwrite|leak|p?fsockopen|passthru|phpinfo|posix_(kill|mkfifo|setpgid|setsid|setuid)|proc_(close|get_status|nice|open|terminate)|system)(.*)(\()(.*)(\))', '(\/)(^$|0day|configbak|curltest|db|index\.php\/index|(my)?sql|(php|web)?shell|php-?info|temp00|vuln|webconfig)(\.php)'));
	
	$query_string_array = apply_filters('query_string_items', array('\(0x', '0x3c62723e', ';!--=', '\(\)\}', ':;\};', '\.\.\/', '\/\*\*\/', '127\.0\.0\.1', 'localhost', 'loopback', '%0a', '%0d', '%00', '%2e%2e', '%0d%0a', '@copy', 'concat(.*)(\(|%28)', 'allow_url_(fopen|include)', '(c99|php|web)shell', 'auto_prepend_file', 'disable_functions?', 'gethostbyname', 'input_file', 'execute', 'safe_mode', 'file_(get|put)_contents', 'mosconfig', 'open_basedir', 'outfile', 'proc_open', 'root_path', 'user_func_array', 'path=\.', 'mod=\.', '(globals|request)(=|\[)', 'f(fclose|fgets|fputs|fsbuff)', '\$_(env|files|get|post|request|server|session)', '(\+|%2b)(concat|delete|get|select|union)(\+|%2b)', '(cmd|command)(=|%3d)(chdir|mkdir)', '(absolute_|base|root_)(dir|path)(=|%3d)(ftp|https?)', '(s)?(ftp|inurl|php)(s)?(:(\/|%2f|%u2215)(\/|%2f|%u2215))', '(\/|%2f)(=|%3d|\$&|_mm|cgi(\.|-)|inurl(:|%3a)(\/|%2f)|(mod|path)(=|%3d)(\.|%2e))', '(<|>|\'|")(.*)(\/\*|alter|base64|benchmark|cast|char|concat|convert|create|declare|delete|drop|encode|exec|fopen|function|html|insert|md5|request|script|select|set|union|update)'));
	
	$user_agent_array   = apply_filters('user_agent_items', array('&lt;', '%0a', '%0d', '%27', '%3c', '%3e', '%00', '0x00', '\/bin\/bash', '360Spider', 'acapbot', 'acoonbot', 'alexibot', 'asterias', 'attackbot', 'backdorbot', 'base64_decode', 'becomebot', 'binlar', 'blackwidow', 'blekkobot', 'blexbot', 'blowfish', 'bullseye', 'bunnys', 'butterfly', 'careerbot', 'casper', 'checkpriv', 'cheesebot', 'cherrypick', 'chinaclaw', 'choppy', 'clshttp', 'cmsworld', 'copernic', 'copyrightcheck', 'cosmos', 'crescent', 'cy_cho', 'datacha', 'demon', 'diavol', 'discobot', 'disconnect', 'dittospyder', 'dotbot', 'dotnetdotcom', 'dumbot', 'emailcollector', 'emailsiphon', 'emailwolf', 'eval\(', 'exabot', 'extract', 'eyenetie', 'feedfinder', 'flaming', 'flashget', 'flicky', 'foobot', 'g00g1e', 'getright', 'gigabot', 'go-ahead-got', 'gozilla', 'grabnet', 'grafula', 'harvest', 'heritrix', 'httrack', 'icarus6j', 'jetbot', 'jetcar', 'jikespider', 'kmccrew', 'leechftp', 'libweb', 'linkextractor', 'linkscan', 'linkwalker', 'loader', 'lwp-download', 'masscan', 'miner', 'majestic', 'md5sum', 'mechanize', 'mj12bot', 'morfeus', 'moveoverbot', 'netmechanic', 'netspider', 'nicerspro', 'nikto', 'nutch', 'octopus', 'pagegrabber', 'planetwork', 'postrank', 'proximic', 'purebot', 'pycurl', 'queryn', 'queryseeker', 'radian6', 'radiation', 'realdownload', 'remoteview', 'rogerbot', 'scooter', 'seekerspider', 'semalt', '(c99|php|web)shell', 'shellshock', 'siclab', 'sindice', 'sistrix', 'sitebot', 'site(.*)copier', 'siteexplorer', 'sitesnagger', 'skygrid', 'smartdownload', 'snoopy', 'sosospider', 'spankbot', 'spbot', 'sqlmap', 'stackrambler', 'stripper', 'sucker', 'surftbot', 'sux0r', 'suzukacz', 'suzuran', 'takeout', 'teleport', 'telesoft', 'true_robots', 'turingos', 'turnit', 'unserialize', 'vampire', 'vikspider', 'voideye', 'webleacher', 'webreaper', 'webstripper', 'webvac', 'webviewer', 'webwhacker', 'winhttp', 'wwwoffle', 'woxbot', 'xaldon', 'xxxyy', 'yamanalab', 'yioopbot', 'youda', 'zeus', 'zmeu', 'zyborg'));
	
	$referrer_array     = apply_filters('referrer_items', array('ambien', 'blue\s?pill', 'ejaculat', 'erectile', 'erections', 'hoodia', 'huronriver', 'impotence', 'levitra', 'libido', 'lipitor', 'phentermin', 'pro[sz]ac', 'sandyauer', 'semalt\.com', 'todaperfeita', 'tramadol', 'ultram', 'unicauca', 'valium', 'viagra', 'vicodin', 'xanax', 'ypxaieo'));
	
	//
	
	$request_uri_string  = '';
	$query_string_string = '';
	$user_agent_string   = '';
	$referrer_string     = '';
	
	$long_requests = apply_filters('bbq_long_requests', true);
	$match_logging = apply_filters('bbq_match_logging', false);
	
	$matches = array();
	
	if (isset($_SERVER['REQUEST_URI'])     && !empty($_SERVER['REQUEST_URI']))     $request_uri_string  = $_SERVER['REQUEST_URI'];
	if (isset($_SERVER['QUERY_STRING'])    && !empty($_SERVER['QUERY_STRING']))    $query_string_string = $_SERVER['QUERY_STRING'];
	if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) $user_agent_string   = $_SERVER['HTTP_USER_AGENT'];
	if (isset($_SERVER['HTTP_REFERER'])    && !empty($_SERVER['HTTP_REFERER']))    $referrer_string     = $_SERVER['HTTP_REFERER'];
	
	if ($request_uri_string || $query_string_string || $user_agent_string || $referrer_string) {
		
		$check_length = (strlen($request_uri_string) > 2000 || strlen($referrer_string) > 2000) ? true : false;
		
		if (
			
			($long_requests && $check_length) || 
			
			preg_match('/'. implode('|', $request_uri_array)  .'/i', $request_uri_string,  $matches) || 
			preg_match('/'. implode('|', $query_string_array) .'/i', $query_string_string, $matches) || 
			preg_match('/'. implode('|', $user_agent_array)   .'/i', $user_agent_string,   $matches) || 
			preg_match('/'. implode('|', $referrer_array)     .'/i', $referrer_string,     $matches)
			
		) {
			
			if ($match_logging && isset($matches[0])) error_log('BBQ: '. $matches[0]);
			
			bbq_response();
			
		}
		
	}
	
}
add_action('plugins_loaded', 'bbq_core');

function bbq_response() {
	
	$header_1 = apply_filters('bbq_header_1', 'HTTP/1.1 403 Forbidden');
	$header_2 = apply_filters('bbq_header_2', 'Status: 403 Forbidden');
	$header_3 = apply_filters('bbq_header_3', 'Connection: Close');
	
	header($header_1);
	header($header_2);
	header($header_3);
	
	exit();
	
}

if (is_admin()) require_once BBQ_DIR .'bbq-settings.php';
