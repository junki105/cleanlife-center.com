<?php

add_theme_support( 'post-thumbnails' );


add_action('wp_enqueue_scripts', function() {
    wp_deregister_style('wp-block-library');
});
add_filter('wp_sitemaps_enabled', '__return_false');

flush_rewrite_rules();

/**
 * Theme scripts and style
 */
function cleanlife_scripts() {

  // 共通
  wp_enqueue_style( 'cleanlife-style', get_stylesheet_uri(), array(), version_num() );
  wp_enqueue_script( 'cleanlife-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), version_num(), true );

  if ( is_front_page() ) {
    wp_enqueue_style( 'top', get_template_directory_uri() . '/assets/css/top.css', false, version_num() );
    wp_enqueue_script( 'top-script', get_template_directory_uri() . '/assets/js/top.js', array( 'jquery' ), version_num(), true );
    wp_enqueue_script( 'faq-script', get_template_directory_uri() . '/assets/js/faq.js', array( 'jquery' ), version_num(), true );
  } 
  else {
    wp_enqueue_style( 'sidebar-style', get_template_directory_uri() . '/assets/css/sidebar.css', false, version_num() );
    if ( is_page( 'area' ) ) {
      wp_enqueue_style( 'area-style', get_template_directory_uri() . '/assets/css/area.css', false, version_num() );
      wp_enqueue_script( 'area-script', get_template_directory_uri() . '/assets/js/top.js', array( 'jquery' ), version_num(), true );
    }
    if ( is_page( 'bath' ) || is_page( 'drainpipe' ) || is_page( 'kitchen' ) || is_page( 'toilet' ) || is_page( 'washroom' ) || is_page( 'waterheater' ) || is_page( 'waterpipe' )) {
      wp_enqueue_style( 'service-style', get_template_directory_uri() . '/assets/css/service.css', false, version_num() );
      wp_enqueue_script( 'service-script', get_template_directory_uri() . '/assets/js/service.js', array( 'jquery' ), version_num(), true );
      wp_enqueue_script( 'top-script', get_template_directory_uri() . '/assets/js/top.js', array( 'jquery' ), version_num(), true );
      wp_enqueue_script( 'faq-script', get_template_directory_uri() . '/assets/js/faq.js', array( 'jquery' ), version_num(), true );
    }
    if ( is_page( 'case' ) ) {
      wp_enqueue_style( 'case-style', get_template_directory_uri() . '/assets/css/case.css', false, version_num() );
    }
    if ( is_page( 'company' ) ) {
	  wp_enqueue_style( 'area-style', get_template_directory_uri() . '/assets/css/area.css', false, version_num() );
      wp_enqueue_style( 'company-style', get_template_directory_uri() . '/assets/css/company.css', false, version_num() );
	   wp_enqueue_script( 'area-script', get_template_directory_uri() . '/assets/js/top.js', array( 'jquery' ), version_num(), true );
    }
    if ( is_page( 'faq' ) ) {
      wp_enqueue_style( 'faq-style', get_template_directory_uri() . '/assets/css/faq.css', false, version_num() );
      wp_enqueue_script( 'faq-script', get_template_directory_uri() . '/assets/js/faq.js', array( 'jquery' ), version_num(), true );
    }
    if ( is_page( 'flow' ) ) {
      wp_enqueue_style( 'flow-style', get_template_directory_uri() . '/assets/css/flow.css', false, version_num() );
    }
    if ( is_page( 'privacy' ) ) {
      wp_enqueue_style( 'privacy-style', get_template_directory_uri() . '/assets/css/privacy.css', false, version_num() );
    }
	
    if( is_singular() ) {
      wp_enqueue_style( 'single-style', get_template_directory_uri() . '/assets/css/single.css', false, version_num() ); 
    }
	
	if( is_archive() ) {
      wp_enqueue_style( 'archive-style', get_template_directory_uri() . '/assets/css/blog_archive.css', false, version_num() ); 
    }
	
    if ( is_page( 'voice' ) || is_page_template('template-parts/template-review.php') ) {
     
      wp_enqueue_script( 'service-script', get_template_directory_uri() . '/assets/js/service.js', array( 'jquery' ), version_num(), true );
    }
     wp_enqueue_style( 'voice-style', get_template_directory_uri() . '/assets/css/voice.css', false, version_num() );
  }

  // アドミンバーのインラインスタイルを出力しない
  //remove_action( 'wp_head', '_admin_bar_bump_cb' );
}
add_action( 'wp_enqueue_scripts', 'cleanlife_scripts' );

/**
 * スクリプトのバージョン管理
 */
function version_num() {
  static $theme_version = null;

  if ( $theme_version !== null ) {
    return $theme_version;
  }

  if ( function_exists( 'wp_get_theme' ) ) {
    $theme_data = wp_get_theme();
  } else {
    $theme_data = get_theme_data( TEMPLATEPATH . '/style.css' );
  }

  if ( isset( $theme_data['Version'] ) ) {
    $theme_version = $theme_data['Version'];
  } else {
    $theme_version = '';
  }

  return $theme_version;
}


// pagination
function wpbeginner_numeric_posts_nav() {
  if( is_singular() )
      return;

  global $wp_query;

  /** Stop execution if there's only 1 page */
  if( $wp_query->max_num_pages <= 1 )
      return;

  $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
  $max   = intval( $wp_query->max_num_pages );

  /** Add current page to the array */
  if ( $paged >= 1 )
      $links[] = $paged;

  /** Add the pages around the current page to the array */
  if ( $paged >= 3 ) {
      $links[] = $paged - 1;
      $links[] = $paged - 2;
  }

  if ( ( $paged + 2 ) <= $max ) {
      $links[] = $paged + 2;
      $links[] = $paged + 1;
  }

  echo '<div class="blog-pagination"><ul>' . "\n";

  /** Previous Post Link */
  if ( get_previous_posts_link() )
      printf( '<li class="previous-post-link">%s</li>' . "\n", get_previous_posts_link() );

  /** Link to first page, plus ellipses if necessary */
  if ( ! in_array( 1, $links ) ) {
      $class = 1 == $paged ? ' class="active"' : '';

      printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

      if ( ! in_array( 2, $links ) )
          echo '<li>…</li>';
  }

  /** Link to current page, plus 2 pages in either direction if necessary */
  sort( $links );
  foreach ( (array) $links as $link ) {
      $class = $paged == $link ? ' class="active"' : '';
      printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
  }

  /** Link to last page, plus ellipses if necessary */
  if ( ! in_array( $max, $links ) ) {
      if ( ! in_array( $max - 1, $links ) )
          echo '<li>…</li>' . "\n";

      $class = $paged == $max ? ' class="active"' : '';
      printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
  }

  /** Next Post Link */
  if ( get_next_posts_link() )
      printf( '<li class="next-post-link">%s</li>' . "\n", get_next_posts_link() );

  echo '</ul></div>' . "\n";

}



add_action("wp_ajax_send_contact_main", "send_contact_main");


function wpcf7_validate_mb_char( $result, $tag ) {
	$field_name	= 'name-hanji';
	$value		= str_replace(array("\r", "\n", ' ', '　'), '', $_POST[$field_name]);

	if (!empty($value)) {
		if (!preg_match('/[ぁ-んァ-ヶー一-龠]/u', $value)) {
			$result['valid'] = false;
			$result['reason'] = array($field_name => '正しい値を入力してください。');
		}
	}
	return $result;
}
add_filter( 'wpcf7_validate', 'wpcf7_validate_mb_char', 10, 2 );


add_action("wp_ajax_review_submit", "review_submit");
add_action("wp_ajax_nopriv_review_submit", "review_submit");

function review_submit() {
parse_str($_POST['formdata'],$params);
global $wpdb;
$tablename = $wpdb->prefix."review";

$name = $params["reviewName"];
$email = $params["reviewEmail"];
$spot = $params["reviewSpot"];
$request = $params["request"];
$title = $params["reviewTitle"];
$remark = $params["remark"];
$avtar = $params["avatar"];
$content = $params["reviewText"];
$terms = $params["reviewAgree"];

$sql = $wpdb->query("INSERT INTO $tablename (name,email,category,request_detail,mouth_title,rating,image,content,terms) VALUES ('$name','$email','$spot','$request','$title','$remark','$avtar','$content','$terms')"); 


$results['type']='success';
$result = json_encode($results);
echo $result;
die;
}


function get_review_top($category){
global $wpdb;
$tablename = $wpdb->prefix."review";

$reviews_list = $wpdb->get_results("SELECT * FROM $tablename WHERE category = '$category' AND status = '1' ORDER BY id DESC LIMIT 4");
$html = '';
foreach($reviews_list as $single){
if($single->rating == 1){
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'>";
} elseif ($single->rating == 2) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}elseif ($single->rating == 3) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}elseif ($single->rating == 4) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}elseif ($single->rating == 5) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}


  $html .= '<div class="voice-box review'.$single->id.'" ><div class="voice-box-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$single->category.'</font></font></div>
                                    <div class="voice-box-header">
                                        <div class="voice-box-head">
                                            <img src="'.get_template_directory_uri().'/assets/image/avatar/avatar'.$single->image.'.png" alt="">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                '.$single->mouth_title.'
                                                </font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$single->name.' / '.$single->request_detail.'</font></font></span>
                                            </div>
                                        </div>
                                        <div class="voice-box-reputation">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">評価:</font></font></div>
                                           '. $star.'
                                        </div>
                                    </div>
                                    <div class="voice-box-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                      '.$single->content.'
                                    </font></font></div>
                                </div>';
}
return $html;
}





