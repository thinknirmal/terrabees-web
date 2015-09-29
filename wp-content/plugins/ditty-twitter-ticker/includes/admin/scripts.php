<?php

/* --------------------------------------------------------- */
/* !Load the admin scripts - 1.2.8 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_admin_scripts( $hook ) {

	global $typenow;

	if( $hook == 'ditty_news_ticker_page_mtphr_dnt_settings' || $typenow == 'ditty_news_ticker' ) {
	
		$settings = mtphr_dnt_twitter_settings();

		// Load the admin scripts
		wp_register_style( 'ditty-twitter-ticker', MTPHR_DNT_TWITTER_URL.'/assets/css/admin/style.css', false, MTPHR_DNT_TWITTER_VERSION );
		wp_enqueue_style( 'ditty-twitter-ticker' );
		wp_register_script( 'ditty-twitter-ticker', MTPHR_DNT_TWITTER_URL.'/assets/js/admin/script.js', array('jquery'), MTPHR_DNT_TWITTER_VERSION, true );
		wp_enqueue_script( 'ditty-twitter-ticker' );
		wp_localize_script( 'ditty-twitter-ticker', 'ditty_twitter_ticker_vars', array(
				'security' => wp_create_nonce( 'ditty-twitter-ticker' ),
				'username' => $settings['username']
			)
		);
	}
}
add_action( 'admin_enqueue_scripts', 'mtphr_dnt_twitter_admin_scripts' );