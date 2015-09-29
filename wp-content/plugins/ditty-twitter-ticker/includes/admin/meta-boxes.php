<?php

/* --------------------------------------------------------- */
/* !Add the twitter type metabox - 1.2.3 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_metabox() {

	add_meta_box( 'mtphr_dnt_twitter_metabox', __('Twitter Ticker Data', 'ditty-twitter-ticker'), 'mtphr_dnt_twitter_render_metabox', 'ditty_news_ticker', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'mtphr_dnt_twitter_metabox', 1 );



/* --------------------------------------------------------- */
/* !Render the default type metabox - 1.2.15 */
/* --------------------------------------------------------- */

if( !function_exists('mtphr_dnt_twitter_render_metabox') ) {
function mtphr_dnt_twitter_render_metabox() {

	$settings = mtphr_dnt_twitter_settings();

	global $post;

	$type = get_post_meta( $post->ID, '_mtphr_dnt_twitter_type', true );
	$handles = get_post_meta( $post->ID, '_mtphr_dnt_twitter_handles', true );
	if( $handles == '' ) {
		$handles = array( array(
			'handle' => $settings['username'],
			'type' => 'user_timeline'
		));
	}
	
	$limit = get_post_meta( $post->ID, '_mtphr_dnt_twitter_limit', true );
	$limit = ( $limit == '' ) ? 10 : $limit;
	
	$retweets = get_post_meta( $post->ID, '_mtphr_dnt_twitter_hide_retweets', true );
	$replies = get_post_meta( $post->ID, '_mtphr_dnt_twitter_hide_replies', true );
	$disbursement = get_post_meta( $post->ID, '_mtphr_dnt_twitter_disbursement', true );
	
	$direct_link = get_post_meta( $post->ID, '_mtphr_dnt_twitter_direct_link', true );
	
	$display_order = get_post_meta( $post->ID, '_mtphr_dnt_twitter_display_order', true );
	if( !is_array($display_order) ) {
		$display_order = array('avatar', 'name', 'text', 'time', 'links');
	}
	if( !in_array('links', $display_order) ) {
		$display_order[] = 'links';
	}	
	
	$avatar = get_post_meta( $post->ID, '_mtphr_dnt_twitter_avatar', true );
	$avatar_dimensions = get_post_meta( $post->ID, '_mtphr_dnt_twitter_avatar_dimensions', true );
	$avatar_dimensions = ( $avatar_dimensions == '' ) ? 40 : $avatar_dimensions;
	
	$avatar_left = get_post_meta( $post->ID, '_mtphr_dnt_twitter_avatar_left', true );
	$avatar_link = get_post_meta( $post->ID, '_mtphr_dnt_twitter_avatar_link', true );
	$avatar_display = get_post_meta( $post->ID, '_mtphr_dnt_twitter_avatar_display', true );
	$avatar_display = ( $avatar_display == '' ) ? 'inline' : $avatar_display;
	
	$name = get_post_meta( $post->ID, '_mtphr_dnt_twitter_name', true );
	$name_handle = get_post_meta( $post->ID, '_mtphr_dnt_twitter_handle', true );
	$name_link = get_post_meta( $post->ID, '_mtphr_dnt_twitter_name_link', true );
	$name_display = get_post_meta( $post->ID, '_mtphr_dnt_twitter_name_display', true );
	$name_display = ( $name_display == '' ) ? 'inline' : $name_display;
	
	$text = get_post_meta( $post->ID, '_mtphr_dnt_twitter_text_display', true );
	$text = ( $text == '' ) ? 'block' : $text;
	
	$time = get_post_meta( $post->ID, '_mtphr_dnt_twitter_time', true );
	$time_format = get_post_meta( $post->ID, '_mtphr_dnt_twitter_time_format', true );
	$time_format = ( $time_format == '' ) ? '{time} '.__('ago', 'ditty-twitter-ticker') : $time_format;
	
	$time_display = get_post_meta( $post->ID, '_mtphr_dnt_twitter_time_display', true );
	$time_display = ( $time_display == '' ) ? 'inline' : $time_display;
	
	$links = get_post_meta( $post->ID, '_mtphr_dnt_twitter_links', true );
	$reply = ( isset($links['reply']) && $links['reply'] != '' ) ? 1 : '';
	$retweet = ( isset($links['retweet']) && $links['retweet'] != '' ) ? 1 : '';
	$favorite = ( isset($links['favorite']) && $links['favorite'] != '' ) ? 1 : '';
	$links_display = get_post_meta( $post->ID, '_mtphr_dnt_twitter_links_display', true );
	$links_display = ( $links_display == '' ) ? 'inline' : $links_display;

	echo '<input type="hidden" name="mtphr_dnt_twitter_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	echo '<table class="mtphr-dnt-table">';
	
		if( mtphr_dnt_twitter_check_access() ) {
	
			echo '<tr>';
				echo '<td class="mtphr-dnt-label">';
					echo '<label>'.__('Handles', 'ditty-twitter-ticker').'</label>';
					echo '<small>'.__('Add an unlimited number of ticks to your ticker', 'ditty-twitter-ticker').'</small>';
				echo '</td>';
				echo '<td>';
					echo '<table class="mtphr-dnt-list mtphr-dnt-advanced-list mtphr-dnt-twitter-list">';
						if( is_array($handles) && count($handles) > 0 ) {
							foreach( $handles as $i=>$data ) {
								if( !isset($data['type']) ) {
									$data['type'] = $type;
								}
								mtphr_dnt_render_twitter_handle( $data );
							}
						} else {
							mtphr_dnt_render_twitter_handle();
						}
					echo '</table>';
				echo '</td>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td class="mtphr-dnt-label">';
					echo '<label>'.__('Feed options', 'ditty-twitter-ticker').'</label>';
					echo '<small>'.__('Limit the number of tweets to show and set additional options', 'ditty-twitter-ticker').'</small>';
				echo '</td>';
				echo '<td>';
					echo '<label style="margin-right:20px;"><input type="number" name="_mtphr_dnt_twitter_limit" value="'.$limit.'" /> '.__('Limit', 'ditty-twitter-ticker').'</label>';
					echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_hide_retweets" value="1" '.checked(1, $retweets, false).' /> '.__('Hide retweets', 'ditty-twitter-ticker').'</label>';
					echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_hide_replies" value="1" '.checked(1, $replies, false).' /> '.__('Hide replies', 'ditty-twitter-ticker').'</label>';
					echo '<label><input type="checkbox" name="_mtphr_dnt_twitter_disbursement" value="1" '.checked(1, $disbursement, false).' /> '.__('Even disbursement', 'ditty-twitter-ticker').'</label>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td class="mtphr-dnt-label">';
					echo '<label>'.__('Direct link', 'ditty-twitter-ticker').'</label>';
					echo '<small>'.__('Convert the full tick into a direct link to the original tweet', 'ditty-twitter-ticker').'</small>';
				echo '</td>';
				echo '<td>';
					echo '<label style="margin-right:20px;margin-bottom:0;"><input type="checkbox" name="_mtphr_dnt_twitter_direct_link" value="1" '.checked(1, $direct_link, false).' /> '.__('Link ticks to original tweet', 'ditty-twitter-ticker').'</label>';
					echo '<small style="display:block;font-style:italic;">*'.__('Enabling this featured will disable all other links for the tick and hide the Tweet links', 'ditty-twitter-ticker').'</small>';
			echo '</tr>';
			
			echo '<tr>';
				echo '<td class="mtphr-dnt-label">';
					echo '<label>'.__('Feed item arrangement', 'ditty-twitter-ticker').'</label>';
					echo '<small>'.__('Set the tweet assets and order', 'ditty-twitter-ticker').'</small>';
				echo '</td>';
				echo '<td>';
					echo '<table class="mtphr-dnt-list mtphr-dnt-sort-list mtphr-dnt-twitter-assets">';
						if( is_array($display_order) && count($display_order) > 0 ) {
							foreach( $display_order as $ds ) {
								switch( $ds ) {
									case 'avatar':
										echo '<tr class="mtphr-dnt-list-item">';
											echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
											echo '<td>';
												echo '<input type="hidden" name="_mtphr_dnt_twitter_display_order[]" value="avatar" />';
												echo '<label style="margin-right:20px;"><strong>'.__('User avatar', 'ditty-twitter-ticker').'</strong> <input type="checkbox" name="_mtphr_dnt_twitter_avatar" value="1" '.checked(1, $avatar, false).' /></label>';
												echo '<label style="margin-right:20px;">'.__('Dimensions', 'ditty-twitter-ticker').' <input type="number" name="_mtphr_dnt_twitter_avatar_dimensions" value="'.$avatar_dimensions.'" /></label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_avatar_left" value="1" '.checked(1, $avatar_left, false).' /> '.__('Lock Left', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_avatar_link" value="1" '.checked(1, $avatar_link, false).' /> '.__('Link', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:10px;"><input type="radio" name="_mtphr_dnt_twitter_avatar_display" value="inline" '.checked('inline', $avatar_display, false).' /> '.__('Inline', 'ditty-twitter-ticker').'</label>';
												echo '<label><input type="radio" name="_mtphr_dnt_twitter_avatar_display" value="block" '.checked('block', $avatar_display, false).' /> '.__('Block', 'ditty-twitter-ticker').'</label>';
											echo '</td>';
										echo '</tr>';
										break;
									case 'name':
										echo '<tr class="mtphr-dnt-list-item">';
											echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
											echo '<td>';
												echo '<input type="hidden" name="_mtphr_dnt_twitter_display_order[]" value="name" />';
												echo '<label style="margin-right:20px;"><strong>'.__('User name', 'ditty-twitter-ticker').'</strong> <input type="checkbox" name="_mtphr_dnt_twitter_name" value="1" '.checked(1, $name, false).' /></label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_handle" value="1" '.checked(1, $name_handle, false).' /> '.__('Handle', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_name_link" value="1" '.checked(1, $name_link, false).' /> '.__('Link', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:10px;"><input type="radio" name="_mtphr_dnt_twitter_name_display" value="inline" '.checked('inline', $name_display, false).' /> '.__('Inline', 'ditty-twitter-ticker').'</label>';
												echo '<label><input type="radio" name="_mtphr_dnt_twitter_name_display" value="block" '.checked('block', $name_display, false).' /> '.__('Block', 'ditty-twitter-ticker').'</label>';
											echo '</td>';
										echo '</tr>';
										break;
									case 'text':
										echo '<tr class="mtphr-dnt-list-item">';
											echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
											echo '<td>';
												echo '<input type="hidden" name="_mtphr_dnt_twitter_display_order[]" value="text" />';
												echo '<label style="margin-right:3px;"><strong>'.__('Tweet Text', 'ditty-twitter-ticker').'</strong></label>';
												echo '<label style="margin-right:10px;"><input type="radio" name="_mtphr_dnt_twitter_text_display" value="inline" '.checked('inline', $text, false).' /> '.__('Inline', 'ditty-twitter-ticker').'</label>';
												echo '<label><input type="radio" name="_mtphr_dnt_twitter_text_display" value="block" '.checked('block', $text, false).' /> '.__('Block', 'ditty-twitter-ticker').'</label>';
											echo '</td>';
										echo '</tr>';
										break;
									case 'time':
										echo '<tr class="mtphr-dnt-list-item">';
											echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
											echo '<td>';
												echo '<input type="hidden" name="_mtphr_dnt_twitter_display_order[]" value="time" />';
												echo '<label style="margin-right:20px;"><strong>'.__('Tweet time', 'ditty-twitter-ticker').'</strong> <input type="checkbox" name="_mtphr_dnt_twitter_time" value="1" '.checked(1, $time, false).' /></label>';
												echo '<label style="margin-right:20px;">'.__('Format', 'ditty-twitter-ticker').' <input style="width:100px;" type="text" name="_mtphr_dnt_twitter_time_format" value="'.$time_format.'" /></label>';
												echo '<label style="margin-right:10px;"><input type="radio" name="_mtphr_dnt_twitter_time_display" value="inline" '.checked('inline', $time_display, false).' /> '.__('Inline', 'ditty-twitter-ticker').'</label>';
												echo '<label><input type="radio" name="_mtphr_dnt_twitter_time_display" value="block" '.checked('block', $time_display, false).' /> '.__('Block', 'ditty-twitter-ticker').'</label>';
											echo '</td>';
										echo '</tr>';
										break;
									case 'links':
										echo '<tr class="mtphr-dnt-list-item">';
											echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
											echo '<td>';
												echo '<input type="hidden" name="_mtphr_dnt_twitter_display_order[]" value="links" />';
												echo '<label style="margin-right:3px;"><strong>'.__('Tweet links', 'ditty-twitter-ticker').'</strong> </label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_links[reply]" value="1" '.checked(1, $reply, false).' /> '.__('Reply', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_links[retweet]" value="1" '.checked(1, $retweet, false).' /> '.__('Retweet', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:20px;"><input type="checkbox" name="_mtphr_dnt_twitter_links[favorite]" value="1" '.checked(1, $favorite, false).' /> '.__('Favorite', 'ditty-twitter-ticker').'</label>';
												echo '<label style="margin-right:10px;"><input type="radio" name="_mtphr_dnt_twitter_links_display" value="inline" '.checked('inline', $links_display, false).' /> '.__('Inline', 'ditty-twitter-ticker').'</label>';
												echo '<label><input type="radio" name="_mtphr_dnt_twitter_links_display" value="block" '.checked('block', $links_display, false).' /> '.__('Block', 'ditty-twitter-ticker').'</label>';
											echo '</td>';
										echo '</tr>';
										break;
								}
							}
						}
					echo '</table>';
				echo '</td>';
			echo '</tr>';
		
		} else {
			
			echo '<tr>';
				echo '<td class="mtphr-dnt-label">';
					$link = admin_url().'edit.php?post_type=ditty_news_ticker&page=mtphr_dnt_settings&tab=twitter';
					echo '<p>'.__('You must authorize <strong>Ditty Twitter Ticker</strong> access through Twitter before you can display any feeds.', 'ditty-twitter-ticker').'<br/>'.sprintf( __('<a href="%s"><strong>Click here</strong></a> for instructions on creating an app and granting acces to <strong>Ditty Twitter Ticker</strong>.', 'ditty-twitter-ticker'), $link ).'</p>';
				echo '</td>';
			echo '</tr>';
			
		}
		
	echo '</table>';
}
}

if( !function_exists('mtphr_dnt_render_twitter_handle') ) {
function mtphr_dnt_render_twitter_handle( $data=false ) {

	$handle = ( $data && isset($data['handle']) ) ? $data['handle'] : '';
	$type = ( $data && isset($data['type']) ) ? $data['type'] : '';

	echo '<tr class="mtphr-dnt-list-item">';
		echo '<td class="mtphr-dnt-list-handle"><span></span></td>';
		echo '<td class="mtphr-dnt-twitter-handle">';
			echo '<input type="text" name="_mtphr_dnt_twitter_handles[handle]" data-name="_mtphr_dnt_twitter_handles" data-key="handle" value="'.$handle.'" />';
		echo '</td>';
	  echo '<td class="mtphr-dnt-twitter-type">';
			echo '<select name="_mtphr_dnt_twitter_handles[type]" data-name="_mtphr_dnt_twitter_handles" data-key="type">';
				echo '<option value="user_timeline" '.selected('user_timeline', $type, false).'>'.__('User timeline', 'ditty-twitter-ticker').'</option>';
				echo '<option value="search" '.selected('search', $type, false).'>'.__('Keyword search', 'ditty-twitter-ticker').'</option>';
				echo '<option value="list" '.selected('list', $type, false).'>'.__('List (of registered user)', 'ditty-twitter-ticker').'</option>';
				echo '<option value="mentions_timeline" '.selected('mentions_timeline', $type, false).'>'.__('Mentions (of registered user)', 'ditty-twitter-ticker').'</option>';
				echo '<option value="home_timeline" '.selected('home_timeline', $type, false).'>'.__('Home timeline (of registered user)', 'ditty-twitter-ticker').'</option>';
				echo '<option value="retweets_of_me" '.selected('retweets_of_me', $type, false).'>'.__('Retweets (of registered user)', 'ditty-twitter-ticker').'</option>';
			echo '</select>';
		echo '</td>';
		echo '<td class="mtphr-dnt-list-delete"><a href="#"></a></td>';
		echo '<td class="mtphr-dnt-list-add"><a href="#"></a></td>';
	echo '</tr>';
}
}



/* --------------------------------------------------------- */
/* !Save the custom meta - 1.2.15 */
/* --------------------------------------------------------- */

function mtphr_dnt_twitter_metabox_save( $post_id ) {

	global $post;

	// verify nonce
	if (!isset($_POST['mtphr_dnt_twitter_nonce']) || !wp_verify_nonce($_POST['mtphr_dnt_twitter_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) return $post_id;

	// don't save if only a revision
	if ( isset($post->post_type) && $post->post_type == 'revision' ) return $post_id;

	// check permissions
	if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	// Update the type & mode
	if( isset($_POST['_mtphr_dnt_twitter_text_display']) ) {

		$limit = isset($_POST['_mtphr_dnt_twitter_limit']) ? intval($_POST['_mtphr_dnt_twitter_limit']) : 10;
		$retweets = isset($_POST['_mtphr_dnt_twitter_hide_retweets']) ? $_POST['_mtphr_dnt_twitter_hide_retweets'] : '';
		$replies = isset($_POST['_mtphr_dnt_twitter_hide_replies']) ? $_POST['_mtphr_dnt_twitter_hide_replies'] : '';
		$disbursement = isset($_POST['_mtphr_dnt_twitter_disbursement']) ? $_POST['_mtphr_dnt_twitter_disbursement'] : '';
		$direct_link = isset($_POST['_mtphr_dnt_twitter_direct_link']) ? $_POST['_mtphr_dnt_twitter_direct_link'] : '';
		$display_order = isset($_POST['_mtphr_dnt_twitter_display_order']) ? $_POST['_mtphr_dnt_twitter_display_order'] : '';
		$avatar = isset($_POST['_mtphr_dnt_twitter_avatar']) ? $_POST['_mtphr_dnt_twitter_avatar'] : '';
		$avatar_dimensions = isset($_POST['_mtphr_dnt_twitter_avatar_dimensions']) ? intval($_POST['_mtphr_dnt_twitter_avatar_dimensions']) : '';
		$avatar_left = isset($_POST['_mtphr_dnt_twitter_avatar_left']) ? $_POST['_mtphr_dnt_twitter_avatar_left'] : '';
		$avatar_link = isset($_POST['_mtphr_dnt_twitter_avatar_link']) ? $_POST['_mtphr_dnt_twitter_avatar_link'] : '';
		$avatar_display = isset($_POST['_mtphr_dnt_twitter_avatar_display']) ? $_POST['_mtphr_dnt_twitter_avatar_display'] : '';
		$name = isset($_POST['_mtphr_dnt_twitter_name']) ? $_POST['_mtphr_dnt_twitter_name'] : '';
		$name_handle = isset($_POST['_mtphr_dnt_twitter_handle']) ? $_POST['_mtphr_dnt_twitter_handle'] : '';
		$name_link = isset($_POST['_mtphr_dnt_twitter_name_link']) ? $_POST['_mtphr_dnt_twitter_name_link'] : '';
		$name_display = isset($_POST['_mtphr_dnt_twitter_name_display']) ? $_POST['_mtphr_dnt_twitter_name_display'] : '';
		$text = isset($_POST['_mtphr_dnt_twitter_text_display']) ? $_POST['_mtphr_dnt_twitter_text_display'] : '';
		$time = isset($_POST['_mtphr_dnt_twitter_time']) ? $_POST['_mtphr_dnt_twitter_time'] : '';
		$time_format = isset($_POST['_mtphr_dnt_twitter_time_format']) ? sanitize_text_field($_POST['_mtphr_dnt_twitter_time_format']) : '';
		$time_display = isset($_POST['_mtphr_dnt_twitter_time_display']) ? $_POST['_mtphr_dnt_twitter_time_display'] : '';
		$links = isset($_POST['_mtphr_dnt_twitter_links']) ? $_POST['_mtphr_dnt_twitter_links'] : array();
		$sanitaize_links = array(
			'reply' => isset($links['reply']) ? $links['reply'] : '',
			'retweet' => isset($links['retweet']) ? $links['retweet'] : '',
			'favorite' => isset($links['favorite']) ? $links['favorite'] : ''
		);
		$links_display = isset($_POST['_mtphr_dnt_twitter_links_display']) ? $_POST['_mtphr_dnt_twitter_links_display'] : '';		
		
		update_post_meta( $post_id, '_mtphr_dnt_twitter_limit', $limit );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_hide_retweets', $retweets );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_hide_replies', $replies );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_disbursement', $disbursement );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_direct_link', $direct_link );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_display_order', $display_order );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_avatar', $avatar );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_avatar_dimensions', $avatar_dimensions );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_avatar_left', $avatar_left );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_avatar_link', $avatar_link );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_avatar_display', $avatar_display );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_name', $name );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_handle', $name_handle );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_name_link', $name_link );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_name_display', $name_display );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_text_display', $text );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_time', $time );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_time_format', $time_format );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_time_display', $time_display );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_links', $sanitaize_links );
		update_post_meta( $post_id, '_mtphr_dnt_twitter_links_display', $links_display );
		
		// Save the rss items
		$handle_data = isset($_POST['_mtphr_dnt_twitter_handles']) ? $_POST['_mtphr_dnt_twitter_handles'] : false;
		$sanitized_handles = array();
		if( is_array($handle_data) && count($handle_data) > 0 ) {
			foreach( $handle_data as $i=>$data ) {
				$sanitized_handles[] = array(
					'handle' => isset($data['handle']) ? sanitize_text_field($data['handle']) : '',
					'type' =>  isset($data['type']) ? $data['type'] : 'user_timeline'
				);
			}
		}
		update_post_meta( $post_id, '_mtphr_dnt_twitter_handles', $sanitized_handles );
	}
}
add_action( 'save_post', 'mtphr_dnt_twitter_metabox_save' );