function get_review_slider($category){
global $wpdb;
$tablename = $wpdb->prefix."review";

$reviews_list = $wpdb->get_results("SELECT * FROM $tablename WHERE category = '$category' AND status = '1'ORDER BY id DESC LIMIT 4");
$html = '';
foreach($reviews_list as $single){
if($single->rating == 1){
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'>";
} elseif ($single->rating == 2) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}elseif ($single->rating == 3) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}elseif ($single->rating == 4) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}elseif ($single->rating == 5) {
 $star = "<img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'><img src='".get_template_directory_uri()."/assets/image/star.png'>";
}


  $html .= '<div class="voice-box swiper-slide"><div class="voice-box-title review'.$single->id.'"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$single->category.'</font></font></div>
                                    <div class="voice-box-header">
                                        <div class="voice-box-head">
                                            <img src="'.get_template_directory_uri().'/assets/image/avatar/avatar'.$single->image.'.png" alt="">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                '.$single->mouth_title.'
                                                </font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$single->name.' / '.$single->request_detail.'</font></font></span>
                                            </div>
                                        </div>
                                        <div class="voice-box-reputation">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">評価:</font></font></div>
                                           '. $star.'
                                        </div>
                                    </div>
                                    <div class="voice-box-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                      '.$single->content.'
                                    </font></font></div>
                                </div>';
}
return $html;
}
/*
function register_scripts() {
  if ( !is_admin() ) {
    // include your script
    wp_enqueue_script( 'email-confirm', get_bloginfo( 'template_url' ) . '/assets/js/email-confirm.js' );
  }
}
add_action( 'wp_enqueue_scripts', 'register_scripts' );*/



