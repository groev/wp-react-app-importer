<?php
    global $post_id;
    wp_nonce_field('upload_react_app', 'react_uploader_nonce'); // nonce field for validating the action
    $name = get_post_meta($post_id, 'react_app_name', true);
    $url = WRAIUPLOADURL.$name.'/'; // getting the previous urls/paths;
    $path = WRAIUPLOADPATH.$name;
    // check if meta and path exists, then display information on the current build.
    if ($name && file_exists($path)) {
        ?>
        <div class="wrai-box">
            <strong><?php _e('Current build files', 'wp-react-app-importer');?>:</strong><br /><?php echo $url;?>
        </div>
        <div class="wrai-box wrai-border ">
            <strong><?php _e('Your shortcode', 'wp-react-app-importer');?></strong>:<br />
            <input class="wrai-input" type="text" disabled name="shortcode" value="[React-App id=&quot;<?php _e($post_id);?>&quot;]" />
        </div>
        <?php 
    }

    // Start rendering the input field for uploading.
    ?>
    <div class="wrai-box">
        <p><?php echo __('Upload a ZIP of your Create-React App build here. Your previous build will be deleted.', 'wp-react-app-importer');?></p>
        <input type="file" id="react_app_uploader" name="react_app_uploader" value="" size="25" />
    </div>


    <?php // Some styling ?>
    <style>
        .wrai-box {
            padding: 1rem 0;
        }
        .wrai-border {
            border-top:1px solid #EEE;
            border-bottom:1px solid #EEE;
        }
        input.wrai-input {
            color: #666;
            width:400px;
            margin-top:5px;
        }
    </style>