<?php
/* 
Template Name: Thank you
*/
?>
<?php 
get_header(); ?>
<style type="text/css">
    header, .standby,  .footer-foot{display: none;}
</style>

    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/contact(thanks).css">
    <div class="content">
        <section class="banner-section">
            <div class="logo">
                <a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri()?>/assets/image/contact/contact_logo.png" alt=""></a>
            </div>
            <div class="banner-main-img">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/contact/contact_thanks.png" alt="">
            </div>
            <div class="free">
                担当者がお問い合わせ内容を確認し、<br>
                早急に折り返しご連絡を差し上げますので少々お待ちください。
            </div>
        </section>
        <div class="contact-back">
            <a class="submit-btn" href="<?php echo home_url( '/' ); ?>">TOPへ戻る</a>
        </div>
        
    </div>
    <footer>
        @Copyright クリーンライフ All rights reserved.
    </footer>
</body>
</html>