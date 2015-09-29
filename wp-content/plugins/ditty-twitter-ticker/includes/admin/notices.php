<?php

/* --------------------------------------------------------- */
/* !Create an admin notice to create settings - 1.2.4 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_settings_notice(){

	if( !mtphr_dnt_twitter_check_access() ) {

		$link = admin_url().'edit.php?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=twitter';
		?>
    <div class="error">
       <p><?php _e('You must authorize <strong>Ditty Twitter Ticker</strong> access through Twitter before you can display any feeds.', 'ditty-twitter-ticker'); ?><br/><?php printf( __('<a href="%s"><strong>Click here</strong></a> for instructions on creating an app and granting acces to <strong>Ditty Twitter Ticker</strong>.', 'ditty-twitter-ticker'), $link ); ?></p>
    </div>
    <?php
  }
}
add_action('admin_notices', 'mtphr_dnt_twitter_settings_notice');



 
/* --------------------------------------------------------- */
/* !Create an admin notice for Twitter handles - 1.2.8 */
/* --------------------------------------------------------- */

function mmtphr_dnt_twitter_admin_notice(){

	global $typenow;

	// Register admin js
	if( $typenow == 'ditty_news_ticker' ) {

		global $post;
		if( $post ) {

			$type = get_post_meta( $post->ID, '_mtphr_dnt_type', true );
			if( $type == 'twitter' ) {

				$error  = false;
				$handle_data = get_post_meta( $post->ID, '_mtphr_dnt_twitter_handles', true );
				if( is_array($handle_data) ) {
					foreach( $handle_data as $data ) {
						if( !($data['type'] == 'mentions_timeline' || $data['type'] == 'home_timeline') ) {
							if( $data['handle'] == '' ) {
								$error = true;
							}
						}	
					}
				}
				if( $error ) {
					echo '<div class="error"><p>'.__('Please include at least one Twitter <strong>handle</strong> or <strong>keyword</strong> to display Tweets!','ditty-twitter-ticker').'</p></div>';
				}
			}
	  }
  }
}
add_action('admin_notices', 'mmtphr_dnt_twitter_admin_notice');
