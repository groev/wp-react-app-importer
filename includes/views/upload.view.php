<?php
    wp_nonce_field('upload_react_app', 'react_uploader_nonce'); // nonce field for validating the action
    $name = get_post_meta($_GET['post'], 'react_app_name', true);
    $url = content_url().'/reactapps/'.$name.'/'; // getting the previous urls/paths;

    // check if url and path exists, then display information on the current build.
    if ($name) {
        echo '<div style="margin:25px 0">';
        echo '<strong>'.__('Current build files', 'wp-react-app-importer').':</strong><br /> '.$url;
        echo '</div>';
        echo '<hr />';
        echo '<div style="margin:25px 0">';
        echo '<strong>'.__('Your shortcode', 'wp-react-app-importer').'</strong>:<br />';
        echo '<input style="color: #666;width:400px;margin-top:5px;" type="text" disabled name="shortcode" value="[React-App id=&quot;'.$_GET['post'].'&quot;]" />';
        echo '</div>';
        echo '<hr />';
    }

    // Start rendering the input field for uploading.
    ?>
    <div style="margin-bottom:25px">
        <p><?php echo __('Upload a ZIP of your Create-React App build here. Your previous build will be deleted.', 'wp-react-app-importer');?></p>
        <input type="file" id="react_app_uploader" name="react_app_uploader" value="" size="25" />
    </div>