/* titleタグいらなくなる */
add_theme_support( 'title-tag' );
function wp_document_title_separator( $separator ) {
  $separator = '|';
  return $separator;
}
add_filter( 'document_title_separator', 'wp_document_title_separator' );

/*  initialize widget */

function custom_first_widget() {
    register_sidebar(
        array (
            'name' => __( 'First Widget in posts', 'cleanlifecenter' ),
            'id' => 'first_widget',
            'description' => __( 'Widgets for Posts', 'cleanlifecenter' ),
			'before_widget' => '<div class="post-ad1">',
            'after_widget' => '</div>',
        )
    );
}
add_action( 'widgets_init', 'custom_first_widget' );

function custom_last_widget() {
    register_sidebar(
        array (
            'name' => __( 'Last Widget in posts', 'cleanlifecenter' ),
            'id' => 'last_widget',
            'description' => __( 'Last Widgets for Posts', 'cleanlifecenter' ),
			'before_widget' => '<div class="banner">',
            'after_widget' => '</div>',
        )
    );
}
add_action( 'widgets_init', 'custom_last_widget' );



function mysite_admin_menu(){
    global $customMenu;
   $customMenu = add_menu_page('Reviews', 'Reviews', 'manage_options', 'review', 'review_function','dashicons-star-filled',26);
              
}
add_action('admin_menu', 'mysite_admin_menu');


