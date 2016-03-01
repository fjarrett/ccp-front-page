<?php
/**
 * Plugin Name: CCP Front Page
 * Description: Allow portfolio projects to be displayed on the front page.
 * Version: 0.0.1
 * Author: Frankie Jarrett
 * Author URI: https://frankiejarrett.com/
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

final class CCP_Front_Page {

	/**
	 * Plugin version number.
	 *
	 * @since  0.0.1
	 * @access private
	 * @var    string
	 */
	private $version = '0.0.1';

	/**
	 * Plugin instance.
	 *
	 * @since  0.0.1
	 * @access private
	 * @var    CCP_Front_Page
	 */
	private static $instance = null;

	/**
	 * Return the plugin instance.
	 *
	 * @since  0.0.1
	 * @access public
	 * @return CCP_Front_Page
	 */
	public static function load() {

		if ( ! static::$instance ) {

			static::$instance = new self();

		}

		return static::$instance;

	}

	/**
	 * Class contructor.
	 *
	 * @since  0.0.1
	 * @access private
	 */
	private function __construct() {

		if ( ! in_array( 'custom-content-portfolio/portfolio.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			return;

		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'pre_get_posts',         array( $this, 'front_page' ) );

	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @action admin_enqueue_scripts
	 * @since  0.0.1
	 * @access public
	 * @param  string $hook
	 */
	public function enqueue( $hook ) {

		if ( 'options-reading.php' !== $hook ) {

			return;

		}

		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'ccp-front-page-options-reading',
			plugin_dir_url(  __FILE__ ) . "js/options-reading{$suffix}.js",
			array( 'jquery' ),
			$this->version
		);

		wp_localize_script(
			'ccp-front-page-options-reading',
			'ccp_front_page_field',
			sprintf(
				'<p><label><input name="show_on_front" type="radio" value="ccp" class="tog" %s /> %s</label></p>',
				( 'ccp' === get_option( 'show_on_front', 'posts' ) ) ? 'checked="checked"' : null,
				esc_html__( 'Your latest portfolio projects', 'custom-content-portfolio' )
			)
		);

	}

	/**
	 * Place portfolio projects on the front page.
	 *
	 * @action pre_get_posts
	 *
	 * @since  0.0.1
	 * @access public
	 * @param  WP_Query $query
	 * @return WP_Query
	 */
	public function front_page( $query ) {

		if ( is_admin() || ! is_home() || ! $query->is_main_query() || 'ccp' !== get_option( 'show_on_front', 'posts' ) ) {

			return;

		}

		$query->set( 'post_type', ccp_get_project_post_type() );
		$query->set( 'page_id',    '' );

		$query->is_page              = 0;
		$query->is_singular          = 0;
		$query->is_post_type_archive = 1;
		$query->is_archive           = 1;

		return $query;

	}

}

/**
 * Return the plugin instance.
 *
 * @since  0.0.1
 * @access public
 * @return CCP_Front_Page
 */
function ccp_front_page() {

	return CCP_Front_Page::load();

}

ccp_front_page();

/**
 * Run when the plugin is deactivated.
 *
 * @since  0.0.1
 * @access public
 */
function ccp_front_page_deactivate() {

	update_option( 'show_on_front', 'posts' );

}

register_deactivation_hook( __FILE__, 'ccp_front_page_deactivate' );
