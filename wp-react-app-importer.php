<?php

/**

 * @wordpress-plugin
 * Plugin Name:         WP React App Importer
 * Plugin URI:          https://github.com/groev/wp-react-app-importer
 * Description:         Import your Create-React-App build to WordPress and use it as Shortcode.
 * Version:             0.0.4
 * Author:              Magnus Westhofen
 * Author URI:          https://www.westhofen.me
 * Text Domain:         wp-react-app-importer
 */

 if (! defined('WPINC')) {
     die;
 }

 define('REACT_APP_SHORTCODES', '0.0.4');
 define('WRAIPATH', plugin_dir_path(__FILE__));
 define('WRAIURL', plugin_dir_url(__FILE__));
 define('WRAIUPLOADPATH', WP_CONTENT_DIR.'/uploads/reactapps/');
 define('WRAIUPLOADURL', WP_CONTENT_URL.'/uploads/reactapps/');

 // main class for the plugin
 class WPReactAppImporter
 {
     // start all functions needed to run the plugin.
     public function __construct()
     {
         $this->require_files();
     }
     // require all files needed.
     public function require_files()
     {
         require WRAIPATH.'includes/shortcode.class.php';
         require WRAIPATH.'includes/app.class.php';
     }
 }
 new WPReactAppImporter();
