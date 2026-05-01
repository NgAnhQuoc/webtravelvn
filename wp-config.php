<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'travelvn' );

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
define( 'AUTH_KEY',         'S!DZk8/998R~h$V5N1yrexFxe=pmZt]AmU cvne= Z|g]/GsTzZ^O6**}Wb.G69<' );
define( 'SECURE_AUTH_KEY',  'tE$l${x#WY~[lJ5zN<k8cXD,@fxgE~+Lezf!5K!?eqZ]5h-wHlv~31m$3pFc*C*?' );
define( 'LOGGED_IN_KEY',    '0zd_4#mBuFq+RF<Go];}TYlkQLz@iI;*x}3cvDAGKkXFL$*+riP_!twaXcT[W{Gv' );
define( 'NONCE_KEY',        '(6^j#R^5;YKiH,PmE>Pu(MaMnP}M0h%P^E~^R[-J|u|!=(h1n+PhuD7ASs.cn#tL' );
define( 'AUTH_SALT',        'jE}tz_=uS6 Y/KGVReOH{&2KZSsx*sG[,u6*34SIt<E(dyhaK8)*Ee>nQ10pjkkf' );
define( 'SECURE_AUTH_SALT', 'fmg751:XNJttmSce(t6kK_F7c8hp}kc ,)M8nVcf#:@73whoUJe@+)?$Z)Fthro0' );
define( 'LOGGED_IN_SALT',   '#vvI2hObCi,eUVP9:-tdmT&QF MJ,;*}=B+;DA[nk@X)bmM] _}Mume0L,SKyKyz' );
define( 'NONCE_SALT',       'k%Z1@$6`o{Vl+QZ =DLknC9+[_FE;At?dk`=>O8XiI_]P(G@3s{|kNOh CtC9+D{' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
