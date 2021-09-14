<?php 
/* Template Name: Review */
get_header();
if(get_the_ID() == 113){
$category = 'トイレの水漏れ・つまり';  
} else if(get_the_ID() == 118){
$category = 'お風呂の水漏れ・つまり';
} else if(get_the_ID() == 120){
$category = 'キッチンの水漏れ・つまり';
}  else if(get_the_ID() == 122){
$category = '給湯器の修理・交換';
}  else if(get_the_ID() == 124){
$category = '洗面所の水漏れ・つまり';
}  else if(get_the_ID() == 126){
$category = '排水管の水漏れ・つまり';
}  else if(get_the_ID() == 128){
$category = '水道管の水漏れ・つまり';
}



global $wpdb;
$tablename = $wpdb->prefix."review";

$items_per_page = 4;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;
$query = 'SELECT * FROM '. $tablename.' WHERE category = "'.$category.'" AND status = "1" ORDER BY id DESC';
$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total = $wpdb->get_var( $total_query );
$results = $wpdb->get_results( $query.' LIMIT '. $offset.', '. $items_per_page, OBJECT );


$reviews_list = $wpdb->get_results("SELECT * FROM $tablename WHERE category = '$category' AND status = '1' ORDER BY id DESC");
 ?>
 <style type="text/css">
     .custom_pagination{text-align: center; padding: 20px 0;}
     .custom_pagination a, .custom_pagination span{margin: 0 5px;
    width: 30px;
    height: 30px;
    background: #01b3ed;
    border-radius: 40px;
    color: #fff;
    display: inline-flex;
    justify-content: center;
    align-items: flex-end;
    line-height: 1.8;}
    .custom_pagination span{ background: #183494;}
 </style>
    <section class="page-title">
        <div class="content">
            <div class="current-page">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>
                > お客様の声
            </div>
            <div class="title">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                <div>お客様の声</div>
                <div class="subtitle"><div>VOICE</div></div>
            </div>
        </div>
    </section>
    <div class="l-wrap">
        <main class="l-main area">
            <section class="voice-main-container">
               <!--  <div class="voice-btn">
                    <div class="service-main-content">
                        <div class="voice-btn-title">修理箇所別にお客様の声をご覧いただけます。</div>
                        
                    </div>
                </div> -->
          
                <div class="voice-main toilet-voice" id="toilet">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-toilet">
                            <?php echo $category; ?>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                               <?php 

foreach($results as $single){
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
}?>




                                 <div class="voice-box"><div class="voice-box-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $single->category; ?></font></font></div>
                                    <div class="voice-box-header">
                                        <div class="voice-box-head">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/image/avatar/avatar<?php echo $single->image; ?>.png" alt="">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                <?php echo $single->mouth_title; ?>
                                                </font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $single->name; ?> / <?php echo $single->request_detail; ?></font></font></span>
                                            </div>
                                        </div>
                                        <div class="voice-box-reputation">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">評価:</font></font></div>
                                          <?php echo $star; ?>
                                        </div>
                                    </div>
                                    <div class="voice-box-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                      <?php echo $single->content; ?>
                                    </font></font></div>
                                </div>
                            <?php } ?>
                              </div>
                            <div class="custom_pagination">
<?php
echo paginate_links( array(
                        'base' => add_query_arg( 'cpage', '%#%' ),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page
));
 ?>
</div>
                          
                           
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container1">
<div class="swiper-wrapper">
<?php 

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
 ?>



                                 <div class="voice-box swiper-slide"><div class="voice-box-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $single->category; ?></font></font></div>
                                    <div class="voice-box-header">
                                        <div class="voice-box-head">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/image/avatar/avatar<?php echo $single->image; ?>.png" alt="">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                <?php echo $single->mouth_title; ?>
                                                </font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $single->name; ?> / <?php echo $single->request_detail; ?></font></font></span>
                                            </div>
                                        </div>
                                        <div class="voice-box-reputation">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">評価:</font></font></div>
                                          <?php echo $star; ?>
                                        </div>
                                    </div>
                                    <div class="voice-box-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                      <?php echo $single->content; ?>
                                    </font></font></div>
                                </div>
                            <?php } ?>
