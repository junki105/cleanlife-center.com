(function($) {
    $.fn.ilj_editor = function() {
        var elem = this;

        var tipsoConfig = {
            width: '',
            maxWidth: '200',
            useTitle: true,
            delay: 100,
            speed: 500,
            background: '#32373c',
            color: '#eeeeee',
            size: 'small'
        };

        var Box = {
            keywords: [],

            inputField: $(elem).find('input[name="ilj_linkdefinition_keys"]'),

            errorMessage: $('<div class="error-feedback"></div>'),

            keywordInputInfo: $('<span class="dashicons dashicons-info"></span>').css({'margin-top': '10px'}).iljtipso({
                content: $(
                    '<ul>'+
                        '<li>' + ilj_editor_translation.howto_case + '</li>'+
                        '<li>' + ilj_editor_translation.howto_keyword + '</li>'+
                    '</ul>'
                ).css({
                    'list-style-type': 'square', 'list-style-position:': 'outside', 'text-align': 'left', 'padding': '0px', 'margin': '10px 20px'
                }),
                delay: 100, speed: 500, background: '#32373c', color: '#eeeeee', size: 'small', position: 'left'
            }),

            gapInputInfo: $('<span class="dashicons dashicons-info"></span>').iljtipso({
                content: $(
                    '<p>'+ ilj_editor_translation.howto_gap + '</p>'
                ).css({
                    'text-align': 'left', 'padding': '0px', 'margin': '10px'
                }),
                delay: 100, speed: 500, background: '#32373c', color: '#eeeeee', size: 'small', position: 'left', tooltipHover: true
            }),

            inputGui: $(
            	'<div class="input-gui">'+
            	'   <input type="text" name="keyword" placeholder="' + ilj_editor_translation.placeholder_keyword + '"/>'+
                '   <a class="button add-keyword">' + ilj_editor_translation.add_keyword + '</a>' +
            	'   <div class="gaps">'+
                '       <h4>' + ilj_editor_translation.headline_gaps + '</h4> '+
            	'       <input type="number" name="count" placeholder="0"/>'+
                '       <a class="button add-gap">' + ilj_editor_translation.add_gap + '</a>'+
                '       <h5>' + ilj_editor_translation.gap_type + '</h5>'+                
                '       <div class="gap-types">'+
                '           <div class="type min"><label for="gap-min" class="tip" title="' + ilj_editor_translation.type_min + '"><input type="radio" name="gap" value="min" id="gap-min"/><span class="dashicons dashicons-upload"></span></label></div>'+
                '           <div class="type exact active"><label for="gap-exact" class="tip" title="' + ilj_editor_translation.type_exact + '"><input type="radio" name="gap" value="exact" checked="checked" id="gap-exact"/><span class="dashicons dashicons-migrate"></span></label></div>'+
                '           <div class="type max"><label for="gap-max" class="tip" title="' + ilj_editor_translation.type_max + '"><input type="radio" name="gap" value="max" id="gap-max"/><span class="dashicons dashicons-download"></span></label></div>'+
                '       </div>'+
                '       <div class="gap-hints">'+
                '           <div class="hint min" id="min"><p class="howto">' + ilj_editor_translation.howto_gap_min + '</p></div>'+
                '           <div class="hint exact active" id="exact"><p class="howto">' + ilj_editor_translation.howto_gap_exact + '</p></div>'+
                '           <div class="hint max" id="max"><p class="howto">' + ilj_editor_translation.howto_gap_max + '</p></div>'+
                '       </div>'+
            	'   </div>'+
                '   <a class="show-gaps">&raquo; ' + ilj_editor_translation.insert_gaps + '</a>'+
            	'</div>'
            ),

            keywordViewGui: $(
                '<div class="keyword-view-gui">'+
                    '<h4>' + ilj_editor_translation.headline_configured_keywords + '</h4>'+
                    '<ul class="keyword-view" role="list"></ul>'+
                '</div>'
            ),

            helpMessage: $(
                '<p class="meta">' +
                '   <a href="https://internallinkjuicer.com/docs/editor/?utm_source=editor&utm_medium=help&utm_campaign=plugin" rel="noopener" target="_blank" class="help"><span class="dashicons dashicons-editor-help"></span>' + ilj_editor_translation.get_help + '</a>'+
                '</p>'
            ),

            init: function() {

            	var that = this;

                this.inputField.css('display', 'none').parent('p').hide();
                this.clearError();

                elem.find('.inside').append(this.errorMessage, this.inputGui, this.keywordViewGui, this.helpMessage);
                elem.find('h2').prepend($('<i/>').addClass('icon icon-ilj'));

                this.keywordViewGui.find('ul.keyword-view').sortable({
                    opacity: 0.5,
                    helper: "clone",
                    forceHelperSize: true,
                    forcePlaceholderSize: true,
                    cursor: "move",
                    placeholder: "placeholder",

                    update: function(event, ui) {
                        that.reorderKeywords();
                    }
                });
                this.keywordViewGui.find('ul.keyword-view').disableSelection();

                this.initKeywords();
                this.syncGui();
                this.inputGui.find('.tip').iljtipso(tipsoConfig);

                this.inputGui.find('.add-keyword').after(this.keywordInputInfo);
                this.inputGui.find('.add-gap').after(this.gapInputInfo);

                this.inputGui.on('keypress', 'input[name="keyword"]', function(e) {
                    if (e.keyCode === 13) {
                        that.inputGui.find('a.add-keyword').click();
                    }
                    return e.keyCode != 13;
                });

                this.inputGui.on('keypress', 'input[name="count"]', function(e) {
                    if (e.keyCode === 13) {
                        that.inputGui.find('a.add-gap').click();
                    }
                    return e.keyCode != 13;
                });

                this.inputGui.on('keypress', 'input[name="gap"]', function(e) {
                    if (e.keyCode === 13) {
                        that.inputGui.find('input[name="count"]').focus();
                    }
                    return e.keyCode != 13;
                });

                this.inputGui.on('click', 'a.add-keyword', function(e) {
                	e.preventDefault();

                	                    var keyword_input = $(this).siblings('input[name="keyword"]');

                	if (keyword_input.val().indexOf(',') !== -1) {
                		var keywords = keyword_input.val().split(',');
                		keywords.forEach(function(keyword, index) {
                        	keyword_value = that.sanitizeKeyword(keyword);
                            valid = that.validateKeyword(keyword_value);

                            if (!valid.is_valid) {
                                return;
                            }

                            that.addKeyword(keyword_value);
                    	});
                	} else {
                        keyword_value = that.sanitizeKeyword(keyword_input.val());
                        valid = that.validateKeyword(keyword_value);

                        if (!valid.is_valid) {
                            that.setError(valid.message);
                            return;
                        }

                        that.addKeyword(keyword_value);
                	}

                	keyword_input.val('');
                    that.clearError();
                	that.syncGui();
                	that.syncField();
                });

                this.inputGui.on('click', '.show-gaps', function(e) {
                   e.preventDefault();
                   $(this).hide();
                   that.inputGui.find('.gaps').show();
                });

                this.inputGui.on('click', 'a.add-gap', function(e) {
                	e.preventDefault();
                    var $count_field = $(this).siblings('input[name="count"]');
                	var gap_type = $(this).siblings('.gap-types').find('input[name="gap"]:checked').val();
                	var gap_value = $count_field.val();
                	var old_value = that.inputGui.find('input[name="keyword"]').val();
                	var gap_placeholder = '';

                	if (/^\d+$/.test(gap_value) === false) {
                		return;
                	}

                	switch(gap_type) {
                		case "min":
                			gap_placeholder = '{+'+gap_value+'}';
                			break;
                		case "max":
                			gap_placeholder = '{-'+gap_value+'}';
                			break;
                		default:
                			gap_placeholder = '{'+gap_value+'}';
                	}
                    $count_field.val('');
                	that.inputGui.find('input[name="keyword"]').val(old_value+gap_placeholder);
                	that.inputGui.find('input[name="keyword"]').focus();
                });

                 this.inputGui.on('change', 'input[name="gap"]', function() {
                    var selected = $(this).val();
                    that.inputGui.find('.gap-types .type').removeClass('active');
                    that.inputGui.find('.gap-types .type.'+selected).addClass('active');
                    that.inputGui.find('.gap-hints .hint').removeClass('active');
                    that.inputGui.find('.gap-hints .hint.'+selected).addClass('active');
                 });

                this.keywordViewGui.on('click', '.keyword a.remove', function(e) {
                	e.preventDefault();
                	var index = $(this).parent('.keyword').data('id');
                	that.keywords.splice(index, 1);
                	that.syncGui();
                	that.syncField();
                });

            },

            initKeywords: function() {
            	that = this;
            	var input_data = $('<textarea/>').text(this.inputField.val()).html(); 
                if (input_data != '' && input_data != null) {
                    var input_keywords = input_data.split(',');
                    input_keywords.forEach(function(keyword, index) {
                        that.addKeyword(keyword);
                    });
                }
            },

            addKeyword: function(keyword) {
            	this.keywords.push(keyword);
            },

            sanitizeKeyword: function(keyword) {
                var keyword_sanitized = keyword
                            .replace(/\s*\{\s*/gu, " {")
                            .replace(/\s*\}\s*/gu, "} ")
                            .replace(/\s{2,}/gu, " ")
                            .replace(/^\s+|\s+$/gu, "")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;");
                return keyword_sanitized;
            },

             validateKeyword: function(keyword) {

                var status = {
                    is_valid: false,
                    message: "Unknown error",
                };
                var min_length = 2;
                var keyword_valid_check = keyword
                                            .replace(/\{.*?\}/gu, "")
                                            .replace(/\s/gu, "");

                for(var i = 0; i < this.keywords.length; i++) {
                    if (keyword.toLowerCase() == this.keywords[i].toLowerCase()) {
                        status.message = ilj_editor_translation.message_keyword_exists;
                        return status;
                    }
                }

                if (keyword_valid_check === "") {
                    status.message = ilj_editor_translation.message_no_keyword;
                    return status;
                }

                if (keyword_valid_check.length < min_length) {
                    status.message = ilj_editor_translation.message_length_not_valid;
                    return status;
                } 

                if (/(\s?\{[+-]*\d+\}\s?){2,}/.test(keyword)) {
                    status.message = ilj_editor_translation.message_multiple_placeholder;
                    return status;
                }

                status.is_valid = true;
                status.message = "";

                                return status;
             },

            syncField: function() {
            	this.inputField.val(this.keywords.join(','));
            },

            syncGui: function() {
            	var that = this;
                that.keywordViewGui.find('ul.keyword-view li').remove();
                if (this.keywords.length > 0) {
                    this.keywords.forEach(function (keyword, index) {
                        that.keywordViewGui.find('ul.keyword-view').append($(that.renderKeyword(keyword, index)));
                    });
                    that.keywordViewGui.find('.tip').iljtipso(tipsoConfig);
                } else {
                    that.keywordViewGui.find('ul.keyword-view').append($('<li>' + ilj_editor_translation.no_keywords + '</li>'));
                }
            },

            reorderKeywords: function() {
                order = [];

                this.keywordViewGui.find('li').each(function() {
                   var id = $(this).data('id');

                   if (id === undefined) {
                       return;
                   }

                   order.push(id);
                });

                new_keywords = [];

                $.each(order, function(key, position) {
                    new_keywords.push(that.keywords[position]);
                });

                that.keywords = new_keywords;
                that.syncGui();
                that.syncField();

                return true;
            },

            renderKeyword: function(keyword, index) {
                keyword_print = keyword
                                    .replace(/\{(\d+)\}/g, '<span class="exact tip" title="' + ilj_editor_translation.gap_hover_exact + ' $1">$1</span>')
                                    .replace(/\{\-(\d+)\}/g, '<span class="max tip" title="' + ilj_editor_translation.gap_hover_max + ' $1">$1</span>')
                                    .replace(/\{\+(\d+)\}/g, '<span class="min tip" title="' + ilj_editor_translation.gap_hover_min + ' $1">$1</span>');
            	return '<li class="keyword" data-id="'+index+'"><a class="dashicons dashicons-dismiss remove"></a>'+keyword_print+'</li>';
            },

            setError: function(message) {
                this.errorMessage.html(message);
                this.errorMessage.show();
            },

            clearError: function() {
                this.errorMessage.html('');
                this.errorMessage.hide();
            },
        };

        Box.init();
    };
}(jQuery));
jQuery(document).ready(function() {
    jQuery('#ilj_linkdefinition').ilj_editor();
});