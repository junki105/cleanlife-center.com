<?php
/**
 * These functions inherited from /wp-includes/pluggable.php are meant to work with "Plugins Manager" and they are triggered very early before the standard WordPress action hooks:
 * https://codex.wordpress.org/Plugin_API/Action_Reference
 *
 * Case 1: If "Test Mode" is enabled, then the guest should load all the plugins
 *         Any plugins marked for unload, will be unloaded for the admin only
 *
 * Case 2: If a plugin is marked for unload, but an exception is added to load only for the logged-in user,
 *         then it will be checked in the MU plugin (before any plugins are loaded) within the "option_active_plugins" filter
 *
 * These functions can be replaced via plugins. If plugins do not redefine these
 * functions, then these will be used instead.
 *
 * @package WordPress
 */

add_filter( 'wpacu_determine_current_user', 'wpacu_wp_validate_logged_in_cookie', 20 );

if ( ! function_exists( 'wpacu_current_user_can' ) ) :
	function wpacu_current_user_can( $capability ) {
		$wpacu_current_user = wpacu_wp_get_current_user();

		if ( $wpacu_current_user === null ) {
			return false;
		}

		return $wpacu_current_user->has_cap( $capability );
	}
endif;

if ( ! function_exists( 'wpacu_wp_set_current_user' ) ) :
	/**
	 * Changes the current user by ID or name.
	 *
	 * Set $id to null and specify a name if you do not know a user's ID.
	 *
	 * Some WordPress functionality is based on the current user and not based on
	 * the signed in user. Therefore, it opens the ability to edit and perform
	 * actions on users who aren't signed in.
	 *
	 * @since 2.0.3
	 * @global WPACU_WP_User $wpacu_current_user The current user object which holds the user data.
	 *
	 * @param int    $id   User ID
	 * @param string $name User's username
	 * @return WPACU_WP_User Current user User object
	 */
	function wpacu_wp_set_current_user( $id, $name = '' ) {
		return new WPACU_WP_User( $id, $name );
	}
endif;

if ( ! function_exists( 'wpacu_wp_get_current_user' ) ) :
	/**
	 * Retrieve the current user object.
	 *
	 * Will set the current user, if the current user is not set. The current user
	 * will be set to the logged-in person. If no user is logged-in, then it will
	 * set the current user to 0, which is invalid and won't have any permissions.
	 *
	 * @since 2.0.3
	 *
	 * @see wpacu_wp_get_current_user()
	 * @global WPACU_WP_User $wpacu_current_user Checks if the current user is set.
	 *
	 * @return WPACU_WP_User Current WPACU_WP_User instance.
	 */
	function wpacu_wp_get_current_user() {
		$user_id = apply_filters( 'wpacu_determine_current_user', false );
		if ( ! $user_id ) {
			return null;
		}

		return wpacu_wp_set_current_user( $user_id );
	}
endif;

if ( ! function_exists( 'wpacu_get_user_by' ) ) :
	/**
	 * Retrieve user info by a given field
	 *
	 * @since 2.8.0
	 * @since 4.4.0 Added 'ID' as an alias of 'id' for the `$field` parameter.
	 *
	 * @param string     $field The field to retrieve the user with. id | ID | slug | email | login.
	 * @param int|string $value A value for $field. A user ID, slug, email address, or login name.
	 * @return WPACU_WP_User|false WPACU_WP_User object on success, false on failure.
	 */
	function wpacu_get_user_by( $field, $value ) {
		$userdata = WPACU_WP_User::get_data_by( $field, $value );

		if ( ! $userdata ) {
			return false;
		}

		$user = new WPACU_WP_User;
		$user->init( $userdata );

		return $user;
	}
endif;

