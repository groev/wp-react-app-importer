<?php

    /**
     * WRAI_App handles all functions around creating and managing the React Aps.
     * This includes uploading the app.
     */


class WRAI_App
{
    // __construct loads all functions into actions/filters.
    public function __construct()
    {
        add_action('init', array($this, 'register_posttype'));
        add_action('add_meta_boxes', array($this, 'register_upload_box'));
        add_action('save_post', array($this, 'upload_on_save'), 10, 2);
        add_action('post_edit_form_tag', array($this, 'update_edit_form'));
        add_action('admin_notices', array($this, 'render_admin_error'));
        add_filter('manage_react_app_posts_columns', array($this,  'add_admin_column'));
        add_filter('manage_react_app_posts_custom_column', array($this, 'fill_admin_column'), 10, 2);
    }
    // register_postype is the standard function to create a new post type.
    public function register_posttype()
    {
        $labels = array(
            'name' => _x('React Apps', 'Post Type General Name', 'wp-react-app-importer'),
            'singular_name' => _x('React App', 'Post Type Singular Name', 'wp-react-app-importer'),
            'menu_name' => _x('React Apps', 'Admin Menu text', 'wp-react-app-importer'),
            'name_admin_bar' => _x('React App', 'Add New on Toolbar', 'wp-react-app-importer'),
            'archives' => __('React App Archives', 'wp-react-app-importer'),
            'attributes' => __('React App Attributes', 'wp-react-app-importer'),
            'parent_item_colon' => __('Parent React App:', 'wp-react-app-importer'),
            'all_items' => __('All React Apps', 'wp-react-app-importer'),
            'add_new_item' => __('Add New React App', 'wp-react-app-importer'),
            'add_new' => __('Add New', 'wp-react-app-importer'),
            'new_item' => __('New React App', 'wp-react-app-importer'),
            'edit_item' => __('Edit React App', 'wp-react-app-importer'),
            'update_item' => __('Update React App', 'wp-react-app-importer'),
            'view_item' => __('View React App', 'wp-react-app-importer'),
            'view_items' => __('View React Apps', 'wp-react-app-importer'),
            'search_items' => __('Search React App', 'wp-react-app-importer'),
            'not_found' => __('Not found', 'wp-react-app-importer'),
            'not_found_in_trash' => __('Not found in Trash', 'wp-react-app-importer'),
            'featured_image' => __('Featured Image', 'wp-react-app-importer'),
            'set_featured_image' => __('Set featured image', 'wp-react-app-importer'),
            'remove_featured_image' => __('Remove featured image', 'wp-react-app-importer'),
            'use_featured_image' => __('Use as featured image', 'wp-react-app-importer'),
            'insert_into_item' => __('Insert into React App', 'wp-react-app-importer'),
            'uploaded_to_this_item' => __('Uploaded to this React App', 'wp-react-app-importer'),
            'items_list' => __('React Apps list', 'wp-react-app-importer'),
            'items_list_navigation' => __('React Apps list navigation', 'wp-react-app-importer'),
            'filter_items_list' => __('Filter React Apps list', 'wp-react-app-importer'),
        );
        $args = array(
            'label' => __('React App', 'wp-react-app-importer'),
            'description' => __('', 'wp-react-app-importer'),
            'labels' => $labels,
            'menu_icon' => 'dashicons-editor-code',
            'supports' => array('title'),
            'taxonomies' => array(),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'publicly_queryable' => false,
            'capabilities' => array(
                'edit_post'          => 'update_core',
                'read_post'          => 'update_core',
                'delete_post'        => 'update_core',
                'edit_posts'         => 'update_core',
                'edit_others_posts'  => 'update_core',
                'delete_posts'       => 'update_core',
                'publish_posts'      => 'update_core',
                'read_private_posts' => 'update_core'
            ),
        );
        register_post_type('react_app', $args);
    }
    // register_upload_box creates a new box in the single-edit-view.
    public function register_upload_box()
    {
        add_meta_box('ras-upload-box', __('Upload', 'wp-react-app-importer'), array($this, 'add_upload_box'), 'react_app');
    }
    // add_upload_box includes the HTML of the upload.view.php
    public function add_upload_box()
    {
        include WRAIPATH.'includes/views/upload.view.php';
    }

