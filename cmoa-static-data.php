<?php

require_once 'vendor/autoload.php';

class WP_Static_Data {

  /*
  *  get_static_directory
  *
  *  This function returns the static directory filepath
  *
  *  @type	function
  *  @date	03/25/16
  *  @since	1.0.0
  *
  *  @param	N/A
  *  @return static directory filepath
  */

  function get_static_directory() {
    return get_template_directory() . '/static/';
  }

  /*
  *  get_static_data
  *
  *  This function get the file contacts of the passed filename
  *
  *  @type	function
  *  @date	03/25/16
  *  @since	1.0.0
  *
  *  @param	filename (string)
  *  @return string (false on error)
  */

  function get_static_data($filename) {
    return file_get_contents($this->get_static_directory().$filename.'.json');
  }

  /*
  *  format_json
  *
  *  This function formats the file string for output
  *
  *  @type	function
  *  @date	03/25/16
  *  @since	1.0.0
  *
  *  @param	filename (string)
  *  @return
  */

  function format_json($data_file) {
    return $data_file ? json_decode($data_file) : [];
  }

}

/* Routes */

/*
*  fetch_api
*
* Get static data files and return them as JSON objects
*
*  @type	function
*  @date	03/25/16
*  @since	1.0.0
*
* @param array $args Arguments from the API call.
* @return array|null JSON array of objects or empty array if file is not found
*/

function fetch_api($args) {
  $static = new WP_Static_Data();
  $data_file = $static->get_static_data($args['file']);
  return $static->format_json($data_file);
}

add_action('rest_api_init', function () {
  register_rest_route('data/v1', '/(?P<file>\w+)', array(
    'methods' => 'GET',
    'callback' => 'fetch_api'
  ));
});

/* Shortcodes */

/*
*  resolve_shortcode
*
* Make static data files available with shortcode [static_data global="file"]
*
*  @type	function
*  @date	03/25/16
*  @since	1.0.0
*
* @param array $atts Arguments for the shortcodes
* @return string|null Raw data or formatted string based on template
*/

function resolve_shortcode($atts) {
  $a = shortcode_atts(array('format' => 'true'), $atts);
  $static = new WP_Static_Data();
  $data_file = $static->get_static_data($atts['global']);

  if(!$data_file) return '';

  if ($a['format'] === 'false') {
    return $data_file;
  }
  else {
    $view = isset($atts['view']) ? $atts['view'] : $atts['global'];
  }

  // Format data according to mustache template
  $data_file = json_decode($data_file);
  $options = array('extension' => '.html');
  $m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader($static->get_static_directory().'views', $options)
  ));

  try {
    return $m->render($view, array($view => $data_file));
  } catch(Exception $e) {
    return '';
  }
}

add_shortcode('static_data', 'resolve_shortcode');

?>
