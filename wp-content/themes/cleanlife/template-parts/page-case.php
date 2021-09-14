<?php
/* 
Template Name: 施工事例
*/
get_header(); ?>

    <section class="page-title">
        <div class="content">
            <div class="current-page">
                <a href="">トップ</a>
                > 施工事例
            </div>
            <div class="title">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                <h1>施工事例</h1>
                <div class="subtitle"><div>CASE</div></div>
            </div>
        </div>
    </section>
    <div class="l-wrap">
        <main class="l-main case">
            <section class="case-information">
                <div class="header-text">
                    修理箇所別に施工事例をご覧いただけます。
                </div>
                <div class="case-header">
                    <a href="#pos-1" class="case-location">
                        トイレ <span>&#9662;</span>
                    </a>
                    <a href="#pos-2" class="case-location">
                        お風呂 <span>&#9662;</span>
                    </a>
                    <a href="#pos-3" class="case-location">
                        キッチン <span>&#9662;</span>
                    </a>
                    <a href="#pos-4" class="case-location">
                        給湯器 <span>&#9662;</span>
                    </a>
                    <a href="#pos-5" class="case-location">
                        洗面所 <span>&#9662;</span>
                    </a>
                    <a href="#pos-6" class="case-location">
                        排水管 <span>&#9662;</span>
                    </a>
                    <a href="#pos-7" class="case-location">
                        水道管 <span>&#9662;</span>
                    </a>
                </div>
                <div class="case-container">
           
                    <div class="case-main" id="pos-1">
                        <div class="location-header">
                            トイレの水漏れ・つまり
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 1, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
                    </div>
                    <div class="case-main" id="pos-2">
                        <div class="location-header">
                            お風呂の水漏れ・つまり
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 4, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
                    </div>
                    <div class="case-main" id="pos-3">
                        <div class="location-header">
                        キッチンの水漏れ・つまり
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 5, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
                    </div>
                    <div class="case-main" id="pos-4">
                        <div class="location-header">
                        洗面所の水漏れ・つまり
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 6, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
                    </div>
                    <div class="case-main" id="pos-5">
                        <div class="location-header">
                        給湯器の修理・交換
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 7, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
                    </div>
                    <div class="case-main" id="pos-6">
                        <div class="location-header">
                        排水管の水漏れ・つまり
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 8, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
                    </div>
                    <div class="case-main" id="pos-7">
                        <div class="location-header">
                        水道管の水漏れ・つまり
                        </div>
                        <?php 
                            $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 9, 'post_status'=>'publish',  'posts_per_page'=>10)); 

                            if ( $wpb_all_query->have_posts() ) :
                            while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                            
                            ?>
                        <div class="location-detail">
                            <div class="case-eyecache">
                            <?php
                            if (has_post_thumbnail()){
                                the_post_thumbnail();
                            }
                            else {
                                echo '<img src="'. get_template_directory_uri().'/assets/image/case/case.png" alt="">';
                            }
                            ?></div>
                            <div class="detail-text">
                                <div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/case/maps-and-flags.png" alt="">
                                    <?php the_title() ?></div>
                                <div><?php the_content() ?></div>
                                <div><?php $custom = get_post_custom();
                                    if(isset($custom['price'])) {
                                        echo $custom['price'][0];
                                    }?><span>円</span></div>
                            </div>
                        </div>                      
                        <?php 
                                endwhile;
                            else :
                                echo '<p>投稿はありません！</p>';
                            endif;
                        ?>    

                        <!--<a class="case-more-btn">
                            もっとみる
                        </a>-->
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
                                    <a href="tel:0120-423-152">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/footer-tel.png" alt="">
                                        0120-423-152
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
<?php get_footer();?>