if ( ! function_exists( 'wpacu_wp_validate_auth_cookie' ) ) :
	/**
	 * Validates authentication cookie.
	 *
	 * The checks include making sure that the authentication cookie is set and
	 * pulling in the contents (if $cookie is not used).
	 *
	 * Makes sure the cookie is not expired. Verifies the hash in cookie is what is
	 * should be and compares the two.
	 *
	 * @since 2.5.0
	 *
	 * @global int $login_grace_period
	 *
	 * @param string $cookie Optional. If used, will validate contents instead of cookie's.
	 * @param string $scheme Optional. The cookie scheme to use: 'auth', 'secure_auth', or 'logged_in'.
	 * @return int|false User ID if valid cookie, false if invalid.
	 */
	function wpacu_wp_validate_auth_cookie( $cookie = '', $scheme = '' ) {
		$cookie_elements = wpacu_wp_parse_auth_cookie( $cookie, $scheme );
		if ( ! $cookie_elements ) {
			return false;
		}

		$scheme     = $cookie_elements['scheme'];
		$username   = $cookie_elements['username'];
		$hmac       = $cookie_elements['hmac'];
		$token      = $cookie_elements['token'];
		$expired    = $cookie_elements['expiration'];
		$expiration = $cookie_elements['expiration'];

		// Allow a grace period for POST and Ajax requests.
		if ( wp_doing_ajax() || 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$expired += HOUR_IN_SECONDS;
		}

		// Quick check to see if an honest cookie has expired.
		if ( $expired < time() ) {
			return false;
		}

		$user = wpacu_get_user_by( 'login', $username );
		if ( ! $user ) {
			return false;
		}

		$pass_frag = substr( $user->user_pass, 8, 4 );

		$key = wpacu_wp_hash( $username . '|' . $pass_frag . '|' . $expiration . '|' . $token, $scheme );

		// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
		$algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
		$hash = hash_hmac( $algo, $username . '|' . $expiration . '|' . $token, $key );

		if ( ! hash_equals( $hash, $hmac ) ) {
			return false;
		}

		$manager = WP_Session_Tokens::get_instance( $user->ID );
		if ( ! $manager->verify( $token ) ) {
			return false;
		}

		return $user->ID;
	}
endif;

if ( ! function_exists( 'wpacu_wp_parse_auth_cookie' ) ) :
	/**
	 * Parses a cookie into its components.
	 *
	 * @since 2.7.0
	 *
	 * @param string $cookie Authentication cookie.
	 * @param string $scheme Optional. The cookie scheme to use: 'logged_in'.
	 * @return string[]|false Authentication cookie components.
	 */
	function wpacu_wp_parse_auth_cookie( $cookie = '', $scheme = '' ) {
		if (! defined('LOGGED_IN_COOKIE')) {
			return false;
		}

		if ( empty( $cookie ) ) {
			$cookie_name = LOGGED_IN_COOKIE;

			if ( ! isset($_COOKIE[ $cookie_name ]) || empty( $_COOKIE[ $cookie_name ] ) ) {
				return false;
			}

			$cookie = $_COOKIE[ $cookie_name ];
		}

		$cookie_elements = explode( '|', $cookie );
		if ( count( $cookie_elements ) !== 4 ) {
			return false;
		}

		list( $username, $expiration, $token, $hmac ) = $cookie_elements;

		return compact( 'username', 'expiration', 'token', 'hmac', 'scheme' );
	}
endif;

