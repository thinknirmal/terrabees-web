<?php

/* --------------------------------------------------------- */
/* !Get the settings - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings') ) {
function mtphr_dnt_twitter_settings() {
	$settings = get_option( 'mtphr_dnt_twitter_settings', array() );
	return wp_parse_args( $settings, mtphr_dnt_twitter_settings_defaults() );
}
}
if( !function_exists('mtphr_dnt_twitter_settings_defaults') ) {
function mtphr_dnt_twitter_settings_defaults() {
	$defaults = array(
		'redirect_uri' => get_admin_url().'?ditty_twitter_authorize=true',
		'key' => '',
		'secret' => '',
		'token' => '',
		'token_secret' => '',
		'access_token' => '',
		'userid' => '',
		'username' => '',
		'fullname' => '',
		'profile_picture' => '',
		'cache_time' => 10
	);
	return $defaults;
}
}



/* --------------------------------------------------------- */
/* !Setup the settings - 1.2.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_initialize_settings() {

	$settings = mtphr_dnt_twitter_settings();
	
	/* --------------------------------------------------------- */
	/* !Add the setting sections - 1.2.7 */
	/* --------------------------------------------------------- */

	add_settings_section( 'mtphr_dnt_twitter_settings_section', __( 'Ditty Twitter Ticker settings', 'ditty-twitter-ticker' ), false, 'mtphr_dnt_twitter_settings' );
	
	
	$reset_url = get_admin_url().'edit.php?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=twitter&settings-updated=reset';
	$reset_auth = '<p><a id="ditty-twitter-reset" class="button button-small" href="'.$reset_url.'">'.__('Reset Authorization', 'ditty-twitter-ticker').'</a></p>';
	$reset_info = '<p><a id="ditty-twitter-reset" class="button button-small" href="'.$reset_url.'">'.__('Reset Information', 'ditty-twitter-ticker').'</a></p>';
	
	/* --------------------------------------------------------- */
	/* !Add the settings - 1.2.3 */
	/* --------------------------------------------------------- */
	
	if( $settings['access_token'] == '' && ($settings['key'] == '' || $settings['secret'] == '') ) {
		
		/* API Information */
		$title = mtphr_dnt_twitter_settings_label( __( 'API Information', 'ditty-twitter-ticker' ), __('In order to connect to Twitter you must create a custom application with your account. Please follow the directions here to quickly get your application up and running!', 'ditty-twitter-ticker') );
		add_settings_field( 'mtphr_dnt_twitter_settings_api_info', $title, 'mtphr_dnt_twitter_settings_api_info', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', array('settings' => $settings) );
	
	} elseif( $settings['access_token'] == '' ) {
	
		if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) ) {
			
			// Get the access token
			$url = 'https://api.twitter.com/oauth/access_token';	
			$args = array(
				'oauth_token' => $_GET['oauth_token'],
				'oauth_verifier' => $_GET['oauth_verifier'],
			);
			$twitter = mtphr_dnt_twitter_oauth( $url, $args );	

			if( !is_wp_error($twitter) && $twitter['response']['code'] == '200' ) {
	
				parse_str( $twitter['body'], $response );
				
				// Update the token_secret
				$settings['access_token'] = $response['oauth_token'];
				$settings['token'] = $response['oauth_token'];
				$settings['token_secret'] = $response['oauth_token_secret'];
				$settings['userid'] = $response['user_id'];
				$settings['username'] = $response['screen_name'];
				
				update_option( 'mtphr_dnt_twitter_settings', $settings );

				//User Info
				$title = mtphr_dnt_twitter_settings_label( __( 'Twitter User Info', 'ditty-twitter-ticker' ), sprintf(__('Congratulation! You are now connected to Twitter. %s', 'ditty-twitter-ticker'), $reset_auth) );
				add_settings_field( 'mtphr_dnt_twitter_settings_user_info', $title, 'mtphr_dnt_twitter_settings_user_info', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', array('settings' => $settings) );
				
			} else {
				
				/* Error */
				$title = mtphr_dnt_twitter_settings_label( __( 'Twitter Error', 'ditty-twitter-ticker' ), sprintf(__('Whoops! There seems to have been an error connecting to Twitter. %s', 'ditty-twitter-ticker'), $reset_info) );
				add_settings_field( 'mtphr_dnt_twitter_settings_error', $title, 'mtphr_dnt_twitter_settings_error', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', array('settings' => $settings, 'error_code' => $twitter['response']['code'], 'error_message' => $twitter['response']['message']) );
				
			}

		} else {

			/* Authorization */
			$title = mtphr_dnt_twitter_settings_label( __( 'Authorization', 'ditty-twitter-ticker' ), sprintf(__('You must authorize your account with Twitter to use Ditty Twitter Ticker %s', 'ditty-twitter-ticker'), $reset_info) );
			add_settings_field( 'mtphr_dnt_twitter_settings_authorize', $title, 'mtphr_dnt_twitter_settings_authorize', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', array('settings' => $settings) );
		}

	} else {

		/* User Info */
		$title = mtphr_dnt_twitter_settings_label( __( 'Twitter User Info', 'ditty-twitter-ticker' ), sprintf(__('Congratulation! You are now connected to Twitter. %s', 'ditty-twitter-ticker'), $reset_auth) );
		add_settings_field( 'mtphr_dnt_twitter_settings_user_info', $title, 'mtphr_dnt_twitter_settings_user_info', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', array('settings' => $settings) );
	}
	
	/* Cache time */
	$title = mtphr_dnt_twitter_settings_label( __( 'Cache Time', 'ditty-twitter-ticker' ), __('Set the amount of time your feeds should stay cached', 'ditty-twitter-ticker') );
	add_settings_field( 'mtphr_dnt_twitter_settings_cache_time', $title, 'mtphr_dnt_twitter_settings_cache_time', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_section', array('settings' => $settings) );


	
	/* --------------------------------------------------------- */
	/* !Register the settings - 1.2.3 */
	/* --------------------------------------------------------- */

	if( false == get_option('mtphr_dnt_twitter_settings') ) {
		add_option( 'mtphr_dnt_twitter_settings' );
	}
	register_setting( 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings', 'mtphr_dnt_twitter_settings_sanitize' );
}
add_action( 'admin_init', 'mtphr_dnt_twitter_initialize_settings' );



/* --------------------------------------------------------- */
/* !API Information - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_api_info') ) {
function mtphr_dnt_twitter_settings_api_info( $args ) {

	$settings = $args['settings'];
	
	echo '<div id="mtphr_dnt_twitter_settings_api_info">';
		echo '<table class="mtphr-dnt-instructions">';
			
			echo '<div id="mtphr_dnt_twitter_settings_api_info">';
		echo '<table class="mtphr-dnt-instructions">';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">1</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						$url = '<a href="https://apps.twitter.com" target="_blank"><strong>'.__('My applications', 'ditty-twitter-ticker').'</strong></a>';
						echo sprintf(__( 'Go to your Twitter Apps page %s and log into your account', 'ditty-twitter-ticker' ), $url);
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">2</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						$url = '<a class="button button-small" href="https://apps.twitter.com/app/new" target="_blank"><strong>'.__('Create New App', 'ditty-twitter-ticker').'</strong></a>';
						echo sprintf(__( 'Select %s', 'ditty-twitter-ticker' ), $url);
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">3</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Give your application an appropriate <strong>Name</strong> and <strong>Description</strong>', 'ditty-twitter-ticker' );
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">4</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Copy and paste the following URL into the <strong>Website</strong> field', 'ditty-twitter-ticker' ).'<br/>';
						echo '<pre><strong>'.home_url().'</strong></pre>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">5</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Copy and paste the following URL into the <strong>Callback URL</strong> field', 'ditty-twitter-ticker' ).'<br/>';
						echo '<pre><strong>'.$settings['redirect_uri'].'</strong></pre>';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">6</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Select the checkbox to agree to the <strong>Developer Agreement</strong>', 'ditty-twitter-ticker' ).' ';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">7</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Select <strong>Create your Twitter Application</strong> to register your new application', 'ditty-twitter-ticker' ).' ';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">8</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Once your application is created select the <strong>Keys and Access Tokens</strong> tab on your App page', 'ditty-twitter-ticker' ).' ';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">9</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Copy and paste the <strong>Consumer Key (API Key)</strong> here', 'ditty-twitter-ticker' ).' ';
						echo '<input style="width:auto;" type="text" name="mtphr_dnt_twitter_settings[key]" value="'.$settings['key'].'" placeholder="'.__('API key', 'ditty-twitter-ticker').'" size="30" />';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">10</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Copy and paste the <strong>Consumer Secret (API Secret)</strong> here', 'ditty-twitter-ticker' ).' ';
						echo '<input style="width:auto;" type="text" name="mtphr_dnt_twitter_settings[secret]" value="'.$settings['secret'].'" placeholder="'.__('API secret', 'ditty-twitter-ticker').'" size="30" />';
					echo '</div>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr class="mtphr-dnt-instruction">';
				echo '<td class="mtphr-dnt-instruction-label">';
					echo '<span class="mtphr-dnt-instruction-number">11</span>';
				echo '</td>';
				echo '<td class="mtphr-dnt-instruction-info">';
					echo '<div class="mtphr-dnt-instruction-info-wrapper">';
						echo __( 'Select <strong>Save Changes</strong> below', 'ditty-twitter-ticker' ).' ';
					echo '</div>';
				echo '</td>';
			echo '</tr>';

		echo '</table>';

	echo '</div>';
}
}


/* --------------------------------------------------------- */
/* !Authorize - 1.2.7 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_authorize') ) {
function mtphr_dnt_twitter_settings_authorize( $args ) {
	
	$settings = $args['settings'];
	
	echo '<div id="mtphr_dnt_twitter_settings_authorize" class="clearfix">';	
	
		$url = 'https://api.twitter.com/oauth/request_token';
		$callback = get_admin_url().'?ditty_twitter_authorize=true';
		
		// Remove any tildes from the callback
		$callback = preg_replace( '%~%', '', $callback );
		
		$args = array(
			'oauth_callback' => urlencode( $callback )
		);
		$fields = array(
			'oauth_callback' => $callback
		);
		$twitter = mtphr_dnt_twitter_oauth( $url, $args, $fields );

		if( is_wp_error($twitter) ) {
		
	   $error_string = $twitter->get_error_message();
	   echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
	   
		} elseif( $twitter['response']['code'] == '200' ) {

			parse_str( $twitter['body'], $response );
			
			// Update the token_secret
			$settings['token'] = $response['oauth_token'];
			$settings['token_secret'] = $response['oauth_token_secret'];
			update_option( 'mtphr_dnt_twitter_settings', $settings );
			
			// Render the authorize button
			echo '<p><a id="ditty-twitter-authorize" class="button button-primary" href="https://api.twitter.com/oauth/authorize?oauth_token='.$response['oauth_token'].'">'.__('Authorize Twitter', 'ditty-twitter-ticker').'</a></p>';

		} else {

			echo '<div id="message" class="error"><p>'.sprintf(__('Error: %s', 'ditty-twitter-ticker'), $twitter['body']).'</p></div>';
		}
		
	echo '</div>';
}
}


/* --------------------------------------------------------- */
/* !Error - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_error') ) {
function mtphr_dnt_twitter_settings_error( $args ) {
	
	$settings = $args['settings'];
	$error_code = $args['error_code'];
	$error_message = $args['error_message'];
	
	echo '<div id="mtphr_dnt_twitter_settings_error" class="clearfix">';	
	
		echo '<p><strong>'.__('Sorry, there was an issue authorizing Twitter.', 'ditty-twitter-ticker').'</strong><br/>';
		echo sprintf(__('Error %s:', 'ditty-twitter-ticker'), $error_code).' '.$error_message.'</p>';
		
	echo '</div>';
}
}


/* --------------------------------------------------------- */
/* !User Info - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_user_info') ) {
function mtphr_dnt_twitter_settings_user_info( $args ) {

	$settings = $args['settings'];
	
	echo '<div id="mtphr_dnt_twitter_settings_user_info" class="clearfix">';	
		if( $settings['access_token'] != '' ) {
			echo '<div id="ditty-twitter-credentials">';
				if( $settings['profile_picture'] == '' ) {		
					$userinfo = mtphr_dnt_twitter_userinfo( $settings['username'], $settings );
					if( $userinfo ) {
						$settings['fullname'] = $userinfo['name'];
						$settings['profile_picture'] = $userinfo['profile_image_url'];
						update_option( 'mtphr_dnt_twitter_settings', $settings );
					}
					echo '<img src="'.$settings['profile_picture'].'" />';
				} else {
					echo '<img src="'.$settings['profile_picture'].'" />';
				}
				echo '<p>'.$settings['username'].'</p>';
			echo '</div>';
		}
	echo '</div>';
}
}


/* --------------------------------------------------------- */
/* !Cache time - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_cache_time') ) {
function mtphr_dnt_twitter_settings_cache_time( $args ) {

	$settings = $args['settings'];
	
	echo '<div id="mtphr_dnt_twitter_settings_cache_time" class="clearfix">';	
		echo '<label><input class="small-text" type="number" name="mtphr_dnt_twitter_settings[cache_time]" value="'.$settings['cache_time'].'" /> '.__('Minutes', 'ditty-twitter-ticker').'</label>';
	echo '</div>';
}
}



/* --------------------------------------------------------- */
/* !Create a settings label - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_label') ) {
function mtphr_dnt_twitter_settings_label( $title, $description = '' ) {

	$label = '<div class="mtphr-dnt-label-alt">';
		$label .= '<label>'.$title.'</label>';
		if( $description != '' ) {
			$label .= '<small>'.$description.'</small>';
		}
	$label .= '</div>';

	return $label;
}
}



/* --------------------------------------------------------- */
/* !Sanitize the setting fields - 1.2.3 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_settings_sanitize') ) {
function mtphr_dnt_twitter_settings_sanitize( $fields ) {

	$settings = mtphr_dnt_twitter_settings();
	
	// Twitter
	$fields['key'] = isset( $fields['key'] ) ? sanitize_text_field($fields['key']) : $settings['key'];
	$fields['secret'] = isset( $fields['secret'] ) ? sanitize_text_field($fields['secret']) : $settings['secret'];
	$fields['cache_time'] = isset( $fields['cache_time'] ) ? intval($fields['cache_time']) : $settings['cache_time'];
	
	return wp_parse_args( $fields, get_option('mtphr_dnt_twitter_settings', array()) );

	return $fields;
}
}
