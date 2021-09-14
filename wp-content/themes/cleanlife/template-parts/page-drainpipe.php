<?php
/* 
Template Name: 排水管の水漏れ・つまり
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
    <section class="service-banner">
        <div class="drainpipe-banner">
            <div class="content">
                <div class="service-current-page-sp">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a> > サービス＆料金 > 排水管の水漏れ・つまり
                </div>
                <div class="service-use">
                    <div class="service-use-box">出張見積無料</div>
                    <div class="service-use-box">キャンセル無料</div>
                    <div class="service-use-box">深夜早朝割増なし</div>
                    <div class="service-use-box">休日料金無料</div>
                </div>
                <h1>
                    <div class="banner-title-drainpipe"><img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/service-title-drainpipe.png" alt="">
                    排水管<span>の</span>水漏れ・つまり<span class="banner-title-spec">なら</span></div>
                    <div class="banner-title-common">クリーンライフ</div><span>に</span>お任せ!
                </h1>
                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/94.png" alt="" class="banner-rate">
                <div class="service-ranking">
                    <div class="service-ranking-l">
                        <div class="service-ranking-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/ranking1.png" alt="">
                            <div>※実施委託先：日本トレンドリサーチ<br>
                                2019年12月実施：サイトのイメージ調査</div>
                        </div>
                        <div class="service-ranking-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/ranking2.png" alt="">
                            <div>※実施委託先：日本トレンドリサーチ<br>
                                2019年12月実施：サイトのイメージ調査</div>
                        </div>
                        <div class="service-ranking-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/ranking3.png" alt="">
                            <div>※実施委託先：日本トレンドリサーチ<br>
                                2019年12月実施：サイトのイメージ調査</div>
                        </div>
                    </div>
                    <div class="service-ranking-r">
                        <div class="service-ranking-men">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/ranking-men.png" alt="">
                            <div class="serivce-ranking-text">私たちに<br>
                                お任せください!</div>
                        </div>
                        <div class="banner-pay-container">
                            <div class="banner-pay-top">
                                <div>基本<br>
                                    料金
                                </div>
                                <div>3<span>,</span>300<span>円</span></div>
                            </div>
                            <div class="banner-pay-bottom">
                                <div class="banner-payment">
                                    <div>各種クレジットカード対応</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment1.png" alt="">
                                </div>
                                <div class="banner-payment">
                                    <div>コンビニ後払い対応</div>
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment2.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="service-amount">
        <div class="content">
            <div class="banner-amount">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/amount.png" alt="">
                <div>大まかなの見積り金額がご確認いただけます</div>
                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/amount.png" alt="">
            </div>
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
    <div class="l-wrap">
        <div class="service-current-page-pc">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a> > サービス＆料金 > 排水管の水漏れ・つまり
        </div>
        <main class="l-main area">
            
            <section class="drainpipe-question question">
                <h2>
                    <div>こんな<span class="drainpipe-question-title">排水管のトラブル</span>で</div>
                    お困りではありませんか？
                </h2>
                <div class="question-container">
                    <div class="question-box">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            排水管が詰まっている
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            ディスポーザーが詰まっている
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            グリストラップが詰まっている
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            排水口から悪臭がする
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            排水口の水はけが悪い
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            シンクに水が溜まってしまう
                        </div>
                    </div>
                    <div class="question-box">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            水が逆流する
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            排水管がひび割れ・破損している
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            給水管から水漏れしている
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            トラップから水漏れしている
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            排水口から異音がする
                        </div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/asking-drop.png" alt="">
                            水が溢れている
                        </div>
                    </div>
                </div>
            </section>
            <section class="ask-us service-ask-us">
                <div class="ask-us-img">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/ranking-men.png" alt="">
                    <div class="ask-us-title"><div>排水管<span>の</span>トラブル</div><span>なら</span>なんでも<br>
                        クリーンライフ<span>に</span>ご相談ください<div class="last-dot">。</div></div>
                </div>
                <div class="ask-us-text">排水管のトラブルはなんでもご相談ください。水漏れやつまり、部品交換修理などクリーンライフでは排水管に関わるあらゆるトラブルを解決いたします。漏水場所が判明しない場合でも水漏れ箇所の特定からご対応することが可能です。お困りごとがありましたら是非クリーンライフにご相談ください。</div>
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
                    <div class="clean-table-title">排水管の水漏れ・つまりの修理料金表</div>
                    <table class="clean-table">
                        <tr>
                            <td>作業内容</td>
                            <td>修理時間目安</td>
                            <td>修理料金目安</td>
                        </tr>
                        <tr>
                            <td>排水管の詰まり（軽度なつまり）</td>
                            <td>30～60分</td>
                            <td>5,500円</td>
                        </tr>
                        <tr>
                            <td>排水管の詰まり(中程度の詰まり)</td>
                            <td>30～120分</td>
                            <td>16,500円</td>
                        </tr>
                        <tr>
                            <td>排水管の詰まり(高程度なつまり)td>
                            <td>30～120分</td>
                            <td>27,500円</td>
                        </tr>
                        <tr>
                            <td>排水管の水漏れ</td>
                            <td>30～120分</td>
                            <td>3,300円</td>
                        </tr>
                       
                    </table>
                    <div class="clean-table-notice">上記作業料金・時間は目安です。<br>
                        状況や部品の種類/仕様によって料金変動します。<br>
                        作業スタッフが現場でトラブル状況を確認の上、<br class="clean-table-br">最終お見積をご提示いたします。
                    </div>
                </div>
                <!--<div class="clean-subtitle">排水管トラブルの作業費用一例</div>
                <div class="clean-box-container">
                    <div class="clean-box">
                        <div class="clean-box-title">排水管の修理</div>
                        <div class="clean-box-subtitle">（名古屋市 K様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>8,800</span>円</div>
                            <div>部品代 <span>10,000</span>円</div>
                            <div>TAX <span>2,130</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>24,230</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>21,230<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box">
                        <div class="clean-box-title">排水管の修理</div>
                        <div class="clean-box-subtitle">（世田谷区 Y様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>13,200</span>円</div>
                            <div>部品代 <span>10,000</span>円</div>
                            <div>TAX <span>2,570</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>29,070</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>26,070<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box">
                        <div class="clean-box-title">排水管の修理</div>
                        <div class="clean-box-subtitle">（横浜市 A様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>5,500</span>円</div>
                            <div>部品代 <span>3,300</span>円</div>
                            <div>TAX <span>1,130</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>13,230</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>10,230<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="clean-box">
                        <div class="clean-box-title">排水管の修理</div>
                        <div class="clean-box-subtitle">（堺市 S様）</div>
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                        <div class="clean-box-content">
                            <div>基本料金 <span>3,300</span>円</div>
                            <div>作業料金 <span>16,500</span>円</div>
                            <div>部品代 <span>10,000</span>円</div>
                            <div>TAX <span>2,900</span>円</div>
                            <div class="clean-box-content-plus">+</div>
                        </div>
                        <div class="clean-box-content clean-box-content-result">合計<span>32,700</span>円</div>
                        <div class="clean-box-result">
                            <div class="clean-box-discount">
                                <div>WEB割引</div>
                                <div>3,000円</div>
                            </div>
                            <div class="clean-box-result01">
                                <div class="clean-box-result-arrow">⇒</div>
                                <div>29,700<span class="clean-box-result-yen">円(税込)</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider-clean-content">
                    <div class="swiper-container swiper-container-clean clean-box-container">
                        <div class="swiper-wrapper">
                            <div class="clean-box swiper-slide">
                                <div class="clean-box-title">排水管の修理</div>
                                <div class="clean-box-subtitle">（名古屋市 K様）</div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                                <div class="clean-box-content">
                                    <div>基本料金 <span>3,300</span>円</div>
                                    <div>作業料金 <span>8,800</span>円</div>
                                    <div>部品代 <span>10,000</span>円</div>
                                    <div>TAX <span>2,130</span>円</div>
                                    <div class="clean-box-content-plus">+</div>
                                </div>
                                <div class="clean-box-content clean-box-content-result">合計<span>24,230</span>円</div>
                                <div class="clean-box-result">
                                    <div class="clean-box-discount">
                                        <div>WEB割引</div>
                                        <div>3,000円</div>
                                    </div>
                                    <div class="clean-box-result01">
                                        <div class="clean-box-result-arrow">⇒</div>
                                        <div>21,230<span class="clean-box-result-yen">円(税込)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clean-box swiper-slide">
                                <div class="clean-box-title">排水管の修理</div>
                                <div class="clean-box-subtitle">（世田谷区 Y様）</div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                                <div class="clean-box-content">
                                    <div>基本料金 <span>3,300</span>円</div>
                                    <div>作業料金 <span>22,000</span>円</div>
                                    <div>部品代 <span>0</span>円</div>
                                    <div>TAX <span>2,450</span>円</div>
                                    <div class="clean-box-content-plus">+</div>
                                </div>
                                <div class="clean-box-content clean-box-content-result">合計<span>10,950</span>円</div>
                                <div class="clean-box-result">
                                    <div class="clean-box-discount">
                                        <div>WEB割引</div>
                                        <div>3,000円</div>
                                    </div>
                                    <div class="clean-box-result01">
                                        <div class="clean-box-result-arrow">⇒</div>
                                        <div>26,070<span class="clean-box-result-yen">円(税込)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clean-box swiper-slide">
                                <div class="clean-box-title">排水管の修理</div>
                                <div class="clean-box-subtitle">（横浜市 A様）</div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                                <div class="clean-box-content">
                                    <div>基本料金 <span>3,300</span>円</div>
                                    <div>作業料金 <span>5,500</span>円</div>
                                    <div>部品代 <span>3,300</span>円</div>
                                    <div>TAX <span>1,130</span>円</div>
                                    <div class="clean-box-content-plus">+</div>
                                </div>
                                <div class="clean-box-content clean-box-content-result">合計<span>13,230</span>円</div>
                                <div class="clean-box-result">
                                    <div class="clean-box-discount">
                                        <div>WEB割引</div>
                                        <div>3,000円</div>
                                    </div>
                                    <div class="clean-box-result01">
                                        <div class="clean-box-result-arrow">⇒</div>
                                        <div>10,230<span class="clean-box-result-yen">円(税込)</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clean-box swiper-slide">
                                <div class="clean-box-title">排水管の修理</div>
                                <div class="clean-box-subtitle">（堺市 S様）</div>
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/drainpipe-clean.png" alt="">
                                <div class="clean-box-content">
                                    <div>基本料金 <span>3,300</span>円</div>
                                    <div>作業料金 <span>16,500</span>円</div>
                                    <div>部品代 <span>10,000</span>円</div>
                                    <div>TAX <span>2,900</span>円</div>
                                    <div class="clean-box-content-plus">+</div>
                                </div>
                                <div class="clean-box-content clean-box-content-result">合計<span>32,700</span>円</div>
                                <div class="clean-box-result">
                                    <div class="clean-box-discount">
                                        <div>WEB割引</div>
                                        <div>3,000円</div>
                                    </div>
                                    <div class="clean-box-result01">
                                        <div class="clean-box-result-arrow">⇒</div>
                                        <div>29,700<span class="clean-box-result-yen">円(税込)</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                <div class="clean-notice">※状況や部品の種類/仕様によって料金変動します。</div>-->
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
            
            
            <section class="case">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                            排水管トラブルの
                        </div>
                        <div>施工事例</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>CASE</div>
                            <div class="service-title-arrow"></div>
                        </div>
                    </div>
                    <div class="case-box-container">
                    <div id="case_box_main">
                        <?php 
       $wpb_all_query = new WP_Query(array('post_type'=>'case', 'cat'=> 8, 'post_status'=>'publish',  'posts_per_page'=>5)); 

       if ( $wpb_all_query->have_posts() ) :
       while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); 
       
       ?>
                            <div class="case-box">
                            <?php
if (has_post_thumbnail()){
    the_post_thumbnail('post-thumbnail', ['class' => 'attachment-post-thumbnail size-post-thumbnail wp-post-image case-img']);
}

else {
    echo '<img class="case-img" src="'. get_template_directory_uri().'/assets/image/toilet/toilet-case.png" alt="">';
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
                            <?php
                            endwhile;
        else :
        echo '<p>投稿はありません！</p>';

        endif;
        ?>    
                        </div>
                        <!--<div class="case-link-container">
                            <a href="">その他の事例も見る</a>
                        </div>-->
                    </div>
                </div>
            </section>
            <section class="faq">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo1.png" alt="">
                            排水管トラブルに関する
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
                        <!--<div class="case-link-container">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>faq/">もっとみる</a>
                        </div>-->
                    </div>
                </div>
            </section>
            <section class="service-flow">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                            排水管トラブルの
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
            <section class="voice drainpipe-voice">
                <div class="service-main-content">
                    <div class="service-title">
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                            排水管トラブルに関する
                        </div>
                        <div>お客様の声</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>VOICE</div>
                            <div class="service-title-arrow"></div>
                        </div>
                    </div>
                    <div class="case-link-container review-post-container">
                        <a class="review-post" onclick="reviewShow();">口コミを投稿する</a>
                    </div>
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
                                <a href="https://cleanlife-center.com/privacy/" target="_blank">個人情報の取り扱い</a>
                                <span>に同意する</span>
                                 <div class="error"></div>
                            </div>
                            </div>
                            <div class="form-check-box">
                                <button class="submit" id="reviewSubmit" value="投稿する">投稿する<div class="lds-ring"><div></div><div></div><div></div></div></button>
                            </div>
                        </form>
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
                            <!--<div class="voice-box swiper-slide">
                                <div class="voice-box-title">排水管の水漏れ・つまり</div>
                                <div class="voice-box-header">
                                    <div class="voice-box-head">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar1.png" alt="">
                                        <div>
                                            口コミタイトルが入ります口コミタイトル
                                            <span>ひとみ/排水管の水漏れ</span> 
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
                                    排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります。
                                </div>
                            </div>
                            <div class="voice-box swiper-slide">
                                <div class="voice-box-title">排水管の水漏れ・つまり</div>
                                <div class="voice-box-header">
                                    <div class="voice-box-head">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar2.png" alt="">
                                        <div>
                                            口コミタイトルが入ります口コミタイトル
                                            <span>ひとみ/排水管の水漏れ</span> 
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
                                    排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります。
                                </div>
                            </div>
                            <div class="voice-box swiper-slide">
                                <div class="voice-box-title">排水管の水漏れ・つまり</div>
                                <div class="voice-box-header">
                                    <div class="voice-box-head">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar3.png" alt="">
                                        <div>
                                            口コミタイトルが入ります口コミタイトル
                                            <span>ひとみ/排水管の水漏れ</span> 
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
                                    排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります。
                                </div>
                            </div>
                            <div class="voice-box swiper-slide">
                                <div class="voice-box-title">排水管の水漏れ・つまり</div>
                                <div class="voice-box-header">
                                    <div class="voice-box-head">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/avatar/avatar4.png" alt="">
                                        <div>
                                            口コミタイトルが入ります口コミタイトル
                                            <span>ひとみ/排水管の水漏れ</span> 
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
                                    排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります排水管トラブルの口コミが入ります。
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-prev swiper-button-prev-voice"></div>
                        <div class="swiper-button-next swiper-button-next-voice"></div>
                    </div>-->
                   <div class="case-link-container">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>voice/">もっとみる</a>
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
                        <div>対応エリア</div>
                        <div>
                            <div class="service-title-arrow"></div>
                            <div>AREA</div>
                            <div class="service-title-arrow"></div>
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
            <section class="howto">
                <div class="service-main-content">
                    <div class="howto-title">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/howto-title.png" alt="">
                        排水管の水漏れ・つまりの原因
                    </div>
                    <div class="howto-description">
                        家で使用した水を流すために不可欠なのが排水管。水道管のように床下や屋外、地下を巡り公共の下水道に排水を流してくれます。そのため、家の排水が集まり様々なトラブルが発生します。また、マンション等の集合住宅か一軒家か、専有部分か共有部分かによっても対応が変わるため注意が必要です。次では、排水管から水が流れない、水が漏れている、臭っていると3つに分けて原因をご紹介します。ご自身の現在の状況がどれに当てはまるのかを確認しましょう。
                    </div>
                    <div class="howto-box-title">よくある排水管トラブルの原因</div>
                    <div class="howto-box-container">
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/howto1.png" alt="">
                            <div class="howto-reason-box-title">水が流れない</div>
                            <div>排水管のトラブルの原因として1番考えられるのが、トイレから流れる汚水やキッチン等から流れる雑排水による詰まりです。トイレであればトイレットペーパーや吐しゃ物、紙おむつやおもちゃを流してしまい詰まっている可能性があり、キッチンや浴室からは油や髪の毛等が汚れとして溜まっている可能性も考えられます。排水口から排水管までを清掃しても解消しない場合には業者に相談しましょう。</div>
                        </div>
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/howto2.png" alt="">
                            <div class="howto-reason-box-title">水が漏れている</div>
                            <div>排水管の水漏れトラブルとして考えられるのは、パイプや接続部分の老朽化です。10年以上経っている排水管は、経年劣化によりパッキンやパイプ本体が傷んでいたり緩んでいる可能性があります。また、上に書いた排水管のつまりが原因で接続部分から水漏れしている可能性もあります。水漏れしている場合には状況を説明して業者に相談し、点検してもらうことをおすすめします。</div>
                        </div>
                        <div class="howto-reason-box">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/howto3.png" alt="">
                            <div class="howto-reason-box-title">排水口や排水桝が臭う</div>
                            <div>排水口や排水管、排水桝から臭いがする場合には、排水桝に汚れやゴミが溜まっている可能性があります。排水枡とは汚水を下水道に流すための中継地点のようなものです。ここに汚れやゴミが溜まっていると臭いの原因となり、排水口が臭うことがあります。専有部分内に排水桝がある場合には開けて確認し、汚れやゴミ、草の根っこがある場合には除去しましょう。それでも解消されなければ業者に相談することをおすすめします。</div>
                        </div>
                    </div>
                    <div class="howto-title howto-title-sec">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/service/howto-title.png" alt="">
                        排水管トラブルのご家庭で出来る対処法
                    </div>
                    <div class="howto-description">
                        排水管のトラブルの対処法をつまり・水漏れ・臭う場合と3つの原因に分けてご紹介します。排水管のトラブルは対処するのはもちろん大切ですが、日ごろから気を付けて使用をし、トラブルを予防することが大切です。もし流れが悪かったり排水に違和感がある場合には、症状が悪化する前に原因を確認して対処をして業者に相談しましょう。悪化する前に相談することで症状も料金も最低限で済ませることができます。
                    </div>
                    <div class="howto-box-container">
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">排水管が詰まっている</div>
                            <div class="howto-box-content">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/howto4.png" alt="">
                                <div>排水口から水があふれ出ていたり水の流れが悪いようであれば、排水管の詰まりが原因と考えられます。まずはパイプクリーナー等を使用して排水管内を清掃してみましょう。排水管から汚れを除去することで詰まりが解消されることがあります。清掃しても詰まりが解消されない場合には異物の詰まり等も考えられます。異物を取り除くのはご自身では難しいため、状況を説明して業者に依頼しましょう。</div>
                            </div>
                        </div>
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">排水管から水漏れしている</div>
                            <div class="howto-box-content">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/howto5.png" alt="">
                                <div>排水管が詰まっておらず、床や排水管から水漏れがある場合には接続部分やパイプの老朽化、緩みが原因として考えられます。まずは水漏れしている箇所を見つけて水漏れしている近くの接続部分に緩みがないかを確認し、締めなおしましょう。それでも解消しない場合にはパッキンやパイプの劣化が原因の可能性があります。ご自身でも交換は可能ですが、不安な場合には業者に依頼しましょう。</div>
                            </div>
                        </div>
                        <div class="howto-deal-box">
                            <div class="howto-deal-box-title">排水管や排水桝が臭う</div>
                            <div class="howto-box-content">
                                <img src="<?php echo get_template_directory_uri()?>/assets/image/service/drainpipe/howto6.png" alt="">
                                <div>排水管や排水桝から臭いがする場合には、排水管内や排水桝内に汚れやゴミが溜まっていることが原因として考えられます。排水管はパイプクリーナーを使用して清掃し、専有部分に排水桝がある場合には開けて中を確認をしてゴミや汚れ、草の根っこがある場合には除去しましょう。それでも解消されない場合には、重度の汚れや共有部分が原因として考えられます。ご自身では無理に対処せずに業者に相談しましょう。</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="service-bottom">
                それでも解決しない場合はお気軽に<br>
                クリーンライフにご相談ください！
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
            }  else {
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
        });

</script>
<?php get_footer();?>