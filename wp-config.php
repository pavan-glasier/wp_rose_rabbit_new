<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'rose_and_rabbit' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         ':cVr)[kjsM*%mpbXm$- .5`4Q{VEfP Nwem]-F9uBJ4yl;srN*X~j_)URKLlyV}!' );
define( 'SECURE_AUTH_KEY',  ']$,:8xU*$7#vRkh$JFQw4&V~nzKo6@k3/~Pw6zou5zRPVYjSOyED2]#d@qjC@lDC' );
define( 'LOGGED_IN_KEY',    'CpVn6)90 /q#sqUdFaLQ>W))E0]XHvq_BT(ITZu!r:J92GD0=@*]ebzx#3yAL@y%' );
define( 'NONCE_KEY',        'HGCHq(h+IvoU!gj+%z#X2%_,b)DObPQ>RGdIBJc@m^f,qb9=W.%9M-E/S{aHhXn6' );
define( 'AUTH_SALT',        'E/_/r(,QR <]#x6P|]7x=?VF~@U~LvLY0opgWj2ei5;/e!Z(-o`Ub^4{7c+1ZgJ7' );
define( 'SECURE_AUTH_SALT', '{-]{YI01;e{6^,BxXKi ^;>}i5lY.eCk&=OUP>%1k,KSUl<O[?Zg%+*Z~dcVYrk>' );
define( 'LOGGED_IN_SALT',   '?>ZZz80vB7~#,$y1$rFjJ6c%RstX?F&C~7JAPTq)k6[tlO_jX8y)7pnPd.5[t:lS' );
define( 'NONCE_SALT',       'o5^|r>~Y}O_Pi-D&2/]oVRA/1k3,wh;EEra]OaEX6<^<oo~!T :hdnvf.A*rGk?X' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'rr_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

define( 'WPCF7_AUTOP', false );

/* Add any custom values between this line and the "stop editing" line. */

define( 'ALLOW_UNFILTERED_UPLOADS', true );


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