    // upload_on_save hooks into the save_post action and uploads and extracts the zip file.
    public function upload_on_save($post_id, $post)
    {
        if ($post->post_status !== 'publish' || $post->post_type !== 'react_app') {
            return $post_id;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        try {
            if (!wp_verify_nonce($_POST['react_uploader_nonce'], 'upload_react_app')) {
                throw new \Exception(__('There was an nonce error uploading your File.', 'wp-react-app-importer'));
            }
            if (!current_user_can('update_core')) {
                throw new \Exception(__('You dont have permissions for uploading a file.', 'wp-react-app-importer'));
            }
            if (!empty($_FILES['react_app_uploader']['name'])) {
                $supported_types = array('application/zip', 'application/octet-stream', 'application/x-zip-compressed', 'multipart/x-zip');
                $arr_file_type = wp_check_filetype(basename($_FILES['react_app_uploader']['name']));
                $uploaded_type = $arr_file_type['type'];
                if (!in_array($uploaded_type, $supported_types)) {
                    throw new \Exception(__('You did not upload a valid ZIP File.', 'wp-react-app-importer'));
                }
                $saveName = 'app-'.$post_id;

                $tmpName = $_FILES['react_app_uploader']['tmp_name'];
                $folderName = WRAIUPLOADPATH.$saveName.'/';
                $pathToZip = $folderName.'build.zip';
                if (!file_exists(WRAIUPLOADPATH)) {
                    mkdir(WRAIUPLOADPATH, 0775);
                } // Create upload folder if does not exist.
                $oldBuild = get_post_meta($post_id, 'react_app_name', true);
                if ($oldBuild) {
                    $this->delete_app_files(WRAIUPLOADPATH.$oldBuild.'/');
                }
                if (!file_exists($folderName)) {
                    mkdir($folderName, 0775);
                } // Create new folder if doesn't exist.
                $move = move_uploaded_file($tmpName, $pathToZip); // Move temp files to folder
                if (!$move) {
                    throw new \Exception(__('There was an error uploading your app.', 'wp-react-app-importer'));
                }
                $zip = new ZipArchive();
                $bOK = $zip->open($pathToZip);
                if (!$zip->locateName('index.html')) {
                    throw new \Exception(__('The Archive you have uploaded does not seem to be a valid Create React App archive.', 'wp-react-app-importer'));
                } // Checking if archive has an index.html
                if ($bOK !== true) {
                    throw new \Exception(__('Could not extract your zip file.', 'wp-react-app-importer'));
                } // Checking if archive can be extracted.
                $bOK = $zip->extractTo($folderName);
                if ($bOK !== true) {
                    throw new \Exception(__('Could not extract your zip file.', 'wp-react-app-importer'));
                } // Checking if archive can be extracted.
                $zip->close();
                add_post_meta($post_id, 'react_app_name', $saveName); // Creating post meta with path
                update_post_meta($post_id, 'react_app_name', $saveName); //Updating post meta with path
                unlink($pathToZip); // Deleting the ZIP File
            }
        } catch (Exception $e) {
            set_transient('react_app_errors_'.$post_id, $e->getMessage(), 10);
            return;
        }
    }

    public function render_admin_error()
    {
        // show error if the transient exists
        if (isset($_GET['post'])) {
            if (get_transient('react_app_errors_'.$_GET['post'])) {?>
             <div class="error">
                <p><?php echo get_transient('react_app_errors_'.$_GET['post']); ?></p>
            </div>
            <?php
            // show error once, delete it afterwards
            delete_transient('react_app_errors_'.$id);
        }
        }
    }

    // Make the post form multipart for file uploads.
    public function update_edit_form($post)
    {
        if ($post->post_type === "react_app") {
            echo ' enctype="multipart/form-data"';
        }
    }

    // Deletes all files in this speficic directory.
    private function delete_app_files($dir)
    {
        if (is_dir($dir) && strpos($dir, 'reactapps')) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        $this->delete_app_files($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    // add the shortcode column to the admin view.
    public function add_admin_column($columns)
    {
        $columns = array(
            'title' => __('Title'),
            'shortcode' => __('Shortcode', 'wp-react-app-importer'),
            'date' => __('Date')
        );
        return $columns;
    }
    // fill the shortcode with a copyable input
    public function fill_admin_column($column, $post_id)
    {
        if ($column === "shortcode") {
            echo '<input type="text" disabled style="color:#666"  value="[React-App id=&quot;'.$post_id.'&quot;]"/>';
        }
    }
}
new WRAI_App();
