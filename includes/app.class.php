<?php

class RAS_App
{
    public function __construct()
    {
        add_action('init', array($this, 'register_posttype'));
        add_action('add_meta_boxes', array($this, 'register_upload_box'));
        add_action('save_post', array($this, 'upload_on_save'));
        add_action('post_edit_form_tag', array($this, 'update_edit_form'));
    }

    public function register_posttype()
    {
        $labels = array(
            'name' => _x('React Apps', 'Post Type General Name', 'textdomain'),
            'singular_name' => _x('React App', 'Post Type Singular Name', 'textdomain'),
            'menu_name' => _x('React Apps', 'Admin Menu text', 'textdomain'),
            'name_admin_bar' => _x('React App', 'Add New on Toolbar', 'textdomain'),
            'archives' => __('React App Archives', 'textdomain'),
            'attributes' => __('React App Attributes', 'textdomain'),
            'parent_item_colon' => __('Parent React App:', 'textdomain'),
            'all_items' => __('All React Apps', 'textdomain'),
            'add_new_item' => __('Add New React App', 'textdomain'),
            'add_new' => __('Add New', 'textdomain'),
            'new_item' => __('New React App', 'textdomain'),
            'edit_item' => __('Edit React App', 'textdomain'),
            'update_item' => __('Update React App', 'textdomain'),
            'view_item' => __('View React App', 'textdomain'),
            'view_items' => __('View React Apps', 'textdomain'),
            'search_items' => __('Search React App', 'textdomain'),
            'not_found' => __('Not found', 'textdomain'),
            'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
            'featured_image' => __('Featured Image', 'textdomain'),
            'set_featured_image' => __('Set featured image', 'textdomain'),
            'remove_featured_image' => __('Remove featured image', 'textdomain'),
            'use_featured_image' => __('Use as featured image', 'textdomain'),
            'insert_into_item' => __('Insert into React App', 'textdomain'),
            'uploaded_to_this_item' => __('Uploaded to this React App', 'textdomain'),
            'items_list' => __('React Apps list', 'textdomain'),
            'items_list_navigation' => __('React Apps list navigation', 'textdomain'),
            'filter_items_list' => __('Filter React Apps list', 'textdomain'),
        );
        $args = array(
            'label' => __('React App', 'textdomain'),
            'description' => __('', 'textdomain'),
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
            'capability_type' => 'post',
        );
        register_post_type('react_app', $args);
    }

    public function register_upload_box()
    {
        add_meta_box('ras-upload-box', __('Upload', 'textdomain'), array($this, 'add_upload_box'), 'react_app');
    }

    public function add_upload_box()
    {
        include RASPATH.'includes/views/upload.view.php';
    }

    public function upload_on_save($id)
    {
        $uploadfolder =  WP_CONTENT_DIR . '/uploads/reactapps'; // Determine the server path to upload files
        $uploadurl = content_url() . '/uploads/reactapps/'; // Determine the absolute url to upload files
        define('RAS_UPLOADDIR', $uploadfolder);
        define('RAS_UPLOADURL', $uploadurl);
        if (!file_exists($uploadfolder)) {
            mkdir($uploadfolder, 0777);
        }
        if (!wp_verify_nonce($_POST['react_uploader_nonce'], 'upload_react_app')) {
            error_log('passed');
            return new WP_Error('error', 'test');
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $id;
        }

        if ('react_app' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $id)) {
                return new WP_Error('error', 'test');
            } else {
                if (!current_user_can('edit_page', $id)) {
                    return new WP_Error('error', 'test');
                }
            }

            if (!empty($_FILES['react_app_uploader']['name'])) {
                $supported_types = array('application/zip', 'application/octet-stream', 'application/x-zip-compressed', 'multipart/x-zip');
                $arr_file_type = wp_check_filetype(basename($_FILES['react_app_uploader']['name']));
                $uploaded_type = $arr_file_type['type'];
                if (in_array($uploaded_type, $supported_types)) {
                    $filename = $_FILES['react_app_uploader']['tmp_name'];
                    $newFolder = $uploadfolder.'/app'.time().'/';
                    $newName = $newFolder.'build.zip';
                    $newUrl = $uploadurl.'app'.time().'/';
                    error_log($filename);
                    if (!file_exists($newFolder)) {
                        mkdir($newFolder);
                    }
                    $move = copy($filename, $newName);
                    $zip = new ZipArchive();
                    $bOK = $zip->open($newName);
                    if ($bOK !== true) {
                        throw new \Exception('Datei konnte nicht geöffnet werden');
                    }

                    $bOK = $zip->extractTo($newFolder);
                    if ($bOK !== true) {
                        throw new \Exception('Datei konnte nicht entpackt werden');
                    }




                    #
                    if (isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                    } else {
                        add_post_meta($id, 'react_app_folder', $newFolder);
                        update_post_meta($id, 'react_app_folder', $newFolder);
                        add_post_meta($id, 'react_app_url', $newUrl);
                        update_post_meta($id, 'react_app_url', $newUrl);
                    }
                } else {
                    return new WP_Error('error', 'test');

                    wp_die("The file type that you've uploaded is not a PDF.");
                }
            } else {
                error_log(print_r($_FILES['react_app_uploader'], true));
            }
        }
    }
    public function update_edit_form($post)
    {
        if ($post->post_type === "react_app") {
            echo ' enctype="multipart/form-data"';
        }
    }
}
new RAS_App();
