<?php
/**
 * The template for displaying posts in the Gallery post format.
 *
 */
?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( !is_single() && ot_get_option('blog_layout_style') == 'medium_blog' ) {
			echo '<div class="post-gallery blog-layout-medium">';
		} else {
			echo '<div class="post-gallery">';
		} ?>
		
		<?php
			if ( is_single() ) {
				if (get_post_meta($post->ID, 'full_width_posts')) {
					echo do_shortcode('[nivo_slider width="940" height="'. get_post_meta($post->ID, 'gallery_height', true) .'" navigation="1" speed="800" delay="5000" pauseonhover="1" bullets="1" effect="fade"]');
				} else {
					echo do_shortcode('[nivo_slider width="650" height="'. get_post_meta($post->ID, 'gallery_height', true) .'" navigation="1" speed="800" delay="5000" pauseonhover="1" bullets="1" effect="fade"]');
				}
			} else {
				if ( ot_get_option('blog_full_width') ) {
					if ( ot_get_option('blog_layout_style') == 'medium_blog' ) {
						echo do_shortcode('[nivo_slider width="440" height="300" navigation="1" speed="900" delay="4000" bullets="1" effect="fade"]');
					} else {
						echo do_shortcode('[nivo_slider width="940" height="350" navigation="1" speed="900" delay="4000" bullets="1" effect="fade"]');
					}	
				} else {
					if ( ot_get_option('blog_layout_style') == 'medium_blog' ) {
						echo do_shortcode('[nivo_slider width="440" height="300" navigation="1" speed="900" delay="4000" bullets="1" effect="fade"]');
					} else {
						echo do_shortcode('[nivo_slider width="650" height="270" navigation="1" speed="900" delay="4000" bullets="1" effect="fade"]');
					}	
				}
			} 	
		?>
		</div>

		<?php if (!is_single()) { ?>
		<h2 class="post-title">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'kickstart' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
		<?php } ?>
		
		<div class="entry-content">
			<?php 
				if (is_single()){
					the_content('',FALSE,'');
				} elseif (is_search()) {
					the_excerpt();					
				} else {
					if(ot_get_option('blog_content_type') == 'full_content') {
						the_content('',FALSE,'');
					} else {
						echo get_excerpt(ot_get_option('blog_excerpt_lenght', '82'));	
					}
				} 
			?>

			<?php 
				if ( function_exists('wp_pagenavi')) {
					wp_pagenavi( array( 'type' => 'multipart' ) );
				} else {
					wp_link_pages();
				} 
			?>
			
			<div class="post-meta">
			<?php mnky_post_meta(); ?>
			</div>
			
			<?php if (!is_single()) { ?>
				<a class="post-link" href="<?php the_permalink(); ?>"><?php _e( 'Read more', 'kickstart' ) ?></a>
			<?php } ?>
			
			<div class="clear"></div>
		</div>
	</div><!-- post -->
