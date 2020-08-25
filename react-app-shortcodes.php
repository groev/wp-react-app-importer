<?php

/**

 * @wordpress-plugin
 * Plugin Name:         React App Shortcodes
 * Plugin URI:          https://github.com/groev/creact-react-app-wordpress-shortcodes/
 * Version:             0.0.2
 * Author:              Magnus Westhofen
 * Text Domain:         react-app-shortcodes

 */

 if (! defined('WPINC')) {
     die;
 }

 define('REACT_APP_SHORTCODES', '0.0.2');
 define('RASPATH', plugin_dir_path(__FILE__));
 define('RASURL', plugin_dir_url(__FILE__));


 class ReactAppShortcodes
 {
     public function __construct()
     {
         $this->require_files();
     }
     
     public function require_files()
     {
         require RASPATH.'includes/shortcode.class.php';
         require RASPATH.'includes/app.class.php';
     }
 }
 new ReactAppShortcodes();
