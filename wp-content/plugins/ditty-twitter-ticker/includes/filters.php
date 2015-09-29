<?php


/* --------------------------------------------------------- */
/* !Add the ticker type to the ticker - 1.2.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_type( $types ) {
	$types['twitter'] = array(
		'button' => __('Twitter', 'ditty-twitter-ticker'),
		'metaboxes' => array( 'mtphr_dnt_twitter_metabox' )
	);
	return $types;
}
add_filter( 'mtphr_dnt_types', 'mtphr_dnt_twitter_type' );



/* --------------------------------------------------------- */
/* !Add a new settings page - 1.2.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_add_settings( $settings ) {
	$settings['twitter'] = 'mtphr_dnt_twitter_settings';
	return $settings;
}
add_filter( 'mtphr_dnt_settings', 'mtphr_dnt_twitter_add_settings' );



/* --------------------------------------------------------- */
/* !Modify the settings submit button  - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_settings_submit_button( $button ) {
	
	if( isset($_GET['tab']) && ($_GET['tab'] == 'twitter') ) {
	
		$settings = mtphr_dnt_twitter_settings();
		
		if( $settings['access_token'] == '' && ($settings['key'] == '' || $settings['secret'] == '') ) {
			return get_submit_button();
		} else {
			return '';
		}	
	}

	return $button;
}
//add_filter( 'mtphr_dnt_settings_submit_button', 'mtphr_dnt_twitter_settings_submit_button' );



/* --------------------------------------------------------- */
/* !Redirect the user to the Twitter tab - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_redirect() {

  if( is_admin() && isset($_GET['ditty_twitter_authorize']) && isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ) {
  	$location = get_admin_url().'edit.php?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=twitter&oauth_token='.$_GET['oauth_token'].'&oauth_verifier='.$_GET['oauth_verifier'];
    wp_redirect( $location );
		exit;
  }
}
add_action( 'wp_loaded', 'mtphr_dnt_twitter_redirect' );



/* --------------------------------------------------------- */
/* !Reset the twitter options - 1.0.0 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_reset() {

  if( is_admin() && isset($_GET['page']) && isset($_GET['tab']) && isset($_GET['settings-updated']) ) {
  	if( $_GET['page'] == 'mtphr_dnt_settings' && $_GET['tab'] == 'twitter' && $_GET['settings-updated'] == 'reset' ) {
  		$settings = mtphr_dnt_twitter_settings();
  		$defaults = mtphr_dnt_twitter_settings_defaults();
  		$cache_time = $settings['cache_time'];
  		$defaults['cache_time'] = $cache_time;
	  	update_option( 'mtphr_dnt_twitter_settings', $defaults );
		}
  }
}
add_action( 'wp_loaded', 'mtphr_dnt_twitter_reset' );