function review_function(){ ?>
 <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
 <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <style type="text/css">
   #table_outer{padding: 20px; position: relative;}
   .dataTables_length select{width: 80px;}
   #example td span{display: inline-block; cursor: pointer; position: relative;}
   #example td span i{display: inline-block; width: 6px; height: 6px; background: #000; border-radius: 10px;}
   #example td span i:nth-child(2){margin: 0 6px;}
   #example td span ul{margin: 0; position: absolute; background: #ffffff; width: 120px; right: 0; box-shadow: 0px 0px 2px 1px #00000047; opacity: 0; visibility: hidden; -webkit-transition: all 0.3s ease;transition: all 0.3s ease;}
   #example td span:hover ul{opacity: 1; visibility: visible; z-index: 1000;}
   #example td span ul li{border-bottom: 1px solid #cccbcb; margin: 0;}
   #example td span ul li a{display: block; padding: 5px 10px; color: #000; font-size: 12px; font-weight: normal;text-decoration: none;}
   #example td span ul li a:hover{background: #f5f5f5;}
   #example td span ul li:last-child{border-bottom:none;}
   .loader_table {border: 16px solid #f3f3f3;border-radius: 50%;border-top: 16px solid #3498db;width: 120px;height: 120px;-webkit-animation: spin 2s linear infinite; /* Safari */ animation: spin 2s linear infinite;}
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.load_outer{position: absolute; top: 0;bottom: 0; left: 0; right: 0; margin: auto; align-items: center;  justify-content: center;
    z-index: 10; background: #00000012; display: none;}
.load_outer.active{display: flex;}
 </style>
 <?php if(isset($_GET['post']) &&  $_GET['post'] != '' && $_GET['action'] == 'edit'){ ?>
  <style type="text/css">
    .review {
    padding: 80px 0 60px;
  display: none;
}
#review {
    background-color: white;
    margin-bottom: 0;
    box-shadow: 0 0 18px rgba(0, 0, 0, .03);
    white-space: nowrap;
    overflow: hidden;
    padding: 40px;
}
.review-title {
    font-weight: bold;
    text-align: center;
    font-size: 35px;
    margin-bottom: 20px;
}
.review-input-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
    font-size: 16px;
    margin-bottom: 15px;
}
.review-input-box-spec {
    align-items: flex-start;
    margin-bottom: 0;
}
.review-input-title {
    display: flex;
    align-items: center;
    width: 370px;
}
.review-input-main {
    /*display: flex;*/
    justify-content: flex-start;
    align-items: center;
    width: 100%;
}
.review-form input,
.review-form select,
.review-form textarea {
    width: 100%;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #dedede;
    border-radius: 5px;
    outline: none;
    resize: none;
}
.review-form input::placeholder,
.review-form textarea::placeholder {
    color: #dedede;
}
.review-input-box-spec .review-input-title {
    padding-top: 12px;
}
.review-form button.submit {
    font-family: kozuka Gothic Pro6N;
    font-weight: bold;
    cursor: pointer;
}
input[type='checkbox'] {
    cursor: pointer;
}
.review-form input[type="number"] {
    display: none;
}
.input-star {
    width: 25px;
    height: 25px;
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    background-color: #dedede;
    cursor: pointer;
    display: inline-block;
}
.input-star-selected {
    background-color: #ffd800;
}
.input-avatar {
    border-radius: 999px;
    margin-right: 5px;
    border: 2px solid transparent;
    cursor: pointer;
    width: 60px;
    height: 60px;
    display: inline-block;
}
.input-avatar-selected {
    border: 2px solid black;
}
.form-check-box {
    font-size: 18px;
    text-align: center;
    margin-top: 30px;
}
.form-check-box input[type="checkbox"] {
    width: 16px;
    height: 16px;
}
.form-check-box a {
    color: #1288eb;
    text-decoration: underline;
}
.form-check-box button.submit {
    width: 200px;
    height: 60px;
    font-size: 24px;
    color: white;
    line-height: 1;
    background-color: #183494;
    border-radius: 999px;
    border: 2px solid #183494;
    transition: all .3s ease;
}
.form-check-box button.submit:hover {
    background-color: white;
    color: #183494;
}
.input-avatar img{
    max-width: 100%;
    height: auto;
    vertical-align: middle;
}
#reviewSubmit{padding: 20px !important; height: auto !important;}
  </style>
 <?php global $wpdb;
$tablename = $wpdb->prefix."review";
$id = $_GET['post'];
$review_lists = $wpdb->get_row("SELECT * FROM $tablename WHERE id = '$id'");
 ?>
 <div id="review">
                        <div class="review-title">口コミ投稿</div>
                        <form class="review-form" id="review-form">
                          <input type="hidden" name="row_id" value="<?php echo $review_lists->id; ?>">
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>ニックネーム</div>
                                </div>
                                <div class="review-input-main">
                                    <input type="text" name="reviewName"  value="<?php echo $review_lists->name; ?>" placeholder="ニックネームをご記入ください" required>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>メールアドレス</div>
                                </div>
                                <div class="review-input-main">
                                    <input type="email" name="reviewEmail"  value="<?php echo $review_lists->email; ?>" placeholder="sample.sameple.com" required>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>依頼カテゴリー</div>
                                </div>
                                <div class="review-input-main">
                                    <select name="reviewSpot" id="reviewSpot" required>
                                        <option <?php if($review_lists->category == 'トイレの水漏れ・つまり'){ echo "selected"; } ?> value="トイレの水漏れ・つまり">トイレの水漏れ・つまり</option>
                                        <option <?php if($review_lists->category == 'お風呂の水漏れ・つまり'){ echo "selected"; } ?> value="お風呂の水漏れ・つまり">お風呂の水漏れ・つまり</option>
                                        <option <?php if($review_lists->category == 'キッチンの水漏れ・つまり'){ echo "selected"; } ?> value="キッチンの水漏れ・つまり">キッチンの水漏れ・つまり</option>
                                        <option <?php if($review_lists->category == '洗面所の水漏れ・つまり'){ echo "selected"; } ?> value="洗面所の水漏れ・つまり">洗面所の水漏れ・つまり</option>
                                        <option <?php if($review_lists->category == '給湯器の修理・交換'){ echo "selected"; } ?> value="給湯器の修理・交換">給湯器の修理・交換</option>
                                        <option <?php if($review_lists->category == '排水管の水漏れ・つまり'){ echo "selected"; } ?> value="排水管の水漏れ・つまり">排水管の水漏れ・つまり</option>
                                        <option <?php if($review_lists->category == '水道管の水漏れ・つまり'){ echo "selected"; } ?> value="水道管の水漏れ・つまり">水道管の水漏れ・つまり</option>
                                    </select>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>依頼内容</div>
                                </div>
                                <div class="review-input-main">
                                    <input type="text" name="request"  value="<?php echo $review_lists->request_detail; ?>" placeholder="例）ウォシュレットの故障" required>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>口コミタイトル</div>
                                </div>
                                <div class="review-input-main">
                                    <input type="text" name="reviewTitle"  value="<?php echo $review_lists->mouth_title; ?>" placeholder="例）本当に助かりました！" required>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>評価（5段階）</div>
                                </div>
                                <div class="review-input-main">
                                    <input type="number" name="remark" min="1" max="5" required value="<?php echo $review_lists->rating; ?>">
                                    <div class="input-star <?php if($review_lists->rating > 0){ ?> input-star-selected <?php } ?>" onclick="inputStar(this);"></div>
                                    <div class="input-star <?php if($review_lists->rating > 1){ ?> input-star-selected <?php } ?>" onclick="inputStar(this);"></div>
                                    <div class="input-star <?php if($review_lists->rating > 2){ ?> input-star-selected <?php } ?>" onclick="inputStar(this);"></div>
                                    <div class="input-star <?php if($review_lists->rating > 3){ ?> input-star-selected <?php } ?>" onclick="inputStar(this);"></div>
                                    <div class="input-star <?php if($review_lists->rating > 4){ ?> input-star-selected <?php } ?>" onclick="inputStar(this);"></div>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>ニックネーム</div>
                                </div>
                                <div class="review-input-main">
                                    <input type="number" name="avatar" min="1" max="4" required>
                                    <div class="input-avatar <?php if($review_lists->image == 1){ ?> input-avatar-selected <?php } ?>" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="">
                                    </div>
                                    <div class="input-avatar  <?php if($review_lists->image == 2){ ?> input-avatar-selected <?php } ?>" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar2.png" alt="">
                                    </div>
                                    <div class="input-avatar  <?php if($review_lists->image == 3){ ?> input-avatar-selected <?php } ?>" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar3.png" alt="">
                                    </div>
                                    <div class="input-avatar  <?php if($review_lists->image == 4){ ?> input-avatar-selected <?php } ?>" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar4.png" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="review-input-box review-input-box-spec">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>ニックネーム</div>
                                </div>
                                <div class="review-input-main">
                                    <textarea name="reviewText" id="" cols="30" rows="10" name="review-text" required><?php echo $review_lists->content; ?></textarea>
                                </div>
                            </div>
                            <div class="form-check-box">
                                <input type="checkbox" checked name="reviewAgree" required>
                                <a href="https://cleanlife-center.com/privacy" target="_blank">個人情報の取り扱い</a>
                                <span>に同意する</span>
                            </div>
                            <div class="form-check-box">
                                <button class="submit" id="reviewSubmit" value="投稿する">投稿する</button>
                            </div>
                        </form>
                    </div>
          <script type="text/javascript">
            function inputStar(x) {
    var inputStar = document.getElementsByClassName('input-star');
    var i;
    var j;
    for(i=0; i<5; i++) {
        if(inputStar[i] == x) {
            document.querySelector('input[name="remark"]').value = i + 1;
            for(j=0; j<i+1; j++) {
                if (!inputStar[j].classList.contains('input-star-selected')) {
                    inputStar[j].classList.add('input-star-selected');
                }
            }
            for(j=i+1; j<5; j++) {
                if (inputStar[j].classList.contains('input-star-selected')) {
                    inputStar[j].classList.remove('input-star-selected');
                }
            }
        }
    }
}
function inputAvatar(x) {
    var inputAvatar = document.getElementsByClassName('input-avatar');
    var i;
    for(i=0; i<4; i++) {
        if(inputAvatar[i] == x) {
            document.querySelector('input[name="avatar"]').value = i + 1;
            if(!x.classList.contains('input-avatar-selected')) {
                x.classList.add('input-avatar-selected');
            }
            for(j=0; j<i; j++) {
                if (inputAvatar[j].classList.contains('input-avatar-selected')) {
                    inputAvatar[j].classList.remove('input-avatar-selected');
                }
            }
            for(j=i+1; j<4; j++) {
                if (inputAvatar[j].classList.contains('input-avatar-selected')) {
                    inputAvatar[j].classList.remove('input-avatar-selected');
                }
            }
        }
    }
}

$=jQuery;
      error = 0;
        $("#reviewSubmit").on("click",function(e){
            e.preventDefault();
            error=0;
            $(".required").each(function(index, element) {
            if($(this).val() == '' || $(this).val() == null){
            error = 1;  
            $(this).siblings(".error").html("この項目は必須です");  
            } else {
            $(this).siblings(".error").html("");
            }
            }); 

            $(".required_terms").each(function(index, element) {
             if($(this).is(":checked")){
               $(this).siblings(".error").html("");
            } else {
            error = 1;
              $(this).siblings(".error").html("この項目は必須です");
            }
            });


           
            $(".required_email").each(function(index, element) {
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{1,50}$/i;
            if($(this).val() == '' || $(this).val() == null){
            error = 1;  
            $(this).siblings(".error").html("この項目は必須です"); 
            } else if(!testEmail.test($(this).val())){
            error = 1;
            $(this).siblings(".error").html("有効なメールIDを入力してください");   
            } else {
            $(this).siblings(".error").html("");
            }
           });
           
            if(error == 0){
           
                $(".lds-ring").addClass("active");
      jQuery.ajax({
         type : "post",
         dataType : "json",
         url :"<?php echo admin_url('admin-ajax.php'); ?>",
         data : {action: "review_update", formdata : $("#review-form").serialize()},
         success: function(response) {
            if(response.type == "success") {
               Swal.fire({
                  icon: 'success',
                  text: 'レビューは正常に送信されました。',
                });
               window.setTimeout(function(){
                window.location.href = "<?php echo admin_url( '/admin.php?page=review'); ?>";
              },1000)
            } else {
               Swal.fire({
                  icon: 'error',
                  text: 'レビューは提出されていません。ページを更新してからお試しください。',
                })
            }
         }
          });
}
        })
          </script>
<?php } else { ?>
 <div class="table_outer" id="table_outer">
  <div class="load_outer"><div class="loader_table"></div></div>
 <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Request Detail</th>
                <th>Mouth title</th>
                <th>Rating</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php global $wpdb;
$tablename = $wpdb->prefix."review";
$review_lists = $wpdb->get_results("SELECT * FROM $tablename");
foreach($review_lists as $single_review){ ?>          
            <tr>
                <td><?php echo $single_review->name; ?></td>
                <td><?php echo $single_review->email; ?></td>
                <td><?php echo $single_review->category; ?></td>
                <td><?php echo $single_review->request_detail; ?></td>
                <td><?php echo $single_review->mouth_title; ?></td>
                <td><?php echo $single_review->rating; ?></td>
                <td><?php echo $single_review->content; ?></td>
                <td><span><i></i><i></i><i></i><ul>
                  <?php if($single_review->status == 1) { 
                    $btntext = 'Unapprove';
                   } else {
                    $btntext = 'Approve';
                   } ?>
                  <li><a class="approve_btn" data-text="<?php echo $btntext; ?>" href="javascript:void(0);" data-id="<?php echo $single_review->id; ?>"><?php echo $btntext; ?></a></li>
                  <li><a class="edit_btn" href="<?php echo admin_url( '/admin.php?page=review&amp;post='.$single_review->id.'&amp;action=edit'); ?>" data-id="<?php echo $single_review->id; ?>">Edit</a></li>
                  <li><a class="delete_btn" href="javascript:void(0);" data-id="<?php echo $single_review->id; ?>">Delete</a></li>
                </ul></span></td>
            </tr>
<?php } ?>      
        </tbody>
        <tfoot>
            <tr>
                 <th>Name</th>
                <th>Email</th>
                <th>Category</th>
                <th>Request Detail</th>
                <th>Mouth title</th>
                <th>Rating</th>
                <th>Content</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
  </div>
    <script>
      $=jQuery;
      $(document).ready(function() {
         $('#example').DataTable();
      });


      $(".approve_btn").click(function(){
        var id = $(this).attr("data-id");
        var text = $(this).attr("data-text");
      $(".load_outer").addClass("active");
      jQuery.ajax({
               type : "post",
               dataType : "json",
               url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
               data : {action : 'approve_review',review_id :id, text:text},
               success: function(response) {
                $(".load_outer").removeClass("active");
                  if(response.type == "success") {
                    Swal.fire({
                      icon: 'success',
                      text: response.msg,
                    });
                    setTimeout(function () {
                      location.reload(true);
                    }, 1000);
               
                  } 
        }
      }); 
});


      $(".delete_btn").click(function(){
        var id = $(this).attr("data-id");
      $(".load_outer").addClass("active");
      jQuery.ajax({
               type : "post",
               dataType : "json",
               url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
               data : {action : 'delete_review',review_id :id},
               success: function(response) {
                $(".load_outer").removeClass("active");
                  if(response.type == "success") {
                    Swal.fire({
                      icon: 'success',
                      text: 'Review deleted successfully.',
                    });
                    setTimeout(function () {
                      location.reload(true);
                    }, 1000);
               
                  } 
        }
      }); 
})

    </script>
<?php }

}





