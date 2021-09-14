<?php
/* 
Template Name: お客様の声
*/
get_header();?>
<style type="text/css">
    .lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 14px;
    height: 14px;
    margin: 2px 8px;
    border: 2px solid #183494;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #fff transparent transparent transparent;
}
 .lds-ring {
    display: none;
    position: relative;
    width: 14px;
    height: 14px;
}
.lds-ring.active{display: inline-block;}
.lds-ring span:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring span:nth-child(3) {
  animation-delay: -0.15s;
}


@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
.lds-ring div:nth-child(1) {
    animation-delay: -0.45s;
}
</style>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <section class="page-title">
        <div class="content">
            <div class="current-page">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>
                > お客様の声
            </div>
            <div class="title">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                <h1>お客様の声</h1>
                <div class="subtitle"><div>VOICE</div></div>
            </div>
        </div>
    </section>
    <div class="l-wrap">
        <main class="l-main area">
            <section class="voice-main-container">
                <div class="voice-btn">
                    <div class="service-main-content">
                        <div class="voice-btn-title">修理箇所別にお客様の声をご覧いただけます。</div>
                        
                    </div>
                </div>
                <div class="service-main-content voice-btn-container">
                    <a href="#toilet">トイレ</a>
                    <a href="#bath">お風呂</a>
                    <a href="#kitchen">キッチン</a>
                    <a href="#waterheater">給湯器</a>
                    <a href="#washroom">洗面所</a>
                    <a href="#drainpipe">排水管</a>
                    <a href="#waterpipe">水道管</a>
                </div>
                <div class="voice-main toilet-voice" id="toilet">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-toilet">
                            トイレの水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(0);">口コミを投稿する</a>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                                 <?php echo get_review_top('トイレの水漏れ・つまり'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(113); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                                <?php echo get_review_slider('トイレの水漏れ・つまり'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                             <a href="<?php echo get_permalink(113); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
                <div class="voice-main bath-voice" id="bath">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-bath">
                            お風呂の水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(1)">口コミを投稿する</a>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                               <?php echo get_review_top('お風呂の水漏れ・つまり'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(118); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                                <?php echo get_review_slider('お風呂の水漏れ・つまり'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                           <a href="<?php echo get_permalink(118); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
                <div class="voice-main kitchen-voice" id="kitchen">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-kitchen">
                            キッチンの水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(2);">口コミを投稿する</a>
                        </div>
    
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                                <?php echo get_review_top('キッチンの水漏れ・つまり'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(120); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                                <?php echo get_review_slider('キッチンの水漏れ・つまり'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                            <a href="<?php echo get_permalink(120); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
                <div class="voice-main waterheater-voice" id="waterheater">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-waterheater">
                            給湯器の水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(3);">口コミを投稿する</a>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                                <?php echo get_review_top('給湯器の修理・交換'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(122); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                                 <?php echo get_review_slider('給湯器の修理・交換'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                            <a href="<?php echo get_permalink(122); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
                <div class="voice-main washroom-voice" id="washroom">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-washroom">
                            洗面所の水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(4);">口コミを投稿する</a>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                                 <?php echo get_review_top('洗面所の水漏れ・つまり'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(124); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                                 <?php echo get_review_slider('洗面所の水漏れ・つまり'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                           <a href="<?php echo get_permalink(124); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
                <div class="voice-main drainpipe-voice" id="drainpipe">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-drainpipe">
                            排水管の水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(5);">口コミを投稿する</a>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                                 <?php echo get_review_top('排水管の水漏れ・つまり'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(126); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                                 <?php echo get_review_slider('排水管の水漏れ・つまり'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                             <a href="<?php echo get_permalink(126); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
                <div class="voice-main waterpipe-voice" id="waterpipe">
                    <div class="service-main-content">
                        <div class="voice-main-title voice-title-waterpipe">
                            水道管の水漏れ・つまり
                            <a href="#reviewContent" onclick="goReview(6);">口コミを投稿する</a>
                        </div>
                        <div class="voice-box-container">
                            <div class="voice-box-main">
                               <?php echo get_review_top('水道管の水漏れ・つまり'); ?>
                            </div>
                            <div class="case-link-container">
                                <a href="<?php echo get_permalink(128); ?>">もっとみる</a>
                            </div>
                        </div>
                    </div>
                    <div class="slider-voice-content">
                        <div class="swiper-container swiper-container-voice voice-box-container">
                            <div class="swiper-wrapper">
                               <?php echo get_review_slider('水道管の水漏れ・つまり'); ?>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev-voice"></div>
                            <div class="swiper-button-next swiper-button-next-voice"></div>
                        </div>
                        <div class="case-link-container">
                            <a href="<?php echo get_permalink(128); ?>">もっとみる</a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="review" id="reviewContent">
                <div class="service-main-content">
                    <div id="review">
                        <div class="review-title">口コミ投稿</div>
                        <form class="review-form" id="review-form">
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>ニックネーム</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <input type="text" name="reviewName" class="required" placeholder="ニックネームをご記入ください" required>
                                    <div class="error"></div>
                                </div>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>メールアドレス</div>
                                </div>
                                <div class="review-input-main">
                                <div>
                                <input type="email" name="reviewEmail" class="required_email" placeholder="sample.sameple.com" required>
                                <div class="error"></div>
                                </div>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>依頼カテゴリー</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <select name="reviewSpot" id="reviewSpot" required class="required_select">
                                        <option value="トイレの水漏れ・つまり">トイレの水漏れ・つまり</option>
                                        <option value="お風呂の水漏れ・つまり">お風呂の水漏れ・つまり</option>
                                        <option value="キッチンの水漏れ・つまり">キッチンの水漏れ・つまり</option>
                                        <option value="洗面所の水漏れ・つまり">洗面所の水漏れ・つまり</option>
                                        <option value="給湯器の修理・交換">給湯器の修理・交換</option>
                                        <option value="排水管の水漏れ・つまり">排水管の水漏れ・つまり</option>
                                        <option value="水道管の水漏れ・つまり">水道管の水漏れ・つまり</option>
                                    </select>
                                     <div class="error"></div>
                                </div>

                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>依頼内容</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <input type="text" name="request" class="required" placeholder="例）ウォシュレットの故障" required>
                                     <div class="error"></div>
                                </div>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>口コミタイトル</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <input type="text" name="reviewTitle" class="required" placeholder="例）本当に助かりました！" required>
                                     <div class="error"></div>
                                </div>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>評価（5段階）</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <input type="number" name="remark" min="1" max="5" class="required" required value="1">
                                    <div class="input-star input-star-selected" onclick="inputStar(this);"></div>
                                    <div class="input-star" onclick="inputStar(this);"></div>
                                    <div class="input-star" onclick="inputStar(this);"></div>
                                    <div class="input-star" onclick="inputStar(this);"></div>
                                    <div class="input-star" onclick="inputStar(this);"></div>
                                    <div class="clr"></div>
                                     <div class="error"></div>
                                </div>
                                </div>
                            </div>
                            <div class="review-input-box">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>サムネイル画像</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <input type="number" name="avatar" min="1" max="4" required class="required">
                                    <div class="input-avatar" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="">
                                    </div>
                                    <div class="input-avatar" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar2.png" alt="">
                                    </div>
                                    <div class="input-avatar" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar3.png" alt="">
                                    </div>
                                    <div class="input-avatar" onclick="inputAvatar(this);">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar4.png" alt="">
                                    </div>
                                     <div class="error"></div>
                                </div>
                                </div>
                            </div>
                            <div class="review-input-box review-input-box-spec">
                                <div class="review-input-title">
                                    <div class="hissu">必須</div>
                                    <div>口コミ内容</div>
                                </div>
                                <div class="review-input-main">
                                    <div>
                                    <textarea name="reviewText" id="" class="required" cols="30" rows="10" name="review-text" required></textarea>
                                     <div class="error"></div>
                                </div>
                                </div>
                            </div>
                           <div class="form-check-box review-input-box"><div class="review-input-title">
                                   
                                </div>
                                <div class="review-input-main" style="text-align: left;">
                                <input type="checkbox" name="reviewAgree" class="required_terms">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>privacy/" target="_blank">個人情報の取り扱い</a>
                                <span>に同意する</span>
                                 <div class="error"></div>
                            </div>
                            </div>
                            <div class="form-check-box">
                                <button class="submit" id="reviewSubmit" value="投稿する">投稿する<div class="lds-ring"><div></div><div></div><div></div></div></button>
                            </div>
                        </form>
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
    <div class="thanksModal" id="thanksModal">
        <div class="modal-content">
            <div class="modal-title">口コミ投稿<br>
                ありがとうございました。</div>
            <div>あなたの有益な口コミは、他の利用者の役に立ち、<br>
                我々クリーンライフスタッフに気づきや学びを与えるとともに、<br>
                励みにもなります。ご協力頂き、誠にありがとうございました。</div>
            <div class="modal-notice">※口コミには審査があり、反映されるまで数日かかる可能性がございます。</div>
            <div class="modal-close-btn" onclick="modalClose();">&times;</div>
        </div>
    </div>
<script>
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
         data : {action: "review_submit", formdata : $("#review-form").serialize()},
         success: function(response) {
            if(response.type == "success") {
               Swal.fire({
                  icon: 'success',
                  text: 'レビューは正常に送信されました。',
                });
               window.setTimeout(function(){location.reload()},2000)
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

<?php get_footer();?>
