<?php
    wp_nonce_field('upload_react_app', 'react_uploader_nonce');
    $url = get_post_meta($_GET['post'], 'react_app_url', true);
    $path = get_post_meta($_GET['post'], 'react_app_folder', true);
    if ($url && $path) {
        echo '<div style="margin:25px 0">';
        echo '<strong>'.__('Current build files', 'react-app-shortcodes').':</strong><br /> '.$url;
        echo '</div>';
        echo '<hr />';
        echo '<div style="margin:25px 0">';
        echo '<strong>'.__('Your shortcode', 'react-app-shortcodes').'</strong>:<br />';
        echo '<input style="color: #666;width:400px;margin-top:5px;" type="text" disabled name="shortcode" value="[React-App id=&quot;'.$_GET['post'].'&quot;]" />';
        echo '</div>';
        echo '<hr />';
    }
    ?>
    <div style="margin-bottom:25px">
<p><?php echo __('Upload a ZIP of your Creat-Reat App build here. Your previous build will be deleted.', 'react-app-shortcodes');?></p>
<input type="file" id="react_app_uploader" name="react_app_uploader" value="" size="25" />
</div>

<?php
