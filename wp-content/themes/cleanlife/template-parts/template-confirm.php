<?php
/* 
Template Name: confirm
*/
get_header();
?>
<style type="text/css">
    header, .standby,  .footer-foot{display: none;}
    .copyright{display: block !important;}
    .lds-ring {
    display: none;
    position: relative;
    width: 14px;
    height: 14px;
}
button{width: 260px;
    height: 60px;
    color: white;
    background-color: #183494;
    font-family: Kozuka Gothic Pro B;
    border: none;
    font-size: 21px;
    border: 3px solid #183494;
    border-radius: 999px;
    transition: all .4s ease; cursor: pointer;}
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
    <link rel="stylesheet" href="<?php echo get_template_directory_uri()?>/assets/css/confirm.css">
   
    

    <section class="confirm">
        <img src="<?php echo get_template_directory_uri()?>/assets/image/logo.png" alt="">
        <div class="header-text">
            送信前にご相談・お問い合わせ内容をご確認ください。<br>
            お間違えがないようでしたら「送信する」をクリックしてください。
        </div>
        <table>
            <tr>
                <td>お名前：</td>
                <td><?php echo $_COOKIE['name'];?></td>
            </tr>
            <tr>
                <td>フリガナ：</td>
                <td><?php echo $_COOKIE['reading'];?></td>
            </tr>
            <tr>
                <td>電話番号：</td>
                <td><?php echo $_COOKIE['phone'];?></td>
            </tr>
            <tr>
                <td>郵便番号：</td>
                <td><?php echo $_COOKIE['postal'];?></td>
            </tr>
            <tr>
                <td>都道府県：</td>
                <td><?php echo $_COOKIE['prefecture'];?></td>
            </tr>
            <tr>
                <td>市区町村：</td>
                <td><?php echo $_COOKIE['address'];?></td>
            </tr>
            <tr>
                <td>建物名称：</td>
                <td><?php echo $_COOKIE['building'];?></td>
            </tr>
            <tr>
                <td>メールアドレス：</td>
                <td><?php echo $_COOKIE['email'];?></td>
            </tr>
            <tr>
                <td>お問い合わせ内容：</td>
                <td><?php echo $_COOKIE['content'];?></td>
            </tr>
        </table>
              <button type="button" value=""  class="submit-btn" id="submit_btn">送信する
            <div class="lds-ring"><div></div><div></div><div></div></div></button>
    </section>
    <footer>
        @Copyright クリーンライフ All rights reserved.
    </footer>


    <script>
        $=jQuery;
        $("#submit_btn").click(function(){
        $(".lds-ring").addClass("active");
        $(".submit_btn").attr("disabled",true);
        jQuery.ajax({
         type : "post",
         dataType : "json",
         url : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
         data : {action:'send_contact_main'},
         success: function(response) {
            $(".submit_btn").attr("disabled",false);
            $(".lds-ring").removeClass("active");
            if(response.type == "success") {
            window.location.href = '<?php echo get_permalink(103); ?>';
            } else {
                alert("Error Occured. Please try again.");
          }
         }
      });
        })

    </script>
</body>
</html>