<?php
/*
Template Name: 地域サービス
Template Post Type: region
*/
get_header();?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/prefecture.css">
<script src="<?php echo get_template_directory_uri()?>/assets/js/faq.js"></script>
<?php 
	if ( have_posts() ) :?>
<?php 

while ( have_posts() ) : the_post(); 

?>
    <section class="service-banner">
        <div class="toilet-banner">
            <div class="content">
                <h1>
                    <div class="hero-prefecture-ttl">
					<?php
						$custom = get_post_custom();
						
						if(!empty($custom['number'][0])) {
					?>
                        <div class="prefecture-num">
                            <div class="prefecture-num1"><?php the_title() ?></div>
                            <div class="prefecture-num2">水道局指定工事店</div>
                            <div class="prefecture-num3">-<?php echo $custom['number'][0];?>-</div>
                        </div>
					<?php } 
						else {
					?>
                        <div class="prefecture-num">
                            <div class="prefecture-num1 prefecture-num1-second">水道局<br>指定工事店</div>
                        </div>
					<?php } ?>
                        <div class="prefecture-sm-ttl">
                            <div class="sm-ttl-1" data-text="<?php $parent_title = get_the_title($post->post_parent); echo $parent_title;?>"><?php  echo $parent_title; ?></div>
                            <div class="sm-ttl-2" data-text="<?php the_title() ?>"><?php the_title() ?><span data-text="の">の</span></div>
                        </div>
                    </div>
                    <div class="banner-title-toilet" data-text="トイレのトラブルならなら">
					<?php
						$categories = get_the_category();
						$output     = '';

						if ( $categories ) {
							foreach ( $categories as $category ) {
								$output .= $category->cat_name;
							}
							$cat_clip_before =  $output;

							$cat_clip_after = substr($cat_clip_before, 0, 9);
						} 
					?>
                    <span class="banner-ttl-effect" data-text="<?php echo $cat_clip_after?>"><?php echo $cat_clip_after?></span><span class="banner-ttl-true-span" data-text="の">の</span><span class="banner-ttl-effect-2" data-text="トラブル">トラブル</span><span class="banner-title-spec" data-text="なら">なら</span></div>
                    <div class="banner-title-common" data-text="クリーンライフ">クリーンライフ</div><span class="banner-common-span-sm" data-text="に">に</span><span class="banner-common-span" data-text="お任せください！">お任せください！</span>
                </h1>
                <div class="hero-img">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/hero-img.png" alt="">
                </div>
                <div class="hero-man-img">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/hero-3man.png" alt="">
                </div>
                <div class="hero-img-sp">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/hero-img-sp.png" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="prefecture-coupon">
        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon.jpg" alt="" class="prefecture-coupon-back">
        <div class="content prefecture-content">
            <div class="prefecture-coupon-content">
                <div class="prefecture-coupon-tel-container">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-off.png" alt="" class="prefecture-coupon-off">
                    <div class="prefecture-coupon-tel-container01">
                        <div class="prefecture-coupon-title">お電話1本<span class="prefecture-coupon-title-small">で</span><br><span class="prefecture-coupon-title-dot">す</span><span class="prefecture-coupon-title-dot">ぐ</span><span class="prefecture-coupon-title-dot">に</span>駆けつけます!</div>
                        <div class="prefecture-coupon-tel-main">
                            <div class="prefecture-coupon-current">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/red-clock.png" alt="">
                                <div class="prefecture-coupon-current-time">
                                    <span  class="current-time-all">13：25</span>
                                    現在、お電話いただけましたら即日修理対応可能です！
                                </div>
                            </div>
                            <div class="prefecture-coupon-tel-main01">
                                <div class="prefecture-coupon-tel-text">
                                    24時間・365日対応
                                    <div>お見積無料</div>
                                </div>
                                <a href="tel:0120-423-152">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/footer-tel.png" alt="">
                                    0120-423-152
                                </a>
                            </div>
                        </div>
                        <div class="prefecture-coupon-drop-container">
                            <div class="prefecture-coupon-drop">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                <div>出張見積<br>
                                    無料！</div>
                            </div>
                            <div class="prefecture-coupon-drop">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                <div>キャンセル<br>
                                    無料！</div>
                            </div>
                            <div class="prefecture-coupon-drop">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                <div>深夜料金<br>
                                    休日料金<br>
                                    一切なし！</div>
                            </div>
                            <div class="prefecture-coupon-drop">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                <div>安心の<br>
                                    無料保証</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CTA ADD Start -->
                <div class="prefecture-coupon-tel-main-sp" onclick="location.href='tel:0120-423-152';" style="cursor:pointer;">
                    <div class="prefecture-coupon-tel-hand">
                        <span>ここをタップして今すぐお電話
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel-hand.png" alt="">
                        </span>
                    </div>
                    <div class="prefecture-coupon-current">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/white-clock.png" alt="">
                        <div class="prefecture-coupon-current-time">
                            <span  class="current-time-all">13：25</span>
                            現在、お電話いただけましたら即日修理対応可能です！
                        </div>
                    </div>
                    <div class="prefecture-coupon-tel-main01">
                        <div class="prefecture-coupon-tel-text">
                            24時間・365日対応
                            <div>お見積無料</div>
                        </div>
                        <a href="tel:0120-423-152">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel.png" alt="">
                            <span>0120-423-152</span>
                        </a>
                    </div>
                </div>
                <div class="prefecture-coupon-pay-container">
                    <div class="prefecture-coupon-payment">
                        <div class="prefecture-coupon-pay-box">
                            <div>各種クレジットカード対応</div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment1.png" alt="">
                        </div>
                        <div class="prefecture-coupon-pay-box">
                            <div>コンビニ後払い対応</div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment2.png" alt="">
                        </div>
                    </div>
                    <div class="prefecture-coupon-line-container">
                        <a href="https://lin.ee/RqJ6Mk3" target="_blank" rel="noopener noreferrer" class="prefecture-coupon-line">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/line.png" alt="">
                            <div>LINEで無料相談</div>
                            <div class="prefecture-coupon-line-add">\最短<span>30秒</span>でご返信/</div>
                        </a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact" class="prefecture-coupon-mail">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/mail.png" alt="">
                            <div>メールで無料相談</div>
                            <div class="prefecture-coupon-mail-add">\専門スタッフが<span>即対応</span>！/</div>
                        </a>
                    </div>
                </div>
                <!-- CTA ADD End -->
                <img src="<?php echo get_template_directory_uri()?>/assets/image/woman.png" alt="" class="prefecture-coupon-woman">
            </div>
        </div>
    </section>
    <div class="l-wrap">
        <main class="l-main area">
            <div class="service-current-page-pc">
                <a href="<?php echo get_template_directory_uri()?>/index.html">トップ</a>> サービス＆料金> <?php
						$categories = get_the_category();
						$output     = '';

						if ( $categories ) {
							foreach ( $categories as $category ) {
								$output .= $category->cat_name;
							}
							echo $output;
						} 
					?>> <?php echo $parent_title;?>> <?php the_title() ?>
            </div>
            
            <section class="toilet-question question">
                <h2>
                    <div>こんな<span class="toilet-question-title">トイレのトラブル</span>で</div>
                    お困りではありませんか？
                </h2>
                <div class="question-container">
                    <div class="question-box">
                        <div class="question-spec">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレの配管から水が漏れ出している
                        </div>
                        <div class="question-spec">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレの床が常に水浸しになってしまう
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレがつまってしまった
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            便器が損傷してしまった
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレに物を落としてしまった
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレの水が止まらない
                        </div>
                    </div>
                    <div class="question-box">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレの換気扇が壊れてしまった
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            ウォシュレットを交換したい
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレから異臭がする
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレから異音がする
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレを交換したい
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トイレのタンクが壊れている
                        </div>
                    </div>
                </div>
                <div class="question-sentence">
                    <h3><?php the_title() ?>のトイレトラブル<span>なら</span><br>クリーンライフ<span>に</span>ご相談ください。</h3>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/question-3man.png" alt="">
                </div>
                <div class="question-bottom">
                    トイレのトラブルはなんでもご相談ください。水漏れやつまり、部品交換修理などクリーンライフではトイレに関わるあらゆるトラブルを解決いたします。漏水場所が判明しない場合でも水漏れ箇所の特定からご対応することが可能です。お困りごとがありましたら是非クリーンライフにご相談ください。
                </div>
            </section>
			<section class="clean">
                <div class="clean-container-main">
                    <div class="clean-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean-tite.png" alt="">
                            <span>クリーンライフの料金体系は</span>
                        </div>
                        <div>安心実直<span>の</span>明朗会計</div>
                    </div>
                    <div class="clean-desc">他社のホームページには明確な料金が書かれていなかったり、極端に安い料金が掲載されているケースがございますが、クリーンライフでは明朗会計な料金体系をモットーにしっかりと料金を記載しておりますので安心してご利用いただけます。</div>
                    <div class="clean-money-box">
                        <div class="clean-money1">
                            <div class="clean-money-kind">お見積り料金</div>
                            <div class="clean-money-dot">・</div>
                            <div class="clean-money-kind">出張料金</div>
                            <div class="clean-money-dot">・</div>
                            <div class="clean-money-kind">キャンセル料金</div>
                            <div class="clean-money-dot">・</div>
                            <div class="clean-money-kind">夜間・早朝割増</div>
                            <div class="clean-money-dot">・</div>
                            <div class="clean-money-kind">休日料金</div>
                            <div class="clean-money-text1">お見積り料金/出張料金/キャンセル料金<br>
                                /夜間・早朝割増/休日料金</div>
                            <div class="clean-money-arrow">⇒</div>
                            <div class="clean-money-result">すべて無料</div>
                        </div>
                        <div class="clean-money2">
                            <div class="clean-money-kind">
                                <div>基本料金</div>
                                <div>3,300<span>円（税込）<span></div>
                            </div>
                            <div class="clean-money-plus">+</div>
                            <div class="clean-money-kind">作業料金</div>
                            <div class="clean-money-plus">+</div>
                            <div class="clean-money-kind">部品代</div>
                            <div class="clean-money-plus clean-money-minus">−</div>
                            <div class="clean-money-result">
                                <div>WEB限定割引</div>
                                <div>3,000<span>円</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-subtitle">トイレトラブルの作業費用一例</div>
                    <div class="clean-box-container">
						<?php
							$region_price = get_field('region-price');
							if  (!empty($region_price)) {
                                $i = 1;
							foreach ($region_price as $price) {
						?>
                        <div class="clean-box">
                            <div class="clean-box-title"><?php if (!empty($price['title'])) { echo $price['title'];} else { echo 'タイトルなし';}?></div>
                            <div class="clean-box-subtitle">（<?php if (!empty($price['client'])) { echo $price['client'];} else { echo '名前なし';}?>）</div>
							<?php if (!empty($price['image'])) 
							{ 
								$image = $price['image']; 
							?>
								<img class="clean-box-img" src="<?php echo esc_url($image['url'])?>" alt="">
							<?php
							} 
							else { 
							?>
								<img class="clean-box-img" src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/toilet-clean1.png" alt="<?php echo esc_attr($image['alt']); ?>">
							<?php
							}
							?>
                            <div class="clean-box-content">
                                <div>基本料金 <span><?php if (!empty($price['main'])) { echo $price['main'];} else { echo 0;}?></span>円</div>
                                <div>作業料金 <span><?php if (!empty($price['work'])) { echo $price['work'];} else { echo 0;}?></span>円</div>
                                <div>部品代 <span><?php if (!empty($price['plus'])) { echo $price['plus'];} else { echo 0;}?></span>円</div>
                                <!-- <div>TAX <span>2,130</span>円</div> -->
                                <div class="clean-box-content-plus">+</div>
                            </div>
                            <div class="clean-box-content clean-box-content-result">合計<span><?php if (!empty($price['amount'])) { echo $price['amount'];} else { echo 0;}?></span>円</div>
                            <div class="clean-box-result">
                                <div class="clean-box-discount">
                                    <div>WEB割引</div>
                                    <div>3,000円</div>
                                </div>
                                <div class="clean-box-result01">
                                    <div class="clean-box-result-arrow">⇒</div>
                                    <div><?php if (!empty($price['amount-with-tax'])) { echo $price['amount-with-tax'];} else { echo 0;}?><span class="clean-box-result-yen">円(税込)</span></div>
                                </div>
                            </div>
                        </div>
						<?php
                            if ($i++ == 3) break;
							}
                        }
						?> 
                    </div>
                    <div class="slider-clean-content">
                        <div class="swiper-container swiper-container-clean clean-box-container">
                            <div class="swiper-wrapper">
							<?php
								$region_price = get_field('region-price');
								foreach ($region_price as $price) {
							?>
                                <div class="clean-box swiper-slide">
                                    <div class="clean-box-title"><?php if (!empty($price['title'])) { echo $price['title'];} else { echo 'タイトルなし';}?></div>
                                    <div class="clean-box-subtitle">（<?php if (!empty($price['client'])) { echo $price['client'];} else { echo '名前なし';}?>）</div>
									<?php if (!empty($price['image'])) 
									{ 
										$image = $price['image']; 
									?>
										<img class="clean-box-img" src="<?php echo esc_url($image['url'])?>" alt="">
									<?php
									} 
									else { 
									?>
										<img class="clean-box-img" src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/toilet-clean1.png" alt="<?php echo esc_attr($image['alt']); ?>">
									<?php
									}
									?>
                                    <div class="clean-box-content">
                                        <div>基本料金 <span><?php if (!empty($price['main'])) { echo $price['main'];} else { echo 0;}?></span>円</div>
                                        <div>作業料金 <span><?php if (!empty($price['work'])) { echo $price['work'];} else { echo 0;}?></span>円</div>
                                        <div>部品代 <span><?php if (!empty($price['plus'])) { echo $price['plus'];} else { echo 0;}?></span>円</div>
                                        <!--<div>TAX <span>2,130</span>円</div>-->
                                        <div class="clean-box-content-plus">+</div>
                                    </div>
                                    <div class="clean-box-content clean-box-content-result">合計<span><?php if (!empty($price['amount'])) { echo $price['amount'];} else { echo 0;}?></span>円</div>
                                    <div class="clean-box-result">
                                        <div class="clean-box-discount">
                                            <div>WEB割引</div>
                                            <div>3,000円</div>
                                        </div>
                                        <div class="clean-box-result01">
                                            <div class="clean-box-result-arrow">⇒</div>
                                            <div><?php if (!empty($price['amount-with-tax'])) { echo $price['amount-with-tax'];} else { echo 0;}?><span class="clean-box-result-yen">円(税込)</span></div>
                                        </div>
                                    </div>
                                </div>
							<?php
								}
							?> 
                            </div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                    <div class="clean-notice">※状況や部品の種類/仕様によって料金変動します。</div>
                    <div class="clean-table-title">トイレのつまり・水漏れの修理料金表</div>
                    <table class="clean-table">
                        <tr>
                            <td>作業内容</td>
                            <td>修理時間目安</td>
                            <td>修理料金目安</td>
                        </tr>
                        <tr>
                            <td>トイレの詰まり（軽度なつまり）</td>
                            <td>30～60分</td>
                            <td>5,500円</td>
                        </tr>
                        <tr>
                            <td>トイレの詰まり（高圧ポンプ使用）</td>
                            <td>30～60分</td>
                            <td>5,500円</td>
                        </tr>
                        <tr>
                            <td>トイレの詰まり（ドレンクリーナー使用）</td>
                            <td>30～60分</td>
                            <td>16,500円～</td>
                        </tr>
                        <tr>
                            <td>トイレの詰まり（高圧洗浄機使用）</td>
                            <td>30～120分</td>
                            <td>27,500円～</td>
                        </tr>
                        <tr>
                            <td>トイレの水漏れ</td>
                            <td>30～60分</td>
                            <td>3,300円</td>
                        </tr>
                        <tr>
                            <td>ポールタップ交換</td>
                            <td>30～60分</td>
                            <td>8,800円</td>
                        </tr>
                        <tr>
                            <td>フロートバルブ交換</td>
                            <td>30～60分</td>
                            <td>8,800円</td>
                        </tr>
                        <tr>
                            <td>上水栓交換</td>
                            <td>30～60分</td>
                            <td>13,200円</td>
                        </tr>
                        <tr>
                            <td>タンクレバー交換</td>
                            <td>30～60分</td>
                            <td>8,800円</td>
                        </tr>
                        <tr>
                            <td>給水管交換</td>
                            <td>30～60分</td>
                            <td>13,200円</td>
                        </tr>
                        <tr>
                            <td>トイレの蛇口交換</td>
                            <td>30～60分</td>
                            <td>13,200円</td>
                        </tr>
                        <tr>
                            <td>フレキ管交換</td>
                            <td>約30分</td>
                            <td>8,800円</td>
                        </tr>
                        <tr>
                            <td>藥品洗浄</td>
                            <td>30～60分</td>
                            <td>8,800円</td>
                        </tr>
                        <tr>
                            <td>排水フランジ交換</td>
                            <td>30～60分</td>
                            <td>8,880円</td>
                        </tr>
                        <tr>
                            <td>便器用フラッシュバルブ</td>
                            <td>30～60分</td>
                            <td>22,000円</td>
                        </tr>
                        <tr>
                            <td>大使器用洗浄管（32nn）-38mm）</td>
                            <td>30～60分</td>
                            <td>22,000円</td>
                        </tr>
                        <tr>
                            <td>排水弁部交換</td>
                            <td>30～60分</td>
                            <td>8,800円</td>
                        </tr>
                        <tr>
                            <td>タンクの交換</td>
                            <td>30～60分</td>
                            <td>16,500円</td>
                        </tr>
                        <tr>
                            <td>和式便器</td>
                            <td>現地状況で異なる</td>
                            <td>現地見積</td>
                        </tr>
                        <tr>
                            <td>洋式トイレ脱着工事費</td>
                            <td>30～60分分</td>
                            <td>33,000円</td>
                        </tr>
                        <tr>
                            <td>ウォシュレットの取替え•取り付け•脱着</td>
                            <td>30～60分</td>
                            <td>16,500円</td>
                        </tr>
                        <tr>
                            <td>便器の交換</td>
                            <td>120～240分</td>
                            <td>33,000円</td>
                        </tr>
                    </table>
                    <div class="clean-table-notice">上記作業料金・時間は目安です。<br>
                        状況や部品の種類/仕様によって料金変動します。<br>
                        作業スタッフが現場でトラブル状況を確認の上、<br class="clean-table-br">最終お見積をご提示いたします。
                    </div>
                </div>
            </section>
            <section class="note">
                <div class="note-content">
                    <div class="note-text">昨今、不当な高額請求をする業者が<br>
                        増えておりますのでお気を付けください。<br>
                        そしてもし、<span>他社で高額請求された場合</span>は、<br>
                        一度弊社にご相談ください。<br>
                        適正価格にてお見積もりいたします。</div>
                <!-- CTA ADD Start -->
                <div class="note-current-container" onclick="location.href='tel:0120-423-152';" style="cursor:pointer;">
                    <div class="note-tel-hand">
                        <span>ここをタップして今すぐお電話
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel-hand.png" alt="">
                        </span>
                    </div>
                    <div class="note-current">
                        <picture>
                            <source media="(max-width: 850px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/white-clock.png">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/red-clock.png" alt="">
                        </picture>
                        <div class="note-current-time">
                            <span class="current-time-all">13：25</span>現在、お電話いただけましたら即日修理対応可能です！
                        </div>
                    </div>
                    <div class="note-tel-main">
                        <div class="note-tel-text">
                            24時間・365日対応
                            <div>お見積無料</div>
                        </div>
                        <a href="tel:0120-423-152">
                            <picture>
                                <source media="(max-width: 850px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/tel.png">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/footer-tel.png" alt="">
                            </picture>
                            <span>0120-423-152</span>
                        </a>
                    </div>
                </div>                    
                <div class="note-line-container">
                    <a href="https://lin.ee/RqJ6Mk3" target="_blank" rel="noopener noreferrer" class="note-line">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/line1.png" alt="">
                        <div>LINEで無料相談</div>
                        <div class="note-line-add">\最短<span>30秒</span>でご返信/</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact" class="note-mail">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/mail1.png" alt="">
                        <div>メールで無料相談</div>
                        <div class="note-mail-add">\専門スタッフが<span>即対応</span>！/</div>
                    </a>
                </div>
                <!-- CTA ADD End -->
                </div>
            </section>
            <section class="reason">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/reason-top.png" alt="" class="reason-top">
                <div class="reason-content">
                    <div class="reason-title"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason-title.png" alt=""></div>
                    <div class="reason-container">
                        <div class="reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason1.png" alt="">
                            <div class="reason-text">
                                <div>迅速<span>な</span>対応</div>
                                水道トラブルのお電話を頂いてから、最短３０分で駆けつけ、作業に関しても新型コロナウィルス対策として、以前より<span>さらに「早く・正確に」</span>を意識し、取り組んでおります。
                            </div>
                        </div>
                        <div class="reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason2.png" alt="">
                            <div class="reason-text">
                                <div>確固<span>たる</span>実績</div>
                                お陰様で<span>施工実績30万件</span>を達成いたしました。その豊富な経験とノウハウを最大限活かし、お客様に最適なご提案を心がけてまいります。
                            </div>
                        </div>
                        <div class="reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason3.png" alt="">
                            <div class="reason-text">
                                <div>経験豊富<span>な</span>スタッフ</div>
                                水のトラブルに関する<span>深い知識と、高い技術力を兼ね備えた経験豊かな有資格者</span>が、丁寧に不明確な事の無いようわかり易く説明いたします。
                            </div>
                        </div>
                        <div class="reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason4.png" alt="">
                            <div class="reason-text">
                                <div>充実<span>の</span>アフターフォロー</div>
                                    修理に応じて<span>１～３年の無料点検、無料保証</span>を用意しております。メーカー保証等に関するご質問も、お電話または訪問スタッフにお気軽にご質問下さい。
                            </div>
                        </div>
                        <div class="reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason5.png" alt="">
                            <div class="reason-text">
                                <div>安心<span>の</span>明朗会計</div>
                                料金が複雑になりがちなこの業界において、<span>明朗会計をモットーにわかりやすい料金体系</span>にてご案内しております。料金に関してご不明な点があればお問い合わせください。
                            </div>
                        </div>
                        <div class="reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason6.png" alt="">
                            <div class="reason-text">
                                <div>トラブルゼロ<span>の</span>自信</div>
                                作業前に修理内容とお見積りをご確認いただき、<span>了承を得てからの作業開始を徹底</span>しております。何か不審な点があれば名刺の裏のお問い合わせからお電話ください。
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="declaration">
                <div class="declaration-title"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/declaration-title.png" alt=""></div>
                <div class="declaration-desc">クリーンライフは必ず作業に入る前に修理内容をご説明し、<br>
                    確定した修理費用をご提示致します。</div>
                <div class="declaration-flow">
                    <div class="declaration-flow-box">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/declaration-flow1.png" alt="">
                        <div>修理内容のご説明、<br>修理費用の提示</div>
                    </div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/declaration-arrow.png" alt="" class="declaration-arrow">
                    <div class="declaration-flow-box">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/declaration-flow2.png" alt="">
                        <div>ご納得頂いた上で<br>修理を開始</div>
                    </div>
                </div>
                <div class="declaration-detail">
                    <div class="declaration-detail01">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/declaration-woman.png" alt="">
                        <div class="declaration-text">
                            <div class="declaration-detail-title">もし見積もりにご納得がいかず<br class="declaration-br-sp">キャンセルになっても…</div>
                            <div class="declaration-detail-subtitle">キャンセル料や出張料も<br class="declaration-br-sp">頂きません!<br class="declaration-br-pc">
                                無理な営業も<br class="declaration-br-sp">一切行うことはありません!</div>
                            <div class="declaration-text-pc">万が一上記の通りで無い場合や、弊社スタッフの出張訪問時に、何か気になる点がございましたら、その場ですぐにお渡しした名刺の裏をご覧いただき、お電話ください。担当者が迅速にご対応させて頂きます。もちろん対応に際し、修理対応、施工作業を行っていないにもかかわらず何らかの料金を請求する事は一切ありません。</div>
                        </div>
                    </div>
                    <div class="declaration-text-sp">万が一上記の通りで無い場合や、弊社スタッフの出張訪問時に、何か気になる点がございましたら、その場ですぐにお渡しした名刺の裏をご覧いただき、お電話ください。担当者が迅速にご対応させて頂きます。もちろん対応に際し、修理対応、施工作業を行っていないにもかかわらず何らかの料金を請求する事は一切ありません。</div>
                </div>
            </section>
			
			<?php
					if(!empty($custom['number'][0])):
				?>
				<section class="prefecture-adv">
					<div class="adv-content">
						<h1>クリーンライフは<br>
							<?php the_title() ?>の水道局指定工事店です。
						</h1>
						<table>
							<tr>
								<td>市町村（広域名）</td>
								<td>指定事業者番号</td>
							</tr>
							<tr>
								<td><?php the_title() ?></td>
								<td>第<?php echo $custom['number'][0];?>号</td>
							</tr>
						</table>
					</div>
				</section>
			<?php 
				endif;
			?>
            <section class="voice toilet-voice">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                            トイレトラブルに関する
                        </div>
                        <div>お客様の声</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>VOICE</div>
                            <div class="service-title-arrow"></div>
                        </div>
                    </div>
                    <p class="voice-p">
                        弊社のサービスをご利用された方で、名古屋市内にお住まいのお客様からいただいた感謝の声をご紹介いたします。このようなお客様から頂いた嬉しいお言葉を励みにして、今度もサービス向上に努めて参ります。
                    </p>
                    <div class="voice-box-container">
                        <div class="voice-box-main">
							<?php
								if (!empty(get_field('region-review'))) {
								$region_review = get_field('region-review');
								foreach ($region_review as $review) {
							?>
                            <div class="voice-box">
								<div class="voice-box-title"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php if (!empty($review['client'])) { echo $review['client'];} else { echo '名前なし';}?></font></font></div>
                                    <div class="voice-box-header">
                                        <div class="voice-box-head">
											<?php if (!empty($review['image'])) 
											{ 
												$image = $review['image']; 
											?>
												<img src="<?php echo esc_url($image['url'])?>" alt="">
											<?php
											} 
											else { 
											?>
												<img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="<?php echo esc_attr($image['alt']); ?>">
											<?php
											}
											?>
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                                <?php if (!empty($review['title'])) { echo $review['title'];} else { echo 'タイトルなし';}?>
                                                </font></font><span><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php if (!empty($review['category'])) { echo $review['category'];} else { echo 'カテゴリーなし';}?></font></font></span>
                                            </div>
                                        </div>
                                        <div class="voice-box-reputation">
                                            <div><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">評価:</font></font></div>
											<?php for ( $i = 1; $i <= $review['mark']; $i++ ) { ?>
                                           		<img src="<?php echo get_template_directory_uri()?>/assets/image/star.png">
											<?php } ?>
                                        </div>
                                    </div>
                                    <div class="voice-box-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                      	<?php if (!empty($review['content'])) { echo $review['content'];} else { echo 'コンテンツなし';}?>
                                    	</font></font>
									</div>
                              </div>
							<?php
								}
							}
							else {
								echo '<p>お客様の声はありません！</p>';
							}
							?> 
                        </div>
                    </div>
                </div>
                <div class="slider-voice-content">
                    <div class="swiper-container swiper-container-voice voice-box-container">
                        <div class="swiper-wrapper">
							<?php
								if (!empty(get_field('region-review'))) {
								$region_review = get_field('region-review');
								foreach ($region_review as $review) {
							?>
                            <div class="voice-box swiper-slide">
                                <div class="voice-box-title"><?php if (!empty($review['client'])) { echo $review['client'];} else { echo '名前なし';}?></div>
                                <div class="voice-box-header">
                                    <div class="voice-box-head">
											<?php if (!empty($review['image'])) 
											{ 
												$image = $review['image']; 
											?>
												<img src="<?php echo esc_url($image['url'])?>" alt="">
											<?php
											} 
											else { 
											?>
												<img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="<?php echo esc_attr($image['alt']); ?>">
											<?php
											}
											?>
                                        <div>
                                            <?php if (!empty($review['title'])) { echo $review['title'];} else { echo 'タイトルなし';}?>
                                            <span><?php if (!empty($review['category'])) { echo $review['category'];} else { echo 'カテゴリーなし';}?></span> 
                                        </div>
                                    </div>
                                    <div class="voice-box-reputation">
                                        <div>評価：</div>
											<?php for ( $i = 1; $i <= $review['mark']; $i++ ) { ?>
                                           		<img src="<?php echo get_template_directory_uri()?>/assets/image/star.png">
											<?php } ?>
                                    </div>
                                </div>
                                <div class="voice-box-text">
                                    <?php if (!empty($review['content'])) { echo $review['content'];} else { echo 'コンテンツなし';}?>
                                </div>
                            </div>
						
						<?php
									}
								}
							else {
								echo '<p>お客様の声はありません！</p>';
							}
						?> 
                        </div>
                        <div class="swiper-button-prev swiper-button-prev-voice"></div>
                        <div class="swiper-button-next swiper-button-next-voice"></div>
                    </div>
                </div>
            </section>
            <section class="faq">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo1.png" alt="">
                            トイレトラブルに関する
                        </div>
                        <div>よくあるご質問</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>FAQ</div>
                            <div class="service-title-arrow"></div>
                        </div>
                    </div>
                    <div class="faq-box-container">
                        <div class="faq-box-main">
                            <div class="faq-box faq-show">
                                <div class="faq-q" onclick="faqClick(this)">Q.電話してどのくらいで来てもらえますか？
                                    <div class="faq-q-icon">
                                        <div><div></div></div>
                                    </div>
                                </div>
                                <div class="faq-a">
                                    <div>A</div>
                                    <div>スタッフの状況にもよりますが、早ければ30分程でお伺いすることが可能です。現場から一番近い営業所のスタッフがお伺いさせていただきます。
                                        お急ぎの場合は、お電話のご依頼がメールよりも早く対応可能です。お電話での受付は：0120-423-152（フリーダイヤル）へご連絡ください。</div>
                                </div>
                            </div>
                            <div class="faq-box">
                                <div class="faq-q" onclick="faqClick(this)">Q.お見積もりは無料でしょうか？
                                    <div class="faq-q-icon">
                                        <div><div></div></div>
                                    </div>
                                </div>
                                <div class="faq-a">
                                    <div>A</div>
                                    <div>はい、現場の調査を行いお見積もりをさせていただきますが、お見積もりは無料です。
