<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: My Plugin
Description: This is not even a plugin
Author: Shalaco
Version: .01
Author URI: http://shalaco.com/
*/

define('WPCF_REPOSITORY', 'http://api.wp-types.com/');

define('WPCF_ABSPATH', dirname(__FILE__));
define('WPCF_RELPATH', plugins_url() . '/' . basename(WPCF_ABSPATH));
define('WPCF_INC_ABSPATH', WPCF_ABSPATH . '/includes');
define('WPCF_INC_RELPATH', WPCF_RELPATH . '/includes');
define('WPCF_RES_ABSPATH', WPCF_ABSPATH . '/resources');
define('WPCF_RES_RELPATH', WPCF_RELPATH . '/resources');
require_once WPCF_INC_ABSPATH . '/types/includes/constants.php';

echo WPCF_ABSPATH;
?>