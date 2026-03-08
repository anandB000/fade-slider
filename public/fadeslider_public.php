<?php
// Enqueue public scripts
add_action( 'wp_enqueue_scripts', 'fadeslider_publicscript' );
function fadeslider_publicscript() {
	// Main plugin styles
	wp_register_style( 'fadesliderpublic_style', plugin_dir_url( __FILE__ ) . 'css/fadeslider_style.css' );
	wp_enqueue_style( 'fadesliderpublic_style' );
	
	// Standalone carousel CSS (no Bootstrap required)
	wp_register_style( 'fadeslider_standalone_css', plugin_dir_url( __FILE__ ) . 'css/slider-standalone.css' );
	wp_enqueue_style( 'fadeslider_standalone_css' );
	
	// Standalone carousel JavaScript (no Bootstrap required)
	wp_enqueue_script( 'fadeslider_standalone_js', plugin_dir_url( __FILE__ ) . 'js/slider-standalone.js', array(), '2.6', true );
}

// Slider shortcode
add_shortcode( 'display_fade_slider', 'display_fade_slider_fun' );
function display_fade_slider_fun( $atts ) {
	ob_start();

	if ( isset( $atts['id'] ) ) {
		$fade_slide = $atts['id'];
	} else {
		$FadeID = NULL;
	}

	$post      = get_post( $fade_slide );
	$slides    = get_post_meta( $fade_slide, 'slide_attachmenid', true );
	$animation = get_post_meta( $post->ID, 'animation', true );

	if ( $animation == 'Fade' ) {
		$fade_class = 'slide carousel-fade';
	} else {
		$fade_class = 'slide';
	}

	if ( $slides ) {
		?>
			<?php 
			$interval = get_post_meta( $post->ID, 'interval', true );
			$pause = esc_attr( get_post_meta( $post->ID, 'hover_pass', true ) );
			?>
			<div id="carousel-fadeslider-<?php echo esc_attr( $post->post_name );?>" class="carousel <?php echo esc_attr( $fade_class ); ?>"
				data-bs-ride="<?php echo ($interval === 'off') ? 'false' : 'carousel'; ?>"<?php if ($interval !== 'off') { ?> data-bs-interval="<?php echo esc_attr( $interval ); ?>"<?php } ?> data-bs-pause="<?php echo $pause; ?>">
		<?php if ( get_post_meta( $post->ID, 'pager', true ) === 'Show' ) { $i = 0; ?>
			<div class="carousel-indicators">
				<?php foreach ( $slides as $slide ) { ?>
					<button type="button" data-bs-target="#carousel-fadeslider-<?php echo esc_attr( $post->post_name );?>" data-bs-slide-to="<?php echo esc_attr( $i );?>" class="<?php if ( $i === 0 ) { echo 'active'; }?>" aria-label="Slide <?php echo esc_attr( $i + 1 );?>"></button>
					<?php $i++; }?>
			</div>
		<?php }?>
			<div class="carousel-inner">
				<?php
					$i = 0;
					foreach ( $slides as $key=>$slide ) {
						$slide_title = get_post_meta( $post->ID, 'fade-slide-title', true );
						$slide_desc  = get_post_meta( $post->ID, 'fade-slide-desc', true );
						$slide_url   = get_post_meta( $post->ID, 'fade-slide-url', true );
						?>
						<div class="carousel-item <?php if ( $i === 0 ) { echo 'active'; }?>">
						<?php
						$image_attributes = wp_get_attachment_image_src( $slide, 'fade-slider-size-' . $post->ID );
						 $slide_url_val = isset( $slide_url[$key] ) ? $slide_url[$key] : '';
						 if( ! empty( $slide_url_val ) ) { ?>
							<a href="<?php echo esc_url( $slide_url_val, array( 'http', 'https' ) ); ?>" target="_blank">
						<?php } ?>
							<img src="<?php echo esc_attr( $image_attributes[0] ); ?>" width="<?php echo esc_attr( $image_attributes[1] ); ?>" height="<?php echo esc_attr( $image_attributes[2] ); ?>" alt="<?php echo esc_attr( $post->post_title );?>" class="d-block w-100">
						<?php if ( ! empty( $slide_url_val ) ) { echo '</a>'; } ?>

						<?php 
						$slide_title_val = isset( $slide_title[$key] ) ? $slide_title[$key] : '';
						$slide_desc_val = isset( $slide_desc[$key] ) ? $slide_desc[$key] : '';
						 if ( ! empty( $slide_title_val ) || ! empty( $slide_desc_val ) ) {?>
						<div class="carousel-caption <?php echo ( get_post_meta( $post->ID, 'desc_resp', true ) === 'Hide' ) ? 'd-md-block' : ''; ?>">
								<div class="display-sec">
									<?php if ( ! empty( $slide_title_val ) ) { ?>
										<h3><?php echo wp_kses_post( $slide_title_val ); ?></h3>
									<?php }
									if ( ! empty( $slide_desc_val ) ) { ?>
										<p><?php echo wp_kses_post( $slide_desc_val ); ?></p>
										<?php } ?>
									</div>
								</div>
							<?php }?>
							</div>
						<?php $i++;
					} wp_reset_postdata();
				?>
			</div>
		<?php if ( get_post_meta( $post->ID, 'arrow', true ) === 'Show' ) { ?>
			<button class="carousel-control-prev" type="button" data-bs-target="#carousel-fadeslider-<?php echo esc_attr( $post->post_name );?>" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#carousel-fadeslider-<?php echo esc_attr( $post->post_name );?>" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		<?php }?>
		</div>
		<?php
	} else {
		echo '<h2>Add slide to show!</h2>';
	}
	return ob_get_clean();
}

// Template shortcode function
function fade_slider_template( $atts ) {
	echo do_shortcode($atts);
}
