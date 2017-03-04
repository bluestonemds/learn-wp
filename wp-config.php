<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'xidryhm00');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         ']-oUrYkP0G7ml&C n:zqrW.jdgvXq>EdTC`N|>[_xsUEk}Z+&Jw5Q>jy.hDg}9GR');
define('SECURE_AUTH_KEY',  '=y>EEgS_HVQM[ 4Qfu}rl^cdLWXntII($,1jwu&lS8E}8LBb<po5/&J`p33x^!.z');
define('LOGGED_IN_KEY',    '7c7d(TG|J{c75Xm:k<oNC-r_QL{C?s;STL7s}]V22.[AjbygqoUZ-Bl);jITNJ0!');
define('NONCE_KEY',        'dYBw~/)D%|Qq7Gu|>QwZbLF+@xH1_w=.bj3$d;;ur1tFxiSSVvjz&hnMecgeHYsJ');
define('AUTH_SALT',        'fV^tEjtN`r+e>=k*1o,/(a-@8_44Dla{64G`!b?>;urQt(?|$A(Cx1Q/ +RmI?3=');
define('SECURE_AUTH_SALT', 'B&p@XH*?i*:tUIw,@]+DLs`_8{69m>dnvysoFuQ9r/6_d)FP/ jXN0L>FLi4.|M#');
define('LOGGED_IN_SALT',   'y3?:oCtw OT4kCFW,K5jMxF!:,g `E0bGQu:Kk;0#spu2Q[gpST{*y@tnZ;NPO(Y');
define('NONCE_SALT',       'n|u>s+svv*{-kpEx1QKzxi+vw,,VK0b9#nUjp,vm5~7qcN$T/exkUW|hL88|7W@H');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
