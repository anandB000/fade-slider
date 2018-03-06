(function( $ ) {
	'use strict';
	$( document ).ready( function() {
		function Focussave() {
			$( '.fadelider-wrap' ).on( 'focusout', '.fade-form-control', function() {
				var value = $(this).val();
				console.log(value);
			});
		}

		var add_slide_wpflexframe;
		var change_slide_frame;

		$( '#fade_slide' ).on( 'click', function(event) {
			event.preventDefault();
			var SliderID = $(this).data('slideid');

			if ( add_slide_wpflexframe ) {
				add_slide_wpflexframe.open();
				return;
			}

			add_slide_wpflexframe = wp.media.frames.file_frame = wp.media({
				multiple: 'add',
				frame: 'post',
				library: {type: 'image'}
			});

			add_slide_wpflexframe.on('insert', function() {

				var selection = add_slide_wpflexframe.state().get('selection');
				var slide_attachmentids = [];

				selection.map( function(attachment) {
					attachment = attachment.toJSON();
					slide_attachmentids.push(attachment.id);
				}); 

				var data = {
					action: 'fadeslider_ajax',
					slider_id: SliderID,
					selection: slide_attachmentids,
					mode: 'slider_save'
				};

				jQuery.post(ajax_var.ajax_url, data, function( response ) {
					$( '#fade_append' ).html( response );
				});
			});

			add_slide_wpflexframe.open();
			$( ".media-menu a:contains('Media Library')" ).remove();
		});

		//Delete Slide 
		jQuery( '.fadelider-wrap' ).on( 'click', '.delete_slide', function( event ) {
			var conformation = confirm( "Are you sure?" );
			if ( conformation == true ) {
				var attachment_key = $( this ).data( 'delete' );
				var SliderID = $( this ).data( 'slider_id' );
				var data = {
					action: 'fadeslider_ajax',
					slider_id: SliderID,
					attachment_key: attachment_key,
					mode: 'slide_delete'
				};

				jQuery.post( ajax_var.ajax_url, data, function( response ) {
					$( '#fade_append' ).html( response );
				});
			}
		});
	});

})( jQuery );