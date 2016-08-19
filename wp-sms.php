<?php
/**
 * Plugin Name: WP SMS
 * Plugin URI: http://wpsms.veronalabs.com
 * Description: A complete wordpress plugin to send sms with a high capability.
 * Version: 4.0.0
 * Author: Mostafa Soufi
 * Author URI: http://mostafa-soufi.ir/
 * Requires at least: 4.0.0
 * Tested up to: 4.6.0
 *
 * Text Domain: wp-sms
 * Domain Path: /languages/
 *
 * @package WP_Sms
 * @category Core
 * @author Matty
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of WP_Sms to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object WP_Sms
 */
function WP_Sms() {
	return WP_Sms::instance();
} // End WP_Sms()

add_action( 'plugins_loaded', 'WP_Sms' );

/**
 * Main WP_Sms Class
 *
 * @class WP_Sms
 * @version	1.0.0
 * @since 1.0.0
 * @package	WP_Sms
 * @author Matty
 */
final class WP_Sms {
	/**
	 * WP_Sms The single instance of WP_Sms.
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
		$this->settings = WP_Sms_Settings::instance();

		if ( is_admin() ) {
			require_once( 'classes/class-wp-sms-admin.php' );
			$this->admin = WP_Sms_Admin::instance();
		}
		// Admin - End
		
		// Post Types - Start
		//require_once( 'classes/class-wp-sms-meta-box.php' );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	} // End __construct()

	/**
	 * Main WP_Sms Instance
	 *
	 * Ensures only one instance of WP_Sms is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WP_Sms()
	 * @return Main WP_Sms instance
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
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '4.0.0' );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '4.0.0' );
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
