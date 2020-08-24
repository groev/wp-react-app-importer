# creact-react-app-wordpress-shortcodes

Publish Apps created with "Create React App" on your Wordpress Site using plugins.

## Guide

1. Download the release from this repository
2. Install the plugin in WordPress using the uploader
3. Go to React Apps and create a new React App
4. Upload the ZIP file of your Create-React-App build and save the post.
5. Use the shortcode [React-App id="POSTID"] anywhere you like.

## Notes

1. This only works with Create-React-App and is tested with the latest Version.
2. You need to be careful about CSS Styling - it will apply to the whole page (Wordpress/React vice versa). JS Styling is recommended.
3. Routing isn't fully supported. This is tested with HashRouter from react-router.
4. Only one shortcode works per page, since React uses the "root" ID.
