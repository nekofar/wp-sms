<?php
/**
 * Plugin Name: WP SMS
 * Plugin URI: http://wpsms.veronalabs.com/
 * Description: A complete wordpress plugin to send sms with a high capability.
 * Version: 4.0.0
 * Author: Mostafa Soufi
 * Author URI: http://mostafa-soufi.ir/
 * Requires at least: 4.0.0
 * Tested up to: 4.7.0
 *
 * Text Domain: wp-sms
 * Domain Path: /languages/
 *
 * @package WP_SMS
 * @category Core
 * @author Mostafa Soufi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of WP_SMS to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object WP_SMS
 */
function WP_SMS() {
	return WP_SMS::instance();
} // End WP_SMS()

add_action( 'plugins_loaded', 'WP_SMS' );

/**
 * Main WP_SMS Class
 *
 * @class WP_SMS
 * @version	1.0.0
 * @since 1.0.0
 * @package	WP_SMS
 * @author Mostafa Soufi
 */
final class WP_SMS {
	/**
	 * WP_SMS The single instance of WP_SMS.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	/**
	 * The plugin directory URL.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_url;

	/**
	 * The plugin directory path.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $plugin_path;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * The settings object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings;
	// Admin - End

	// Post Types - Start
	/**
	 * The post types we're registering.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $post_types = array();
	// Post Types - End
	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct () {
		$this->token 			= 'wp-sms';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '4.0.0';

		// Admin - Start
		require_once( 'classes/class-wp-sms-settings.php' );
			$this->settings = WP_SMS_Settings::instance();

		if ( is_admin() ) {
			require_once( 'classes/class-wp-sms-admin.php' );
			$this->admin = WP_SMS_Admin::instance();
		}
		// Admin - End

		// Post Types - Start
		require_once( 'classes/class-wp-sms-post-type.php' );
		require_once( 'classes/class-wp-sms-taxonomy.php' );

		// Register an example post type. To register other post types, duplicate this line.
		$this->post_types['thing'] = new WP_SMS_Post_Type( 'thing', __( 'Thing', 'wp-sms' ), __( 'Things', 'wp-sms' ), array( 'menu_icon' => 'dashicons-carrot' ) );
		// Post Types - End
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	} // End __construct()

	/**
	 * Main WP_SMS Instance
	 *
	 * Ensures only one instance of WP_SMS is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WP_SMS()
	 * @return Main WP_SMS instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wp-sms', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	} // End load_plugin_textdomain()

	/**
	 * Cloning is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	} // End __wakeup()

	/**
	 * Installation. Runs on activation.
	 * @access  public
	 * @since   1.0.0
	 */
	public function install () {
		$this->_log_version_number();
	} // End install()

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 */
	private function _log_version_number () {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	} // End _log_version_number()
} // End Class