</div>
                          <div class="swiper-button-prev swiper-button-prev-voice1"></div>
                            <div class="swiper-button-next swiper-button-next-voice1"></div>
                        </div>
                       
                    </div>
                </div>
            
            </section>
          
            <section class="ask-us">
                <div class="ask-us-img">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/ranking-men.png" alt="">
                    <div class="ask-us-title"><div>水道<span>の</span>トラブル</div><span>なら</span>なんでも<br>
                        クリーンライフ<span>に</span>ご相談ください<div class="last-dot">。</div></div>
                </div>
                <div class="ask-us-text">水道のトラブルはなんでもご相談ください。水漏れやつまり、部品交換修理などクリーンライフでは水道に関わるあらゆるトラブルを解決いたします。漏水場所が判明しない場合でも水漏れ箇所の特定からご対応することが可能です。お困りごとがありましたら是非クリーンライフにご相談ください。</div>
            </section>
            <section class="coupon">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/sub-coupon.jpg" alt="" class="coupon-back">
                <div class="coupon-content">
                    <div class="coupon-tel-container">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-off.png" alt="" class="coupon-off">
                        <div class="coupon-tel-container01">
                            <div class="coupon-title">お電話1本<span class="coupon-title-small">で</span><br><span class="coupon-title-dot">す</span><span class="coupon-title-dot">ぐ</span><span class="coupon-title-dot">に</span>駆けつけます!</div>
                            <div class="coupon-tel-main">
                                <div class="coupon-current">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/red-clock.png" alt="">
                                    <div class="coupon-current-time">
                                        <span  class="current-time-all">13：25</span>
                                        現在、お電話いただけましたら即日修理対応可能です！
                                    </div>
                                </div>
                                <div class="coupon-tel-main01">
                                    <div class="coupon-tel-text">
                                        24時間・365日対応
                                        <div>お見積無料</div>
                                    </div>
                                    <a href="tel:050-7562-0599">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/footer-tel.png" alt="">
                                        050-7562-0599
                                    </a>
                                </div>
                            </div>
                            <div class="coupon-drop-container">
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>出張見積<br>
                                        無料！</div>
                                </div>
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>キャンセル<br>
                                        無料！</div>
                                </div>
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>深夜料金<br>
                                        休日料金<br>
                                        一切なし！</div>
                                </div>
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>安心の<br>
                                        無料保証</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- CTA ADD Start -->
                <div class="coupon-tel-main-sp" onclick="location.href='tel:0120-423-152';" style="cursor:pointer;">
                    <div class="coupon-tel-hand">
                        <span>ここをタップして今すぐお電話
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel-hand.png" alt="">
                        </span>
                    </div>
                    <div class="coupon-current">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/white-clock.png" alt="">
                        <div class="coupon-current-time">
                            <span  class="current-time-all">13：25</span>
                            現在、お電話いただけましたら即日修理対応可能です！
                        </div>
                    </div>
                    <div class="coupon-tel-main01">
                        <div class="coupon-tel-text">
                            24時間・365日対応
                            <div>お見積無料</div>
                        </div>
                        <a href="tel:0120-423-152">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel.png" alt="">
                            <span>0120-423-152</span>
                        </a>
                    </div>
                </div>
                <div class="coupon-pay-container">
                    <div class="coupon-payment">
                        <div class="coupon-pay-box">
                            <div>各種クレジットカード対応</div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment1.png" alt="">
                        </div>
                        <div class="coupon-pay-box">
                            <div>コンビニ後払い対応</div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment2.png" alt="">
                        </div>
                    </div>
                    <div class="coupon-line-container">
                        <a href="https://lin.ee/RqJ6Mk3" target="_blank" rel="noopener noreferrer" class="coupon-line">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/line.png" alt="">
                            <div>LINEで無料相談</div>
                            <div class="coupon-line-add">\最短<span>30秒</span>でご返信/</div>
                        </a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact" class="coupon-mail">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/mail.png" alt="">
                            <div>メールで無料相談</div>
                            <div class="coupon-mail-add">\専門スタッフが<span>即対応</span>！/</div>
                        </a>
                    </div>
                </div>
                <!-- CTA ADD End -->
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/woman.png" alt="" class="coupon-woman">
                </div>
            </section>
        </main>
        <?php get_sidebar();?>
    </div>
 
<script>
     const swiper2 = new Swiper('.voice-box-container1', {
              loop: true,
              slidesPerView: 1,
              centeredSlides: true,
              spaceBetween: 50,
              autoplay: {
                  delay: 3000,
                  disableOnInteraction: false,
              },
              navigation: {
                  nextEl: ".swiper-button-next-voice1",
                  prevEl: ".swiper-button-prev-voice1",
                },
              breakpoints: {
                  // when window width is >= 480px
                  450: {
                    slidesPerView: 1.9,
                  },
                  // when window width is >= 320px
                  250: {
                    slidesPerView: 1,
                  },
                }
            });
        </script>
<?php get_footer();?>
