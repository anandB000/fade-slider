<?php

add_action( 'wp_loaded', 'reg_fade_slide_image_size' );
function reg_fade_slide_image_size(){
	global $post;
	$arg = array(
		'numberposts' => -1,
		'post_type' => 'fade_slider',
	);

	$posts = get_posts( $arg );
	foreach ( $posts as $post ) {
		setup_postdata( $post );
		$width = get_post_meta( $post->ID, 'width', true );
		$height = get_post_meta( $post->ID, 'height', true );

		if( !$width ) {
			$width = 1200;
		}
		if( !$height ) {
			$height = 350;
		}

		add_image_size( 'fade-slider-size-'.$post->ID, $width, $height, true );	
	}
}

//Hide featured image meta
add_action( 'do_meta_boxes', 'remove_featured_meta' );
function remove_featured_meta() {
	remove_meta_box( 'postimagediv','fade_slider','side' );
}

//Fade slider Post Type
add_action( 'init', 'slider_post' ); 
function slider_post() {
	$labels = array(
		'name' => _x('Sliders', 'fadeslider'),
		'singular_name' => _x('Slider', 'fadeslider'),
		'add_new' => _x('Add New', 'fadeslider', 'fadeslider'),
		'add_new_item' => __('Add New Slider', 'fadeslider'),
		'edit_item' => __('Edit Slider', 'fadeslider'),
		'new_item' => __('New Slider', 'fadeslider'),
		'all_items' => __('All Sliders', 'fadeslider'),
		'view_item' => __('View Slider', 'fadeslider'),
		'search_items' => __('Search Sliders', 'fadeslider'),
		'not_found' =>  __('No Sliders found', 'fadeslider'),
		'not_found_in_trash' => __('No Sliders found in Trash', 'fadeslider'), 
		'parent_item_colon' => '',
		'menu_name' => __('Fade Slider', 'fadeslider'),
	);
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'capability_type' => 'post',
		'has_archive' => true, 
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-images-alt2',
		'supports' =>array( 
			'title','thumbnail' 
		),
	); 
	register_post_type( 'fade_slider', $args );
}

//Enqueue Scripts
add_action( 'admin_enqueue_scripts', 'fadeslider_adminscripts' );
function fadeslider_adminscripts() { 
	wp_register_style( 'slider_admin_style', plugin_dir_url( __FILE__ ) . 'css/fadeslider-admin_style.css' );
	wp_enqueue_style( 'slider_admin_style' );
	
	wp_enqueue_script( 'fade-sliderjs', plugin_dir_url( __FILE__ ).'js/fadeslider-admin_js.js', array( 'jquery' ), 'v5', false );
	wp_localize_script('fade-sliderjs', 'ajax_var', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ajax-nonce'),
	));
}

//Slider Meta's
add_action( 'admin_init', 'fade_slider_metaboxes' );
function fade_slider_metaboxes() {
	add_meta_box( 'fadeslider_add_slide', __( 'Add Slides', 'fadeslider' ), 'fade_meta_box_add_slide', 'fade_slider', 'advanced', 'default' );
	add_meta_box( 'fadeslider_slider_options', __( 'Slider Options', 'fadeslider' ), 'fadeslider_options', 'fade_slider', 'side', 'default' );
}