簡単な修理で解決する場合もございますが、つまりなどの場合はどこの場所で詰まっているかを判断するために現場調査とさせていただいております。</div>
                                </div>
                            </div>
                            <div class="faq-box">
                                <div class="faq-q" onclick="faqClick(this)">Q.修理はどのくらいの時間を要しますか？
                                    <div class="faq-q-icon">
                                        <div><div></div></div>
                                    </div>
                                </div>
                                <div class="faq-a">
                                    <div>A</div>
                                    <div>修理の程度や箇所にもよりますが簡単な修理であれば30分ほどで終了します。
基本的には平均1時間以内で完了することが多いです。</div>
                                </div>
                            </div>
                            <div class="faq-box">
                                <div class="faq-q" onclick="faqClick(this)">Q.伺ってもらえる時間の指定は可能でしょうか？
                                    <div class="faq-q-icon">
                                        <div><div></div></div>
                                    </div>
                                </div>
                                <div class="faq-a">
                                    <div>A</div>
                                    <div>はい。もちろん可能でございます。
他のお客様のご予約状況の都合上お受けできない場合もございますが、お早めにご連絡頂ければ調整致します。</div>
                                </div>
                            </div>
                            <div class="faq-box">
                                <div class="faq-q" onclick="faqClick(this)">Q.修理にはどんな人が来るんでしょうか？
                                    <div class="faq-q-icon">
                                        <div><div></div></div>
                                    </div>
                                </div>
                                <div class="faq-a">
                                    <div>A</div>
                                    <div>弊社のスタッフ全員が専門の技術者でございます。