add_action( 'wp_ajax_nopriv_delete_review', 'delete_review' );
add_action( 'wp_ajax_delete_review', 'delete_review' );

function delete_review() {
$id = $_POST['review_id'];
global $wpdb;
$tablename = $wpdb->prefix."review";
$wpdb->delete( $tablename, array( 'id' => $id ) );
$results['type'] = 'success';
header('Content-type: application/json');
$result = json_encode($results);
echo $result; 
die;
}


add_action( 'wp_ajax_nopriv_approve_review', 'approve_review' );
add_action( 'wp_ajax_approve_review', 'approve_review' );

function approve_review() {
$id = $_POST['review_id'];
$status = $_POST['text'];
global $wpdb;
$tablename = $wpdb->prefix."review";
if($status == 'Approve'){
$wpdb->update( $tablename, array( 'status' => 1),array('id'=>$id));
$results['msg'] = 'Review approved successfully.';
} else if($status == 'Unapprove'){
$wpdb->update( $tablename, array( 'status' => 0),array('id'=>$id));
$results['msg'] = 'Review unapproved successfully.';
}

$results['type'] = 'success';
header('Content-type: application/json');
$result = json_encode($results);
echo $result; 
die;
}


add_action( 'wp_ajax_nopriv_review_update', 'review_update' );
add_action( 'wp_ajax_review_update', 'review_update' );

function review_update() {
parse_str($_POST['formdata'],$params);
global $wpdb;
$tablename = $wpdb->prefix."review";
$id = $params["row_id"];
$name = $params["reviewName"];
$email = $params["reviewEmail"];
$spot = $params["reviewSpot"];
$request = $params["request"];
$title = $params["reviewTitle"];
$remark = $params["remark"];
$avtar = $params["avatar"];
$content = $params["reviewText"];

$wpdb->update( $tablename, array('name' => $name,'email' => $email,'category' => $spot,'request_detail' => $request,'mouth_title' => $title,'rating' => $remark, 'image' => $avtar,'content' => $content),array('id'=>$id));
$results['msg'] = 'Review updated successfully.';

$results['type'] = 'success';
header('Content-type: application/json');
$result = json_encode($results);
echo $result; 
die;
}
