jQuery(function($) {
    var autoCompleteEl = '#wpacu-search-form-assets-manager .search-field';

    $(autoCompleteEl).autocomplete({
        source: function(request, response) {
            var wpacu_post_type;

            if ($('#wpacu-custom-post-type-choice').length > 0) {
                // Custom Post Type
                wpacu_post_type = $('#wpacu-custom-post-type-choice').val();
            } else {
                // Post, Page or Attachment
                wpacu_post_type = wpacu_autocomplete_search_obj.post_type;
            }

            $.ajax({
                dataType: 'json',
                url: wpacu_autocomplete_search_obj.ajax_url,
                cache: false,
                data: {
                    wpacu_term:      request.term,
                    wpacu_post_type: wpacu_post_type,
                    action:          wpacu_autocomplete_search_obj.ajax_action,
                    wpacu_security:  wpacu_autocomplete_search_obj.ajax_nonce,
                    wpacu_time:      new Date().getTime()
                },
                success: function(data) {
                    $('#wpacu-search-form-assets-manager-no-results').hide(); // in case it was ever shown
                    response(data);
                    console.log(data);
                },
                complete: function(jqXHR, textStatus) {
                    if (jqXHR.responseText == 'no_results') {
                        var noResultsArray = new Object();
                        $('#wpacu-search-form-assets-manager-no-results').show();
                        response(noResultsArray);
                        //$(autoCompleteEl).val('');
                    }
                }
            });
        },
        select: function(event, ui) {
            $('#wpacu-search-form-assets-manager').hide();
            $('#wpacu-post-chosen-loading-assets').show();

            var redirectTo = wpacu_autocomplete_search_obj.redirect_to.replace('[post_id_here]', ui.item.id);
            window.location.href = redirectTo;
        },
        close: function(el) {
            el.target.value = '';
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $( "<li>" )
            .append( "<div>" + item.label + "<span style='display:block;color:green;font-size:11px;'>"+ item.link +"</span></div>" )
            .appendTo( ul );
    };
});