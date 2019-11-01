<?php
/**
 * Plugin name: MyEpiscopal Connector
 * Plugin URI: https://myepiscopal.com
 * Description:  Include rich rescourses of MyEpiscopal in your Church's Website.
 * Author: Connected Church
 * Author URI:   https://connectedchurch.app
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  myep
 * Version:     0.0.1
 * Domain Path:  /languages
 *
 * @package My_Episcopal_Collector
 * @since 0.0.1
 */

namespace My_Episcopal_Collector;

use My_Episcopal_Collector\Services\Votd as Votd;
use My_Episcopal_Connector\Services\Cow;

/**
 * Main initiation class.
 *
 * @since  0.0.1
 */
final class MyEpiscopal_Collector {

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  0.0.1
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  0.0.1
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  0.0.1
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    MyEpiscopal_Collector
	 * @since  0.0.1
	 */
	protected static $single_instance = null;


	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return  MyEpiscopal_Collector A single instance of this class.
	 * @since   0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}
		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'init' ], 0 );
	}

	/**
	 * Initialize all maajor class actions
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public function init() {

		// Include class files.
		$this->includes();

		// Initialize plugin classes.
		$this->plugin_classes();

	}

	/**
	 * Include all class files.
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public function includes() {
		include $this->path . 'src/services/myep-votd.php';
		include $this->path . 'src/services/myep-cow.php';
	}

	/**
	 * Initialize all class files.
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public function plugin_classes() {
		new Votd();
		new Cow();
	}

}


/**
 * Grab the MyEpiscopal_Connector object and return it.
 * Wrapper for MyEpiscopal_Connector::get_instance().
 *
 * @return MyEpiscopal_Collector  Singleton instance of plugin class.
 * @since  0.0.1
 * @author Scott Anderson <scott@getcodesmart.com>
 */
function myep_post_meta_generator() {
	return MyEpiscopal_Collector::get_instance();
}

// Kick it off.
add_action( 'plugins_loaded', [ myep_post_meta_generator(), 'hooks' ] );



?>
