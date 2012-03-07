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
define('DB_NAME', 'jtn');

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
define('AUTH_KEY',         'h~Kb@gp+NGjAIdfOpJS[4&+wHE(;43_raWs=dU8-rq}hD+B7,7l];|{sujd:0;q>');
define('SECURE_AUTH_KEY',  'Y48Ejfh{0z8nvl6pQ8Sqp(j&y)N(pXk-<43Uy 9m@W_x$e^;),<$5*RMwN&W_3Y_');
define('LOGGED_IN_KEY',    '`@%Pr,>fW?&7VrUrFb7}l3+{j6% {TAgVacas+2eb i<hv;b_21iC(AULqeh.Tzl');
define('NONCE_KEY',        'DJxPROo`@!}vcl.eQ+[,4GB_;|6-`@4xuELct:-yJ2W&)VO]e;<~Pe5=+{A&,Ku4');
define('AUTH_SALT',        'I#a3LFgep$trJ[+&hCFc$]#/gXd7j=w$|hdG.W}(uuVCm:l0,ql[oD4#s!M04Nn.');
define('SECURE_AUTH_SALT', '@*Q6ms@wnwp+d=m Zn <0uTP7~b*(++?t.[pbbH V.1n1W7te^Qv0|nuIO;4GMgJ');
define('LOGGED_IN_SALT',   'C`r7AC4q]VhMIW?eW3TU(@,hJpDs_6tGhc*@o;FeXs+S3uo##5%|S muNe,%7K%g');
define('NONCE_SALT',       '=gtT=W&]kK6)^Sk+N&M*8f;0>9o.b@4fT*Pz&)lR#UymtBj+#)h0!{0?l}!h2_EV');

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
