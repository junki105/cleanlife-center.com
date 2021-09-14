<?php
/* 
Template Name: よくある質問
*/
get_header();?>

    <section class="page-title">
        <div class="content">
            <div class="current-page">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>
                > よくある質問
            </div>
            <div class="title">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                <h1>よくある質問</h1>
                <div class="subtitle"><div>FAQ</div></div>
            </div>
        </div>
    </section>
    <div class="l-wrap">
        <main class="l-main area">
            <section class="faq">
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
                            <div class="faq-q" onclick="faqClick(this)">Q.トイレが詰まりました！料金はいくらぐらいでしょうか？
                                <div class="faq-q-icon">
                                    <div><div></div></div>
                                </div>
                            </div>
                            <div class="faq-a">
                                <div>A</div>
                                <div>簡単なつまりであれば8,800円（税込）のお見積もりですが、症状によって異なります。
例えば、マンションの5階に住んでいる方がトイレを詰まらしてしまい、詰まってる場所が分からない場合は各階を調査する必要がございます。</div>
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
                        <div class="faq-box">
                            <div class="faq-q" onclick="faqClick(this)">Q.水漏れなどが酷い時など、修理にきてくれるまでの間応急処置など必要でしょうか？
                                <div class="faq-q-icon">
                                    <div><div></div></div>
                                </div>
                            </div>
                            <div class="faq-a">
                                <div>A</div>
                                <div>コールセンターのスタッフ、または担当のスタッフにトラブルの状況を教えていただければ、駆けつけるまでの対処法などをお伝えいたします。
到着までの間もご安心ください。</div>
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