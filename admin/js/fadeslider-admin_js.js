(function( $ ) {
	'use strict';
	$(document).ready(function(){
		function Focussave(){
			$('.fadelider-wrap').on('focusout','.fade-form-control',function(){
				var value = $(this).val();
				console.log(value);
			});
		}

		var add_slide_wpflexframe;
		var change_slide_frame;

		$('#fade_slide').on('click', function(event){
			event.preventDefault();
			var SliderID = $(this).data('slideid');
			
			if ( add_slide_wpflexframe ) {
				//console.log("enter cond");
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

				selection.map(function(attachment) {
					attachment = attachment.toJSON();
					slide_attachmentids.push(attachment.id);
				}); 
				
				var data = {
					action: 'fadeslider_ajax',
					slider_id: SliderID,
					selection: slide_attachmentids,
					mode: 'slider_save'
				};

				jQuery.post(ajax_var.ajax_url, data, function(response) {
					$('#fade_append').html(response);
				});
				
			
			});
			add_slide_wpflexframe.open();
			$(".media-menu a:contains('Media Library')").remove();
		});

		// Edit Slide
		/*$('.edit_slide').on('click', function(event){
			event.preventDefault();
			var SliderID = $(this).data('slideid');
			var td = $(this).closest('td');
			
			if ( add_slide_wpflexframe ) {
				//console.log("enter cond");
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
				var slide_attachmentids = [];
				var attachment_url = '';
				var attachment_id = '';
				selection.map(function(attachment) {
					attachment = attachment.toJSON();
					attachment_url = attachment.url;
					attachment_id = attachment.id;
					//slide_attachmentids.push(attachment.url);
				});
				var data = {
					action: 'fadeslider_ajax',
					slide_url: attachment_url,
					slide_id: attachment_id,
					mode: 'edit_slide'
				};

				jQuery.post(ajax_var.ajax_url, data, function( response ) {
					$( td ).html( response );
				});
			});
			add_slide_wpflexframe.open();
			$(".media-menu a:contains('Media Library')").remove();
		});*/

		// Delete Slide
		jQuery('.fadelider-wrap').on('click','.delete_slide', function(event){ 
			var conformation = confirm("Are you sure?");
			if(conformation == true) { 
				var attachment_key = $(this).data('delete');
				var SliderID = $(this).data('slider_id');
				var data = {
					action: 'fadeslider_ajax',
					slider_id: SliderID,
					attachment_key: attachment_key,
					mode: 'slide_delete'
				};

				jQuery.post(ajax_var.ajax_url, data, function(response) {
					$('#fade_append').html(response);
				});
			}

		});

	});

})( jQuery );

// Edit slide
function edit_slide( edit ) {
	var add_slide_wpflexframe;
	var change_slide_frame;

	event.preventDefault();
	var post_id = $( edit ).data( 'slider_id' );
	var key = $( edit ).data( 'edit' );
	var td = $( edit ).closest('td');
	
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
			//slide_attachmentids.push(attachment.url);
		});
		var data = {
			action: 'fadeslider_ajax',
			attachment_id: attachment_id,
			post_id: post_id,
			key: key,
			mode: 'edit_slide'
		};
		jQuery.post(ajax_var.ajax_url, data, function( response ) {
			$( td ).html( response );
		});
	});
	add_slide_wpflexframe.open();
	$(".media-menu a:contains('Media Library')").remove();
}