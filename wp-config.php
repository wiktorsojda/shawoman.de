<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings
define('WP_CACHE', true); // Added by WP Rocket
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '%7I$Mf{SNOh6w7YOs(iHa0OTc-#*1%*X:&E2waF_9g-m{$a76M33DVlKD.xTY3.X' );
define( 'SECURE_AUTH_KEY',   '7Ei4:#*(l8=_N;uA:kMvfW.8,a}#wb$TkvNu^A#4g1An2y}(VNrHY%1f`{/&_nR^' );
define( 'LOGGED_IN_KEY',     ')7xh`e_$cbJK$it;hR&nWE`-wp8KNm*63UPjfA10g*q[x}YB/GYqJCS4h^h^Z:Y#' );
define( 'NONCE_KEY',         '<g*Tf:A.)<<VOqq:@?{Y<N45xpL_!!^2iDxT+g(5(2GWeF$ewQ0!vLQ~vOa/FxbP' );
define( 'AUTH_SALT',         'Bkfg!mMiEx+j^YfmXuf6V>Bz|QmX{sDtbxcOW?S57yLSKa@O@ol#?BFf^>m0y4O~' );
define( 'SECURE_AUTH_SALT',  '<z[yNwj/dW-`[nIO!{Gs*}!cDOc$8ik [D.F(K&-.*($I*DJ2yui03`*j5rqVo|M' );
define( 'LOGGED_IN_SALT',    'yO=RDgUD</R,N;HL1k:%9R1U-8`SD[;[s@+>#/SQfp(iO#K$WwP9,;3>jooPw30+' );
define( 'NONCE_SALT',        't]o:HQJ>Z{&Fx|FWP$+,VX(J]2WNXo{Pf4J8E12o:CKw,q6AZ0rkINy<#q(g#5Fe' );
define( 'WP_CACHE_KEY_SALT', 'hAtaaU+@fWMwpH^jR8v5wd20TDfQtQmv:zdkL2`97lHa#tH3 F(CX^^UZR|/$4nq' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
	
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
