<?php
/* 
Template Name: contact
*/
?>
<?php 
get_header(); ?>
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<style type="text/css">
    header, .standby,  .footer-foot{display: none;}
    .copyright{display: block !important;}
    .lds-ring {
    display: none;
    position: relative;
    width: 14px;
    height: 14px;
}
.error{color:#e60000; font-size: 14px;}
.lds-ring div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 14px;
    height: 14px;
    margin: 2px 8px;
    border: 2px solid #fff;
    border-radius: 50%;
    animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: #fff transparent transparent transparent;
}
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
button:disabled {
  background: rgb(191 191 191 / 10%);
  border: 2px solid #d6d6d6;
}

</style>

    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/contact.css">
    <div class="contact-content">
        <section class="banner-section">
            <div class="logo">
                <a href="<?php echo home_url( '/' ); ?>"><img src="<?php echo get_template_directory_uri()?>/assets/image/contact/contact_logo.png" alt=""></a>
            </div>
            <div class="inquiry-btn">ご相談・お問い合わせ</div>
            <div class="free">
                お見積・ご相談・訪問調査は、すべて無料です。<br>
                どんなに小さな事でも構いません。お気軽にお問い合わせください。
            </div>
        </section>
       <section class="contact-main contact-back">
    <?php echo do_shortcode( '[contact-form-7 id="12" title="contact-form"]' ); ?>
        </section>
    </div>
    <footer class="">
        @Copyright クリーンライフ All rights reserved.
    </footer> 
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
    <script>
        var btn = document.getElementById("submit_btn");
        btn.disabled = true;

        $=jQuery;
        $(".required_terms").on("change",function(){
            if($(this).is(":checked")){
                $("#submit_btn").attr("disabled",false);
            } else {
                $("#submit_btn").attr("disabled",true);
            }
        })

        $("#postal").on("input",function(){
           AjaxZip3.zip2addr('fax-01','','prefecture','address-01');
        })

    </script>
    <script src="<?php echo get_template_directory_uri()?>/assets/js/contact.js"></script>
    <script src="//vxml4.plavxml.com/sited/ref/ctrk/1565-g-90713-104467" async></script>
<?php wp_footer(); ?>