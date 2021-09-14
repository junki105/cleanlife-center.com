<?php
/**
 * @package CloudAdvisorStopTranslate
 */
/*
Plugin Name: CloudAdvisorStopTranslate
Plugin URI: 
Description: 日本語版WordPressなど、多言語対応したWordPressの翻訳処理を停止することで表示を高速化します。管理画面の翻訳は停止しません。テーマの言語ファイルを使う必要がない場合に性能を大幅に向上できます。
Version: 1.0
Author: TOWN, Inc.
Author URI: https://cloudadvisor.jp/
License: GPLv2 or later
Text Domain: CloudAdvisorStopTranslate
Domain Path: /languages/
*/
class CloudAdvisorStopTranslate {

var $version = '1.0';

public function __construct() {
        add_filter( 'override_load_textdomain', array( $this, 'load_textdomain' ), 10 , 2 );
}

public function load_textdomain($domain, $mofile) {
        if ( is_admin() ) {
                return false;
        } elseif ( preg_match( '/wp-login.php/', $_SERVER['REQUEST_URI'] ) ) {
                return false;
        }
        return true;
}
}
new CloudAdvisorStopTranslate;

