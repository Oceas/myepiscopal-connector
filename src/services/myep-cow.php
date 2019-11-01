<?php
/**
 * Contains class to handle catechism of the week.
 *
 * @package My_Episcopal_Collector
 * @since 0.0.1
 */

namespace My_Episcopal_Connector\Services;

/**
 * Provides catechism of the week services.
 *
 * @package My_Episcopal_Collector
 * @since 0.0.1
 */
class Cow {

	private $endpoint = 'catechism/cow/';

	/**
	 * Setup our verse of the day services.
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
		add_shortcode( 'myep-cow', [ $this, 'cow' ] );
	}

	/**
	 * Return COW text for shortcode.
	 *
	 * @return string
	 * @author Scott Anderson <scott@getcodesmart.com>
	 * @since  0.0.1
	 */
	public function cow() {
		$response = get_transient( 'myep-cow' );

		if ( ! $response ) {
			$response = $this->fetch_votd( 'verses/votd' );
			set_transient( 'myep-cow', $response, DAY_IN_SECONDS );
		}
		return sprintf( "<p class='myep-question'>%s</p><p class='myep-answer'>%s</p>", $response->data->question, $response->data->answer);
	}

	/**
	 * Fetch verse of the day text from myepiscopal itself.
	 *
	 * @param string $args
	 * @param string $method
	 *
	 * @return array|bool|mixed|object
	 * @author Scott Anderson <scott@getcodesmart.com>
	 * @since  0.0.1
	 */
	function fetch_votd( $args = 'null', $method = 'GET' ) {

		// Populate the correct endpoint for the API request.
		$url = "https://api.myepiscopal.com/api/{$this->endpoint}";

		// Make the call and store the response in $res.
		$res = wp_remote_get( $url );

		// Check for success.
		if ( ! is_wp_error( $res ) && 200 === ( $res['response']['code'] ) ) {
			return json_decode( $res['body'] );
		} else {
			return false;
		}
	}
}
