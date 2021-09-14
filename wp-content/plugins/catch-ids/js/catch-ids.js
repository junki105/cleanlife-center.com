(function( $ ) {
	'use strict';

	$(function() {

		/* For Input Switch */
		$( '.catchids-input-switch' ).on( 'click', function() {
			var loader = $( this ).parent().next();
			
			loader.show();
			
			var main_control = $( this );
			var data = {
				'action'      : 'catchids_switch',
				'value'       : this.checked,
				'option_name' : main_control.attr( 'rel' )
			};

			$.post( ajaxurl, data, function( response ) {
				response = $.trim( response );

				if ( '1' == response ) {
					main_control.parent().parent().addClass( 'active' );
					main_control.parent().parent().removeClass( 'inactive' );
				} else if( '0' == response ) {
					main_control.parent().parent().addClass( 'inactive' );
					main_control.parent().parent().removeClass( 'active' );
				} else {
					alert( response );
				}
				
				loader.hide();
			});
		});
		/* For Input Switch End */

		/* CPT switch */
		$( '.ctp-switch' ).on( 'click', function() {
			var loader = $( this ).parent().next();
			
			loader.show();
				
			var main_control = $( this );
			var data = {
				'action'      : 'ctp_switch',
				'value'       : this.checked,
				'option_name' : main_control.attr( 'rel' )
			};

			$.post( ajaxurl, data, function( response ) {
				response = $.trim( response );

				if ( '1' == response ) {
					main_control.parent().parent().addClass( 'active' );
					main_control.parent().parent().removeClass( 'inactive' );
				} else if( '0' == response ) {
					main_control.parent().parent().addClass( 'inactive' );
					main_control.parent().parent().removeClass( 'active' );
				} else {
					alert( response );
				}
				loader.hide();
			});
		});
		/* CPT switch End */
	});

	$(function() {

        // Tabs
        $('.catchp_widget_settings .nav-tab-wrapper a').on('click', function(e){
            e.preventDefault();

            if( !$(this).hasClass('ui-state-active') ){
                $('.nav-tab').removeClass('nav-tab-active');
                $('.wpcatchtab').removeClass('active').fadeOut(0);

                $(this).addClass('nav-tab-active');

                var anchorAttr = $(this).attr('href');

                $(anchorAttr).addClass('active').fadeOut(0).fadeIn(500);
            }

        });
    });

    // jQuery Match Height init for sidebar spots
    $(document).ready(function() {
        $('.catchp-sidebar-spot .sidebar-spot-inner, .col-2 .catchp-lists li, .col-3 .catchp-lists li').matchHeight();
    });

})( jQuery );
