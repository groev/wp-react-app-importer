<?php

/**

 * @wordpress-plugin
 * Plugin Name:       React App Shortcodes
 * Plugin URI:        https://www.westhofen.me
 * Version:           1.0.0
 * Author:            Magnus Westhofen

 */

 if (! defined('WPINC')) {
     die;
 }

 define('REACT_APP_SHORTCODES', '1.0.0');
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
