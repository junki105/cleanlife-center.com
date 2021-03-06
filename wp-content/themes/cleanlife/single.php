<?php get_header();?>
    <div class="l-wrap">
        <main class="l-main">
            <div class="post-wrap">
                <?php 

                if ( have_posts() ) :?>
                <?php 

                while ( have_posts() ) : the_post(); 

                ?>
                <div class="post-eyecache">
                <?php

                    if (has_post_thumbnail()){
                    the_post_thumbnail( 'full' ); 
                    }

                    else {
                    echo '<img src="'. get_template_directory_uri().'/assets/image/single/post_eyecache.png" alt="">';
                    }
                    ?>
                    <div class="post-category">
                    <?php
                        $categories = get_the_category();
                        $comma      = '　｜　';
                        $output     = '';
                        
                        if ( $categories ) {
                            foreach ( $categories as $category ) {
                            $output .= '<a href="' . get_category_link( $category->term_id ) . '">' . $category->cat_name . '</a>' . $comma;
                            }
                            echo trim( $output, $comma );
                        } ?>
                    </div>
                    <h1 class="post-ttl"><?php the_title() ?></h1>
                </div>
                <div class="post-content-wrap">
                    <div class="post-date">
                        <div class="post-date-published"><i class="far fa-clock"></i><?php the_time( 'Y.m.d' ); ?></div>
                        <div class="post-date-updated">
                            <?php $u_time = get_the_time('U'); 
                            $u_modified_time = get_the_modified_time('U'); 
                            if ($u_modified_time >= $u_time + 86400) { 
                            echo '<i class="fas fa-sync-alt"></i>';
                                the_modified_time('Y.m.d'); 
                                } ?>
                        </div>
                    </div>
                    <div class="post-content"><div class="post-menu">
<div class="water-flow"><?php the_content() ?></div>
</div>
                    </div>
                    <?php
					   if ( is_active_sidebar( 'first_widget' ) ) {
						  dynamic_sidebar( 'first_widget' );
					   }
					?>
                    
						
                <?php 
                    endwhile;
					wp_reset_postdata();
                ?>
                  <?php
                    else :
                    echo '<p>投稿はありません！</p>';
                    
                    endif;
                ?>

						<!--<div class="related-post">
                            <div class="related-post-mark">関連記事</div>
                            <div class="related-post-content">
							    <?php 
                                $wpb_recent_query = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>1)); 

                                if ( $wpb_recent_query->have_posts() ) :
                                while ( $wpb_recent_query->have_posts() ) : $wpb_recent_query->the_post(); 
                                
                                ?>
                                <div class="related-post-img">
                                    <?php

                                    if (has_post_thumbnail()){
                                        the_post_thumbnail();
                                    }

                                    else {
                                        echo '<img src="'. get_template_directory_uri().'/assets/image/single/post_eyecache.png" alt="">';
                                    }
                                    ?>
								</div>
                                <div class="related-post-container">
                                    <div class="related-post-ttl"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></div>
                                    <div class="related-post-date">
                                        <div class="related-post-date-published"><i class="far fa-clock"></i><?php the_time( 'Y.m.d' ); ?></div>
                                        <div class="related-post-date-updated"><i class="fas fa-sync-alt"></i>
                                        <?php $u_time = get_the_time('U'); 
                                            $u_modified_time = get_the_modified_time('U'); 
                                            if ($u_modified_time >= $u_time + 86400) { 
                                                the_modified_time('Y.m.d'); 
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endwhile;
                                wp_reset_postdata();
                            ?>
                    <?php
                    else :
                    echo '<p>関連記事はありません！</p>';
                    
                    endif;
                    ?>
                            </div>

                        </div>-->

                        <!--<?php
						   if ( is_active_sidebar( 'last_widget' ) ) {
							  dynamic_sidebar( 'last_widget' );
						   }
						?>-->
                    </div>

                </div>
 
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