<?php
 wp_nonce_field('upload_react_app', 'react_uploader_nonce');
 $meta = get_post_meta($_GET['post'], 'react_app_url', true);
if ($meta) {
    echo "Current build files: ".$meta;
}


?>



<p>Upload a ZIP of your create react app build here.</p>
<input type="file" id="react_app_uploader" name="react_app_uploader" value="" size="25" />
