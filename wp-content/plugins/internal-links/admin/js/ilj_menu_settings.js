function ilj_dynamicSelect(elem, action, searchResults) {
    jQuery(elem).ilj_select2({
        width: '50%',
        minimumInputLength: 3,
        templateSelection: function(state) {
            return "ID: " + state.id;
        },
        ajax: {
            url: ajaxurl,
            type: "POST",
            data: function(params) {
                return {
                    action: action,
                    search: params.term,
                    per_page: searchResults,
                    page: (params.page || 1)
                }
            },
            processResults: function(data) {
                if (data.length === 0) {
                    return false;
                }
                more = true;
                if (data.length < searchResults) {
                    more = false;
                }
                return data_new = {
                    "results": data,
                    "pagination": {
                        "more": more
                    }
                };
            }
        },
        language: {
            errorLoading: function() {
                return ilj_select2_translation.error_loading;
            },
            inputTooShort: function(args) {
                var remainingChars = args.minimum - args.input.length;
                return ilj_select2_translation.input_too_short + ': ' + remainingChars;
            },
            loadingMore: function() {
                return ilj_select2_translation.loading_more;
            },
            noResults: function() {
                return ilj_select2_translation.no_results;
            },
            searching: function() {
                return ilj_select2_translation.searching;
            }
        }
    });
}
jQuery(document).ready(function() {
    jQuery('#ilj_settings_field_editor_role, #ilj_settings_field_index_generation, #ilj_settings_field_whitelist, #ilj_settings_field_taxonomy_whitelist,#ilj_settings_field_limit_taxonomy_list, #ilj_settings_field_keyword_order, #ilj_settings_field_no_link_tags').ilj_select2({
        minimumResultsForSearch: 10,
        width: '50%'
    });

    ilj_dynamicSelect('#ilj_settings_field_blacklist', 'ilj_search_posts', 20);
    ilj_dynamicSelect('#ilj_settings_field_term_blacklist', 'ilj_search_terms', 20);

    jQuery('#ilj_settings_field_multiple_keywords').on('change', function() {
        var $inverse_setting_field = jQuery('#ilj_settings_field_links_per_page, #ilj_settings_field_links_per_target');
        if (this.checked) {
            $inverse_setting_field.each(function() {
                jQuery(this).closest('tr').find('th').addClass('inactive');
            });

            $inverse_setting_field.prop('disabled', true);
        } else {
            $inverse_setting_field.each(function() {
                jQuery(this).closest('tr').find('th').removeClass('inactive');
            });

            $inverse_setting_field.prop('disabled', false);
        }
    });

    jQuery(document).ready(function() {
        var $multiple_keywords = jQuery('#ilj_settings_field_multiple_keywords');
        if (!$multiple_keywords.length) {
            return;
        }
        if ($multiple_keywords[0].checked) {
            var $inverse_setting_field = jQuery('#ilj_settings_field_links_per_page, #ilj_settings_field_links_per_target');

            $inverse_setting_field.each(function() {
                jQuery(this).closest('tr').find('th').addClass('inactive');
            });t
        }

    });

    var tipsoConfig = {
            width: '',
            maxWidth: '200',
            useTitle: true,
            delay: 100,
            speed: 500,
            background: '#32373c',
            color: '#eeeeee',
            size: 'small'
        }

    jQuery('.tip').iljtipso(tipsoConfig);
});