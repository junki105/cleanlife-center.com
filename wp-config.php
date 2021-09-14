<?php
define( 'WP_CACHE', true ); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cleanlife-center' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '> ,SW_wZ@Ex[8080.Xf3/)?w]ypkz/p7e{wkaT5mjK1!>,SwX;-L{y~,-tREqpc ' );
define( 'SECURE_AUTH_KEY',  'y.gd(c#q^L:{ol?Tx+eo;UM]4hc{{?t$RyZi)Bf]p;z6Xd:zK04qA]N?icYX?$mC' );
define( 'LOGGED_IN_KEY',    '!K-#(&P5aM+-9<#IlSBAfkeM2jl,&E Qxc0BW8yLZ?k*Ytq#bqet7E&?|)mvq|P?' );
define( 'NONCE_KEY',        ',8W?.m&z>kb$$<K3na4-`me ^r=Is0en6~D4WtvwTGk^B.jde 2}{k_p1k2kj+co' );
define( 'AUTH_SALT',        'zw<]HqH:dv?@3LTfk.S y:NQl5ZyA5n)GuUdW7BxxW;3au2*5{Wx3D{aSwN/4<:q' );
define( 'SECURE_AUTH_SALT', '-;A.OjE6Si,E-sNUlkJ7b?@ajFHq`b+>O0K/9/N%rw1U1c6B^3^bM.Hs`:%KqDB(' );
define( 'LOGGED_IN_SALT',   '26Jl`( .5@E/dmZE=0<USR?grORTTS%J(uqvb;|qxAF/F<z6WOFo)eh@(z|FdMsi' );
define( 'NONCE_SALT',       ']2Lv+yLyUbZ^aK{4T:<T.-c[+3AXSu{;./^K:C)>g{:$kBaL16XMGF{}k5#$#PnW' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
