<?php
/*
Plugin Name: Ditty Twitter Ticker
Plugin URI: http://dittynewsticker.com/ditty-twitter-ticker/
Description: Add a twitter ticker type to your <a href="http://wordpress.org/extend/plugins/ditty-news-ticker/">Ditty News Tickers</a>. Display twitter feeds in a ticker, rotator, or list.
Version: 1.2.16
Author: Metaphor Creations
Author URI: http://www.metaphorcreations.com
License: GPL2
*/

/*
Copyright 2012 Metaphor Creations  (email : joe@metaphorcreations.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/




/**
 * Define constants
 *
 * @since 1.2.16
 */

define ( 'MTPHR_DNT_TWITTER_VERSION', '1.2.16' );
define ( 'MTPHR_DNT_TWITTER_DIR', plugin_dir_path(__FILE__) );
define ( 'MTPHR_DNT_TWITTER_URL', plugins_url().'/ditty-twitter-ticker' );



if( !function_exists('is_plugin_active_for_network') ) {
	require_once( ABSPATH.'/wp-admin/includes/plugin.php' );
}
if( is_plugin_active('ditty-news-ticker/ditty-news-ticker.php') || is_plugin_active_for_network('ditty-news-ticker/ditty-news-ticker.php') ) {
	
	/* --------------------------------------------------------- */
	/* !Include files - 1.2.7 */
	/* --------------------------------------------------------- */
	
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/update.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/scripts.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/filters.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/helpers.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/functions.php' );
	require_once( MTPHR_DNT_TWITTER_DIR.'includes/settings.php' );
	
	if( is_admin() ) {
		require_once( MTPHR_DNT_TWITTER_DIR.'includes/admin/scripts.php' );
		require_once( MTPHR_DNT_TWITTER_DIR.'includes/admin/meta-boxes.php' );
		require_once( MTPHR_DNT_TWITTER_DIR.'includes/admin/notices.php' );
	}
	
} else {
	
	
	/* --------------------------------------------------------- */
	/* !Display an admin notice to install Ditty News Ticker - 1.2.1 */
	/* --------------------------------------------------------- */
	
	function mtphr_dnt_twitter_admin_notice(){
		$url = get_bloginfo('wpurl').'/wp-admin/plugin-install.php?tab=plugin-information&plugin=ditty-news-ticker&TB_iframe=true&width=640&height=500';
    echo '<div class="error"><p>'.sprintf(__('<a class="thickbox" href="%s"><strong>Ditty News Ticker</strong></a> must be installed and activated to use Ditty Twitter Ticker.','ditty-twitter-ticker'), $url).'</p></div>';
	}
	add_action('admin_notices', 'mtphr_dnt_twitter_admin_notice');
}


