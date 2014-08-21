<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'msl');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'ak0_Q|I6kyjoPd:[!h3drT<|_1kv`7Q-d3gCtpW_:q*+p0m?JT#H5}n*xeOWo?Q&');
define('SECURE_AUTH_KEY',  'Iu3a8<gPKNpQI-~ILu+uW~BdKg~+,dC@`0&Bh)B<DZT;.kE<;o ?|`MDK>M[jxr,');
define('LOGGED_IN_KEY',    'I8ANo_QR#Kh8ncfUM]G*d5&k7-R z-AG+-FVZIP5>.JbUEP+[i<4SxX89ty.-/9:');
define('NONCE_KEY',        ',?02/.S0Jq?&vcGR#vKFu`!LODeRh]ElMwizO/9i#>g,@-2{.>!uz&nm |fI=0BW');
define('AUTH_SALT',        'dYdW$+LV1/85I-oU#AuOz8OyA7r|RGRhy<FwZ2X-o!_>t-<M-&qu3d!QTDJ]9 Nc');
define('SECURE_AUTH_SALT', '&:+XJB5&{3a-oXGm!)7I1P$Zh@kpCPe69[,tZ]-K6G@f%,xp!.eL6CSjNs;(u$wm');
define('LOGGED_IN_SALT',   '&($/P0s.oL8+.ko[?rB(bFv{*w~RO-cb+VVZPMDxQ<pDt]#w<L+uXT1Jr*VjW>G{');
define('NONCE_SALT',       '21,{l13cR3-TJ}l(t$I)_KA`e+~#&_v{?3&u-5,xbQT5tH%Hg[6gOk(SwCgc0to1');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
