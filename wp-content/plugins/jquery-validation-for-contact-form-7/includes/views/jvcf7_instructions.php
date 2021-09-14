<div class="dcinstructions">
    <div class="instruct-head">        
        How to Add Validation Class to Contact Form Fields ?
    </div>

    <div class="instruct-content hidden">        
        <p>While add fields to your contact form, you can add our validation class to those fields. You can check the snapshot below. For available validation class, you can check it here <a target="_blank" href="https://dnesscarkey.com/jquery-validation/validation-methods/">https://dnesscarkey.com/jquery-validation/validation-methods/</a></p>
        <img src="<?php echo plugins_url('jquery-validation-for-contact-form-7/includes/assets/img/add_validation.png') ?>" />
    </div>
</div>


<div class="dcinstructions">
    <div class="instruct-head">        
        From where can i change the error message (Pro Version) ?
    </div>

    <div class="instruct-content hidden">        
        <p>From Error Message Tab, you can change the error messages. You can also keep the error message blank if you don't want to show any error message. </p>
    </div>
</div>

<div class="dcinstructions">
    <div class="instruct-head">        
        How to use Custom Code Validation  (Pro Version) ?
    </div>

    <div class="instruct-content hidden">        
        <ul>
            <li>Please goto Custom Code Tab</li>
            <li>From there click on <strong>Add Custom Code</strong> button at the top right side.</li>
            <li>Using the form, give reference name, add custom codes and invalid message.</li>
            <li>If you want the code to be used only once, please select Yes for One time use. For that you must provide the field name you will use this custom code validation on.</li>
            <li>Click On Save. Once saved you will get the listing.</li>
            <li>From the listing, you will get the Class Name. Please place the class name in your field and also make sure field name matches with the one you provided.</li>
            <li>That's all. You are done. If you need you can also create multiple custom code validations.</li>
        </ul>
    </div>
</div>

<div class="dcinstructions">
    <div class="instruct-head">        
        Why & How to use Custom RegEx Validation  (Pro Version) ?
    </div>

    <div class="instruct-content hidden">        
        <p>
            If you need custom validation rules that are not provided with our plugin, then you can create your own validation rules using Custom RegEx validation. However, you should have some knowledge of RegEx. Or you can contact us if you need any help.
        </p>
        <ul>
            <li>Please goto Custom RegEx Validation</li>
            <li>From there click on <strong>Add Custom RegEx</strong> button at the top right side.</li>
            <li>Using the form, give reference name, add regEx and invalid message.</li>
            <li>Click On Save. Once saved you will get the listing.</li>
            <li>From the listing, you will get the Class Name. Please place the class name in your field </li>
            <li>That's all. You are done.</li>
        </ul>
    </div>
</div>

<div class="dcinstructions">
    <div class="instruct-head">        
        I am still having trouble, what support methods are available ?
    </div>

    <div class="instruct-content hidden">        
        <p>
            We are open with all the possible support channel. Please use one that most suits you.
        </p>
        <ul>
            <li><a target="_blank" href="https://dineshkarki.com.np/forums/forum/jquery-validation-for-contact-form">Support Forum For Pro Version Users</a></li>
            <li><a target="_blank" href="https://wordpress.org/support/plugin/jquery-validation-for-contact-form-7/">Support Forum For Lite Version Users</a></li>
            <li><a href="https://dineshkarki.com.np/contact" target="_blank">Using Our Contact Form</a></li>
            <li><a href="https://www.facebook.com/Dnesscarkey-77553779916" target="_blank">Send Us Msg via Facebook</a></li>
        </ul>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.instruct-head').click(function(){
            jQuery(this).next('.instruct-content').slideToggle('medium');
        })
    });
</script>