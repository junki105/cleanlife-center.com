// VALIDATION CODES
jQuery(document).ready(function(){
	
	jQuery('.wpcf7-validates-as-required').addClass('required');
	jQuery('.wpcf7-email').addClass('email');
	jQuery('.wpcf7-checkbox.wpcf7-validates-as-required input').addClass('required');
	jQuery('.wpcf7-radio input').addClass('required');
	
	jQuery('form.wpcf7-form').each(function(){
		jQuery(this).addClass(scriptData.jvcf7_default_settings.jvcf7_invalid_field_design);
		jQuery(this).addClass(scriptData.jvcf7_default_settings.jvcf7_show_label_error);
		jQuery(this).validate({
			errorPlacement: function(error, element) {
	            if (element.is(':checkbox') || element.is(':radio')){
	            	error.insertAfter(jQuery(element).parent().parent().parent());
	            } else {
	            	error.insertAfter(element);
	            }
         	}
		});
	});

	jQuery('.wpcf7-form-control.wpcf7-submit').click(function(e){ 
		$jvcfpValidation 	=	jQuery(this).parents('form');		
		if (!jQuery($jvcfpValidation).valid()){
			e.preventDefault();
			$topErrorPosition 		= jQuery('.wpcf7-form-control.error').offset().top;
			$topErrorPosition		= parseInt($topErrorPosition) - 100;
			jQuery('body, html').animate({scrollTop:$topErrorPosition}, 'normal');
		}
	});	
});

jQuery.validator.addMethod("email", 
    function(value, element) {
        return this.optional(element) || /^[+\w-\.]+@([\w-]+\.)+[\w-]{2,10}$/i.test(value);
    },"Please enter a valid email address"
);

jQuery.validator.addMethod("letters_space", function(value, element) {
  return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
}, "Letters and space only");