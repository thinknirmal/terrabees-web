<!DOCTYPE html>
<!--[if IE 6]><html id="ie6" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 7]><html id="ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html id="ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
	
	<meta charset="utf-8" />
	<?php $detect = new Mobile_Detect();
	if ((ot_get_option('responsive_layout') == 'responsive_mobile' && !$detect->isTablet()) || ot_get_option('responsive_layout') == 'responsive_all') { 
		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
	} ?>

	<title><?php bloginfo('name'); ?>  <?php wp_title('-'); ?></title>

	<?php if (ot_get_option('favicon')){
		echo '<link rel="shortcut icon" href="'. ot_get_option('favicon') .'" />';
	} 

	if (ot_get_option('ipad_retina_icon')){
		echo '<link rel="apple-touch-icon" sizes="144x144" href="'. ot_get_option('ipad_retina_icon') .'" >';
	} 

	if (ot_get_option('iphone_retina_icon')){
		echo '<link rel="apple-touch-icon" sizes="114x114" href="'. ot_get_option('iphone_retina_icon') .'" >';
	}

	if (ot_get_option('ipad_icon')){
		echo '<link rel="apple-touch-icon" sizes="72x72" href="'. ot_get_option('ipad_icon') .'" >';
	} 

	if (ot_get_option('iphone_icon')){
		echo '<link rel="apple-touch-icon" href="'. ot_get_option('iphone_icon') .'" >';
	} ?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
	
	<!--[if IE 7 ]>
	<link href="<?php echo MNKY_CSS ?>/ie7.css" media="screen" rel="stylesheet" type="text/css">
	<![endif]-->
	<!--[if IE 8 ]>
	<link href="<?php echo MNKY_CSS ?>/ie8.css" media="screen" rel="stylesheet" type="text/css">
	<![endif]-->
	<!--[if lte IE 6]>
	<div id="ie-message">Your browser is obsolete and does not support this webpage. Please use newer version of your browser or visit <a href="http://www.ie6countdown.com/" target="_new">Internet Explorer 6 countdown page</a>  for more information. </div>
	<![endif]-->

	<?php echo ot_get_option('analytics_code'); ?>  
	<?php wp_head(); ?>
        
        <!-- start Mixpanel --><script type="text/javascript">(function(f,b){if(!b.__SV){var a,e,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=f.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";e=f.getElementsByTagName("script")[0];e.parentNode.insertBefore(a,e)}})(document,window.mixpanel||[]);
mixpanel.init("701081698ddfcbae6527cb7e85e970b1");</script><!-- end Mixpanel -->
</head>
<body <?php body_class('no-title-wrapper'); ?>>
<!-- Layout wrapper -->
	<div id="layout-wrapper" class="<?php echo ot_get_option('theme_layout', 'full-width'); ?>">

<!-- Top bar -->
	<?php if(ot_get_option('top_bar')) {
		echo '<div id="top-bar-wrapper">
			<div id="top-bar">';
				get_sidebar('top-left'); 
				get_sidebar('top-right');
			echo '<div class="clear"></div>
			</div>
		</div>';
	} ?>
			
<!-- Header -->
	<div id="header-wrapper">
		<div id="header" class="size-wrap">
			
			<div id="logo">
				<?php 
				$default_logo = ot_get_option('logo_upload');
				$retina_logo = ot_get_option('retina_logo_upload');
				if (!$default_logo){
					echo '<a href="'. home_url() .'">
							<h1>', bloginfo('name') .'</h1>
						</a>';
				} else {
					if ($retina_logo){
						$retina_logo_width = ot_get_option('retina_logo_width');
						$retina_logo_width = str_replace("px", "", $retina_logo_width);
						$retina_logo_height = ot_get_option('retina_logo_height');
						$retina_logo_height = str_replace("px", "", $retina_logo_height);
						echo '<a href="'. home_url() .'">
							<img src="'. $default_logo .'" alt="', bloginfo('name') .'" class="default-logo" />
							<img src="'. $retina_logo .'" width="'. $retina_logo_width .'" height="'. $retina_logo_height .'" alt="', bloginfo('name') .'" class="retina-logo" />
						</a>';
					} else {
						echo '<a href="'. home_url() .'">
							<img src="'. $default_logo .'" alt="', bloginfo('name') .'" />
						</a>';
					}
				}
				
				?>
			</div>
			
			<?php get_sidebar('header') ?>
			<div id="menu-wrapper">
				<?php if (!ot_get_option('header_search')) { ?>
					<a class="toggleMenu" href="#"><?php _e('Menu', 'kickstart'); ?><span></span><div class="clear"></div></a>
					<?php wp_nav_menu( array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul id="primary-main-menu" class=%2$s>%3$s<li class="header-search-toggle"><a href="#">'. __('Search', 'kickstart') .'</a></li></ul>', 'fallback_cb' => false)); ?>
				<?php } else { ?>
					<a class="toggleMenu" href="#"><?php _e('Menu', 'kickstart'); ?><span></span><div class="clear"></div></a>
					<?php wp_nav_menu( array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul id="primary-main-menu" class=%2$s>%3$s</ul>', 'fallback_cb' => false)); ?>
				<?php } ?>	
				<div class="clear"></div>
			</div>
			
			<?php if (!ot_get_option('header_search')) { 
				echo '<div id="header-search-wrapper" >'; 
					get_search_form();
				echo '</div>';
			} ?>
			
		</div>
	</div>

<!-- Content wrapper -->
	<div id="wrapper" class="size-wrap">