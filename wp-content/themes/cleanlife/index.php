<?php get_header();?>
    <section class="banner-new">
        <picture>
            <source srcset='<?php echo get_template_directory_uri()?>/assets/image/top/bg-banner-sp.png' media='(max-width: 640px)'>
            <img src='<?php echo get_template_directory_uri()?>/assets/image/top/bg-banner.png' alt=''>
        </picture>
    </section>
    <section class="banner-amount-new">
        <div class="content">
            <h1 class="banner-amount-ttl">
                <picture>
                    <source srcset='<?php echo get_template_directory_uri()?>/assets/image/top/banner-amount-ttl-sp.png' media='(max-width: 640px)'>
                    <img src='<?php echo get_template_directory_uri()?>/assets/image/top/banner-amount-ttl.png' alt=''>
                </picture>
            </h1>
            <div class="banner-amount-show">
                <div class="banner-condition">
                    <button id="banner-condition-spot" onclick="conditionSpot(this)">トラブルの箇所&#x25BE;</button>
                    <div class="banner-condition-dash"></div>
                    <button id="banner-condition-symptom" onclick="conditionSymptom(this)" disabled>トラブルの症状&#x25BE;</button>
                    <div class="banner-condition-dash"></div>
                    <button id="banner-condition-type" onclick="conditionType(this)">物件の種類&#x25BE;</button>
                    <div class="banner-condition-dash"></div>
                    <button id="banner-condition-age" onclick="conditionAge(this)">築年数&#x25BE;</button>
                    <div id="banner-condition-select01" class="banner-condition-select">
                        <div><input type="radio" id="spot1" name="spot" onchange="spotCheck()"><label for="spot1">トイレ</label></div>
                        <div><input type="radio" id="spot2" name="spot" onchange="spotCheck()"><label for="spot2">キッチン</label></div>
                        <div><input type="radio" id="spot3" name="spot" onchange="spotCheck()"><label for="spot3">お風呂</label></div>
                        <div><input type="radio" id="spot4" name="spot" onchange="spotCheck()"><label for="spot4">洗面所</label></div>
                        <div><input type="radio" id="spot5" name="spot" onchange="spotCheck()"><label for="spot5">給湯器</label></div>
                        <div><input type="radio" id="spot6" name="spot" onchange="spotCheck()"><label for="spot6">排水管</label></div>
                        <div><input type="radio" id="spot7" name="spot" onchange="spotCheck()"><label for="spot7">水道管</label></div>
                    </div>
                    <div id="banner-condition-select02" class="banner-condition-select">
                        <div><input type="radio" id="symptom1" name="symptom" onchange="symptomCheck()"><label for="symptom1">詰まっている</label></div>
                        <div><input type="radio" id="symptom2" name="symptom" onchange="symptomCheck()"><label for="symptom2">水が漏れている</label></div>
                        <div><input type="radio" id="symptom3" name="symptom" onchange="symptomCheck()"><label for="symptom3">異臭がする</label></div>
                        <div><input type="radio" id="symptom4" name="symptom" onchange="symptomCheck()"><label for="symptom4">異音がする</label></div>
                        <div><input type="radio" id="symptom5" name="symptom" onchange="symptomCheck()"><label for="symptom5">水が止まらない</label></div>
                        <div><input type="radio" id="symptom6" name="symptom" onchange="symptomCheck()"><label for="symptom6">水が逆流した</label></div>
                        <div><input type="radio" id="symptom7" name="symptom" onchange="symptomCheck()"><label for="symptom7">異物を流した</label></div>
                        <div><input type="radio" id="symptom8" name="symptom" onchange="symptomCheck()"><label for="symptom8">落としたものの回収</label></div>
                        <div><input type="radio" id="symptom9" name="symptom" onchange="symptomCheck()"><label for="symptom9">水が溢れそう</label></div>
                        <div><input type="radio" id="symptom10" name="symptom" onchange="symptomCheck()"><label for="symptom10">水位がおかしい</label></div>
                        <div><input type="radio" id="symptom11" name="symptom" onchange="symptomCheck()"><label for="symptom11">便器・便座等の破損</label></div>
                        <div><input type="radio" id="symptom12" name="symptom" onchange="symptomCheck()"><label for="symptom12">ウォシュレットの故障</label></div>
                        <div><input type="radio" id="symptom13" name="symptom" onchange="symptomCheck()"><label for="symptom13">蛇口の異常</label></div>
                        <div><input type="radio" id="symptom14" name="symptom" onchange="symptomCheck()"><label for="symptom14">わからない・それ以外</label></div>
                    </div>
                    <div id="banner-condition-select03" class="banner-condition-select">
                        <div><input type="radio" id="type1" name="type" onchange="typeCheck()"><label for="type1">一戸建て</label></div>
                        <div><input type="radio" id="type2" name="type" onchange="typeCheck()"><label for="type2">マンション・アパート</label></div>
                        <div><input type="radio" id="type3" name="type" onchange="typeCheck()"><label for="type3">事務所・オフィス</label></div>
                        <div><input type="radio" id="type4" name="type" onchange="typeCheck()"><label for="type4">店舗・レストラン</label></div>
                        <div><input type="radio" id="type5" name="type" onchange="typeCheck()"><label for="type5">ビル・商業施設</label></div>
                        <div><input type="radio" id="type6" name="type" onchange="typeCheck()"><label for="type6">宿泊施設</label></div>
                    </div>
                    <div id="banner-condition-select04" class="banner-condition-select">
                        <div><input type="radio" id="age1" name="age" onchange="ageCheck()"><label for="age1">1~5 年</label></div>
                        <div><input type="radio" id="age2" name="age" onchange="ageCheck()"><label for="age2">6~10 年</label></div>
                        <div><input type="radio" id="age3" name="age" onchange="ageCheck()"><label for="age3">11~15 年</label></div>
                        <div><input type="radio" id="age4" name="age" onchange="ageCheck()"><label for="age4">16~20 年</label></div>
                        <div><input type="radio" id="age5" name="age" onchange="ageCheck()"><label for="age5">21~30 年</label></div>
                        <div><input type="radio" id="age6" name="age" onchange="ageCheck()"><label for="age6">30 年以上</label></div>
                        <div><input type="radio" id="age7" name="age" onchange="ageCheck()"><label for="age7">わからない</label></div>
                    </div>
                </div>
                <button id="amount-show" onclick="resultShow()" disabled>見積り結果を見る</button>
                <div id="banner-amount-result">
                    <div class="amount-money">
                        <div>《シミュレーション結果》</div>
                        <div class="amount-money-text">お客様の症状ですと<br class="amount-money-br1"><span id="money-result01">8,000~15,000</span>円<div class="spec-char">※</div><br class="amount-money-br2">だと思われます。</div>
                        <div class="guide-only">※実祭に現場を見てみないと正確な見積りは出せないので<br>あくまでも目安として参考にしてください。</div>
                    </div>
                    <div class="result-now">"今なら"</div>
                    <div class="amount-discount">その金額から<span>3000円割引<div class="spec-char">※</div></span>できます。</div>
                    <div class="guide-only">※お問い合わせの際に、「ホームページを見た」とお伝えください。</div>
                    <div class="result-form-container">
                        <div class="result-form-title">ご相談・お問い合わせ</div>
                        <div class="result-form-subtitle">
                            お見積・ご相談・訪問調査は、すべて無料です。<br>
                            どんなに小さな事でも構いません。お気軽にお問い合わせください。
                        </div>
                        <div class="contact-form">
                            <?php echo do_shortcode( '[contact-form-7 id="106" title="Untitled"]' ); ?>
                        </div>
                    </div>
                    <div class="result-close">
                        <div onclick="resultClose()">&times;閉じる</div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
	
	<!-- <section>
	  <div class="pay_top_pc"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/pay_pc.jpg" alt=""></div>
	  <div class="pay_top_sp"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/pay_sp.jpg" alt=""></div>
	</section> -->
	
	

    <section class="coupon">
        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon.jpg" alt="" class="coupon-back">
        <div class="content">
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
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/footer-tel.png" alt="">
                                    0120-423-152
                                </a>
                            </div>
                        </div>
                        <div class="coupon-btn-group-new">
                            <a href="https://lin.ee/RqJ6Mk3"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-line.png" alt=""></a>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-mail.png" alt=""></a>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-woman-new.png" class="coupon-woman-new" alt="">
                        </div>
                    </div>
                </div>
                <div class="coupon-pay-container">
                    <picture>
                        <source srcset='<?php echo get_template_directory_uri()?>/assets/image/top/coupon-payment-new-sp.png' media='(max-width: 640px)'>
                        <img src='<?php echo get_template_directory_uri()?>/assets/image/top/coupon-payment-new.png' alt=''>
                    </picture>
                </div>
            </div>
        </div>
    </section>
    <section class="coupon-sp">
        <div class="coupon-sp-content">
            <h1>\ 24時間・365日対応・出張お見積無料 / </h1>
            <a href="tel:0120-423-152" class="coupon-tel-btn"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-tel-btn.png" alt=""></a>
            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-web-sp.png" class="coupon-web-sp" alt="">
            <div class="coupon-btn-group-sp">
                <div class="coupon-btn-group-sp-content">
                    <p>\最短<span>30秒</span>でご返信/</p>
                    <a href="https://lin.ee/RqJ6Mk3"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-line-sp.png" alt=""></a>
                    <p>\専門スタッフが<span>即対応</span>！/</p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-mail-sp.png" alt=""></a>
                </div>
                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-woman-new-sp.png" class="coupon-woman-new-sp" alt="">
            </div>
            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-payment-new-sp.png" class="coupon-payment-new-sp" alt="">
        </div>
    </section>
    <section class="compare">
        <div class="content">
            <picture>
                <source srcset='<?php echo get_template_directory_uri()?>/assets/image/top/compare-sp.png' media='(max-width: 640px)'>
                <img src='<?php echo get_template_directory_uri()?>/assets/image/top/compare.png' alt=''>
            </picture>
            <p>
                昨今、こういった<span>1,000円以下の安すぎる金額</span>を提示する<br class="compare-br-pc">
                水道事業者が増えております。<span>基本料金だけ見ると低価格</span>に見えますが、<br class="compare-br-pc">
                作業料金や出張見積費などの追加料金で、<span>最終的に割高</span>になってしまったり、<br class="compare-br-pc">
                <span>非常識な高額請求</span>をする業者もいますのでご注意ください。<br class="compare-br-pc">
            </p>
        </div>
    </section>
    <section class="munic">
        <div class="content _pc">
            <div class="munic-main">
                <div class="munic-main-header">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/medal.png" alt="">
                    <div>
                        <div>
                        クリーンライフは、<br class="_sp">各市町村から指定を受けた<br>
                        <span>指定給水装置工事事業者<br class="_sp">(水道局指定工事店)</span>です。
                        </div>
                        <div class="_pc">
                        水道局指定工事店は、必要な機材・資材を取り揃えていて、適切な工事と正しい事務手続きを行い、誠実な対応ができると保証されている事業者になります。あらゆる水まわりのトラブルに対応可能ですので、安心してご依頼ください。
                        </div>
                    </div>
                </div>
                <div class="munic-container" id="munic_con1">
                    <div class="munic-con-title" >
                        <span>関東</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
                        <div class="munic-con-sub-title">
                            <span>東京都</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>千代田区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>新宿区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>墨田区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>目黒区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>渋谷区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>豊島区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>板橋区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>葛飾区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>立川市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>府中市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>町田市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>日野市日野市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>国立市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>東大和市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>武蔵村山市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>あきる野市</td>
                                        <td>第9712号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>中央区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>文京区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>江東区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>大田区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>中野区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>北区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>練馬区</td>
                                        <td>第9712号</td>
                                    </tr>

                                    <tr>
                                        <td>江戸川区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>三鷹市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>昭島市</td>
                                        <td>第488号</td>
                                    </tr>
                                    <tr>
                                        <td>小金井市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>東村山市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>福生市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>清瀬市</td>
                                        <td>第488号</td>
                                    </tr>
                                    <tr>
                                        <td>多摩市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>西東京市</td>
                                        <td>第9712号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>港区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>台東区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>品川区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>世田谷区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>杉並区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>荒川区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>足立区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>八王子市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>青梅市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>調布市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>小平市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>国分寺市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>狛江市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>東久留米市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>稲城市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>西多摩郡</td>
                                        <td>第9712号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>神奈川県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>横浜市</td>
                                        <td>第3008号</td>
                                    </tr>
                                    <tr>
                                        <td>鎌倉市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>茅ヶ崎市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>厚木市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>海老名市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>高座郡</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>愛甲郡</td>
                                        <td>第2887号</td>
                                    </tr>
									<tr>
                                        <td>座間市</td>
                                        <td>第614号</td>
                                    </tr>
									<tr>
                                        <td>足柄上郡山北町</td>
                                        <td>第164号</td>
                                    </tr>
									<tr>
                                        <td>真鶴町</td>
                                        <td>第111号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>川崎市</td>
                                        <td>第1613号</td>
                                    </tr>
                                    <tr>
                                        <td>藤沢市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>逗子市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>大和市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>綾瀬市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>中郡</td>
                                        <td>第2887号</td>
                                    </tr>
									<tr>
                                        <td>横須賀市</td>
                                        <td>第542号</td>
                                    </tr>
									<tr>
                                        <td>南足柄市</td>
                                        <td>南第252号</td>
                                    </tr>
									<tr>
                                        <td>愛甲郡愛川町</td>
                                        <td>第281号</td>
                                    </tr>
									<tr>
                                        <td>湯河原町</td>
                                        <td>第174号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>平塚市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>小田原市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>相模原市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>伊勢原市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>三浦郡</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>足柄下郡</td>
                                        <td>第2887号</td>
                                    </tr>
									<tr>
                                        <td>三浦市</td>
                                        <td>第228号</td>
                                    </tr>
									<tr>
                                        <td>足柄上郡大井町</td>
                                        <td>第191号</td>
                                    </tr>
									<tr>
                                        <td>愛甲郡清川村</td>
                                        <td>第2021002号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>埼玉県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>さいたま市</td>
                                        <td>第1186号</td>
                                    </tr>
                                    <tr>
                                        <td>所沢市</td>
                                        <td>所沢市</td>
                                    </tr>
                                    <tr>
                                        <td>草加市</td>
                                        <td>第376号</td>
                                    </tr>
                                    <tr>
                                        <td>新座市</td>
                                        <td>第415号</td>
                                    </tr>
                                    <tr>
                                        <td>ふじみ野市</td>
                                        <td>第302号</td>
                                    </tr>
                                    <tr>
                                        <td>入間郡毛呂山町</td>
                                        <td>第148号</td>
                                    </tr>
									<tr>
                                        <td>深谷市</td>
                                        <td>第10457号</td>
                                    </tr>
									<tr>
                                        <td>三郷市</td>
                                        <td>第340号</td>
                                    </tr>
									<tr>
                                        <td>入間郡三芳町</td>
                                        <td>第218号</td>
                                    </tr>
									<tr>
                                        <td>児玉郡美里町</td>
                                        <td>第169号</td>
                                    </tr>
									<tr>
                                        <td>大里郡寄居町</td>
                                        <td>第245号</td>
                                    </tr>
									<tr>
                                        <td>熊谷市</td>
                                        <td>第505号</td>
                                    </tr>
									<tr>
                                        <td>秩父市</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>本庄市</td>
                                        <td>第298号</td>
                                    </tr>
									<tr>
                                        <td>鴻巣市</td>
                                        <td>第383号</td>
                                    </tr>
									<tr>
                                        <td>戸田市</td>
                                        <td>第369号</td>
                                    </tr>
									<tr>
                                        <td>北本市</td>
                                        <td>第428号</td>
                                    </tr>
									<tr>
                                        <td>足立郡伊奈町</td>
                                        <td>第255号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡横瀬町</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡小鹿野町</td>
                                        <td>第216号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>川越市</td>
                                        <td>第598号</td>
                                    </tr>
                                    <tr>
                                        <td>東松山市</td>
                                        <td>第331号</td>
                                    </tr>
                                    <tr>
                                        <td>松伏市</td>
                                        <td>第428号</td>
                                    </tr>
                                    <tr>
                                        <td>吉川市</td>
                                        <td>第226号</td>
                                    </tr>
                                    <tr>
                                        <td>坂戸市</td>
                                        <td>第422号</td>
                                    </tr>
                                    <tr>
                                        <td>比企郡鳩山町</td>
                                        <td>第R02125号</td>
                                    </tr>
                                    <tr>
                                        <td>比企郡川島町</td>
                                        <td>第156号</td>
                                    </tr>
									<tr>
                                        <td>和光市</td>
                                        <td>第268号</td>
                                    </tr>
									<tr>
                                        <td>蓮田市</td>
                                        <td>第265号</td>
                                    </tr>
									<tr>
                                        <td>入間郡越生町</td>
                                        <td>第104号</td>
                                    </tr>
									<tr>
                                        <td>児玉郡神川町</td>
                                        <td>第161号</td>
                                    </tr>
									<tr>
                                        <td>北葛飾郡杉戸町</td>
                                        <td>第10179号</td>
                                    </tr>
									<tr>
                                        <td>飯能市</td>
                                        <td>第249号</td>
                                    </tr>
									<tr>
                                        <td>狭山市</td>
                                        <td>第335号</td>
                                    </tr>
									<tr>
                                        <td>上尾市</td>
                                        <td>第534号</td>
                                    </tr>
									<tr>
                                        <td>志木市</td>
                                        <td>第265号</td>
                                    </tr>
									<tr>
                                        <td>幸手市</td>
                                        <td>第R03-211号</td>
                                    </tr>
									<tr>
                                        <td>入間市</td>
                                        <td>第329号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡皆野町</td>
                                        <td>第216号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>川口市</td>
                                        <td>第843号</td>
                                    </tr>
                                    <tr>
                                        <td>狭山市</td>
                                        <td>第335号</td>
                                    </tr>
                                    <tr>
                                        <td>越谷市</td>
                                        <td>第428号</td>
                                    </tr>
                                    <tr>
                                        <td>八潮市</td>
                                        <td>第290号</td>
                                    </tr>
                                    <tr>
                                        <td>入間市</td>
                                        <td>第329号</td>
                                    </tr>
                                    <tr>
                                        <td>比企郡ときがわ町</td>
                                        <td>第109号</td>
                                    </tr>
									<tr>
                                        <td>春日部市</td>
                                        <td>第443号</td>
                                    </tr>
									<tr>
                                        <td>久喜市</td>
                                        <td>第347号</td>
                                    </tr>
									<tr>
                                        <td>日高市</td>
                                        <td>第264号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡東秩父村</td>
                                        <td>第56号</td>
                                    </tr>
									<tr>
                                        <td>児玉郡上里町</td>
                                        <td>第204号</td>
                                    </tr>
									<tr>
                                        <td>行田市</td>
                                        <td>第337号</td>
                                    </tr>
									<tr>
                                        <td>加須市</td>
                                        <td>第344号</td>
                                    </tr>
									<tr>
                                        <td>羽生市</td>
                                        <td>第275号</td>
                                    </tr>
									<tr>
                                        <td>蕨市</td>
                                        <td>第277号</td>
                                    </tr>
									<tr>
                                        <td>桶川市</td>
                                        <td>第428号</td>
                                    </tr>
									<tr>
                                        <td>鶴ヶ島市</td>
                                        <td>第422号</td>
                                    </tr>
									<tr>
                                        <td>比企郡鳩山町</td>
                                        <td>第R02125号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡長瀞町</td>
                                        <td>第216号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>千葉県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>千葉県</td>
                                        <td>第448号</td>
                                    </tr>
									<tr>
                                        <td>松戸市</td>
                                        <td>第415号</td>
                                    </tr>
									<tr>
                                        <td>習志野市</td>
                                        <td>第386号</td>
                                    </tr>
									<tr>
                                        <td>市原市</td>
                                        <td>第607号</td>
                                    </tr>
									<tr>
                                        <td>君津市</td>
                                        <td>第243号</td>
                                    </tr>
									<tr>
                                        <td>袖ヶ浦市</td>
                                        <td>第243号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>千葉県</td>
                                        <td>第2195号</td>
                                    </tr>
									<tr>
                                        <td>野田市</td>
                                        <td>第326号</td>
                                    </tr>
									<tr>
                                        <td>印西市</td>
                                        <td>第118号</td>
                                    </tr>
									<tr>
                                        <td>我孫子市</td>
                                        <td>第362号</td>
                                    </tr>
									<tr>
                                        <td>富津市</td>
                                        <td>第243号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                            <table class="munic-table">
							        <tr>
                                        <td>木更津市</td>
                                        <td>第243号</td>
                                    </tr>
									<tr>
                                        <td>成田市</td>
                                        <td>第351号</td>
                                    </tr>
									<tr>
                                        <td>印旛郡栄町</td>
                                        <td>第118号</td>
                                    </tr>
									<tr>
                                        <td>鎌ヶ谷市</td>
                                        <td>第2195号</td>
                                    </tr>
									<tr>
                                        <td>浦安市</td>
                                        <td>第2195号</td>
                                    </tr>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="munic-container" id="munic_con2">
                    <div class="munic-con-title" >
                    <span>東海</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
					<div class="munic-con-sub-title">
                            <span>三重県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>津市</td>
                                        <td>第753号</td>
                                    </tr>
                                    <tr>
                                        <td>松阪市</td>
                                        <td>第544号</td>
                                    </tr>
									<tr>
                                        <td>亀山市</td>
                                        <td>第2014号</td>
                                    </tr>
									<tr>
                                        <td>伊賀市</td>
                                        <td>第385号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>四日市市</td>
                                        <td>第543号</td>
                                    </tr>
                                    <tr>
                                        <td>鈴鹿市</td>
                                        <td>第571号</td>
                                    </tr>
									<tr>
                                        <td>鳥羽市</td>
                                        <td>第179号</td>
                                    </tr>
									<tr>
                                        <td>桑名郡木曽岬町</td>
                                        <td>第61号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>伊勢市</td>
                                        <td>第396号</td>
                                    </tr>
                                    <tr>
                                        <td>尾鷲市</td>
                                        <td>第2003号</td>
                                    </tr>
									<tr>
                                        <td>熊野市</td>
                                        <td>第62号</td>
                                    </tr>
									<tr>
                                        <td>員弁郡東員町</td>
                                        <td>第158号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
						<div class="munic-con-sub-title">
                            <span>岐阜県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>岐阜市</td>
                                        <td>第97号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>大垣市</td>
                                        <td>第335号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>多治見市</td>
                                        <td>第211号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
						<div class="munic-con-sub-title">
                            <span>静岡県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>熱海市</td>
                                        <td>第167号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>愛知県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>名古屋市</td>
                                        <td>第1437号</td>
                                    </tr>
                                    <tr>
                                        <td>一宮市</td>
                                        <td>第346号</td>
                                    </tr>
                                    <tr>
                                        <td>豊田市</td>
                                        <td>第394号</td>
                                    </tr>
                                    <tr>
                                        <td>尾張旭市</td>
                                        <td>第176号</td>
                                    </tr>
                                    <tr>
                                        <td>長久手市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>愛西市</td>
                                        <td>第112号</td>
                                    </tr>
                                    <tr>
                                        <td>江南市</td>
                                        <td>第170号</td>
                                    </tr>
                                    <tr>
                                        <td>飛島村</td>
                                        <td>第168号</td>
                                    </tr>
                                    <tr>
                                        <td>常滑市</td>
                                        <td>第209号</td>
                                    </tr>
									<tr>
                                        <td>豊川市</td>
                                        <td>第263号</td>
                                    </tr>
									<tr>
                                        <td>小牧市</td>
                                        <td>第210号</td>
                                    </tr>
									<tr>
                                        <td>長久手市</td>
                                        <td>第326号</td>
                                    </tr>
									<tr>
                                        <td>大府市</td>
                                        <td>第193号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>豊橋市</td>
                                        <td>第290号</td>
                                    </tr>
                                    <tr>
                                        <td>春日井市</td>
                                        <td>第267号</td>
                                    </tr>
                                    <tr>
                                        <td>西尾市</td>
                                        <td>第JOR1号</td>
                                    </tr>
                                    <tr>
                                        <td>豊明市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>みよし市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>北名古屋市</td>
                                        <td>第179号</td>
                                    </tr>
                                    <tr>
                                        <td>犬山市</td>
                                        <td>第137号</td>
                                    </tr>

                                    <tr>
                                        <td>弥富市</td>
                                        <td>第168号</td>
                                    </tr>
                                    <tr>
                                        <td>知多市</td>
                                        <td>第150号</td>
                                    </tr>
									<tr>
                                        <td>刈谷市</td>
                                        <td>第235号</td>
                                    </tr>
									<tr>
                                        <td>新城市</td>
                                        <td>第2021-149号</td>
                                    </tr>
									<tr>
                                        <td>豊山町</td>
                                        <td>第179号</td>
                                    </tr>
									<tr>
                                        <td>幸田町</td>
                                        <td>第142号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>岡崎市</td>
                                        <td>第437号</td>
                                    </tr>
                                    <tr>
                                        <td>碧南市</td>
                                        <td>第147号</td>
                                    </tr>
                                    <tr>
                                        <td>知立市</td>
                                        <td>第151号</td>
                                    </tr>
                                    <tr>
                                        <td>日進市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>東郷町</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>岩倉市</td>
                                        <td>第120号</td>
                                    </tr>
                                    <tr>
                                        <td>あま市</td>
                                        <td>第130号</td>
                                    </tr>
                                    <tr>
                                        <td>津島市</td>
                                        <td>第148号</td>
                                    </tr>
									<tr>
                                        <td>瀬戸市</td>
                                        <td>第182号</td>
                                    </tr>
									<tr>
                                        <td>蒲郡市</td>
                                        <td>第174号</td>
                                    </tr>
									<tr>
                                        <td>高浜市</td>
                                        <td>第130号</td>
                                    </tr>
									<tr>
                                        <td>扶桑、大口町</td>
                                        <td>第141号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="munic-container" id="munic_con3">
                    <div class="munic-con-title" >
                    <span>関西</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
                        <div class="munic-con-sub-title">
                            <span>大阪府</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>堺市</td>
                                        <td>第1417号</td>
                                    </tr>
                                    <tr>
                                        <td>吹田市</td>
                                        <td>第633号</td>
                                    </tr>
                                    <tr>
                                        <td>貝塚市</td>
                                        <td>第375号</td>
                                    </tr>
                                    <tr>
                                        <td>茨木市</td>
                                        <td>第609号</td>
                                    </tr>
                                    <tr>
                                        <td>富田林市</td>
                                        <td>第456号</td>
                                    </tr>
                                    <tr>
                                        <td>和泉市</td>
                                        <td>第520号</td>
                                    </tr>
                                    <tr>
                                        <td>藤井寺市</td>
                                        <td>第396号</td>
                                    </tr>
                                    <tr>
                                        <td>南河内郡河南町</td>
                                        <td>第174号</td>
                                    </tr>
									<tr>
                                        <td>大東市</td>
                                        <td>第479号</td>
                                    </tr>
									<tr>
                                        <td>豊能郡豊能町</td>
                                        <td>第102号</td>
                                    </tr>
									<tr>
                                        <td>摂津市</td>
                                        <td>第495号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>豊中市</td>
                                        <td>第656号</td>
                                    </tr>
                                    <tr>
                                        <td>泉大津市</td>
                                        <td>第354号</td>
                                    </tr>
                                    <tr>
                                        <td>守口市</td>
                                        <td>第526号</td>
                                    </tr>
                                    <tr>
                                        <td>八尾市</td>
                                        <td>第757号</td>
                                    </tr>
                                    <tr>
                                        <td>寝屋川市</td>
                                        <td>第598号</td>
                                    </tr>
                                    <tr>
                                        <td>箕面市</td>
                                        <td>第527号</td>
                                    </tr>
                                    <tr>
                                        <td>交野市</td>
                                        <td>第363号</td>
                                    </tr>
									<tr>
                                        <td>大阪市</td>
                                        <td>第2490号</td>
                                    </tr>
									<tr>
                                        <td>門真市</td>
                                        <td>第527号</td>
                                    </tr>
									<tr>
                                        <td>能勢町</td>
                                        <td>第112号</td>
                                    </tr>
									<tr>
                                        <td>三島郡島本町</td>
                                        <td>第198号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>池田市</td>
                                        <td>第454号</td>
                                    </tr>
                                    <tr>
                                        <td>高槻市</td>
                                        <td>第656号</td>
                                    </tr>
                                    <tr>
                                        <td>枚方市</td>
                                        <td>第761号</td>
                                    </tr>
                                    <tr>
                                        <td>泉佐野市</td>
                                        <td>第413号</td>
                                    </tr>
                                    <tr>
                                        <td>松原市</td>
                                        <td>第461号</td>
                                    </tr>
                                    <tr>
                                        <td>柏原市</td>
                                        <td>第419号</td>
                                    </tr>
                                    <tr>
                                        <td>大阪狭山市</td>
                                        <td>第359号</td>
                                    </tr>
									<tr>
                                        <td>岸和田市</td>
                                        <td>第562号</td>
                                    </tr>
									<tr>
                                        <td>四條畷市</td>
                                        <td>第338号</td>
                                    </tr>
									<tr>
                                        <td>高石市</td>
                                        <td>第398号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>兵庫県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>神戸市</td>
                                        <td>第71474号</td>
                                    </tr>
                                    <tr>
                                        <td>西宮市</td>
                                        <td>第777号</td>
                                    </tr>
									<tr>
                                        <td>芦屋市</td>
                                        <td>第343号</td>
                                    </tr>
									<tr>
                                        <td>龍野市</td>
                                        <td>第254号</td>
                                    </tr>
									<tr>
                                        <td>高砂市</td>
                                        <td>第377号</td>
                                    </tr>
									<tr>
                                        <td>南あわじ市</td>
                                        <td>第313号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>尼崎市</td>
                                        <td>第596号</td>
                                    </tr>
                                    <tr>
                                        <td>宝塚市</td>
                                        <td>第570号</td>
                                    </tr>
                                    <tr>
                                        <td>伊丹市</td>
                                        <td>第522号</td>
                                    </tr>
									<tr>
                                        <td>赤穂市</td>
                                        <td>第96号</td>
                                    </tr>
									<tr>
                                        <td>川西市</td>
                                        <td>番号無</td>
                                    </tr>
									<tr>
                                        <td>淡路市</td>
                                        <td>第313号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>明石市</td>
                                        <td>第212号</td>
                                    </tr>
                                   <tr>
                                        <td>洲本市</td>
                                        <td>第313号</td>
                                    </tr>
									<tr>
                                        <td>相生市</td>
                                        <td>第162号</td>
                                    </tr>
									<tr>
                                        <td>三木市</td>
                                        <td>第443号</td>
                                    </tr>
									<tr>
                                        <td>小野市</td>
                                        <td>第309号</td>
                                    </tr>
									<tr>
                                        <td>川辺郡猪名川町</td>
                                        <td>第148号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
						<div class="munic-con-sub-title">
                            <span>奈良県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>奈良市</td>
                                        <td>第739号</td>
                                    </tr>
                                    <tr>
                                        <td>天理市</td>
                                        <td>第322号</td>
                                    </tr>
									<tr>
                                        <td>五條市</td>
                                        <td>第146号</td>
                                    </tr>
									<tr>
                                        <td>香芝市</td>
                                        <td>第311号</td>
                                    </tr>
									<tr>
                                        <td>宇陀市</td>
                                        <td>第184号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡斑鳩町</td>
                                        <td>第188号</td>
                                    </tr>
									<tr>
                                        <td>磯城郡川西町</td>
                                        <td>第135号</td>
                                    </tr>
									<tr>
                                        <td>宇陀郡御杖村</td>
                                        <td>第22号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡上牧町</td>
                                        <td>第168号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡河合町</td>
                                        <td>第173号</td>
                                    </tr>
									<tr>
                                        <td>吉野郡下市町</td>
                                        <td>第83号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>大和高田市</td>
                                        <td>第262号</td>
                                    </tr>
                                    <tr>
                                        <td>橿原市</td>
                                        <td>第404号</td>
                                    </tr> 
									<tr>
                                        <td>御所市</td>
                                        <td>第165号</td>
                                    </tr>
									<tr>
                                        <td>葛城市</td>
                                        <td>第247号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡平群町</td>
                                        <td>第180号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡安堵町</td>
                                        <td>第100号</td>
                                    </tr>
									<tr>
                                        <td>磯城郡田原本町</td>
                                        <td>第240号</td>
                                    </tr>
									<tr>
                                        <td>高市郡高取町</td>
                                        <td>第89号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡王寺町</td>
                                        <td>第178号</td>
                                    </tr>
									<tr>
                                        <td>吉野郡吉野町</td>
                                        <td>第96号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>大和郡山市</td>
                                        <td>第333号</td>
                                    </tr>
                                   <tr>
                                        <td>桜井市</td>
                                        <td>第242号</td>
                                    </tr>
									<tr>
                                        <td>生駒市</td>
                                        <td>第388号</td>
                                    </tr>
									<tr>
                                        <td>山辺郡山添村</td>
                                        <td>第20号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡三郷町</td>
                                        <td>第181号</td>
                                    </tr>
									<tr>
                                        <td>磯城郡三宅町</td>
                                        <td>第124号</td>
                                    </tr>
									<tr>
                                        <td>宇陀郡曽爾村</td>
                                        <td>第20号</td>
                                    </tr>
									<tr>
                                        <td>高市郡明日香村</td>
                                        <td>第88号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡広陵町</td>
                                        <td>第214号</td>
                                    </tr>
									<tr>
                                        <td>吉野郡大淀町</td>
                                        <td>第136号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>京都府</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>京都市</td>
                                        <td>第949号</td>
                                    </tr>
									<tr>
                                        <td>南丹市</td>
                                        <td>第303号</td>
                                    </tr>
									<tr>
                                        <td>長岡京市</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>乙訓郡大山崎町</td>
                                        <td>第112号</td>
                                    </tr>
									<tr>
                                        <td>宇治田原町</td>
                                        <td>第148号</td>
                                    </tr>
									<tr>
                                        <td>南山城村</td>
                                        <td>第70号</td>
                                    </tr>
                                </table>
                            </div>
							<div>
                                <table class="munic-table">
                                    <tr>
                                        <td>宇治市</td>
                                        <td>第491号</td>
                                    </tr>
									<tr>
                                        <td>亀岡市</td>
                                        <td>第314号</td>
                                    </tr>
									<tr>
                                        <td>八幡市</td>
                                        <td>第309号</td>
                                    </tr>
									<tr>
                                        <td>木津川市</td>
                                        <td>第274号</td>
                                    </tr>
									<tr>
                                        <td>井手町</td>
                                        <td>第R-69号</td>
                                    </tr>
									<tr>
                                        <td>相楽郡精華町</td>
                                        <td>第217号</td>
                                    </tr>
                                </table>
                            </div>
							<div>
                                <table class="munic-table">
                                    <tr>
                                        <td>京丹波町</td>
                                        <td>第21-2509号</td>
                                    </tr>
									<tr>
                                        <td>向日市</td>
                                        <td>第208号</td>
                                    </tr>
									<tr>
                                        <td>京田辺市</td>
                                        <td>第327号</td>
                                    </tr>
									<tr>
                                        <td>久世郡久御山町</td>
                                        <td>第197号</td>
                                    </tr>
									<tr>
                                        <td>和束町</td>
                                        <td>第64号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
						<div class="munic-con-sub-title">
                            <span>和歌山県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>和歌山市</td>
                                        <td>第618号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="munic-container" id="munic_con4">
                    <div class="munic-con-title" >
                    <span>中国</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
                        <div class="munic-con-sub-title">
                            <span>岡山県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>岡山市</td>
                                        <td>第841号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>倉敷市</td>
                                        <td>第658号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                            </div>
                        </div>
                        <!--<div class="munic-con-sub-title">
                            <span>広島県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>広島市</td>
                                        <td>第14560号</td>
                                    </tr>
                                    <tr>
                                        <td>三原市</td>
                                        <td>第253号</td>
                                    </tr>
                                    <tr>
                                        <td>三次市</td>
                                        <td>第2-5号</td>
                                    </tr>
                                    <tr>
                                        <td>江田島市</td>
                                        <td>第20200001号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>呉市</td>
                                        <td>第458号</td>
                                    </tr>
                                    <tr>
                                        <td>尾道市</td>
                                        <td>番号無</td>
                                    </tr>
                                    <tr>
                                        <td>東広島市</td>
                                        <td>第424号</td>
                                    </tr>
                                    <tr>
                                        <td>世羅郡</td>
                                        <td>A2-4</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>竹原市</td>
                                        <td>第173号</td>
                                    </tr>
                                    <tr>
                                        <td>福山市</td>
                                        <td>番号無</td>
                                    </tr>
                                    <tr>
                                        <td>廿日市市</td>
                                        <td>第382号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>-->
                        <div class="munic-con-sub-title">
                            <span>山口県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>

                                <table class="munic-table">
                                    <tr>
                                        <td>岩国市</td>
                                        <td>第196号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                
                            </div>
                            <div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content _sp">
            <div class="munic-main">
                <div class="munic-main-header">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/medal.png" alt="">
                    <div>
                        <div>
                        クリーンライフは、<br class="_sp">各市町村から指定を受けた<br>
                        <span>指定給水装置工事事業者<br class="_sp">(水道局指定工事店)</span>です。
                        </div>
                        <div class="_pc">
                        水道局指定工事店は、必要な機材・資材を取り揃えていて、適切な工事と正しい事務手続きを行い、誠実な対応ができると保証されている事業者になります。あらゆる水まわりのトラブルに対応可能ですので、安心してご依頼ください。
                        </div>
                    </div>
                </div>
                <div class="munic-header-sp">
                        水道局指定工事店は、必要な機材・資材を取り揃えていて、適切な工事と正しい事務手続きを行い、誠実な対応ができると保証されている事業者になります。あらゆる水まわりのトラブルに対応可能ですので、安心してご依頼ください。
                </div>
                <div class="munic-container">
                    <div class="munic-con-title">
                        <span>関東</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
                        <div class="munic-con-sub-title">
                            <span>東京都</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>千代田区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>新宿区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>墨田区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>目黒区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>渋谷区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>豊島区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>板橋区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>葛飾区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>立川市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>府中市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>町田市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>日野市日野市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>国立市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>東大和市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>武蔵村山市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>あきる野市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>港区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>台東区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>品川区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>世田谷区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>杉並区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>荒川区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>足立区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>八王子市</td>
                                        <td>第9712号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>中央区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>文京区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>江東区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>大田区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>中野区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>北区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>練馬区</td>
                                        <td>第9712号</td>
                                    </tr>

                                    <tr>
                                        <td>江戸川区</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>三鷹市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>昭島市</td>
                                        <td>第488号</td>
                                    </tr>
                                    <tr>
                                        <td>小金井市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>東村山市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>福生市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>清瀬市</td>
                                        <td>第488号</td>
                                    </tr>
                                    <tr>
                                        <td>多摩市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>西東京市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>青梅市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>調布市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>小平市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>国分寺市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>狛江市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>東久留米市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>稲城市</td>
                                        <td>第9712号</td>
                                    </tr>
                                    <tr>
                                        <td>西多摩郡</td>
                                        <td>第9712号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>神奈川県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>横浜市</td>
                                        <td>第3008号</td>
                                    </tr>
                                    <tr>
                                        <td>鎌倉市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>茅ヶ崎市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>厚木市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>海老名市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>高座郡</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>愛甲郡</td>
                                        <td>第2887号</td>
                                    </tr>
									<tr>
                                        <td>座間市</td>
                                        <td>第614号</td>
                                    </tr>
									<tr>
                                        <td>足柄上郡山北町</td>
                                        <td>第164号</td>
                                    </tr>
									<tr>
                                        <td>真鶴町</td>
                                        <td>第111号</td>
                                    </tr>
	<tr>
                                        <td>平塚市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>小田原市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>相模原市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>伊勢原市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>三浦郡</td>
                                        <td>第2887号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>川崎市</td>
                                        <td>第1613号</td>
                                    </tr>
                                    <tr>
                                        <td>藤沢市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>逗子市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>大和市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>綾瀬市</td>
                                        <td>第2887号</td>
                                    </tr>
                                    <tr>
                                        <td>中郡</td>
                                        <td>第2887号</td>
                                    </tr>
									<tr>
                                        <td>横須賀市</td>
                                        <td>第542号</td>
                                    </tr>
									<tr>
                                        <td>南足柄市</td>
                                        <td>南第252号</td>
                                    </tr>
									<tr>
                                        <td>愛甲郡愛川町</td>
                                        <td>第281号</td>
                                    </tr>
									<tr>
                                        <td>湯河原町</td>
                                        <td>第174号</td>
                                    </tr>
									<tr>
                                        <td>足柄下郡</td>
                                        <td>第2887号</td>
                                    </tr>
									<tr>
                                        <td>三浦市</td>
                                        <td>第228号</td>
                                    </tr>
									<tr>
                                        <td>足柄上郡大井町</td>
                                        <td>第191号</td>
                                    </tr>
									<tr>
                                        <td>愛甲郡清川村</td>
                                        <td>第2021002号</td>
                                    </tr>
                                </table>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>埼玉県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>さいたま市</td>
                                        <td>第1186号</td>
                                    </tr>
                                    <tr>
                                        <td>所沢市</td>
                                        <td>所沢市</td>
                                    </tr>
                                    <tr>
                                        <td>草加市</td>
                                        <td>第376号</td>
                                    </tr>
                                    <tr>
                                        <td>新座市</td>
                                        <td>第415号</td>
                                    </tr>
                                    <tr>
                                        <td>ふじみ野市</td>
                                        <td>第302号</td>
                                    </tr>
                                    <tr>
                                        <td>入間郡毛呂山町</td>
                                        <td>第148号</td>
                                    </tr>
									<tr>
                                        <td>深谷市</td>
                                        <td>第10457号</td>
                                    </tr>
									<tr>
                                        <td>三郷市</td>
                                        <td>第340号</td>
                                    </tr>
									<tr>
                                        <td>入間郡三芳町</td>
                                        <td>第218号</td>
                                    </tr>
									<tr>
                                        <td>児玉郡美里町</td>
                                        <td>第169号</td>
                                    </tr>
									<tr>
                                        <td>大里郡寄居町</td>
                                        <td>第245号</td>
                                    </tr>
									<tr>
                                        <td>熊谷市</td>
                                        <td>第505号</td>
                                    </tr>
									<tr>
                                        <td>秩父市</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>本庄市</td>
                                        <td>第298号</td>
                                    </tr>
									<tr>
                                        <td>鴻巣市</td>
                                        <td>第383号</td>
                                    </tr>
									<tr>
                                        <td>戸田市</td>
                                        <td>第369号</td>
                                    </tr>
									<tr>
                                        <td>北本市</td>
                                        <td>第428号</td>
                                    </tr>
									<tr>
                                        <td>足立郡伊奈町</td>
                                        <td>第255号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡横瀬町</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡小鹿野町</td>
                                        <td>第216号</td>
                                    </tr>
