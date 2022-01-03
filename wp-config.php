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
define( 'DB_NAME', 'wp_aws' );

/** MySQL database username */
define( 'DB_USER', 'mng' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Fds123..!!' );

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
define( 'AUTH_KEY',         'w:Ypo3Q^G8tk~f+E/*p<$85Tow7Qdt|Xx!p^5?{7oso8?C6*^_+`VaV}MO}#%]wi' );
define( 'SECURE_AUTH_KEY',  ' N~y}-myf?E]G*8,nSt7,i_T[%`#3tk`CT#?Y&TZ;{&R>^<.>s]MCq!7JGNW]BZr' );
define( 'LOGGED_IN_KEY',    '5O#]`{}tR6DlbEj{jMqfE~u;cZWxNYnKhs3*zB1qA[:gn3X(-L.,fT1`B~%j}H1_' );
define( 'NONCE_KEY',        'umw?2yCM7,.,L^)2 9A/Mb[>P.gR6B2T*=q MYaPG=m%c,EC.NPW3<lzd<YF^T0n' );
define( 'AUTH_SALT',        '}*Q5atmA}ub~64[tM k~3>@r]3eG%/12+6IjK64.dSV2sqZrcY~DZr!4lRj1<w=q' );
define( 'SECURE_AUTH_SALT', '6n>Dv*=dwxSzPYr7@D7u#O:{K9o59otBo$1CQ!j1@L-|9bW=w8nOw]jYYui[s&&(' );
define( 'LOGGED_IN_SALT',   '@D3{D-Zbiodcl;rT-TJ__L[pR/a/gf*?6]*!z3sAb!D/-hZ$/^hS$){y{^PT/;Z(' );
define( 'NONCE_SALT',       'o/ojp$ZA+Rg(@nqN]qSqR=V_V0@D.H,stpG}v,c>zJCsmFzhgIe=|w8dWI[0YrQ_' );

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