if ( ! function_exists( 'wpacu_wp_validate_logged_in_cookie' ) ) :
	/**
	 * Validates the logged-in cookie.
	 *
	 * Checks the logged-in cookie if the previous auth cookie could not be
	 * validated and parsed.
	 *
	 * This is a callback for the {@see 'wpacu_determine_current_user'} filter, rather than API.
	 *
	 * @since 3.9.0
	 *
	 * @param int|bool $user_id The user ID (or false) as received from
	 *                          the `determine_current_user` filter.
	 * @return int|false User ID if validated, false otherwise. If a user ID from
	 *                   an earlier filter callback is received, that value is returned.
	 */
	function wpacu_wp_validate_logged_in_cookie( $user_id ) {
		if ( $user_id ) {
			return $user_id;
		}

		if ( ! defined('LOGGED_IN_COOKIE') || (! isset( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) ) {
			return false;
		}

		if ( is_blog_admin() || is_network_admin() || empty( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {
			return false;
		}

		return wpacu_wp_validate_auth_cookie( $_COOKIE[ LOGGED_IN_COOKIE ], 'logged_in' );
	}
endif;

if ( ! function_exists( 'wpacu_is_user_logged_in' ) ) :
	/**
	 * Determines whether the current visitor is a logged in user.
	 *
	 * For more information on this and similar theme functions, check out
	 * the {@link https://developer.wordpress.org/themes/basics/conditional-tags/
	 * Conditional Tags} article in the Theme Developer Handbook.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True if user is logged in, false if not logged in.
	 */
	function wpacu_is_user_logged_in() {
		$user = wpacu_wp_get_current_user();

		if ($user === null) {
			return false;
		}

		return $user->exists();
	}
endif;

if ( ! function_exists( 'wpacu_wp_salt' ) ) :
	/**
	 * Returns a salt to add to hashes.
	 *
	 * Salts are created using secret keys. Secret keys are located in two places:
	 * in the database and in the wp-config.php file. The secret key in the database
	 * is randomly generated and will be appended to the secret keys in wp-config.php.
	 *
	 * The secret keys in wp-config.php should be updated to strong, random keys to maximize
	 * security. Below is an example of how the secret key constants are defined.
	 * Do not paste this example directly into wp-config.php. Instead, have a
	 * {@link https://api.wordpress.org/secret-key/1.1/salt/ secret key created} just
	 * for you.
	 *
	 *     define('AUTH_KEY',         ' Xakm<o xQy rw4EMsLKM-?!T+,PFF})H4lzcW57AF0U@N@< >M%G4Yt>f`z]MON');
	 *     define('SECURE_AUTH_KEY',  'LzJ}op]mr|6+![P}Ak:uNdJCJZd>(Hx.-Mh#Tz)pCIU#uGEnfFz|f ;;eU%/U^O~');
	 *     define('LOGGED_IN_KEY',    '|i|Ux`9<p-h$aFf(qnT:sDO:D1P^wZ$$/Ra@miTJi9G;ddp_<q}6H1)o|a +&JCM');
	 *     define('NONCE_KEY',        '%:R{[P|,s.KuMltH5}cI;/k<Gx~j!f0I)m_sIyu+&NJZ)-iO>z7X>QYR0Z_XnZ@|');
	 *     define('AUTH_SALT',        'eZyT)-Naw]F8CwA*VaW#q*|.)g@o}||wf~@C-YSt}(dh_r6EbI#A,y|nU2{B#JBW');
	 *     define('SECURE_AUTH_SALT', '!=oLUTXh,QW=H `}`L|9/^4-3 STz},T(w}W<I`.JjPi)<Bmf1v,HpGe}T1:Xt7n');
	 *     define('LOGGED_IN_SALT',   '+XSqHc;@Q*K_b|Z?NC[3H!!EONbh.n<+=uKR:>*c(u`g~EJBf#8u#R{mUEZrozmm');
	 *     define('NONCE_SALT',       'h`GXHhD>SLWVfg1(1(N{;.V!MoE(SfbA_ksP@&`+AycHcAV$+?@3q+rxV{%^VyKT');
	 *
	 * Salting passwords helps against tools which has stored hashed values of
	 * common dictionary strings. The added values makes it harder to crack.
	 *
	 * @since 2.5.0
	 *
	 * @link https://api.wordpress.org/secret-key/1.1/salt/ Create secrets for wp-config.php
	 *
	 * @staticvar array $cached_salts
	 * @staticvar array $duplicated_keys
	 *
	 * @param string $scheme Authentication scheme (auth, secure_auth, logged_in, nonce)
	 * @return string Salt value
	 */
	function wpacu_wp_salt( $scheme = 'auth' ) {
		static $cached_salts = array();
		if ( isset( $cached_salts[ $scheme ] ) ) {
			/**
			 * Filters the WordPress salt.
			 *
			 * @since 2.5.0
			 *
			 * @param string $cached_salt Cached salt for the given scheme.
			 * @param string $scheme      Authentication scheme. Values include 'auth',
			 *                            'secure_auth', 'logged_in', and 'nonce'.
			 */
			return apply_filters( 'salt', $cached_salts[ $scheme ], $scheme );
		}

		static $duplicated_keys;
		if ( null === $duplicated_keys ) {
			$duplicated_keys = array( 'put your unique phrase here' => true );
			foreach ( array( 'AUTH', 'SECURE_AUTH', 'LOGGED_IN', 'NONCE', 'SECRET' ) as $first ) {
				foreach ( array( 'KEY', 'SALT' ) as $second ) {
					if ( ! defined( "{$first}_{$second}" ) ) {
						continue;
					}
					$value                     = constant( "{$first}_{$second}" );
					$duplicated_keys[ $value ] = isset( $duplicated_keys[ $value ] );
				}
			}
		}

		$values = array(
			'key'  => '',
			'salt' => '',
		);
		if ( defined( 'SECRET_KEY' ) && SECRET_KEY && empty( $duplicated_keys[ SECRET_KEY ] ) ) {
			$values['key'] = SECRET_KEY;
		}
		if ( 'auth' == $scheme && defined( 'SECRET_SALT' ) && SECRET_SALT && empty( $duplicated_keys[ SECRET_SALT ] ) ) {
			$values['salt'] = SECRET_SALT;
		}

		if ( in_array( $scheme, array( 'auth', 'secure_auth', 'logged_in', 'nonce' ) ) ) {
			foreach ( array( 'key', 'salt' ) as $type ) {
				$const = strtoupper( "{$scheme}_{$type}" );
				if ( defined( $const ) && constant( $const ) && empty( $duplicated_keys[ constant( $const ) ] ) ) {
					$values[ $type ] = constant( $const );
				} elseif ( ! $values[ $type ] ) {
					$values[ $type ] = get_site_option( "{$scheme}_{$type}" );
					if ( ! $values[ $type ] ) {
						$values[ $type ] = wpacu_wp_generate_password( 64, true, true );
						update_site_option( "{$scheme}_{$type}", $values[ $type ] );
					}
				}
			}
		}
		$cached_salts[ $scheme ] = $values['key'] . $values['salt'];

		/** This filter is documented in wp-includes/pluggable.php */
		return apply_filters( 'salt', $cached_salts[ $scheme ], $scheme );
	}
endif;

if ( ! function_exists( 'wpacu_wp_hash' ) ) :
	/**
	 * Get hash of given string.
	 *
	 * @since 2.0.3
	 *
	 * @param string $data   Plain text to hash
	 * @param string $scheme Authentication scheme (auth, secure_auth, logged_in, nonce)
	 * @return string Hash of $data
	 */
	function wpacu_wp_hash( $data, $scheme = 'auth' ) {
		$salt = wpacu_wp_salt( $scheme );

		return hash_hmac( 'md5', $data, $salt );
	}
endif;

if ( ! function_exists( 'wpacu_wp_generate_password' ) ) :
	/**
	 * Generates a random password drawn from the defined set of characters.
	 *
	 * Uses wpacu_wp_rand() is used to create passwords with far less predictability
	 * than similar native PHP functions like `rand()` or `mt_rand()`.
	 *
	 * @since 2.5.0
	 *
	 * @param int  $length              Optional. The length of password to generate. Default 12.
	 * @param bool $special_chars       Optional. Whether to include standard special characters.
	 *                                  Default true.
	 * @param bool $extra_special_chars Optional. Whether to include other special characters.
	 *                                  Used when generating secret keys and salts. Default false.
	 * @return string The random password.
	 */
	function wpacu_wp_generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		if ( $special_chars ) {
			$chars .= '!@#$%^&*()';
		}
		if ( $extra_special_chars ) {
			$chars .= '-_ []{}<>~`+=,.;:/?|';
		}

		$password = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$password .= substr( $chars, wpacu_wp_rand( 0, strlen( $chars ) - 1 ), 1 );
		}

		/**
		 * Filters the randomly-generated password.
		 *
		 * @since 3.0.0
		 * @since 5.3.0 Added the `$length`, `$special_chars`, and `$extra_special_chars` parameters.
		 *
		 * @param string $password            The generated password.
		 * @param int    $length              The length of password to generate.
		 * @param bool   $special_chars       Whether to include standard special characters.
		 * @param bool   $extra_special_chars Whether to include other special characters.
		 */
		return apply_filters( 'random_password', $password, $length, $special_chars, $extra_special_chars );
	}
endif;

if ( ! function_exists( 'wpacu_wp_rand' ) ) :
	/**
	 * Generates a random number.
	 *
	 * @since 2.6.2
	 * @since 4.4.0 Uses PHP7 random_int() or the random_compat library if available.
	 *
	 * @global string $rnd_value
	 * @staticvar string $seed
	 * @staticvar bool $use_random_int_functionality
	 *
	 * @param int $min Lower limit for the generated number
	 * @param int $max Upper limit for the generated number
	 * @return int A random number between min and max
	 */
	function wpacu_wp_rand( $min = 0, $max = 0 ) {
		global $rnd_value;

		// Some misconfigured 32-bit environments (Entropy PHP, for example)
		// truncate integers larger than PHP_INT_MAX to PHP_INT_MAX rather than overflowing them to floats.
		$max_random_number = 3000000000 === 2147483647 ? (float) '4294967295' : 4294967295; // 4294967295 = 0xffffffff

		// We only handle ints, floats are truncated to their integer value.
		$min = (int) $min;
		$max = (int) $max;

		// Use PHP's CSPRNG, or a compatible method.
		static $use_random_int_functionality = true;
		if ( $use_random_int_functionality ) {
			try {
				$_max = ( 0 != $max ) ? $max : $max_random_number;
				// wpacu_wp_rand() can accept arguments in either order, PHP cannot.
				$_max = max( $min, $_max );
				$_min = min( $min, $_max );
				$val  = random_int( $_min, $_max );
				if ( false !== $val ) {
					return absint( $val );
				} else {
					$use_random_int_functionality = false;
				}
			} catch ( Error $e ) {
				$use_random_int_functionality = false;
			} catch ( Exception $e ) {
				$use_random_int_functionality = false;
			}
		}

		// Reset $rnd_value after 14 uses.
		// 32 (md5) + 40 (sha1) + 40 (sha1) / 8 = 14 random numbers from $rnd_value.
		if ( strlen( $rnd_value ) < 8 ) {
			if ( defined( 'WP_SETUP_CONFIG' ) ) {
				static $seed = '';
			} else {
				$seed = get_transient( 'random_seed' );
			}
			$rnd_value  = md5( uniqid( microtime() . mt_rand(), true ) . $seed );
			$rnd_value .= sha1( $rnd_value );
			$rnd_value .= sha1( $rnd_value . $seed );
			$seed       = md5( $seed . $rnd_value );
			if ( ! defined( 'WP_SETUP_CONFIG' ) && ! defined( 'WP_INSTALLING' ) ) {
				set_transient( 'random_seed', $seed );
			}
		}

		// Take the first 8 digits for our value.
		$value = substr( $rnd_value, 0, 8 );

		// Strip the first eight, leaving the remainder for the next call to wpacu_wp_rand().
		$rnd_value = substr( $rnd_value, 8 );

		$value = abs( hexdec( $value ) );

		// Reduce the value to be within the min - max range.
		if ( 0 != $max ) {
			$value = $min + ( $max - $min + 1 ) * $value / ( $max_random_number + 1 );
		}

		return abs( intval( $value ) );
	}
endif;

/**
 * Maps meta capabilities to primitive capabilities.
 *
 * This function also accepts an ID of an object to map against if the capability is a meta capability. Meta
 * capabilities such as `edit_post` and `edit_user` are capabilities used by this function to map to primitive
 * capabilities that a user or role has, such as `edit_posts` and `edit_others_posts`.
 *
 * Example usage:
 *
 *     map_meta_cap( 'edit_posts', $user->ID );
 *     map_meta_cap( 'edit_post', $user->ID, $post->ID );
 *     map_meta_cap( 'edit_post_meta', $user->ID, $post->ID, $meta_key );
 *
 * This does not actually compare whether the user ID has the actual capability,
 * just what the capability or capabilities are. Meta capability list value can
 * be 'delete_user', 'edit_user', 'remove_user', 'promote_user', 'delete_post',
 * 'delete_page', 'edit_post', 'edit_page', 'read_post', or 'read_page'.
 *
 * @since 2.0.0
 * @since 5.3.0 Formalized the existing and already documented `...$args` parameter
 *              by adding it to the function signature.
 *
 * @global array $post_type_meta_caps Used to get post type meta capabilities.
 *
 * @param string $cap     Capability name.
 * @param int    $user_id User ID.
 * @param mixed  ...$args Optional further parameters, typically starting with an object ID.
 * @return string[] Actual capabilities for meta capability.
 */
function wpacu_map_meta_cap( $cap, $user_id, ...$args ) {
	return array($cap); // return original $cap (this is a custom function, no extra $caps are needed
}

require_once __DIR__.'/class-wpacu-wp-user.php';