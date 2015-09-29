<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '25Passme$');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'O_{yb6O9_{79}BL@w210:.U:A>2o_bkx2IrxyI#F?~i?J)|D0A[lLrJmfA+y&xq{');
define('SECURE_AUTH_KEY',  'n[<I)U87I$t4Du(i,w[l>CC$+F}:lot|>VE^+&P9SR38+{0d5__1:-+yVi611- L');
define('LOGGED_IN_KEY',    '8i;-{jGG32umJx&/|vjid_yaM+LH|(2Qgz:euOpbf;+d&U+$<7@@4n9EZ+#s+ jW');
define('NONCE_KEY',        '.LmdBnYc4S2?TS|S|y0}|Ky7wI&&>ToIOg|c9G39&d>_!k;K=4f]!;7vv2B2azw$');
define('AUTH_SALT',        'y2)kiodGrs+<p?fL16,lW}U>ljMH5u)g>n@j_CyCDPSi1<hIj4iYgY3c+;Ufg)zj');
define('SECURE_AUTH_SALT', '5?&|)?j4~96yBAYt(+|qvPb.6JJ|jr%sNG%:B^m(Ik W9;bq@ePjmm71Y^P|V@A6');
define('LOGGED_IN_SALT',   '%qzntb:yHm#JPV>rR#nTjo)1vjqA:MT.-U3SV]G)QJkgjv)_FiM|[d/W_WK]Ry]%');
define('NONCE_SALT',       '7-`Xk1hag0<+DHgB6k$eMK-4e.}s_P#G?7c[lcuK|^ aQTvt{Q*iN}*,9BReIe3:');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'tb_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

define('FS_METHOD', 'direct'); // for automatic plugin installation

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
