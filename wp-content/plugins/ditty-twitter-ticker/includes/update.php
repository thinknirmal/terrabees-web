<?php

/* --------------------------------------------------------- */
/* !Auto updater script - 1.2.12 */
/* --------------------------------------------------------- */

require MTPHR_DNT_TWITTER_DIR.'/includes/plugin-updates/plugin-update-checker.php';
$ExampleUpdateChecker = PucFactory::buildUpdateChecker(
	'http://www.metaphorcreations.com/envato/plugins/ditty-twitter-ticker/auto-update.json',
	MTPHR_DNT_TWITTER_DIR.'ditty-twitter-ticker.php'
);
//$ExampleUpdateChecker->checkForUpdates();