トイレ・水道修理など、水まわりのトラブルやお悩み等、何でもご相談ください。</div>
                                </div>
                            </div>
                            <div class="faq-box">
                                <div class="faq-q" onclick="faqClick(this)">Q.お支払い方法は？
                                    <div class="faq-q-icon">
                                        <div><div></div></div>
                                    </div>
                                </div>
                                <div class="faq-a">
                                    <div>A</div>
                                    <div>現金や銀行振込、各種クレジットカード、コンビニ後払いにも対応しております。
その他にもpaypay決済など柔軟に対応しておりますので一度ご相談頂ければと思います。</div>
                                </div>
                            </div>
                        </div>
                        <div class="case-link-container">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>faq">もっとみる</a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="service-flow">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                            トイレトラブルの
                        </div>
                        <div>修理の流れ</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>SERVICE FLOW</div>
                            <div class="service-title-arrow"></div>
                        </div>
                    </div>
                    <div class="service-flow-container">
                        <div class="service-flow-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp1.png" alt="">
                            <div class="service-flow-text">
                                <div>お問い合わせ</div>
                                水回りでお困りの際はお電話、メール、LINEよりお気軽にご相談ください。
                            </div>
                        </div>
                        <div class="service-flow-arrow"></div>
                        <div class="service-flow-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp2.png" alt="">
                            <div class="service-flow-text">
                                <div>現地調査</div>
                                故障や水漏れ原因を調査します。現地調査は無料で行っておりますのでご安心ください。
                            </div>
                        </div>
                        <div class="service-flow-arrow"></div>
                        <div class="service-flow-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp3.png" alt="">
                            <div class="service-flow-text">
                                <div>お見積り提示</div>
                                お見積りをご提示して、作業内容とお見積りにご納得いただいてはじめて施工へと移ります。
                            </div>
                        </div>
                        <div class="service-flow-arrow"></div>
                        <div class="service-flow-box">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp4.png" alt="">
                            <div class="service-flow-text">
                                <div>施工・お支払い</div>
                                作業完了後、作業箇所を確認していただき、料金をお支払いただきます。
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="area service-area">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo1.png" alt="">
                            クリーンライフが対応可能な
                        </div>
                        <div><?php the_title()?>エリア</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>AREA</div>
                            <div class="service-title-arrow"></div>
                        </div>
                    </div>
                    <div class="area-box-container">
					
					<?php
						if (!empty(get_field('region-area'))) {
							$region_area = get_field('region-area');
							foreach ($region_area as $area) {
					?>
                        <div class="area-box">
                            <div class="area-q"><?php if (!empty($area['title'])) { echo $area['title'];} else { echo 'タイトルなし';}?>
                            </div>
                            <div class="area-a">
                               <?php if (!empty($area['content'])) { echo $area['content'];} else { echo 'コンテンツなし';}?>
                            </div>
                        </div>
						
						<?php
									}
								}
							else {
								echo '<p>エリアはありません！</p>';
							}
						?> 
                    </div>
                </div>
            </section>
            <section class="area service-area service-info">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                        </div>
                        <div><?php the_title()?>の自治体情報</div>
                        <div></div>
                    </div>
                    <div class="area-box-container">
					
					<?php
						if (!empty(get_field('region-info'))) {
							$region_info = get_field('region-info');
							foreach ($region_info as $info) {
					?>
                        <div class="area-box area-show area-box-custom">
							<?php if (!empty($info['content'])) { echo $info['content'];} else { echo 'コンテンツなし';}?>
                        </div>
						
						<?php
									}
								}
							else {
								echo '<p>エリアはありません！</p>';
							}
						?> 
                        <div class="area-box">
                            <div class="area-q info-ttl"><img src="<?php echo get_template_directory_uri()?>/assets/image/border-left.png" alt="">&nbsp;&nbsp;自治体<span>に依頼する場合</span>
                            </div>
                            <div class="area-a area-box-add">
                                <!-- <div>トラブル発生</div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/arrow-black.png" alt="">
                                <div>トラブルの場所によって<br>
                                    自治体対応かどうか判断or確認
                                </div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/arrow-black.png" alt="">
                                <div class="last-div">水道局指定工事店の中から<br>
                                    自分で調べて探す必要あり
                                    <span class="absolute-span">自治体が対応不可の場合</span>
                                </div> -->
                                        
                                <picture>
                                    <source media="(max-width: 640px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/Group3.png">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/Group 2.png" alt="">
                                </picture>
                            </div>
                            <div class="area-q info-ttl last-ttl"><img src="<?php echo get_template_directory_uri()?>/assets/image/border-left.png" alt="">&nbsp;&nbsp;クリーンライフ<span>に依頼する場合</span>
                            </div>
                            <div class="area-a area-box-add">
                                <!-- <div>トラブル発生</div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/arrow-black.png" alt="">
                                <div>トラブルの場所に限らず依頼可能!<br>
                                    しかも<span>水道局指定工事店</span>なので安心
                                </div> -->
                                <picture>
                                    <source media="(max-width: 640px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/Group4.png">
                                    <img class="area-box-add-img" src="<?php echo get_template_directory_uri()?>/assets/image/Group 1.png" alt="">
                                </picture>
                            </div>
							
								<div class="area-a important-sec">
								
							<?php
								$custom = get_post_custom();

								if(!empty($custom['number'][0])) {
							?>
									<?php the_title();?>の
									
							<?php 
								} 
							?>
									水道局指定工事店である<br>
									クリーンライフなら、緊急性の高い<br class="sp">水道トラブルにおいて、<br class="pc">
									よりスピーディーに対応可能です！
								</div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="howto">
                <div class="service-main-content">
                    <div class="howto-box-container howto-box-container-add">
					
					<?php
						if (!empty(get_field('region-paper'))) {
							$region_paper = get_field('region-paper');
							foreach ($region_paper as $paper) {
					?>
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">トイレに便（トイレットペーパー）が詰まった</div>
                            <div class="howto-box-content">
											<?php if (!empty($paper['image'])) 
											{ 
												$image = $paper['image']; 
											?>
												<img src="<?php echo esc_url($image['url'])?>" alt="">
											<?php
											} 
											else { 
											?>
												<img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/toilet-clean1.png" alt="<?php echo esc_attr($image['alt']); ?>">
											<?php
											}
											?>
                                <div><?php if (!empty($paper['content'])) { echo $paper['content'];} else { echo 'コンテンツなし';}?></div>
                            </div>
                        </div>
						
						<?php
									}
								}
							else {
								echo '<p>コンテンツはありません！</p>';
							}
						?> 
                    </div>
                    <div class="howto-title">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/howto-title.png" alt="">
                        トイレの水漏れ・つまりの原因
                    </div>
                    <div class="howto-description">
                        トイレが詰まる原因の多くは、便やトイレットペーパーといったトイレに流せるものを1回に流しすぎた場合と、トイレには流せない異物を誤って落としてしまったり流してしまった場合がほとんどです。水漏れの場合には水漏れの発生している箇所によって原因が異なります。次では、よくあるトイレのつまりと水漏れの原因をご紹介します。このうちのいずれかに該当していないかを確認してみてください。
                    </div>
                    <div class="howto-box-title toilet-howto-box-title">よくあるトイレつまりの原因</div>
                    <div class="howto-box-container">
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto1.png" alt="">
                            <div class="howto-reason-box-title">トイレが詰まってしまって水が全く流れない</div>
                            <div>トイレが詰まって全く水が流れない場合には、便やトイレットペーパーが1度に流せる量を越えて、大量に流してしまったことが原因として考えられます。便やトイレットペーパーが原因でトイレが詰まった時にはご自身でも対処できる場合があります。詳しくは、便（トイレットペーパー）が詰まった際の対処法をご覧ください。</div>
                        </div>
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto2.png" alt="">
                            <div class="howto-reason-box-title">トイレは詰まっているが少しだけ水は流れている</div>
                            <div>トイレが詰まっているけれど少しずつ水が流れている場合には、トイレに流せないものや異物が詰まっていることが原因として考えられます。特に多いのは子どものおもちゃ、食品、吐しゃ物、犬や猫の糞といった原因が考えられます。これらは無理に詰まりを解消しようとすると悪化する恐れがあります。詳しくは、トイレに流せないもの（異物）が詰まった際の対処法をご覧ください。</div>
                        </div>
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto3.png" alt="">
                            <div class="howto-reason-box-title">トイレは流れているが流れが悪い</div>
                            <div>水に流れないものを流したわけではないのにトイレの流れが悪い、トイレの水は流れているものの本来よりも流れが悪いという場合には、トイレの奥に尿石が溜まっている場合があります。尿石は便器の黄ばみやつまり、ニオイの元となり、溜まってしまうと除去が難しくなります。尿石を溜めないためにも小まめに清掃を行い、溜まってしまっている可能性がある場合には、業者に依頼して清掃してもらいましょう。</div>
                        </div>
                    </div>
                    <div class="howto-box-title toilet-howto-box-title">トイレの水漏れの原因</div>
                    <div class="howto-box-container">
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto4.png" alt="">
                            <div class="howto-reason-box-title">給水管・排水管からの水漏れ</div>
                            <div>トイレの給水管（トイレのタンクに水を送る配管）や排水管（トイレの排水を流す配管）からの水漏れがある場合には、各接続部の緩みや配管・配管内のパッキンの経年劣化の可能性があります。給水管の繋ぎ目からの水漏れの場合にはパッキンの交換のみで解消できる場合もあります。</div>
                        </div>
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto5.png" alt="">
                            <div class="howto-reason-box-title">便器（トイレタンク）からの水漏れ</div>
                            <div>トイレタンクから便器への水漏れがある場合には内部のパーツの劣化の可能性があり、トイレタンクから外部への水漏れがある場合には外部との接続部のパッキンの劣化の可能性があります。いずれもパーツの交換によって解消できますが、パーツには細かい違いがあります。不安な方は無理をせずに業者に依頼することをおすすめします。</div>
                        </div>
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto6.png" alt="">
                            <div class="howto-reason-box-title">ウォシュレットからの水漏れ</div>
                            <div>ウォシュレットからの水漏れがある場合には、内部の水抜き栓・給水フィルター・ノズルの内部の劣化によりものか本体の故障の可能性があります。内部の劣化の場合にはいずれもパーツの交換によって解消できますが、本体の故障の可能性もあります。不安な方は無理をせずに業者に依頼することをおすすめします。
