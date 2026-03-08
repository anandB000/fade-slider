(function( $ ) {
	'use strict';
	$( document ).ready( function() {
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
					mode: 'slider_save',
					nonce: ajax_var.nonce
				};
			console.log('Sending AJAX data:', data);
			$( '#fade_append' ).html('<tr><td><img id="slide-loader" src="../wp-admin/images/wpspin_light-2x.gif"></td></tr>');
			jQuery.post(ajax_var.ajax_url, data, function( response ) {
				console.log('AJAX Response:', response);
				// Check if response contains error
				if ( response.indexOf('Error:') !== -1 ) {
					$( '#fade_append' ).html('<tr><td><strong style="color:red;">' + response + '</strong></td></tr>');
					console.error('AJAX Error:', response);
				} else {
					$( '#fade_append' ).html( response );
				}
			}).fail(function(xhr) {
				console.error('AJAX Fail:', xhr.responseText);
					$( '#fade_append' ).html('<tr><td><strong style="color:red;">Error: ' + xhr.responseText + '</strong></td></tr>');
				});
			});

			add_slide_wpflexframe.open();
			$( ".media-menu a:contains('Media Library')" ).remove();
		});

		// Delete Slide
		jQuery('.fadelider-wrap').on('click','.delete_slide', function(event){
			var conformation = confirm("Are you sure?");
			var td = $( this ).closest('td');
			if(conformation == true) {
				$( td ).html('<tr><td><img id="slide-loader" src="../wp-admin/images/wpspin_light-2x.gif"></td></tr>');
				var attachment_key = $(this).data('delete');
				var SliderID = $(this).data('slider_id');
				var data = {
					action: 'fadeslider_ajax',
					slider_id: SliderID,
					attachment_key: attachment_key,
					mode: 'slide_delete',
					nonce: ajax_var.nonce
				};

				jQuery.post( ajax_var.ajax_url, data, function( response ) {
					$( '#fade_append' ).html( response );
				}).fail(function(xhr) {
					console.error('Delete AJAX Error:', xhr.responseText);
					$( '#fade_append' ).html('<tr><td><strong style="color:red;">Error: ' + xhr.responseText + '</strong></td></tr>');
				});
			}
		});

		// Sortable slide
		$( ".sortable .ui-sortable" ).sortable();
		$( ".sortable .ui-sortable" ).disableSelection();
		
		$('.copy-shortcode-btn').on('click', function() {
			var target = $(this).data('target');
			var input = document.getElementById(target);
			if (input) {
				input.removeAttribute('readonly');
				input.select();
				input.setSelectionRange(0, 99999);
				document.execCommand('copy');
				input.setAttribute('readonly', 'readonly');
				$(this).text('Copied!');
				var btn = $(this);
				setTimeout(function() { btn.html('<span class="dashicons dashicons-admin-page"></span>'); }, 1200);
			}
		});
	});

})( jQuery );

// Edit slide - defined outside IIFE for onclick compatibility
function edit_slide( edit ) {
	var add_slide_wpflexframe;
	var change_slide_frame;

	var post_id = jQuery( edit ).data( 'slider_id' );
	var key = jQuery( edit ).data( 'edit' );
	var td = jQuery( edit ).closest('td');

	if ( add_slide_wpflexframe ) {
		add_slide_wpflexframe.open();
		return;
	}

	add_slide_wpflexframe = wp.media.frames.file_frame = wp.media({
		multiple: false,
		frame: 'post',
		library: {type: 'image'}
	});
	add_slide_wpflexframe.on('insert', function() {

		var selection = add_slide_wpflexframe.state().get('selection');
		var attachment_id = '';
		selection.map(function(attachment) {
			attachment = attachment.toJSON();
			attachment_id = attachment.id;
		});
		var data = {
			action: 'fadeslider_ajax',
			attachment_id: attachment_id,
			post_id: post_id,
			key: key,
			mode: 'edit_slide',
			nonce: ajax_var.nonce
		};
		jQuery.post(ajax_var.ajax_url, data, function( response ) {
			if ( response.indexOf('Error:') !== -1 ) {
				jQuery( td ).html( '<strong style="color:red;">' + response + '</strong>' );
				console.error('Edit AJAX Error:', response);
			} else {
				jQuery( td ).html( response );
			}
		}).fail(function(xhr) {
			console.error('Edit AJAX Fail:', xhr.responseText);
			jQuery( td ).html( '<strong style="color:red;">Error: ' + xhr.responseText + '</strong>' );
		});
	});
	add_slide_wpflexframe.open();
	jQuery(".media-menu a:contains('Media Library')").remove();
}