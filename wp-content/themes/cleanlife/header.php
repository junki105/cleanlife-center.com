<!DOCTYPE html>
<html lang="ja">
<head>
<!-- anti-flicker snippet (recommended)  -->
<style>.async-hide { opacity: 0 !important} </style>
<script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'async-hide','dataLayer',4000,
{'OPT-KDS938T':true});</script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-197828083-1', 'auto');
ga('require', 'OPT-KDS938T'); 
</script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <!-- Google Tag Manager -->
    <!-- End Google Tag Manager -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.8.4/swiper-bundle.min.css" integrity="sha512-aMup4I6BUl0dG4IBb0/f32270a5XP7H1xplAJ80uVKP6ejYCgZWcBudljdsointfHxn5o302Jbnq1FXsBaMuoQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TQRPP38');</script>
    
    
    <!-- Site fevicon icons -->
    <link rel="icon" href="<?php echo get_template_directory_uri()?>/assets/image/favicon.ico" sizes="32x32" />
    <link rel="icon" href="<?php echo get_template_directory_uri()?>/assets/image/favicon.ico" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri()?>/assets/image/apple-touch-icon.jpg" />
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri()?>/assets/image/favicon.ico" />
	<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "7dq8elgon0");
</script>
</head>
<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TQRPP38"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <header>
        <div class="content">
            <div class="header-head">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
					<?php if ( is_front_page() ): ?>
					  <h1><img src="<?php echo get_template_directory_uri()?>/assets/image/logo.png" alt=""></h1>
					<?php else : ?>
					  <p><img src="<?php echo get_template_directory_uri()?>/assets/image/logo.png" alt=""></p>
					<?php endif; ?>
				</a>
                <div class="header-btn">
                    <a href="https://lin.ee/RqJ6Mk3" target="_blank" rel="noopener noreferrer" class="header-line">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/line.png" alt="">
                        <div>LINEで<br>
                            無料相談</div>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/" class="header-mail">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/mail.png" alt="">
                        <div>メールで<br>
                            無料相談</div>
                    </a>
                    <a href="tel:0120-423-152" class="header-tel">
                        <div>お見積無料 24時間365日 年中無休・迅速対応</div>
                        <div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel.png" alt="">
                            0120-423-152
                        </div>
                    </a>
                </div>
                <div class="header-btn01">
                    <a href="" class="peace-mind">
                        <div>“安心業者宣言“</div>
                        <div>水道局指定工事店</div>
                    </a>
                    <a href="tel:0120-423-152" class="header-sp-tel">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/tel.png" alt="">
                        <div>電話で<br>
                            無料相談</div>
                    </a>
                    <div id="menu_btn" onclick="event.stopPropagation(); myFunction(this)">
                        <div id="menu_icon">
                            <div class="bar1"></div>
                            <div class="bar2"></div>
                            <div class="bar3"></div>
                        </div>
                        <div id="menu_btn_text">MENU</div>
                    </div>
                </div>
            </div>
            <nav>
                <div class="navbtn">
                    <a class="navLink" href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
                </div>
                <div class="navbtn navService">
                    <div class="navLink" onclick="event.stopPropagation(); navService()">サービス・料金</div>
                    <div id="service-submenu">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>toilet/">トイレの水漏れ・つまり</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>bath/">お風呂の水漏れ・つまり</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>kitchen/">キッチンの水漏れ・つまり</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>washroom/">洗面所の水漏れ・つまり</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>waterheater/">給湯器の修理・交換</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>drainpipe/">排水管の水漏れ・つまり</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>waterpipe/">水道管の水漏れ・つまり</a>
                    </div>
                </div>
                <div class="navbtn">
                    <a class="navLink" href="<?php echo esc_url( home_url( '/' ) ); ?>case/">施工事例</a>
                </div>
                <div class="navbtn">
                    <a class="navLink" href="<?php echo esc_url( home_url( '/' ) ); ?>voice/">お客様の声</a>
                </div>
                <div class="navbtn">
                    <a class="navLink" href="<?php echo esc_url( home_url( '/' ) ); ?>flow/">修理の流れ</a>
                </div>
                <div class="navbtn">
                    <a class="navLink" href="<?php echo esc_url( home_url( '/' ) ); ?>area/">対応エリア</a>
                </div>
                <div class="navbtn">
                    <a class="navLink" href="<?php echo esc_url( home_url( '/' ) ); ?>faq/">よくあるご質問</a>
                </div>
            </nav>
        </div>
        <div id="menu">
            <div class="menu-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a></div>
            <div class="menu-link" id="menu_link01"><div onclick="menuService();">サービス・料金</div></div>
            <div id="menu-service-container">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>toilet/" class="menu-service">トイレの水漏れ・つまり</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>bath/" class="menu-service">お風呂の水漏れ・つまり</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>kitchen/" class="menu-service">キッチンの水漏れ・つまり</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>washroom/" class="menu-service">洗面所の水漏れ・つまり</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>waterheater/" class="menu-service">給湯器の修理・交換</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>drainpipe/" class="menu-service">排水管の水漏れ・つまり</a>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>waterpipe/" class="menu-service">水道管の水漏れ・つまり</a>
            </div>
            <div class="menu-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>case/">施工事例</a></div>
            <div class="menu-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>voice/">お客様の声</a></div>
            <div class="menu-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>flow/">修理の流れ</a></div>
            <div class="menu-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>area/">対応エリア</a></div>
            <div class="menu-link"><a href="<?php echo esc_url( home_url( '/' ) ); ?>faq/">よくあるご質問</a></div>
            <div class="menu-footer">
                <div class="menu-tel-container">
                    <div class="menu-current-time">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/white-clock.png" alt="">
                        <div class="menu-current"><span class="current-time-all">13：25</span>現在、お電話いただけましたら即日修理対応可能です！</div>
                    </div>
                    <div class="menu-tel-main">
                        <div class="menu-tel-text">24時間・365日対応
                            <div>お見積無料</div>
                        </div>
                        <a href="tel:0120-423-152">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel.png" alt="">
                            0120-423-152
                        </a>
                    </div>
                </div>
                <div class="menu-line-container">
                    <a href="https://lin.ee/RqJ6Mk3" target="_blank" rel="noopener noreferrer" class="menu-line">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/line.png" alt="">
                        LINEで無料相談
                    </a>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact/" class="menu-mail">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/mail.png" alt="">
                        メールで無料相談
                    </a>
                </div>
            </div>
        </div>
    </header>
    <section class="standby">
        <div class="content">
            <div class="standby-title">作業員待機中</div>
            <div class="standby-text">
                <div>
                    <span id="currentTime">13時25分</span>現在、近くの作業員が<span>最短30分〜</span>でお伺いできます。
                </div>
            </div>
        </div>
    </section>