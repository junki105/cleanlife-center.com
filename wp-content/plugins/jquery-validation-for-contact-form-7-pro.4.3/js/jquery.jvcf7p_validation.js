// VALIDATION CODES
jQuery(document).ready(function(){
	
	jQuery('.wpcf7-validates-as-required').addClass('required');
	jQuery('.wpcf7-email').addClass('email');
	jQuery('.wpcf7-checkbox.wpcf7-validates-as-required input').addClass('required');
	
	jQuery('form.wpcf7-form').each(function(){
		jQuery(this).addClass(jvcf7p_optionValues['jvcf7p_invalid_field_design']);
		jQuery(this).addClass(jvcf7p_optionValues['jvcf7p_show_label_error']);
		jQuery(this).validate();
	});

	jQuery('.wpcf7-form-control.wpcf7-submit').click(function(e){ 
		$jvcfpValidation 	=	jQuery(this).parents('form');		
		if (!jQuery($jvcfpValidation).valid()){
			e.preventDefault();
		}
	});
	
	jQuery('[class*="JVmin-"]').each(function(){ // Min
		allClasser = jQuery(this).attr('class');
		processingClass 		= allClasser.match(/JVmin-[0-9]+/);
		var processingClassSplit	= processingClass.toString().split("-");
		jQuery(this).attr('min',processingClassSplit[1]);
	});
	
	jQuery('[class*="JVmax-"]').each(function(){ // Max
		allClasser = jQuery(this).attr('class');
		processingClass 		= allClasser.match(/JVmax-[0-9]+/);
		var processingClassSplit	= processingClass.toString().split("-");
		jQuery(this).attr('max',processingClassSplit[1]);
	});
	
	jQuery('[class*="JVminlength-"]').each(function(){ // Minlength
		allClasser = jQuery(this).attr('class');
		processingClass 		= allClasser.match(/JVminlength-[0-9]+/);
		var processingClassSplit	= processingClass.toString().split("-");
		jQuery(this).rules( "add", {minlength: processingClassSplit[1]});
	});
	
	jQuery('[class*="JVmaxlength-"]').each(function(){ // Maxlength
		allClasser = jQuery(this).attr('class');
		processingClass 			= allClasser.match(/JVmaxlength-[0-9]+/);
		var processingClassSplit	= processingClass.toString().split("-");
		jQuery(this).rules( "add", {maxlength: processingClassSplit[1]});
	});
	
	jQuery('[class*="JVrangelength-"]').each(function(){ // rangelength
		allClasser = jQuery(this).attr('class');
		processingClass 			= allClasser.match(/JVrangelength-[0-9]+-[0-9]+/);
		var processingClassSplit	= processingClass.toString().split("-");
		jQuery(this).rules( "add", {rangelength: [processingClassSplit[1],processingClassSplit[2] ]});
	});
	
	jQuery('[class*="JVrange-"]').each(function(){ // range
		allClasser = jQuery(this).attr('class');
		processingClass 			= allClasser.match(/JVrange-[0-9]+-[0-9]+/);
		var processingClassSplit	= processingClass.toString().split("-");
		jQuery(this).rules( "add", {range: [processingClassSplit[1],processingClassSplit[2] ]});
	});
	
	jQuery('[class*="JVequalTo-"]').each(function(){ // range
		allClasser = jQuery(this).attr('class');
		processingClass 			= allClasser.match(/JVequalTo-[a-zA-Z0-9-_]+/);
		var processingClassSplit	= processingClass.toString().split("To-");
		jQuery(this).rules( "add", {equalTo: "[name="+processingClassSplit[1]+"]" });
	});
	
	jQuery('[class*="JVextension-"]').each(function(){ // range
		allClasser = jQuery(this).attr('class');
		processingClass 				= allClasser.match(/JVextension-[a-zA-Z0-9-_]+/);
		var processingClassSplit		= processingClass.toString().split("extension-");
		var processingExtensionSplit	= processingClassSplit[1].toString().split("_");
		var extnesions 					= processingExtensionSplit.join('|');
		jQuery(this).rules( "add", {extension: extnesions });		
	});
		
	jQuery('[class*="JVrequireGroup-"]').each(function(){ // range
		allClasser = jQuery(this).attr('class');
		processingClass 				= allClasser.match(/JVrequireGroup-[a-zA-Z0-9-_]+/);
		var processingClassSplit		= processingClass.toString().split("requireGroup-");
		var processingCountClassSplit	= processingClassSplit[1].toString().split("_");
		jQuery(this).addClass(processingCountClassSplit[1]);
		jQuery(this).rules( "add", {require_from_group: [processingCountClassSplit[0], "."+processingCountClassSplit[1]] });		
	});
	
});

//

jQuery.validator.addMethod("email2", 
    function(value, element) {
        return /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i.test(value);
    },"Please enter a valid email address"
);

jQuery.validator.addMethod("letters_space", function(value, element) {
  return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
}, "Letters and space only");

jQuery.validator.addMethod("customCode", 
    function(value, element) {
       if (jQuery.inArray(value.toLowerCase(), jvcf7p_custom_code) == -1){
		   	return false;
	   } else {
			return true;   
	   }
    },"Please enter valid code. Codes are Case Sensitive."
);

jQuery.extend(jQuery.validator.messages, {
    required: jvcf7p_optionValues['jvcf7p_msg_required'],
    email: jvcf7p_optionValues['jvcf7p_msg_email'],
	email2: jvcf7p_optionValues['jvcf7p_msg_email'],
    url: jvcf7p_optionValues['jvcf7p_msg_url'],
    date: jvcf7p_optionValues['jvcf7p_msg_date'],
    dateISO: jvcf7p_optionValues['jvcf7p_msg_dateISO'],
    number: jvcf7p_optionValues['jvcf7p_msg_number'],
    digits: jvcf7p_optionValues['jvcf7p_msg_digits'],
	alphanumeric: jvcf7p_optionValues['jvcf7p_msg_alpha_numeric'],
	lettersonly: jvcf7p_optionValues['jvcf7p_msg_letters_only'],
	letters_space: jvcf7p_optionValues['jvcf7p_msg_letters_space'],
    creditcard: jvcf7p_optionValues['jvcf7p_msg_creditcard'],
	phoneUS: jvcf7p_optionValues['jvcf7p_msg_phoneUS'],
    equalTo: jvcf7p_optionValues['jvcf7p_msg_equalTo'],
    extension: jvcf7p_optionValues['jvcf7p_msg_extension'],
	require_from_group: jvcf7p_optionValues['jvcf7p_msg_require_from_group'],
    maxlength: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_maxlength']),
    minlength: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_minlength']),
    rangelength: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_rangelength']),
    range: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_range']),
    max: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_max']),
    min: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_min']),
	iban: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_iban']),
	bic: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_bic']),
	customCode: jQuery.validator.format(jvcf7p_optionValues['jvcf7p_msg_custom_code'])
});