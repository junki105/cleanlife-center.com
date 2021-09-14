jQuery( document ).ready( function () {
    jQuery('.wpcf7-submit').click(function () {
        // We remove the error to avoid duplicate errors
        jQuery('.error').remove();
        // We create a variable to store our error message
        var errorMsg = jQuery('<span class="error">あなたのメールアドレスは一致しません。</span>');
        // Then we check our values to see if they match
        // If they do not match we display the error and we do not allow form to submit
        if (jQuery('.email-01').find('input').val() !== jQuery('.email-confirm').find('input').val()) {
            errorMsg.insertAfter(jQuery('.email-confirm').find('input'));
            return false;
        } else {
        // If they do match we remove the error and we submit the form
            jQuery('.error').remove();
            return true;
        }
    });
} );