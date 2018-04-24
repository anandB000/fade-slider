<?php

//Enqueue Scripts
add_action( 'wp_enqueue_scripts', 'fadeslider_publicscript' );
function fadeslider_publicscript() { 
	wp_register_style( 'fadesliderpublic_style',  plugin_dir_url( __FILE__ ) . 'css/fadeslider_style.css' );  
	wp_enqueue_style( 'fadesliderpublic_style' );
	wp_register_style( 'fadeslidebootstrap_style',  plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css' );  
	wp_enqueue_style( 'fadeslidebootstrap_style' );

	wp_enqueue_script( 'fadeslidebootstrap-min-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), 2.0, false );
}

add_shortcode('display_fade_slider', 'display_fade_slider_fun');
function display_fade_slider_fun( $atts ) { 
	ob_start();

	if ( isset($atts['id']) ) {
		$fade_slide = $atts['id'];
	} else {
		$FadeID = NULL;
	}

	$post = get_post( $fade_slide ); 
	$slides = get_post_meta( $fade_slide,'slide_attachmenid', true );
	$animation = get_post_meta( $post->ID, 'animation', true );

	if ( $animation == 'Fade' ) {
		$fade_class = 'slide carousel-fade';
	} else {
		$fade_class = 'slide';
	}

	if ( $slides  ){
		?>
		<div id="carousel-fadeslider-<?php echo $post->post_name;?>" class="carousel <?php echo $fade_class; ?>" data-ride="carousel" data-interval="<?php echo get_post_meta( $post->ID, 'interval', true ); ?>"  data-pause="<?php echo get_post_meta( $post->ID, 'hover_pass', true ); ?>">
			<?php if ( get_post_meta( $post->ID, 'pager', true ) == 'Show' ) { $i = 0; ?>
				<ol class="carousel-indicators">
					<?php foreach ( $slides as $slide ) { ?>
						<li data-target="#carousel-fadeslider-<?php echo $post->post_name;?>" data-slide-to="<?php echo $i;?>" class="<?php if ( $i == 0 ) { ?> active <?php }?>"></li>
					<?php $i++; }?>
				</ol>
			<?php }?>
			<div class="carousel-inner" role="listbox">
				<?php $i = 0; 
				foreach ( $slides as $key=>$slide ) { 
				$slide_title = get_post_meta( $post->ID, 'fade-slide-title', true );
				$slide_desc = get_post_meta( $post->ID, 'fade-slide-desc', true );
				$slide_url = get_post_meta( $post->ID, 'fade-slide-url', true ); ?>
				<div class="item <?php if ( $i == 0 ) { ?> active <?php }?>">
				<?php $image_attributes = wp_get_attachment_image_src( $slide,'fade-slider-size-'.$post->ID ); ?>
					<?php if( $slide_url[$key] ) { ?>
						<a href="<?php echo esc_url($slide_url[$key],array('http', 'https')); ?>" target="_blank">
					<?php } ?>

					<img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>"alt="<?php echo $post->post_title;?>">

					<?php if ( $slide_url[$key ] ) { echo '</a>'; } ?>
					<?php if ( $slide_title[$key] || $slide_title[$key] ) {?>
						<div class="carousel-caption <?php if ( get_post_meta( $post->ID, 'desc_resp', true ) == 'Hide' ) { ?>hidden-sm<?php }?>">
							<div class="display-sec">
								<?php if ( $slide_title[$key] ) { ?>
									<h3><?php echo sanitize_text_field( $slide_title[$key] ); ?></h3>
								<?php }
								if ( $slide_desc[$key] ) { ?>
									<p><?php echo sanitize_text_field( $slide_desc[$key] ); ?></p>
								<?php } ?>
							</div>
						</div>
					<?php }?>
					</div>

				<?php $i++; } wp_reset_postdata();?>
			</div>
			<?php if ( get_post_meta( $post->ID, 'arrow', true ) == 'Show') { ?>
				<a class="left carousel-control"  href="#carousel-fadeslider-<?php echo $post->post_name;?>" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control"  href="#carousel-fadeslider-<?php echo $post->post_name;?>" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			<?php }?>
		</div>
		<?php
	} else {
		echo '<h2>Add slide to show!</h2>';
	}
	return ob_get_clean();
}

function fade_slider_template( $atts ) {
	echo do_shortcode($atts);
}