function fadeslider_options( $post ) { 
wp_nonce_field( 'fadeslider_options', 'fadeslider_options_nonce' );
?>
	<div class="fadeslider-shortcode">
		<p>
			<label><?php echo esc_html( 'Shortcode for page or post' );?></label>
			<input type="text" readonly="" class="ui-corner-all fade-form-control" value="<?php echo '[display_fade_slider id='.$post->ID.']';?>" />
		</p>
		<p>
			<label><?php echo esc_html( 'Shortcode for template' );?></label>
			<input type="text" readonly="" class="ui-corner-all fade-form-control" value="fade_slider_template('[display_fade_slider id=<?php echo $post->ID; ?>]')" />
		</p>
	</div>
	<div class="fadeslider-options">
		<p>
			<label><?php echo esc_html( 'Animation' );?></label>
			<select name="<?php echo esc_attr( 'fade_options[animation]' ); ?>" class="fade-form-control" id="animation">
				<option <?php if ( get_post_meta( $post->ID, 'animation', true) =='Slide' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Slide' ); ?>"><?php echo esc_html( 'Slide' ); ?></option>
				<option <?php if ( get_post_meta( $post->ID, 'animation', true) =='Fade' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Fade' ); ?>"><?php echo esc_html( 'Fade' ); ?></option>
			</select>
		</p>
		<p>
			<label><?php echo esc_html( 'Arrow indicator' );?></label>
			<select name="<?php echo esc_attr( 'fade_options[arrow]' ); ?>" class="fade-form-control" id="arrow">
				<option <?php if ( get_post_meta( $post->ID, 'arrow', true) =='Show' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Show' ); ?>"><?php echo esc_html( 'Show' ); ?></option>
				<option <?php if ( get_post_meta( $post->ID, 'arrow', true) =='Hide' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Hide' ); ?>"><?php echo esc_html( 'Hide' ); ?></option>
			</select>
		</p>
	</div>
	<div class="fadeslider-options">
		<p>
			<label><?php echo esc_html( 'Pager indicator' );?></label>
			<select name="<?php echo esc_attr( 'fade_options[pager]' ); ?>" class="fade-form-control" id="nav_ind">
				<option <?php if ( get_post_meta( $post->ID, 'pager', true) =='Show' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Show' ); ?>"><?php echo esc_html( 'Show' ); ?></option>
				<option <?php if ( get_post_meta( $post->ID, 'pager', true) =='Hide' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Hide' ); ?>"><?php echo esc_html( 'Hide' ); ?></option>
			</select>
		</p>
		<p>
			<label><?php echo esc_html( 'Hover pass' );?></label>
			<select name="<?php echo esc_attr( 'fade_options[hover_pass]' ); ?>" class="fade-form-control" id="pass">
				<option <?php if ( get_post_meta( $post->ID, 'hover_pass', true ) =='hover' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'hover' ); ?>"><?php echo esc_html( 'Yes' ); ?></option>
				<option <?php if ( get_post_meta( $post->ID, 'hover_pass', true ) =='false' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'false' ); ?>"><?php echo esc_html( 'No' ); ?></option>
			</select>
		</p>
	</div>
	<div class="fadeslider-options">
		<p>
			<label><?php echo esc_html( 'Slider Width' );?></label>
			<input type="text" name="<?php echo esc_attr( 'fade_option_dimention[width]' ); ?>" class="ui-corner-all fade-form-control" value="<?php $width = get_post_meta( $post->ID, 'width', true); if ( $width ) { echo esc_attr( $width ); } ?>" />
		</p>
		<p>
			<label><?php echo esc_html( 'Slider Height' );?></label>
			<input type="text" name="<?php echo esc_attr( 'fade_option_dimention[height]' ); ?>" class="ui-corner-all fade-form-control" value="<?php $height = get_post_meta( $post->ID, 'height', true); if ( $height ) { echo esc_attr( $height ); } ?>" />
		</p>
	</div>
	<div class="fadeslider-options">
		<p style="width:100%">
			<label><?php echo esc_html( 'Set Interval' );?></label>
			<select name="<?php echo esc_attr( 'interval' ); ?>" class="fade-form-control" id="interval">
				<?php for( $j = 1000; $j <= 10000; $j+=1000 ){?>
				<option <?php if( get_post_meta( $post->ID, 'interval', true ) == $j){?> selected="selected"<?php }?> value="<?php echo esc_attr( $j );?>"><?php echo esc_html( $j/1000 ); ?><?php echo esc_html( 'sec' );?></option>
				<?php }?>
			</select>
		</p>
	</div>
	<div class="fadeslider-options">
		<p style="width:100%">
			<label><?php echo esc_html( 'Slide description on responsive' );?></label>
			<select name="<?php echo esc_attr( 'fade_options[desc_resp]' ); ?>" class="fade-form-control" id="pass">
				<option <?php if ( get_post_meta( $post->ID, 'desc_resp', true) =='Hide' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Hide' ); ?>"><?php echo esc_html( 'Hide' ); ?></option>
				<option <?php if ( get_post_meta( $post->ID, 'desc_resp', true) =='Show' ) { echo 'selected="selected"'; } ?> value="<?php echo esc_attr( 'Show' ); ?>"><?php echo esc_html( 'Show' ); ?></option>
			</select>
		</p>
	</div>
<?php }

function fade_meta_box_add_slide( $post ) {
	wp_nonce_field( 'fade_meta_box_add_slide', 'fade_meta_box_add_slide_nonce' );
	$get_attachmentids = get_post_meta($post->ID,'slide_attachmenid',true);
?>
<div class="fadelider-wrap">
	<div id="fadeslider_appenda">
		<div id="post-body-content">
			<div class="left">
				<table class="widefat sortable">
					<thead>
						<tr>
							<th style="width: 100px;">
								<h3><?php echo esc_html( 'Slides' );?></h3>
							</th>
							<th>
								<button type="button" data-slideid="<?php echo $post->ID; ?>" class="button alignright add-slide button-primary button-large" id="fade_slide"><span class="dashicons dashicons-images-alt2 "></span> <?php echo esc_html( 'Add Slide' );?> </button>
							</th>
						</tr>
					</thead>
					<tbody id="fade_append" class="ui-sortable">
					<?php
					if ( $get_attachmentids ) {

						$get_the_title = get_post_meta( $post->ID, 'fade-slide-title', true);
						$get_the_url = get_post_meta( $post->ID, 'fade-slide-url', true);
						$get_the_desc = get_post_meta( $post->ID, 'fade-slide-desc', true);

						foreach ( $get_attachmentids as $k => $get_attachmentid ) { ?>
						<tr class="append_slide">
							<td>
								<div class="slide-thum fade-slide-image" style="background-image:url('<?php echo wp_get_attachment_url($get_attachmentid);?>');">
									<span data-delete="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-trash delete_slide"></span>
									<span data-edit="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-edit edit_slide" onclick="edit_slide( this )"></span>
								</div>
							</td>
							<td>
								<div class="fade-slide-inputs">
									<input type="text" name="<?php echo esc_attr( 'fade-slide-title[]' ); ?>" class="fade-form-control" value="<?php echo esc_attr( $get_the_title[$k] ); ?>" placeholder="Title" />
									<input type="text" name="<?php echo esc_attr( 'fade-slide-url[]' ); ?>" id="meta-image" class="meta_image fade-form-control" value="<?php echo esc_attr( $get_the_url[$k] ); ?>" placeholder="URL" />
									<textarea name="<?php echo esc_attr( 'fade-slide-desc[]' ); ?>" class="fade-form-control" placeholder="Description" rows="4"><?php echo esc_html( $get_the_desc[$k] ); ?></textarea>
								</div>
							</td>
						</tr>
					<?php 
						}
					} else {
						?>
						<tr class="append_slide">
							<td style="width: 100%; text-align: center;">
								<h3 style="color: #333; font-size: 18px">Click Add slide button to add slides</h3>
							</td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
}

//Meta save
add_action( 'save_post', 'save' );
function save( $post_id ) {
	/*Select Theme options*/
	if ( ! isset( $_POST['fade_meta_box_add_slide_nonce'] ) )
		return $post_id;

	$nonce = $_POST['fade_meta_box_add_slide_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'fade_meta_box_add_slide' ) )
		return $post_id;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;
	if ( isset( $_POST['fade-slide-title'] ) ) {
		foreach( $_POST['fade-slide-title'] as $fade_titles ) {
			$fadeslide_titles[] = sanitize_text_field( $fade_titles );
		}	
		update_post_meta( $post_id, 'fade-slide-title', $fadeslide_titles );
	}

	if ( isset( $_POST['fade-slide-url'] ) ) {
		foreach($_POST['fade-slide-url'] as $fade_urls) {
			$fadeslide_urls[] = esc_url( $fade_urls,array( 'http', 'https' ) );
		}
		update_post_meta( $post_id, 'fade-slide-url', $fadeslide_urls );
	}

	if ( isset( $_POST['fade-slide-desc'] ) ) {
		foreach( $_POST['fade-slide-desc'] as $fade_decs ) {
			$fadeslide_decs[] = sanitize_text_field( $fade_decs );
		}	
		update_post_meta( $post_id, 'fade-slide-desc', $fadeslide_decs );
	}

	if ( ! isset( $_POST['fadeslider_options_nonce'] ) )
		return $post_id;

	$nonce = $_POST['fadeslider_options_nonce'];
	if ( ! wp_verify_nonce( $nonce, 'fadeslider_options' ) )
		return $post_id;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return $post_id;
	
	if ( isset( $_POST['fade_options'] ) ) {
		foreach ( $_POST['fade_options'] as $key=>$fade_options ) {
			$options = sanitize_text_field( $fade_options );
			update_post_meta( $post_id, $key, $options );
		}
	}

	if ( isset( $_POST['interval'] ) ) {
		$interval = absint($_POST['interval']);
		update_post_meta( $post_id, 'interval' , $interval );
	}

	if( isset( $_POST['fade_option_dimention'] ) ) {
		$width = absint( $_POST['fade_option_dimention']['width'] );
		$height = absint( $_POST['fade_option_dimention']['height'] );

		if ( $width ) {
			update_post_meta( $post_id, 'width' , $width );
			$width = get_post_meta( $post_id, 'width', true );
		} else {
			$width = 1200;
		}

		if ( $height ) {
			update_post_meta( $post_id, 'height' , $height );
			$height = get_post_meta( $post_id, 'height', true );
		} else {
			$height = 350;
		}
	}

}

//Admin Ajax
// Slider Save Ajax
add_action( 'wp_ajax_nopriv_fadeslider_ajax', 'fadeslider_ajax' );
add_action( 'wp_ajax_fadeslider_ajax', 'fadeslider_ajax' );
function fadeslider_ajax() {
	if ( $_POST['mode'] == 'slider_save' ) {
		$wpfadeslider_id = $_POST['slider_id'];
		$wpfadeslide_ids = $_POST['selection'];

		$get_title = get_post_meta( $wpfadeslider_id, 'fade-slide-title', true);
		$get_url = get_post_meta( $wpfadeslider_id, 'fade-slide-url', true);
		$get_desc = get_post_meta( $wpfadeslider_id, 'fade-slide-desc', true);
		
		$get_attachmentids = get_post_meta($wpfadeslider_id,'slide_attachmenid',true);
		if( $get_attachmentids ) {
			$merge_attachments = array_merge($get_attachmentids,$wpfadeslide_ids);
			$save_slideids = update_post_meta($wpfadeslider_id,'slide_attachmenid',$merge_attachments);
			
			$get_attachmentids = get_post_meta($wpfadeslider_id,'slide_attachmenid',true);
			foreach($get_attachmentids as $k=>$get_attachmentid){ ?>
				<tr class="append_slide">
					<td>
						<div class="slide-thum fade-slide-image" style="background-image:url('<?php echo wp_get_attachment_url($get_attachmentid);?>');">
							<span data-delete="<?php echo $k; ?>" data-slider_id="<?php echo $wpfadeslider_id; ?>" class="dashicons dashicons-trash delete_slide"></span>
							<span data-edit="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-edit edit_slide" onclick="edit_slide( this )"></span>
						</div>
					</td>
					<td>
						<div class="fade-slide-inputs">
							<input type="text" name="<?php echo esc_attr( 'fade-slide-title[]' ); ?>" class="fade-form-control" value="<?php echo esc_attr( $get_title[$k] );?>" placeholder="Title" />
							<input type="text" name="<?php echo esc_attr( 'fade-slide-url[]' ); ?>" id="meta-image" class="meta_image fade-form-control" value="<?php echo esc_attr( $get_url[$k] );?>" placeholder="URL" />
							<textarea name="<?php echo esc_attr( 'fade-slide-desc[]' ); ?>" class="fade-form-control" placeholder="Description" rows="4"><?php echo esc_textarea( $get_desc[$k] ); ?></textarea>
						</div>
					</td>
				</tr>
			<?php }
		} else {
			$save_slideids = update_post_meta($wpfadeslider_id,'slide_attachmenid',$wpfadeslide_ids);
			
			$get_attachmentids = get_post_meta($wpfadeslider_id,'slide_attachmenid',true);
			foreach($wpfadeslide_ids as $k=>$wpfadeslide_id){ ?>
				<tr class="append_slide">
					<td>
						<div class="slide-thum fade-slide-image" style="background-image:url('<?php echo wp_get_attachment_url( $wpfadeslide_id ); ?>');">
							<span data-delete="<?php echo $k; ?>" data-slider_id="<?php echo $wpfadeslider_id; ?>" class="delete_slide dashicons dashicons-trash"></span>
							<span data-edit="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-edit edit_slide" onclick="edit_slide( this )"></span>
						</div>
					</td>
					<td>
						<div class="fade-slide-inputs">
							<input type="text" name="<?php echo esc_attr( 'fade-slide-title[]' ); ?>" class="fade-form-control" value="<?php echo esc_attr( $get_title[$k] ); ?>" placeholder="Title" />
							<input type="text" name="<?php echo esc_attr( 'fade-slide-url[]' ); ?>" id="meta-image" class="meta_image fade-form-control" value="<?php echo esc_attr( $get_url[$k] );?>" placeholder="URL" />
							<textarea name="<?php echo esc_attr( 'fade-slide-desc[]' ); ?>" class="fade-form-control" placeholder="Description" rows="4"><?php echo esc_textarea( $get_desc[$k] );?></textarea>
						</div>
					</td>
				</tr>
			<?php }
		}
	} elseif ( $_POST['mode'] == 'slide_delete' ) {
		$wpfadeslider_id = $_POST['slider_id'];
		$wpfadeslider_metakey = $_POST['attachment_key'];

		$get_attachmentids = get_post_meta($wpfadeslider_id,'slide_attachmenid',true);
		$get_title = get_post_meta( $wpfadeslider_id, 'fade-slide-title', true);
		$get_url = get_post_meta( $wpfadeslider_id, 'fade-slide-url', true);
		$get_desc = get_post_meta( $wpfadeslider_id, 'fade-slide-desc', true);
		
		//if (array_key_exists($wpfadeslider_metakey,$get_attachmentids)){
		unset($get_attachmentids[$wpfadeslider_metakey]);
		$reindex_ids = array_values($get_attachmentids);
		update_post_meta($wpfadeslider_id,'slide_attachmenid',$reindex_ids);
		$get_attachmentids = get_post_meta($wpfadeslider_id,'slide_attachmenid',true);
		
		unset($get_desc[$wpfadeslider_metakey]);
		$reindex_desc = array_values($get_desc);
		update_post_meta($wpfadeslider_id,'fade-slide-desc',$reindex_desc);
		$get_desc = get_post_meta($wpfadeslider_id,'fade-slide-desc',true);
		
		unset($get_url[$wpfadeslider_metakey]);
		$reindex_url = array_values($get_url);
		update_post_meta($wpfadeslider_id,'fade-slide-url',$reindex_url);
		$get_url = get_post_meta($wpfadeslider_id,'fade-slide-url',true);
		
		unset($get_title[$wpfadeslider_metakey]);
		$reindex_title = array_values($get_title);
		update_post_meta($wpfadeslider_id,'fade-slide-title',$reindex_title);
		$get_title = get_post_meta($wpfadeslider_id,'fade-slide-title',true);
		
		foreach($get_attachmentids as $k=>$get_attachmentid){ ?>
			<tr class="append_slide">
				<td>
					<div class="slide-thum fade-slide-image" style="background-image:url('<?php echo wp_get_attachment_url($get_attachmentid);?>');">
						<span data-delete="<?php echo $k; ?>" data-slider_id="<?php echo $wpfadeslider_id; ?>" class="dashicons dashicons-trash delete_slide"></span>
						<span data-edit="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-edit edit_slide" onclick="edit_slide( this )"></span>
					</div>
				</td>
				<td>
					<div class="fade-slide-inputs">
						<input type="text" name="<?php echo esc_attr( 'fade-slide-title[]' ); ?>" class="fade-form-control" value="<?php echo esc_attr( $get_title[$k] );?>" placeholder="Title" />
						<input type="text" name="<?php echo esc_attr( 'fade-slide-url[]' ); ?>" id="meta-image" class="meta_image fade-form-control" value="<?php echo esc_attr( $get_url[$k]); ?>" placeholder="URL" />
						<textarea name="<?php echo esc_attr( 'fade-slide-desc[]' ); ?>" class="fade-form-control" placeholder="Description" rows="4"><?php echo esc_textarea( $get_desc[$k] ); ?></textarea>
					</div>
				</td>
			</tr>
		<?php }
	}
	else if ( $_POST['mode'] == 'edit_slide' ) {
		$wpattachment_id = $_POST['attachment_id'];
		$wpfadeslider_id = $_POST['post_id'];
		$k = $_POST['key'];
		$get_attachmentid = get_post_meta( $wpfadeslider_id,'slide_attachmenid',true );
		$get_attachmentid[$k] = $wpattachment_id;
		$update_slide_id = update_post_meta( $wpfadeslider_id, 'slide_attachmenid', $get_attachmentid );
		$get_attachmentid = get_post_meta( $wpfadeslider_id,'slide_attachmenid',true );
		if ( $update_slide_id ) {
			?>
			<div class="slide-thum fade-slide-image " style="background-image:url('<?php echo wp_get_attachment_url( $wpattachment_id );?>');">
				<span data-delete="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-trash delete_slide"></span>
				<span data-edit="<?php echo $k; ?>" data-slider_id="<?php echo get_the_ID(); ?>" class="dashicons dashicons-edit edit_slide" onclick="edit_slide( this )"></span>
			</div>
			<?php
		}
	}

	die();
}