<tr>
                                        <td>川口市</td>
                                        <td>第843号</td>
                                    </tr>
                                    <tr>
                                        <td>狭山市</td>
                                        <td>第335号</td>
                                    </tr>
                                    <tr>
                                        <td>越谷市</td>
                                        <td>第428号</td>
                                    </tr>
                                    <tr>
                                        <td>八潮市</td>
                                        <td>第290号</td>
                                    </tr>
                                    <tr>
                                        <td>入間市</td>
                                        <td>第329号</td>
                                    </tr>
                                    <tr>
                                        <td>比企郡ときがわ町</td>
                                        <td>第109号</td>
                                    </tr>
									<tr>
                                        <td>春日部市</td>
                                        <td>第443号</td>
                                    </tr>
									<tr>
                                        <td>久喜市</td>
                                        <td>第347号</td>
                                    </tr>
									<tr>
                                        <td>日高市</td>
                                        <td>第264号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>川越市</td>
                                        <td>第598号</td>
                                    </tr>
                                    <tr>
                                        <td>東松山市</td>
                                        <td>第331号</td>
                                    </tr>
                                    <tr>
                                        <td>松伏市</td>
                                        <td>第428号</td>
                                    </tr>
                                    <tr>
                                        <td>吉川市</td>
                                        <td>第226号</td>
                                    </tr>
                                    <tr>
                                        <td>坂戸市</td>
                                        <td>第422号</td>
                                    </tr>
                                    <tr>
                                        <td>比企郡鳩山町</td>
                                        <td>第R02125号</td>
                                    </tr>
                                    <tr>
                                        <td>比企郡川島町</td>
                                        <td>第156号</td>
                                    </tr>
									<tr>
                                        <td>和光市</td>
                                        <td>第268号</td>
                                    </tr>
									<tr>
                                        <td>蓮田市</td>
                                        <td>第265号</td>
                                    </tr>
									<tr>
                                        <td>入間郡越生町</td>
                                        <td>第104号</td>
                                    </tr>
									<tr>
                                        <td>児玉郡神川町</td>
                                        <td>第161号</td>
                                    </tr>
									<tr>
                                        <td>北葛飾郡杉戸町</td>
                                        <td>第10179号</td>
                                    </tr>
									<tr>
                                        <td>飯能市</td>
                                        <td>第249号</td>
                                    </tr>
									<tr>
                                        <td>狭山市</td>
                                        <td>第335号</td>
                                    </tr>
									<tr>
                                        <td>上尾市</td>
                                        <td>第534号</td>
                                    </tr>
									<tr>
                                        <td>志木市</td>
                                        <td>第265号</td>
                                    </tr>
									<tr>
                                        <td>幸手市</td>
                                        <td>第R03-211号</td>
                                    </tr>
									<tr>
                                        <td>入間市</td>
                                        <td>第329号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡皆野町</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡東秩父村</td>
                                        <td>第56号</td>
                                    </tr>
									<tr>
                                        <td>児玉郡上里町</td>
                                        <td>第204号</td>
                                    </tr>
									<tr>
                                        <td>行田市</td>
                                        <td>第337号</td>
                                    </tr>
									<tr>
                                        <td>加須市</td>
                                        <td>第344号</td>
                                    </tr>
									<tr>
                                        <td>羽生市</td>
                                        <td>第275号</td>
                                    </tr>
									<tr>
                                        <td>蕨市</td>
                                        <td>第277号</td>
                                    </tr>
									<tr>
                                        <td>桶川市</td>
                                        <td>第428号</td>
                                    </tr>
									<tr>
                                        <td>鶴ヶ島市</td>
                                        <td>第422号</td>
                                    </tr>
									<tr>
                                        <td>比企郡鳩山町</td>
                                        <td>第R02125号</td>
                                    </tr>
									<tr>
                                        <td>秩父郡長瀞町</td>
                                        <td>第216号</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="munic-con-sub-title">
                            <span>千葉県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>千葉県</td>
                                        <td>第448号</td>
                                    </tr>
									<tr>
                                        <td>松戸市</td>
                                        <td>第415号</td>
                                    </tr>
									<tr>
                                        <td>習志野市</td>
                                        <td>第386号</td>
                                    </tr>
									<tr>
                                        <td>市原市</td>
                                        <td>第607号</td>
                                    </tr>
									<tr>
                                        <td>君津市</td>
                                        <td>第243号</td>
                                    </tr>
									<tr>
                                        <td>袖ヶ浦市</td>
                                        <td>第243号</td>
                                    </tr>
	<tr>
                                        <td>木更津市</td>
                                        <td>第243号</td>
                                    </tr>
									<tr>
                                        <td>成田市</td>
                                        <td>第351号</td>
                                    </tr>
									
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>千葉県</td>
                                        <td>第2195号</td>
                                    </tr>
									<tr>
                                        <td>野田市</td>
                                        <td>第326号</td>
                                    </tr>
									<tr>
                                        <td>印西市</td>
                                        <td>第118号</td>
                                    </tr>
									<tr>
                                        <td>我孫子市</td>
                                        <td>第362号</td>
                                    </tr>
									<tr>
                                        <td>富津市</td>
                                        <td>第243号</td>
                                    </tr>
									<tr>
                                        <td>鎌ヶ谷市</td>
                                        <td>第2195号</td>
                                    </tr>
									<tr>
                                        <td>浦安市</td>
                                        <td>第2195号</td>
                                    </tr>
									<tr>
                                        <td>印旛郡栄町</td>
                                        <td>第118号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="munic-container">
                    <div class="munic-con-title" >
                    <span>東海</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
						<div class="munic-con-sub-title">
                            <span>三重県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>津市</td>
                                        <td>第753号</td>
                                    </tr>
                                    <tr>
                                        <td>松阪市</td>
                                        <td>第544号</td>
                                    </tr>
									<tr>
                                        <td>亀山市</td>
                                        <td>第2014号</td>
                                    </tr>
									<tr>
                                        <td>伊賀市</td>
                                        <td>第385号</td>
                                    </tr>
	<tr>
                                        <td>伊勢市</td>
                                        <td>第396号</td>
                                    </tr>
                                    <tr>
                                        <td>尾鷲市</td>
                                        <td>第2003号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>四日市市</td>
                                        <td>第543号</td>
                                    </tr>
                                    <tr>
                                        <td>鈴鹿市</td>
                                        <td>第571号</td>
                                    </tr>
									<tr>
                                        <td>鳥羽市</td>
                                        <td>第179号</td>
                                    </tr>
									<tr>
                                        <td>桑名郡木曽岬町</td>
                                        <td>第61号</td>
                                    </tr>
									<tr>
                                        <td>熊野市</td>
                                        <td>第62号</td>
                                    </tr>
									<tr>
                                        <td>員弁郡東員町</td>
                                        <td>第158号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
						<div class="munic-con-sub-title">
                            <span>岐阜県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>岐阜市</td>
                                        <td>第97号</td>
                                    </tr>
	<tr>
                                        <td>多治見市</td>
                                        <td>第211号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>大垣市</td>
                                        <td>第335号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
						<div class="munic-con-sub-title">
                            <span>静岡県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>熱海市</td>
                                        <td>第167号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>愛知県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>名古屋市</td>
                                        <td>第1437号</td>
                                    </tr>
                                    <tr>
                                        <td>一宮市</td>
                                        <td>第346号</td>
                                    </tr>
                                    <tr>
                                        <td>豊田市</td>
                                        <td>第394号</td>
                                    </tr>
                                    <tr>
                                        <td>尾張旭市</td>
                                        <td>第176号</td>
                                    </tr>
                                    <tr>
                                        <td>長久手市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>愛西市</td>
                                        <td>第112号</td>
                                    </tr>
                                    <tr>
                                        <td>江南市</td>
                                        <td>第170号</td>
                                    </tr>
                                    <tr>
                                        <td>飛島村</td>
                                        <td>第168号</td>
                                    </tr>
                                    <tr>
                                        <td>常滑市</td>
                                        <td>第209号</td>
                                    </tr>
									<tr>
                                        <td>豊川市</td>
                                        <td>第263号</td>
                                    </tr>
									<tr>
                                        <td>小牧市</td>
                                        <td>第210号</td>
                                    </tr>
									<tr>
                                        <td>長久手市</td>
                                        <td>第326号</td>
                                    </tr>
									<tr>
                                        <td>大府市</td>
                                        <td>第193号</td>
                                    </tr>
	<tr>
                                        <td>岡崎市</td>
                                        <td>第437号</td>
                                    </tr>
                                    <tr>
                                        <td>碧南市</td>
                                        <td>第147号</td>
                                    </tr>
                                    <tr>
                                        <td>知立市</td>
                                        <td>第151号</td>
                                    </tr>
                                    <tr>
                                        <td>日進市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>東郷町</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>岩倉市</td>
                                        <td>第120号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>豊橋市</td>
                                        <td>第290号</td>
                                    </tr>
                                    <tr>
                                        <td>春日井市</td>
                                        <td>第267号</td>
                                    </tr>
                                    <tr>
                                        <td>西尾市</td>
                                        <td>第JOR1号</td>
                                    </tr>
                                    <tr>
                                        <td>豊明市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>みよし市</td>
                                        <td>第326号</td>
                                    </tr>
                                    <tr>
                                        <td>北名古屋市</td>
                                        <td>第179号</td>
                                    </tr>
                                    <tr>
                                        <td>犬山市</td>
                                        <td>第137号</td>
                                    </tr>

                                    <tr>
                                        <td>弥富市</td>
                                        <td>第168号</td>
                                    </tr>
                                    <tr>
                                        <td>知多市</td>
                                        <td>第150号</td>
                                    </tr>
									<tr>
                                        <td>刈谷市</td>
                                        <td>第235号</td>
                                    </tr>
									<tr>
                                        <td>新城市</td>
                                        <td>第2021-149号</td>
                                    </tr>
									<tr>
                                        <td>豊山町</td>
                                        <td>第179号</td>
                                    </tr>
									<tr>
                                        <td>幸田町</td>
                                        <td>第142号</td>
                                    </tr>
									<tr>
                                        <td>あま市</td>
                                        <td>第130号</td>
                                    </tr>
                                    <tr>
                                        <td>津島市</td>
                                        <td>第148号</td>
                                    </tr>
									<tr>
                                        <td>瀬戸市</td>
                                        <td>第182号</td>
                                    </tr>
									<tr>
                                        <td>蒲郡市</td>
                                        <td>第174号</td>
                                    </tr>
									<tr>
                                        <td>高浜市</td>
                                        <td>第130号</td>
                                    </tr>
									<tr>
                                        <td>扶桑、大口町</td>
                                        <td>第141号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="munic-container">
                    <div class="munic-con-title" >
                    <span>関西</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
                        <div class="munic-con-sub-title">
                            <span>大阪府</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>堺市</td>
                                        <td>第1417号</td>
                                    </tr>
                                    <tr>
                                        <td>吹田市</td>
                                        <td>第633号</td>
                                    </tr>
                                    <tr>
                                        <td>貝塚市</td>
                                        <td>第375号</td>
                                    </tr>
                                    <tr>
                                        <td>茨木市</td>
                                        <td>第609号</td>
                                    </tr>
                                    <tr>
                                        <td>富田林市</td>
                                        <td>第456号</td>
                                    </tr>
                                    <tr>
                                        <td>和泉市</td>
                                        <td>第520号</td>
                                    </tr>
                                    <tr>
                                        <td>藤井寺市</td>
                                        <td>第396号</td>
                                    </tr>
                                    <tr>
                                        <td>南河内郡河南町</td>
                                        <td>第174号</td>
                                    </tr>
									<tr>
                                        <td>大東市</td>
                                        <td>第479号</td>
                                    </tr>
									<tr>
                                        <td>豊能郡豊能町</td>
                                        <td>第102号</td>
                                    </tr>
									<tr>
                                        <td>摂津市</td>
                                        <td>第495号</td>
                                    </tr>
	<tr>
                                        <td>池田市</td>
                                        <td>第454号</td>
                                    </tr>
                                    <tr>
                                        <td>高槻市</td>
                                        <td>第656号</td>
                                    </tr>
                                    <tr>
                                        <td>枚方市</td>
                                        <td>第761号</td>
                                    </tr>
                                    <tr>
                                        <td>泉佐野市</td>
                                        <td>第413号</td>
                                    </tr>
                                    <tr>
                                        <td>松原市</td>
                                        <td>第461号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>豊中市</td>
                                        <td>第656号</td>
                                    </tr>
                                    <tr>
                                        <td>泉大津市</td>
                                        <td>第354号</td>
                                    </tr>
                                    <tr>
                                        <td>守口市</td>
                                        <td>第526号</td>
                                    </tr>
                                    <tr>
                                        <td>八尾市</td>
                                        <td>第757号</td>
                                    </tr>
                                    <tr>
                                        <td>寝屋川市</td>
                                        <td>第598号</td>
                                    </tr>
                                    <tr>
                                        <td>箕面市</td>
                                        <td>第527号</td>
                                    </tr>
                                    <tr>
                                        <td>交野市</td>
                                        <td>第363号</td>
                                    </tr>
									<tr>
                                        <td>大阪市</td>
                                        <td>第2490号</td>
                                    </tr>
									<tr>
                                        <td>門真市</td>
                                        <td>第527号</td>
                                    </tr>
									<tr>
                                        <td>能勢町</td>
                                        <td>第112号</td>
                                    </tr>
									<tr>
                                        <td>三島郡島本町</td>
                                        <td>第198号</td>
                                    </tr>
									<tr>
                                        <td>柏原市</td>
                                        <td>第419号</td>
                                    </tr>
                                    <tr>
                                        <td>大阪狭山市</td>
                                        <td>第359号</td>
                                    </tr>
									<tr>
                                        <td>岸和田市</td>
                                        <td>第562号</td>
                                    </tr>
									<tr>
                                        <td>四條畷市</td>
                                        <td>第338号</td>
                                    </tr>
									<tr>
                                        <td>高石市</td>
                                        <td>第398号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="munic-con-sub-title">
                            <span>兵庫県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>神戸市</td>
                                        <td>第71474号</td>
                                    </tr>
                                    <tr>
                                        <td>西宮市</td>
                                        <td>第777号</td>
                                    </tr>
									<tr>
                                        <td>芦屋市</td>
                                        <td>第343号</td>
                                    </tr>
									<tr>
                                        <td>龍野市</td>
                                        <td>第254号</td>
                                    </tr>
									<tr>
                                        <td>高砂市</td>
                                        <td>第377号</td>
                                    </tr>
									<tr>
                                        <td>南あわじ市</td>
                                        <td>第313号</td>
									</tr>
                                        <td>明石市</td>
                                        <td>第212号</td>
                                    </tr>
                                   <tr>
                                        <td>洲本市</td>
                                        <td>第313号</td>
                                    </tr>
									<tr>
                                        <td>相生市</td>
                                        <td>第162号</td>
                                    </tr>
                                   
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>尼崎市</td>
                                        <td>第596号</td>
                                    </tr>
                                    <tr>
                                        <td>宝塚市</td>
                                        <td>第570号</td>
                                    </tr>
                                    <tr>
                                        <td>伊丹市</td>
                                        <td>第522号</td>
                                    </tr>
									<tr>
                                        <td>赤穂市</td>
                                        <td>第96号</td>
                                    </tr>
									<tr>
                                        <td>川西市</td>
                                        <td>番号無</td>
                                    </tr>
									<tr>
                                        <td>淡路市</td>
                                        <td>第313号</td>
                                    </tr>
									<tr>
                                        <td>三木市</td>
                                        <td>第443号</td>
                                    </tr>
									<tr>
                                        <td>小野市</td>
                                        <td>第309号</td>
                                    </tr>
									<tr>
                                        <td>川辺郡猪名川町</td>
                                        <td>第148号</td>
                                    </tr>
                                
                                </table>
                            </div>

                        </div>
					<div class="munic-con-sub-title">
                            <span>奈良県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>奈良市</td>
                                        <td>第739号</td>
                                    </tr>
                                    <tr>
                                        <td>天理市</td>
                                        <td>第322号</td>
                                    </tr>
									<tr>
                                        <td>五條市</td>
                                        <td>第146号</td>
                                    </tr>
									<tr>
                                        <td>香芝市</td>
                                        <td>第311号</td>
                                    </tr>
									<tr>
                                        <td>宇陀市</td>
                                        <td>第184号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡斑鳩町</td>
                                        <td>第188号</td>
                                    </tr>
									<tr>
                                        <td>磯城郡川西町</td>
                                        <td>第135号</td>
                                    </tr>
									<tr>
                                        <td>宇陀郡御杖村</td>
                                        <td>第22号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡上牧町</td>
                                        <td>第168号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡河合町</td>
                                        <td>第173号</td>
                                    </tr>
									<tr>
                                        <td>吉野郡下市町</td>
                                        <td>第83号</td>
                                    </tr>
	<tr>
                                        <td>大和郡山市</td>
                                        <td>第333号</td>
                                    </tr>
                                   <tr>
                                        <td>桜井市</td>
                                        <td>第242号</td>
                                    </tr>
									<tr>
                                        <td>生駒市</td>
                                        <td>第388号</td>
                                    </tr>
									<tr>
                                        <td>山辺郡山添村</td>
                                        <td>第20号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡三郷町</td>
                                        <td>第181号</td>
                                    </tr>
                                   
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>大和高田市</td>
                                        <td>第262号</td>
                                    </tr>
                                    <tr>
                                        <td>橿原市</td>
                                        <td>第404号</td>
                                    </tr> 
									<tr>
                                        <td>御所市</td>
                                        <td>第165号</td>
                                    </tr>
									<tr>
                                        <td>葛城市</td>
                                        <td>第247号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡平群町</td>
                                        <td>第180号</td>
                                    </tr>
									<tr>
                                        <td>生駒郡安堵町</td>
                                        <td>第100号</td>
                                    </tr>
									<tr>
                                        <td>磯城郡田原本町</td>
                                        <td>第240号</td>
                                    </tr>
									<tr>
                                        <td>高市郡高取町</td>
                                        <td>第89号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡王寺町</td>
                                        <td>第178号</td>
                                    </tr>
									<tr>
                                        <td>吉野郡吉野町</td>
                                        <td>第96号</td>
                                    </tr>
									<tr>
                                        <td>磯城郡三宅町</td>
                                        <td>第124号</td>
                                    </tr>
									<tr>
                                        <td>宇陀郡曽爾村</td>
                                        <td>第20号</td>
                                    </tr>
									<tr>
                                        <td>高市郡明日香村</td>
                                        <td>第88号</td>
                                    </tr>
									<tr>
                                        <td>北葛城郡広陵町</td>
                                        <td>第214号</td>
                                    </tr>
									<tr>
                                        <td>吉野郡大淀町</td>
                                        <td>第136号</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <div class="munic-con-sub-title">
                            <span>京都府</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>京都市</td>
                                        <td>第949号</td>
                                    </tr>
									<tr>
                                        <td>南丹市</td>
                                        <td>第303号</td>
                                    </tr>
									<tr>
                                        <td>長岡京市</td>
                                        <td>第216号</td>
                                    </tr>
									<tr>
                                        <td>乙訓郡大山崎町</td>
                                        <td>第112号</td>
                                    </tr>
									<tr>
                                        <td>宇治田原町</td>
                                        <td>第148号</td>
                                    </tr>
									<tr>
                                        <td>南山城村</td>
                                        <td>第70号</td>
                                    </tr>
	<tr>
                                        <td>京丹波町</td>
                                        <td>第21-2509号</td>
                                    </tr>
									<tr>
                                        <td>向日市</td>
                                        <td>第208号</td>
                                    </tr>
									<tr>
                                        <td>京田辺市</td>
                                        <td>第327号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>宇治市</td>
                                        <td>第491号</td>
                                    </tr>
									<tr>
                                        <td>亀岡市</td>
                                        <td>第314号</td>
                                    </tr>
									<tr>
                                        <td>八幡市</td>
                                        <td>第309号</td>
                                    </tr>
									<tr>
                                        <td>木津川市</td>
                                        <td>第274号</td>
                                    </tr>
									<tr>
                                        <td>井手町</td>
                                        <td>第R-69号</td>
                                    </tr>
									<tr>
                                        <td>相楽郡精華町</td>
                                        <td>第217号</td>
                                    </tr>
									<tr>
                                        <td>久世郡久御山町</td>
                                        <td>第197号</td>
                                    </tr>
									<tr>
                                        <td>和束町</td>
                                        <td>第64号</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
					<div class="munic-con-sub-title">
                            <span>和歌山県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>和歌山市</td>
                                        <td>第618号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="munic-container">
                    <div class="munic-con-title" >
                    <span>中国</span>の指定給水装置工事事業者 指定番号
                        <div class="munic-con-icon">
                            <div>
                                <div></div>
                            </div>
                        </div>   
                    </div>
                    <div class="munic-con-main">
                        <div class="munic-con-sub-title">
                            <span>岡山県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>岡山市</td>
                                        <td>第841号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>倉敷市</td>
                                        <td>第658号</td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                        <!--<div class="munic-con-sub-title">
                            <span>広島県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>広島市</td>
                                        <td>第14560号</td>
                                    </tr>
                                    <tr>
                                        <td>三原市</td>
                                        <td>第253号</td>
                                    </tr>
                                    <tr>
                                        <td>三次市</td>
                                        <td>第2-5号</td>
                                    </tr>
                                    <tr>
                                        <td>江田島市</td>
                                        <td>第20200001号</td>
                                    </tr>
                                    <tr>
                                        <td>竹原市</td>
                                        <td>第173号</td>
                                    </tr>
                                    <tr>
                                        <td>福山市</td>
                                        <td>番号無</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>呉市</td>
                                        <td>第458号</td>
                                    </tr>
                                    <tr>
                                        <td>尾道市</td>
                                        <td>番号無</td>
                                    </tr>
                                    <tr>
                                        <td>東広島市</td>
                                        <td>第424号</td>
                                    </tr>
                                    <tr>
                                        <td>世羅郡</td>
                                        <td>A2-4</td>
                                    </tr>
                                    <tr>
                                        <td>廿日市市</td>
                                        <td>第382号</td>
                                    </tr>
                                </table>
                            </div>
                        </div>-->
                        <div class="munic-con-sub-title">
                            <span>山口県</span>の指定給水装置工事事業者 指定番号
                        </div>
                        <div class="munic-con-main-main">
                            <div>
                                <table class="munic-table">
                                    <tr>
                                        <td>岩国市</td>
                                        <td>第196号</td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="clean">
        <div class="content">
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
                        <div>3,300<span>円（税込）</span></div>
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
            <div class="clean-subtitle">作業費用一例</div>
            <div class="clean-box-container">
               
                <div class="clean-box">
                    <div class="clean-box-title">トイレのつまり除去</div>
                    <div class="clean-box-subtitle">（名古屋市 K様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean2.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>5,500</span>円</div>
                        <div>部品代 <span>0</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>8,800</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>5,800<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
				 <div class="clean-box">
                    <div class="clean-box-title">洗面所のパッキン交換</div>
                    <div class="clean-box-subtitle">（堺市 S様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean1.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>3,300</span>円</div>
                        <div>部品代 <span>0</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>6,600</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>3,600<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
				<div class="clean-box">
                    <div class="clean-box-title">お風呂のつまり除去</div>
                    <div class="clean-box-subtitle">（世田谷区 Y様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean4.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>5,500</span>円</div>
                        <div>部品代 <span>0</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>8,800</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>5,800<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
                <div class="clean-box">
                    <div class="clean-box-title">洗面所のつまり除去</div>
                    <div class="clean-box-subtitle">（横浜市 A様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean3.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>5,500</span>円</div>
                        <div>部品代 <span>0</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>8,800</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>5,800<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
                
                <div class="clean-box">
                    <div class="clean-box-title">水栓の交換</div>
                    <div class="clean-box-subtitle">（千葉県 H様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean5.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>16,500</span>円</div>
                        <div>部品代 <span>26,000</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>45,800</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>42,800<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
                <div class="clean-box">
                    <div class="clean-box-title">排水管のつまり</div>
                    <div class="clean-box-subtitle">（大田区 T様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean6.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>16,500</span>円</div>
                        <div>部品代 <span>0</span>円</div>

                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>19,800</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>16,800<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
                <div class="clean-box">
                    <div class="clean-box-title">屋外の蛇口交換</div>
                    <div class="clean-box-subtitle">（八王子市 M様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean7.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>13,200</span>円</div>
                        <div>部品代 <span>0</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>16,500</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>13,500<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
                <div class="clean-box">
                    <div class="clean-box-title">漏水調査</div>
                    <div class="clean-box-subtitle">（船橋市 F様）</div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean8.png" alt="">
                    <div class="clean-box-content">
                        <div>基本料金 <span>3,300</span>円</div>
                        <div>作業料金 <span>22,000</span>円</div>
                        <div>部品代 <span>0</span>円</div>
                        <div class="clean-box-content-plus">+</div>
                    </div>
                    <div class="clean-box-content clean-box-content-result">合計<span>25,300</span>円</div>
                    <div class="clean-box-result">
                        <div class="clean-box-discount">
                            <div>WEB割引</div>
                            <div>3,000円</div>
                        </div>
                        <div class="clean-box-result01">
                            <div class="clean-box-result-arrow">⇒</div>
                            <div>22,300<span class="clean-box-result-yen">円(税込)</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-clean-content">
            <div class="swiper-container swiper-container-clean clean-box-container">
                <div class="swiper-wrapper">
				<div class="clean-box swiper-slide">
                        <div class="clean-box-title">トイレのつまり除去</div>
                        <div class="clean-box-subtitle">（名古屋市 K様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean2.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>5,500</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>8,800</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>5,800<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box swiper-slide">
                        <div class="clean-box-title">洗面所のパッキン交換</div>
                        <div class="clean-box-subtitle">（堺市 S様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean1.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>3,300</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>6,600</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>3,600<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>                                        
                    <div class="clean-box swiper-slide">
                        <div class="clean-box-title">お風呂のつまり除去</div>
                        <div class="clean-box-subtitle">（世田谷区 Y様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean4.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>5,500</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>8,800</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>5,800<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
					<div class="clean-box swiper-slide">
                        <div class="clean-box-title">洗面所のつまり除去</div>
                        <div class="clean-box-subtitle">（横浜市 A様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean3.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>5,500</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>8,800</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>5,800<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box swiper-slide">
                        <div class="clean-box-title">浄水器の設置・取り外し</div>
                        <div class="clean-box-subtitle">（千葉県 H様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean5.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>16,500</span>円</div>
                            <div>部品代 <span>26,000</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>45,800</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>42,800<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box swiper-slide">
                        <div class="clean-box-title">排水管のつまり</div>
                        <div class="clean-box-subtitle">（大田区 T様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean6.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>16,500</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>19,800</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>16,800<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box swiper-slide">
                        <div class="clean-box-title">屋外の蛇口交換</div>
                        <div class="clean-box-subtitle">（八王子市 M様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean7.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>13,200</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>16,500</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>13,500<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box swiper-slide">
                        <div class="clean-box-title">漏水調査</div>
                        <div class="clean-box-subtitle">（船橋市 F様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/clean8.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>22,000</span>円</div>
                            <div>部品代 <span>0</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>25,300</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>22,300<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <div class="clean-notice">※状況や部品の種類/仕様によって料金変動します。</div>
    </section>
    <section class="service">
        <div class="service-subtitle">
            <div>\ 水道トラブルは /</div>
            クリーンライフにお任せください!
        </div>

        <div class="service-container">
            <div class="content">
                <div class="service-title">
                    <div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo1.png" alt="">
                        クリーンライフが対応可能な
                    </div>
                    <div>サービス一覧</div>
                    <div>
                        <div class="service-title-arrow"></div>
                        <div>SERVICE</div>
                    </div>
                </div>
                <div class="service-box-container">
                    <div class="service-box"></div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service1.jpg" alt="">
                            <div class="service-box-title">
                                <div>01</div>
                                <div>トイレ</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>トイレが詰まってしまった</li>
                                <li>タンクから水漏れしている</li>
                                <li>水が流れない</li>
                                <li>トイレを交換したい　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>toilet/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service2.jpg" alt="">
                            <div class="service-box-title">
                                <div>02</div>
                                <div>お風呂</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>シャワーから水漏れしている</li>
                                <li>蛇口から水漏れしている</li>
                                <li>排水口から悪臭がする</li>
                                <li>ユニットを交換したい　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>bath/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service3.jpg" alt="">
                            <div class="service-box-title">
                                <div>03</div>
                                <div>キッチン</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>シャワーから水漏れしている</li>
                                <li>蛇口から水漏れしている</li>
                                <li>排水口から悪臭がする</li>
                                <li>ユニットを交換したい　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>kitchen/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service4.jpg" alt="">
                            <div class="service-box-title">
                                <div>04</div>
                                <div>洗面所</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>シンクしたから水漏れしている</li>
                                <li>蛇口から水漏れしている</li>
                                <li>排水口が詰まってしまった</li>
                                <li>洗面台を交換したい　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>washroom/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service5.jpg" alt="">
                            <div class="service-box-title">
                                <div>05</div>
                                <div>給湯器</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>お湯が出ない</li>
                                <li>エラーコードが出ている</li>
                                <li>給湯器から煙が出ている</li>
                                <li>給湯器を交換したい　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>waterheater/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service6.jpg" alt="">
                            <div class="service-box-title">
                                <div>06</div>
                                <div>排水管</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>排水管から水漏れしている</li>
                                <li>排水管から悪臭がする</li>
                                <li>排水口が詰まってしまった</li>
                                <li>排水管から異音がする　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>drainpipe/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                    <div class="service-box">
                        <div class="service-box-img">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service7.jpg" alt="">
                            <div class="service-box-title">
                                <div>07</div>
                                <div>水道管</div>
                            </div>
                        </div>
                        <div class="service-box-text">
                            <ul>
                                <li>水道管から水漏れしている</li>
                                <li>水道管から悪臭がする</li>
                                <li>水道管が詰まってしまった</li>
                                <li>水道管から異音がする　など</li>
                            </ul>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>waterpipe/">料金・詳細はこちら &#9656;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="reason">
        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/reason-top.jpg" alt="" class="reason-top">
        <div class="reason-content">
            <div class="content">
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
                            <div>充実<span>の</span><br>
                                アフターフォロー</div>
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
        </div>
    </section>
    <section class="case">
        <div class="content">
            <div class="service-title">
                <div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                    施工事例をご紹介します
                </div>
                <div>施工事例</div>
                <div>
                    <div class="service-title-arrow"></div>
                    <div>CASE</div>
                    <div class="service-title-end"></div>
                </div>
            </div>
            <div class="case-box-container">
                <div id="case_box_main">
                                                <?php 
                                $wpb_all_query = new WP_Query(array('post_type'=>'case', 'post_status'=>'publish', 'posts_per_page'=>5)); 

                                if ( $wpb_all_query->have_posts() ) :
                                while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
                                
                                ?>
                                                    <div class="case-box">
                                                    <?php
                                if (has_post_thumbnail()){
                                    the_post_thumbnail('post-thumbnail', ['class' => 'attachment-post-thumbnail size-post-thumbnail wp-post-image case-img']);
                                }

                                else {
                                    echo '<img class="case-img" src="'. get_template_directory_uri().'/assets/image/top/case1.jpg" alt="">';
                                }
                                ?>
                                                        <div class="case-box-content">
                            <div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/maps-and-flags.png" alt="">
                                <?php the_title() ?>
                            </div>
                            <div><?php the_content() ?>
                            </div>
                            <div><?php $custom = get_post_custom();
                                if(isset($custom['price'])) {
                                    echo $custom['price'][0];
                                }?><span>円</span></div>
                                                        </div>
                                                    </div>
                                                    <?php endwhile;

                                ?>
                                <?php
                                else :
                                echo '<p>投稿はありません！</p>';

                                endif;
                                ?>
                </div>
                <div class="case-link-container">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>case/">その他の事例も見る</a>
                </div>
            </div>
        </div>
    </section>
    <section class="voice-faq">
        <div class="voice">
            <div class="content">
                <div class="service-title">
                    <div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                        クリーンライフに寄せられる
                    </div>
                    <div>お客様の声</div>
                    <div>
                        <div class="service-title-arrow"></div>
                        <div>VOICE</div>
                        <div class="service-title-end"></div>
                    </div>
                </div>
                <div class="voice-box-container">
                    <div class="voice-box-main">
                        <div class="voice-box">
                            <div class="voice-box-title">給湯器の修理・交換</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="">
                                    <div>
                                        安心して任せられました!
                                        <span>まさし/給湯器の交換</span>
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                給湯器の調子が悪かったので交換をお願いしました。似たようなトラブルを例に料金の目安を教えてもらえたり、作業内容の説明もしっかりしていたりと安心して任せられました。
                            </div>
                        </div>
                        <div class="voice-box">
                            <div class="voice-box-title">トイレの水漏れ・つまり</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar2.png" alt="">
                                    <div>
                                        プロにお願いして正解でした!
                                        <span>ひとみ/トイレの水漏れ</span>
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                あっという間に駆けつけてくれて、原因もすぐに特定して修理してくれました。老朽化によって詰まりやすくなっていたみたいで、やはりプロにお願いして正解でした。
                            </div>
                        </div>
                        <div class="voice-box">
                            <div class="voice-box-title">キッチンの水漏れ・つまり</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar3.png" alt="">
                                    <div>
                                        料金も手頃で済み、頼んで良かったと思います。
                                        <span>みーちゃん/キッチンの水漏れ</span>
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                自宅に一番近い業者に電話してみたところ、来てくれるということで頼むことにしました。パイプに固形物が詰まっていたようで、職人の方に工具で取り除いてもらいました。料金も手頃で済み、頼んで良かったと思います。
                            </div>
                        </div>
                        <div class="voice-box">
                            <div class="voice-box-title">洗面所の水漏れ・つまり</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar4.png" alt="">
                                    <div>
                                        放置なんてせずに、さっさと頼めばよかった。
                                        <span>たっつー/洗面所の水漏れ</span> 
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                少し前からポタポタ蛇口から漏れ出していて、お願いすることに。電話口で説明してもらえたのでその時点で安心できました。実際に来ていただいて作業も早くて二度目の安心でした。
                            </div>
                        </div>
                    </div>
                    <div class="case-link-container">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>voice/">もっとみる</a>
                    </div>
                </div>
            </div>
            <div class="slider-voice-content">
                <div class="swiper-container swiper-container-voice voice-box-container">
                    <div class="swiper-wrapper">
                        <div class="voice-box swiper-slide">
                            <div class="voice-box-title voice-box-title1">給湯器の修理・交換</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="">
                                    <div class="voice-box-subtitle1">
                                        安心して任せられました!
                                        <span>まさし/給湯器の交換</span>
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                給湯器の調子が悪かったので交換をお願いしました。似たようなトラブルを例に料金の目安を教えてもらえたり、作業内容の説明もしっかりしていたりと安心して任せられました。
                            </div>
                        </div>
                        <div class="voice-box swiper-slide">
                            <div class="voice-box-title voice-box-title2">トイレの水漏れ・つまり</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar2.png" alt="">
                                    <div class="voice-box-subtitle2">
                                        プロにお願いして正解でした!
                                        <span>ひとみ/トイレの水漏れ</span>
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                あっという間に駆けつけてくれて、原因もすぐに特定して修理してくれました。老朽化によって詰まりやすくなっていたみたいで、やはりプロにお願いして正解でした。
                            </div>
                        </div>
                        <div class="voice-box swiper-slide">
                            <div class="voice-box-title voice-box-title3">キッチンの水漏れ・つまり</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar3.png" alt="">
                                    <div class="voice-box-subtitle3">
                                        料金も手頃で済み、頼んで良かったと思います。
                                        <span>みーちゃん/キッチンの水漏れ</span>
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                自宅に一番近い業者に電話してみたところ、来てくれるということで頼むことにしました。パイプに固形物が詰まっていたようで、職人の方に工具で取り除いてもらいました。料金も手頃で済み、頼んで良かったと思います。
                            </div>
                        </div>
                        <div class="voice-box swiper-slide">
                            <div class="voice-box-title voice-box-title4">洗面所の水漏れ・つまり</div>
                            <div class="voice-box-header">
                                <div class="voice-box-head">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar4.png" alt="">
                                    <div class="voice-box-subtitle4">
                                        放置なんてせずに、さっさと頼めばよかった。
                                        <span>たっつー/洗面所の水漏れ</span> 
                                    </div>
                                </div>
                                <div class="voice-box-reputation">
                                    <div>評価：</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/star.png" alt="">
                                </div>
                            </div>
                            <div class="voice-box-text">
                                少し前からポタポタ蛇口から漏れ出していて、お願いすることに。電話口で説明してもらえたのでその時点で安心できました。実際に来ていただいて作業も早くて二度目の安心でした。
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-prev swiper-button-prev-voice"></div>
                    <div class="swiper-button-next swiper-button-next-voice"></div>
                </div>
                <div class="case-link-container">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>voice/">もっとみる</a>
                    </div>
            </div>
        </div>
        <div class="faq">
            <div class="content">
                <div class="service-title">
                    <div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo1.png" alt="">
                        クリーンライフに寄せられる
                    </div>
                    <div>よくあるご質問</div>
                    <div>
                        <div class="service-title-arrow"></div>
                        <div>FAQ</div>
                        <div class="service-title-end"></div>
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
                                <div>スタッフの状況にもよりますが、早ければ30分程でお伺いすることが可能です。現場から一番近い営業所のスタッフがお伺いさせていただきます。お急ぎの場合は、お電話でのご依頼がメールよりも早く対応可能です。</div>
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
                                <div>はい、現場の調査を行いお見積もりをさせていただきますが、お見積もりは無料です。簡単な修理で解決する場合もございますが、つまりなどの場合はどこの場所で詰まっているかを判断するために現場調査とさせていただいております。</div>
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
                                <div>修理の程度や箇所にもよりますが簡単な修理であれば30分ほどで終了します。基本的には平均1時間以内で完了することが多いです。</div>
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
                                <div>
                                    現金や銀行振込、各種クレジットカード、コンビニ後払いにも対応しております。
                                    その他にもpaypay決済など柔軟に対応しておりますので一度ご相談頂ければと思います。
                                </div>
                            </div>
                        </div>
                        <div class="faq-box">
                            <div class="faq-q" onclick="faqClick(this)">Q.トイレが詰まりました！料金はいくらぐらいでしょうか？
                                <div class="faq-q-icon">
                                    <div><div></div></div>
                                </div>
                            </div>
                            <div class="faq-a">
                                <div>A</div>
                                <div>簡単なつまりであれば8,800円(税込)のお見積もりですが、症状によって異なります。例えば、マンションの5階に住んでいる方がトイレを詰まらしてしまい、詰まってる場所が分からない場合は各階を調査する必要がございます。</div>
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
                                <div>はい。もちろん可能でございます。他のお客様のご予約状況の都合上お受けできない場合もございますが、お早めにご連絡頂ければ調整致します。</div>
                            </div>
                        </div>
                    </div>
                    <div class="case-link-container">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>faq/">もっとみる</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="service-flow">
        <div class="content">
            <div class="service-title">
                <div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                    クリーンライフの
                </div>
                <div>修理の流れ</div>
                <div>
                    <div class="service-title-arrow"></div>
                    <div>SERVICE FLOW</div>
                    <div class="service-title-end"></div>
                </div>
            </div>
            <div class="service-flow-container">
                <div class="service-flow-box">
                    <picture>
                        <source media="(max-width: 850px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp1.png">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow1.png" alt="">
                    </picture>
                    <div class="service-flow-text">
                        <div>お問い合わせ</div>
                        水回りでお困りの際はお電話、メール、LINEよりお気軽にご相談ください。
                    </div>
                </div>
                <div class="service-flow-arrow"></div>
                <div class="service-flow-box">
                    <picture>
                        <source media="(max-width: 850px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp2.png">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow2.png" alt="">
                    </picture>
                    <div class="service-flow-text">
                        <div>現地調査</div>
                        故障や水漏れ原因を調査します。現地調査は無料で行っておりますのでご安心ください。
                    </div>
                </div>
                <div class="service-flow-box">
                    <picture>
                        <source media="(max-width: 850px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp3.png">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow3.png" alt="">
                    </picture>
                    <div class="service-flow-text">
                        <div>お見積り提示</div>
                        お見積りをご提示して、作業内容とお見積りにご納得いただいてはじめて施工へと移ります。
                    </div>
                </div>
                <div class="service-flow-arrow"></div>
                <div class="service-flow-box">
                    <picture>
                        <source media="(max-width: 850px)" srcset="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow-sp4.png">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/service-flow4.png" alt="">
                    </picture>
                    <div class="service-flow-text">
                        <div>施工・お支払い</div>
                        作業完了後、作業箇所を確認していただき、料金をお支払いただきます。
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="area">
        <div class="content">
            <div class="service-title">
                <div>
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo1.png" alt="">
                    クリーンライフが対応可能な
                </div>
                <div>対応エリア</div>
                <div>
                    <div class="service-title-arrow"></div>
                    <div>AREA</div>
                    <div class="service-title-end"></div>
                </div>
            </div>
            <div class="area-box-container">
                <div class="area-box area-show">
                    <div class="area-q" onclick="areaClick(this);">北海道・東北対応エリア
                        <div class="area-q-icon">
                            <div><div></div></div>
                        </div>
                    </div>
                    <div class="area-a">
                        <div>【北海道】</div>
                        石狩市｜恵庭市｜江別市｜小樽市｜北広島市｜札幌市
                        <div>【宮城県】</div>
                        岩沼市｜塩竈市｜仙台市｜多賀城市｜名取市
                    </div>
                </div>
                <div class="area-box">
                    <div class="area-q" onclick="areaClick(this);">関東対応エリア
                        <div class="area-q-icon">
                            <div><div></div></div>
                        </div>
                    </div>
                    <div class="area-a">
                        <div>【茨城県】</div>
                        石岡市｜潮来市｜稲敷市｜牛久市｜笠間市｜鹿嶋市｜かすみがうら市｜北茨城市｜古河市｜下妻市｜高萩市｜筑西市｜つくば市｜土浦市｜取手市｜那珂市｜坂東市｜常陸太田市｜常陸大宮市｜日立市｜ひたちなか市｜水戸市｜守谷市｜結城市｜龍ケ崎市
                        <div>【栃木県】</div>
                        足利市｜宇都宮市｜小山市｜鹿沼市｜佐野市｜栃木市｜真岡市
                        <div>【群馬県】</div>
                        伊勢崎市｜太田市｜桐生市｜高崎市｜館林市｜藤岡市｜前橋市
                        <div>【埼玉県】</div>
                        上尾市｜朝霞市｜入間郡越生町｜入間郡三芳町｜入間郡毛呂山町｜入間市｜大里郡寄居町｜桶川市｜春日部市｜加須市｜川口市｜川越市｜北足立郡伊奈町｜北葛飾郡杉戸町｜北葛飾郡松伏町｜北本市｜行田市｜久喜市｜熊谷市｜鴻巣市｜越谷市｜児玉郡神川町｜児玉郡上里町｜児玉郡美里町｜さいたま市岩槻区｜さいたま市浦和区｜さいたま市大宮区｜さいたま市北区｜さいたま市桜区｜さいたま市中央区｜さいたま市西区｜さいたま市緑区｜さいたま市南区｜さいたま市見沼区｜坂戸市｜幸手市｜狭山市｜志木市｜白岡市｜草加市｜秩父郡小鹿野町｜秩父郡長瀞町｜秩父郡東秩父村｜秩父郡皆野町｜秩父郡横瀬町｜秩父市｜鶴ヶ島市｜所沢市｜戸田市｜新座市｜蓮田市｜羽生市｜飯能市｜東松山市｜比企郡小川町｜比企郡川島町｜比企郡ときがわ町｜比企郡滑川町｜比企郡鳩山町｜比企郡吉見町｜比企郡嵐山町｜日高市｜深谷市｜富士見市｜ふじみ野市｜本庄市｜三郷市｜南埼玉郡宮代町｜八潮市｜吉川市｜和光市｜蕨市
                        <div>【千葉県】</div>
                        我孫子市｜市川市｜市原市｜印西市｜浦安市｜柏市｜勝浦市｜木更津市｜君津市｜佐倉市｜白井市｜袖ケ浦市｜館山市｜千葉市稲毛区｜千葉市中央区｜千葉市花見川区｜千葉市緑区｜千葉市美浜区｜千葉市若葉区｜東金市｜富里市｜流山市｜習志野市｜成田市｜野田市｜富津市｜船橋市｜松戸市｜茂原市｜八街市｜八千代市｜四街道市
                        <div>【東京都】</div>
                        昭島市｜あきる野市｜足立区｜荒川区｜板橋区｜稲城市｜江戸川区｜青梅市｜大田区｜葛飾区｜北区｜清瀬市｜国立市｜江東区｜小金井市｜国分寺市｜小平市｜狛江市｜品川区｜渋谷区｜新宿区｜杉並区｜墨田区｜世田谷区｜台東区｜立川市｜多摩市｜中央区｜調布市｜千代田区｜中野区｜西多摩郡奥多摩町｜西多摩郡日の出町｜西多摩郡檜原村｜西多摩郡瑞穂町｜西東京市｜練馬区｜八王子市｜羽村市｜東久留米市｜東村山市｜東大和市｜日野市｜府中市｜福生市｜文京区｜町田市｜三鷹市｜港区｜武蔵野市｜武蔵村山市｜目黒区
                        <div>【神奈川県】</div>
                        愛甲郡愛川町｜愛甲郡清川村｜足柄上郡大井町｜足柄上郡開成町｜足柄上郡中井町｜足柄上郡松田町｜足柄上郡山北町｜足柄下郡箱根町｜足柄下郡真鶴町｜足柄下郡湯河原町｜厚木市｜綾瀬市｜伊勢原市｜海老名市｜小田原市｜鎌倉市｜川崎市麻生区｜川崎市川崎区｜川崎市幸区｜川崎市高津区｜川崎市多摩区｜川崎市中原区｜川崎市宮前区｜高座郡寒川町｜相模原市中央区｜横浜市緑区｜横浜市南区｜座間市｜逗子市｜茅ヶ崎市｜中郡大磯町｜中郡二宮町｜秦野市｜平塚市｜藤沢市｜三浦郡葉山町｜三浦市｜南足柄市｜大和市｜横須賀市｜横浜市青葉区｜横浜市旭区｜横浜市泉区｜横浜市磯子区｜横浜市神奈川区｜横浜市金沢区｜横浜市港南区｜横浜市港北区｜横浜市栄区｜横浜市瀬谷区｜横浜市都筑区｜横浜市鶴見区｜横浜市戸塚区｜横浜市中区｜横浜市西区｜横浜市保土ケ谷区
                    </div>
                </div>
                <div class="area-box">
                    <div class="area-q" onclick="areaClick(this);">東海対応エリア
                        <div class="area-q-icon">
                            <div><div></div></div>
                        </div>
                    </div>
                    <div class="area-a">
                        <div>【愛知県】</div>
                        愛西市｜愛知郡東郷町｜海部郡大治町｜海部郡蟹江町｜海部郡飛島村｜あま市｜安城市｜一宮市｜稲沢市｜犬山市｜岩倉市｜大府市｜岡崎市｜尾張旭市｜春日井市｜蒲郡市｜刈谷市｜北設楽郡設楽町｜北設楽郡東栄町｜北設楽郡豊根村｜北名古屋市｜清須市｜江南市｜小牧市｜新城市｜瀬戸市｜高浜市｜田原市｜知多郡阿久比町｜知多郡武豊町｜知多郡東浦町｜知多郡南知多町｜知多郡美浜町｜知多市｜知立市｜津島市｜東海市｜常滑市｜豊明市｜豊川市｜豊田市｜豊橋市｜名古屋市熱田区｜名古屋市北区｜名古屋市昭和区｜名古屋市千種区｜名古屋市天白区｜名古屋市中川区｜名古屋市中区｜名古屋市中村区｜名古屋市西区｜名古屋市東区｜名古屋市瑞穂区｜名古屋市緑区｜名古屋市港区｜名古屋市南区｜名古屋市名東区｜名古屋市守山区｜西尾市｜西春日井郡豊山町｜日進市｜丹羽郡大口町｜丹羽郡扶桑町｜額田郡幸田町｜半田市｜碧南市
                        <div>【岐阜県】</div>
                        揖斐郡池田町｜揖斐郡揖斐川町｜揖斐郡大野町｜恵那市｜大垣市｜大野郡白川村｜海津市｜各務原市｜可児郡御嵩町｜可児市｜加茂郡川辺町｜加茂郡坂祝町｜加茂郡白川町｜加茂郡富加町｜加茂郡東白川村｜加茂郡七宗町｜加茂郡八百津町｜岐阜市｜下呂市｜関市｜多治見市｜土岐市｜中津川市｜羽島郡笠松町｜羽島郡岐南町｜羽島市｜瑞浪市｜瑞穂市｜美濃加茂市｜美濃市｜本巣郡北方町｜本巣市｜山県市｜養老郡養老町
                        <div>【静岡県】</div>
                        磐田市｜御前崎市｜掛川市｜菊川市｜静岡市葵区｜静岡市清水区｜静岡市駿河区｜島田市｜浜松市北区｜浜松市天竜区｜浜松市中区｜浜松市西区｜浜松市浜北区｜浜松市東区｜浜松市南区｜袋井市｜藤枝市｜焼津市
                        <div>【三重県】</div>
                        伊勢市｜員弁郡東員町｜いなべ市｜亀山市｜北牟婁郡紀北町｜桑名郡木曽岬町｜桑名市｜鈴鹿市｜多気郡大台町｜多気郡多気町｜多気郡明和町｜津市｜名張市｜松阪市｜三重郡朝日町｜三重郡川越町｜三重郡菰野町｜南牟婁郡紀宝町｜南牟婁郡御浜町｜四日市市｜度会郡大紀町｜度会郡玉城町｜度会郡南伊勢町｜度会郡度会町
                    </div>
                </div>
                <div class="area-box">
                    <div class="area-q" onclick="areaClick(this);">関西対応エリア
                        <div class="area-q-icon">
                            <div><div></div></div>
                        </div>
                    </div>
                    <div class="area-a">
                        <div>【大阪府】</div>
                        池田市｜泉大津市｜泉佐野市｜和泉市｜茨木市｜大阪狭山市｜大阪市旭区｜大阪市阿倍野区｜大阪市生野区｜堺市北区｜大阪市此花区｜大阪市城東区｜大阪市住之江区｜大阪市住吉区｜大阪市大正区｜大阪市中央区｜大阪市鶴見区｜大阪市天王寺区｜大阪市浪速区｜堺市西区｜大阪市西成区｜大阪市西淀川区｜大阪市東住吉区｜大阪市東成区｜大阪市東淀川区｜大阪市平野区｜大阪市福島区｜大阪市港区｜大阪市都島区｜大阪市淀川区｜貝塚市｜柏原市｜交野市｜門真市｜河内長野市｜岸和田市｜堺市堺区｜堺市中区｜堺市東区｜堺市南区｜堺市美原区｜四條畷市｜吹田市｜摂津市｜泉南郡熊取町｜泉南郡田尻町｜泉南郡岬町｜泉南市｜泉北郡忠岡町｜大東市｜高石市｜高槻市｜豊中市｜豊能郡豊能町｜豊能郡能勢町｜富田林市｜寝屋川市｜羽曳野市｜阪南市｜東大阪市｜枚方市｜藤井寺市｜松原市｜三島郡島本町｜南河内郡河南町｜南河内郡太子町｜南河内郡千早赤阪村｜箕面市｜守口市｜八尾市
                        <div>【京都府】</div>
                        宇治市｜乙訓郡大山崎町｜亀岡市｜京田辺市｜京都市右京区｜京都市上京区｜京都市北区｜京都市左京区｜京都市下京区｜京都市中京区｜京都市西京区｜京都市東山区｜京都市伏見区｜京都市南区｜京都市山科区｜久世郡久御山町｜城陽市｜相楽郡笠置町｜相楽郡精華町｜相楽郡南山城村｜相楽郡和束町｜綴喜郡井手町｜綴喜郡宇治田原町｜向日市｜八幡市
                        <div>【兵庫県】</div>
                        相生市｜明石市｜芦屋市｜尼崎市｜淡路市｜伊丹市｜揖保郡太子町｜小野市｜加古川市｜加古郡稲美町｜加古郡播磨町｜加西市｜加東市｜川西市｜川辺郡猪名川町｜神戸市北区｜神戸市須磨区｜神戸市垂水区｜神戸市中央区｜神戸市長田区｜神戸市灘区｜神戸市西区｜神戸市東灘区｜神戸市兵庫区｜佐用郡佐用町｜三田市｜洲本市｜高砂市｜宝塚市｜西宮市｜西脇市｜姫路市｜三木市｜南あわじ市
                        <div>【滋賀県】</div>
                        近江八幡市｜大津市｜草津市｜甲賀市｜長浜市｜東近江市｜彦根市｜米原市｜守山市｜栗東市
                        <div>【奈良県】</div>
                        生駒郡安堵町｜生駒郡斑鳩町｜生駒郡三郷町｜生駒郡平群町｜生駒市｜宇陀郡曽爾村｜宇陀郡御杖村｜宇陀市｜香芝市｜橿原市｜葛城市｜北葛城郡王寺町｜北葛城郡河合町｜北葛城郡上牧町｜北葛城郡広陵町｜五條市｜御所市｜桜井市｜磯城郡川西町｜磯城郡田原本町｜磯城郡三宅町｜高市郡明日香村｜高市郡高取町｜天理市｜奈良市｜大和郡山市｜大和高田市｜山辺郡山添村｜吉野郡大淀町｜吉野郡上北山村｜吉野郡川上村｜吉野郡黒滝村｜吉野郡下市町｜吉野郡下北山村｜吉野郡天川村｜吉野郡十津川村｜吉野郡野迫川村｜吉野郡東吉野村｜吉野郡吉野町
                        <div>【和歌山県】</div>
                        橋本市｜和歌山市
                    </div>
                </div>
                <div class="area-box">
                    <div class="area-q" onclick="areaClick(this);">中国対応エリア
                        <div class="area-q-icon">
                            <div><div></div></div>
                        </div>
                    </div>
                    <div class="area-a">
                        <div>【岡山県】</div>
                        浅口郡里庄町｜浅口市｜井原市｜岡山市北区｜岡山市中区｜岡山市東区｜岡山市南区｜笠岡市｜倉敷市｜玉野市
                        <div>【山口県】</div>
                        阿武郡阿武町｜岩国市｜宇部市｜大島郡周防大島町｜玖珂郡和木町｜下松市｜熊毛郡上関町｜熊毛郡田布施町｜熊毛郡平生町｜山陽小野田市｜下関市｜周南市｜長門市｜萩市｜光市｜防府市｜美祢市｜柳井市｜山口市
                    </div>
                </div>
                <div class="area-box">
                    <div class="area-q" onclick="areaClick(this);">九州・沖縄対応エリア
                        <div class="area-q-icon">
                            <div><div></div></div>
                        </div>
                    </div>
                    <div class="area-a">
                        <div>【福岡県】</div>
                        朝倉郡筑前町｜朝倉郡東峰村｜朝倉市｜飯塚市｜糸島市｜うきは市｜大川市｜大野城市｜大牟田市｜小郡市｜遠賀郡芦屋町｜遠賀郡岡垣町｜遠賀郡遠賀町｜遠賀郡水巻町｜春日市｜糟屋郡宇美町｜糟屋郡粕屋町｜糟屋郡篠栗町｜糟屋郡志免町｜糟屋郡新宮町｜糟屋郡須惠町｜糟屋郡久山町｜嘉穂郡桂川町｜嘉麻市｜北九州市小倉北区｜北九州市小倉南区｜北九州市戸畑区｜北九州市門司区｜北九州市八幡西区｜北九州市八幡東区｜北九州市若松区｜鞍手郡鞍手町｜鞍手郡小竹町｜久留米市｜古賀市｜田川郡赤村｜田川郡糸田町｜田川郡大任町｜田川郡川崎町｜田川郡香春町｜田川郡添田町｜田川郡福智町｜田川市｜太宰府市｜筑後市｜筑紫野市｜築上郡上毛町｜築上郡築上町｜築上郡吉富町｜那珂川市｜中間市｜直方市｜福岡市早良区｜福岡市城南区｜福岡市中央区｜福岡市西区｜福岡市博多区｜福岡市東区｜福岡市南区｜福津市｜豊前市｜宗像市｜柳川市｜八女市｜行橋市
                        <div>【佐賀県】</div>
                        神埼郡吉野ヶ里町｜佐賀市
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="covid">
        <div class="content">
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
                            <div class="covid-box-title">作業員 出勤前健康チェック</div>
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
        </div>
    </section>
    <section class="coupon coupon-last">
        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon.jpg" alt="" class="coupon-back">
        <div class="content">
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
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/footer-tel.png" alt="">
                                    0120-423-152
                                </a>
                            </div>
                        </div>
                        <div class="coupon-btn-group-new">
                            <a href="https://lin.ee/RqJ6Mk3"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-line.png" alt=""></a>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-mail.png" alt=""></a>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-woman-new.png" class="coupon-woman-new" alt="">
                        </div>
                    </div>
                </div>
                <div class="coupon-pay-container">
                    <picture>
                        <source srcset='<?php echo get_template_directory_uri()?>/assets/image/top/coupon-payment-new-sp.png' media='(max-width: 640px)'>
                        <img src='<?php echo get_template_directory_uri()?>/assets/image/top/coupon-payment-new.png' alt=''>
                    </picture>
                </div>
            </div>
        </div>
    </section>
    <section class="coupon-sp">
        <div class="coupon-sp-content">
            <h1>\ 24時間・365日対応・出張お見積無料 / </h1>
            <a href="tel:0120-423-152" class="coupon-tel-btn"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-tel-btn.png" alt=""></a>
            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-web-sp.png" class="coupon-web-sp" alt="">
            <div class="coupon-btn-group-sp">
                <div class="coupon-btn-group-sp-content">
                    <p>\最短<span>30秒</span>でご返信/</p>
                    <a href="https://lin.ee/RqJ6Mk3"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-line-sp.png" alt=""></a>
                    <p>\専門スタッフが<span>即対応</span>！/</p>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/"><img src="<?php echo get_template_directory_uri()?>/assets/image/top/btn-mail-sp.png" alt=""></a>
                </div>
                <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-woman-new-sp.png" class="coupon-woman-new-sp" alt="">
            </div>
            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-payment-new-sp.png" class="coupon-payment-new-sp" alt="">
        </div>
    </section>
    <section class="off">
        <div class="content">
            <img class="_pc" src="<?php echo get_template_directory_uri()?>/assets/image/top/off-img.png" alt="">
            <img class="_sp" src="<?php echo get_template_directory_uri()?>/assets/image/top/off-img-sp.png" alt="">
        </div>
    </section>
    <?php get_footer();?>