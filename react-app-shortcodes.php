<?php

/**

 * @wordpress-plugin
 * Plugin Name:         React App Shortcodes
 * Plugin URI:          https://github.com/groev/creact-react-app-wordpress-shortcodes/
 * Description:         Upload your Create-React-App build to WordPress and use it as Shortcode.
 * Version:             0.0.2
 * Author:              Magnus Westhofen
 * Author URI:         https://www.westhofen.me
 * Text Domain:         react-app-shortcodes

 */

 if (! defined('WPINC')) {
     die;
 }

 define('REACT_APP_SHORTCODES', '0.0.2');
 define('RASPATH', plugin_dir_path(__FILE__));
 define('RASURL', plugin_dir_url(__FILE__));


 // main class for the plugin
 class ReactAppShortcodes
 {
     // start all functions needed to run the plugin.
     public function __construct()
     {
         $this->require_files();
     }
     // require all files needed.
     public function require_files()
     {
         require RASPATH.'includes/shortcode.class.php';
         require RASPATH.'includes/app.class.php';
     }
 }
 new ReactAppShortcodes();