</div>
                        </div>
                    </div>
                    <div class="howto-title howto-title-sec">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/howto-title.png" alt="">
                        トイレが詰まった際のご家庭でできる対処法
                    </div>
                    <div class="howto-description">
                       突然に訪れるトイレのつまりですが、こちらでは急なトイレつまりの際にご家庭でもできる対処法をご紹介します。原因によってはトイレつまりを解消できますが、間違えた対処をすると詰まりを悪化させてしまう可能性もあります。解消できないものは無理にご自身で対処せず、業者に依頼することをおすすめします。
                    </div>
                    <div class="howto-box-container">
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">トイレに便（トイレットペーパー）が詰まった</div>
                            <div class="howto-box-content">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto7.png" alt="">
                                <div>便やトイレットペーパーといった流せるものがトイレに詰まった場合には、一定時間放置をしてから再度流してみましょう。便やトイレットペーパーといった水溶性のものは時間を置くとふやけて流れやすくなります。それでも流れない場合には、便器内の水の量を減らしたうえで、60～70度ほどのぬるま湯を高い位置から勢いを付けて流したり、ラバーカップ（すっぽん）の使用もおすすめです。これらで流れない場合には、状況説明したうえで業者に依頼しましょう。</div>
                            </div>
                        </div>
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">トイレに流せないもの（異物）が詰まった</div>
                            <div class="howto-box-content">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto8.png" alt="">
                                <div>トイレに流せないものや異物が詰まっていると断定できる場合、詰まっているものが見える位置や手の届く位置にあるようであればゴム手袋等をしたうえで取りましょう。取れないほど奥に詰まっている場合には、水を流したりはせずにそれ以上奥に流さないようにしましょう。異物が奥にいってしまうと取るのが大変になり、業者に依頼した際にも費用が高額になる可能性があります。ご自身で取れない場合には、状況も踏まえて業者に依頼することをおすすめします。</div>
                            </div>
                        </div>
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">トイレから水漏れしている</div>
                            <div class="howto-box-content">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/toilet/howto9.png" alt="">
                                <div>トイレからの水漏れが発生している場合にはパーツの劣化や漏れている箇所の故障が原因の場合がほとんどです。まずはトイレに繋がっている止水栓をマイナスドライバーやコインを使用して右に回して締めましょう。それから落ち着いて、状況を説明しながら業者に依頼することをおすすめします。</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="service-bottom">
                それでも解決しない場合はお気軽に<br>
                クリーンライフにご相談ください！
            </section>
			<?php 
                    endwhile;
					wp_reset_postdata();
                ?>
                  <?php
                    else :
                    echo '<p>投稿はありません！</p>';
                    
                    endif;
                ?>
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
                                        <div>通話料無料</div>
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
			<section class="covid">
                <div class="covid-content">
                    <div class="covid-edge01"></div>
                    <div class="covid-edge02"></div>
                    <div class="covid-edge03"></div>
                    <div class="covid-edge04"></div>
                    <div class="covid-title-container">
                        <div class="covid-title">新型コロナウイルス対策実施店</div>
                        <div class="covid-subtitle">お客様、従業員の安全を守るため、以下を徹底します。</div>
                    </div>
                    <div class="covid-box-container">
                        <div class="covid-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/covid1.png" alt="">
                            <div class="covid-box-content">
                                <div class="covid-box-title">マスク、アルコール消毒</div>
                                スタッフのマスク着用とアルコール消毒を徹底しております。
                            </div>
                        </div>
                        <div class="covid-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/covid2.png" alt="">
                            <div class="covid-box-content">
                                <div class="covid-box-title">出勤前健康チェック</div>
                                毎朝検温を行い、スタッフに発熱がないことを確認しております。
                            </div>
                        </div>
                        <div class="covid-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/covid3.png" alt="">
                            <div class="covid-box-content">
                                <div class="covid-box-title">作業時間短縮</div>
                                感染のリスクを減らす為に、作業時間の短縮に努めます。
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php get_sidebar();?>
    </div>

<?php get_footer();?>