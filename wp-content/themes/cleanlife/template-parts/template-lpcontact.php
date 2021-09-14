<?php
/* 
Template Name: lp_contact
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

</style>

    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/contact.css">
    <div class="contact-content">
        <section class="banner-section">
            <div class="logo">
                <a href="<?php echo home_url( '/' ); ?>"><img src="<?php echo get_template_directory_uri()?>/assets/image/contact/contact_logo.png" alt=""></a>
            </div>
            <div class="banner-main-img">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/contact/banner_main_img.png" alt="">
            </div>
            <div class="inquiry-btn">ご相談・お問い合わせ</div>
            <div class="free">
                お見積・ご相談・訪問調査は、すべて無料です。<br>
                どんなに小さな事でも構いません。お気軽にお問い合わせください。
            </div>
        </section>
       <section class="contact-main contact-back">
        <!--<form id="cform">
            <div class="header-text">
                メールでのお問い合わせは下記よりお送りください。
            </div>
            <div class="input-container">
                <div class="input-information necessary">
                    お名前
                </div>
                <div>
                <input type="text" name="name" class="required" id="name" placeholder="お名前をご記入ください">
                <div class="error"></div>
            </div>
            </div>
            <div class="input-container">
                <div class="input-information necessary">
                    フリガナ
                </div>
                  <div>
                <input type="text" name="reading"  class="required" id="reading" placeholder="フリガナをご記入ください">
                 <div class="error"></div>
             </div>
            </div>
            <div class="input-container">
                <div class="input-information arbitary">
                    電話番号
                </div>
                <input type="text" name="phone" id="phone" placeholder="000-0000-0000" class="num-input">
            </div>
            <div class="input-container">
                <div class="input-information arbitary">
                    郵便番号
                </div>
                <div class="position-input">
                    <input type="text" name="postal" id="postal" placeholder="1234567">
                    <a class="search_zipcode" href="javascript:void(0);">郵便番号を調べる</a>
                </div>
            </div>
            <div class="input-container">
                <div class="div-space">
                </div>
                <div class="prefecture-input">
                    都道府県
                    <select name="prefecture"  id="prefecture">
                        <option value="">選択する</option>
                        <option value="北海道">北海道</option>
                        <option value="青森県">青森県</option>
                        <option value="岩手県">岩手県</option>
                        <option value="宮城県">宮城県</option>
                        <option value="秋田県">秋田県</option>
                        <option value="山形県">山形県</option>
                        <option value="福島県">福島県</option>
                        <option value="茨城県">茨城県</option>
                        <option value="栃木県">栃木県</option>
                        <option value="群馬県">群馬県</option>
                        <option value="埼玉県">埼玉県</option>
                        <option value="千葉県">千葉県</option>
                        <option value="東京都">東京都</option>
                        <option value="神奈川県">神奈川県</option>
                        <option value="新潟県">新潟県</option>
                        <option value="富山県">富山県</option>
                        <option value="石川県">石川県</option>
                        <option value="福井県">福井県</option>
                        <option value="山梨県">山梨県</option>
                        <option value="長野県">長野県</option>
                        <option value="岐阜県">岐阜県</option>
                        <option value="静岡県">静岡県</option>
                        <option value="愛知県">愛知県</option>
                        <option value="三重県">三重県</option>
                        <option value="滋賀県">滋賀県</option>
                        <option value="京都府">京都府</option>
                        <option value="大阪府">大阪府</option>
                        <option value="兵庫県">兵庫県</option>
                        <option value="奈良県">奈良県</option>
                        <option value="和歌山県">和歌山県</option>
                        <option value="鳥取県">鳥取県</option>
                        <option value="島根県">島根県</option>
                        <option value="岡山県">岡山県</option>
                        <option value="広島県">広島県</option>
                        <option value="山口県">山口県</option>
                        <option value="徳島県">徳島県</option>
                        <option value="香川県">香川県</option>
                        <option value="愛媛県">愛媛県</option>
                        <option value="高知県">高知県</option>
                        <option value="福岡県">福岡県</option>
                        <option value="佐賀県">佐賀県</option>
                        <option value="長崎県">長崎県</option>
                        <option value="熊本県">熊本県</option>
                        <option value="大分県">大分県</option>
                        <option value="宮崎県">宮崎県</option>
                        <option value="鹿児島県">鹿児島県</option>
                        <option value="沖縄県">沖縄県</option>
                    </select>
                </div>
            </div>
            <div class="input-container">
                <div class="div-space">
                </div>
                <div class="address-input">
                    市区町村
                    <input type="text" name="address"  id="address">
                </div>
            </div>
            <div class="input-container">
                <div class="div-space">
                </div>
                <div class="address-input">
                    建物名称
                    <input type="text" name="building" id="building">
                </div>
            </div>
            <div class="input-container">
                <div class="input-information necessary">
                    メールアドレス
                </div>
                 <div>

                <input type="email" name="email"  class="required_email" id="eamil1" placeholder="メールアドレスをご記入ください">
                 <div class="error"></div>
             </div>
            </div>
            <div class="input-container">
                <div class="input-information necessary">
                    メールアドレス(確認)
                </div>
                <div>
                <input type="email" name="email_repeat"  class="required_email" id="email2" placeholder="確認のためもう一度メールアドレスをご記入ください">
                 <div class="error"></div>
             </div>
            </div>
            <div class="input-container textarea-input-container">
                <div class="input-information necessary">
                    お問い合わせ内容
                </div>
                <div>
                <textarea name="content"  class="required" id="content"  rows="9"></textarea>
                 <div class="error"></div>
             </div>
            </div>
            <div class="input-container textarea-input-container">
                <div class="input-information necessary" >
                    送信前確認
                </div>
                <div class="confirm-text">
                    ■個人情報の取り扱い<br>
                    当社では、送信フォームより送られてきたお客様の個人情報をお預かりする場合があります。お預かりした個人情報は、厳重な管理の下、個人情報の漏洩／流用／改ざんなどの防止に細心の注意を払っております。当社では、お預かりした個人情報を、正当な事由のある場合を除き第三者へ提供または開示することはありません。ただし、法律の適用を受ける場合や法的強制力のある請求の場合はその限りではございません。また、個人情報を第三者に提供する場合は、契約による義務付けの方法により、その第三者からの漏洩および再提供を防止いたします。
                </div>
            </div>
            <div class="input-container textarea-input-container">
                <div class="div-space">
                </div>
                <div class="check-div">
                    <input type="checkbox" name="terms"  class="required_terms" id="check" onclick="myFunction()">
                    <label for="check">上記内容に同意されましたらチェックをしてください。</label>
                </div>
            </div>
            <div>
            <button type="button" value=""  class="submit-btn-disable" disabled="true" id="submit_btn">確認画面へ
            <div class="lds-ring"><div></div><div></div><div></div></div></button>
        </div>
    </form>-->
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
        error = 0;
        $("#submit_btn").on("click",function(){
            error=0;
            $(".required").each(function(index, element) {
            if($(this).val() == '' || $(this).val() == null){
            error = 1;  
            $(this).siblings(".error").html("この項目は必須です");  
            } else {
            $(this).siblings(".error").html("");
            }
            }); 


           
            $(".required_email").each(function(index, element) {
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{1,50}$/i;
            if($(this).val() == '' || $(this).val() == null){
            error = 1;  
            $(this).siblings(".error").html("この項目は必須です"); 
            } else if(!testEmail.test($(this).val())){
            error = 1;
            $(this).siblings(".error").html("有効なメールIDを入力してください");   
            }  else {
            $(this).siblings(".error").html("");
            }
           });
           
            if(error == 0){
                //$('#cform').submit();
                //var formdata = $('#cform').serialize();
                Cookies.set('name', $('#name').val(), { expires: 1 });
                Cookies.set('reading',$('#reading').val(), { expires: 1 });
                Cookies.set('phone',$('#phone').val(), { expires: 1 });
                Cookies.set('postal',$('#postal').val(), { expires: 1 });
                Cookies.set('prefecture', $("#prefecture").val(), { expires: 1 });
                Cookies.set('address', $('#address').val(), { expires: 1 });
                Cookies.set('building', $('#building').val(), { expires: 1 });
                Cookies.set('email', $('#eamil1').val(), { expires: 1 });
                Cookies.set('content', $('#content').val(), { expires: 1 });
                window.location.href = "https://cleanlife-center.com/confirm/";
            }

        })

        $(".search_zipcode").on("click",function(){
           AjaxZip3.zip2addr('fax-01','','prefecture','address-01');
        })




    </script>
    <script src="<?php echo get_template_directory_uri()?>/assets/js/contact.js"></script>
    
<?php //get_footer(); ?>