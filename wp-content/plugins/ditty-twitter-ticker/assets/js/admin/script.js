jQuery( document ).ready( function($) {

	/* --------------------------------------------------------- */
	/* !Adjust the feed handle for mentions & home timelines - 1.2.8 */
	/* --------------------------------------------------------- */
	
	function mtphr_dnt_twitter_set_handle( $select ) {
	
		var $parent = $select.parents('.mtphr-dnt-list-item'),
				$handle = $parent.find('.mtphr-dnt-twitter-handle').children('input');
	
		if( $select.val() == 'mentions_timeline' || $select.val() == 'home_timeline' || $select.val() == 'retweets_of_me' ) {
			$handle.val( ditty_twitter_ticker_vars.username );
			$handle.attr('disabled', 'disabled');
		} else {
			$handle.removeAttr('disabled');
		}
	}
	
	$('.mtphr-dnt-twitter-type select').each( function(index) {
		mtphr_dnt_twitter_set_handle( $(this) );
	});
		
	$('.mtphr-dnt-twitter-type select').live('change', function() {
		mtphr_dnt_twitter_set_handle( $(this) );
	});
	
});