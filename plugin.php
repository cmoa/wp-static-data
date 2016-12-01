<?php
/**
 * Plugin Name: WP Static Data
 * Description: Serve static data JSON files from shortcodes and API
 * Author: Carney+Co.
 * Author URI: http://carney.co
 * Version: 1.0.1
 */

/**
 *  CMOA Static Data class
 */
if ( ! class_exists( 'WP_Static_Data' ) ) {
	require_once dirname( __FILE__ ) . '/cmoa-static-data.php';
	class_alias('WP_Static_Data', 'CMOA_Static_Data');
}

?>
