<?php
/**
 * Contains class to handle verses of the day.
 *
 * @package My_Episcopal_Collector
 * @since 0.0.1
 */

namespace My_Episcopal_Collector\Services;

/**
 * Provides verse of the day Shortcodes.
 *
 * @package My_Episcopal_Collector
 * @since 0.0.1
 */
class Votd {

	/**
	 * Setup our verse of the day Shortcodes.
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Register hooks for class.
	 *
	 * @since  0.0.1
	 * @author Scott Anderson <scott@getcodesmart.com>
	 */
	public function hooks() {
		add_shortcode( 'myep-votd', [ $this, 'votd' ] );
	}

	/**
	 * Return VOTD text for shortcode.
	 *
	 * @return string
	 * @author Scott Anderson <scott@getcodesmart.com>
	 * @since  0.0.1
	 */
	public function votd() {
		$response = get_transient( 'votd_response' );
		if ( ! $response ) {
			$response = $this->fetch_votd( 'verses/votd' );
			set_transient( 'votd_response', $response, DAY_IN_SECONDS );
		}

		return sprintf( '<p>%s - %s</p>', $response->verse, $response->reading );
	}

	/**
	 * Fetch verse of the day text from myepiscopal itself.
	 *
	 * @param $endpointk
	 * @param string $args
	 * @param string $method
	 *
	 * @return array|bool|mixed|object
	 * @author Scott Anderson <scott@getcodesmart.com>
	 * @since  0.0.1
	 */
	function fetch_votd( $endpoint, $args = 'null', $method = 'GET' ) {
		//Populate the correct endpoint for the API requestfv
		$url = "https://api.myepiscopal.com/api/{$endpoint}";

		//Make the call and store the response in $res
		$res = wp_remote_get( $url );

		//Check for success
		if ( ! is_wp_error( $res ) && ( $res['response']['code'] == 200 || $res['response']['code'] == 201 ) ) {
			return json_decode( $res['body'] );
		} else {
			return false;
		}
	}
}
