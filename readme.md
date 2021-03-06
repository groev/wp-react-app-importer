# WP React App Importer

Publish Apps created with "Create React App" on your WordPress Site using shortcodes.

## Examples 
Simple: https://testend.westhofen.me/sample-page/

Advanced (Routing & Fetching): https://testend.westhofen.me/advanced-example/

Todo List with Firebase Auth: https://testend.westhofen.me/todo-example/

## Guide

1. Download the release from this repository
2. Install the plugin in WordPress using the uploader
3. Go to React Apps and create a new React App
4. Upload the ZIP file of your Create-React-App build and save the post.
5. Use the shortcode  anywhere you like.
```
[React-App id="POSTID"]
```

## Shortcode Params

- id: The ID of your application.
- stylesheet: "on" or "off", if you want to disable the react stylesheet (default: on)

## Notes

1. This only works with Create-React-App and is tested with the latest Version.
2. You need to be careful about CSS Styling - it will apply to the whole page (Wordpress/React vice versa). JS Styling is recommended.
3. Routing works with HashRouter from react-router. BrowserRouter doesn't fully work on page refresh.
4. If you want to use multiple shortcodes per page, you need to change the main div from "root" to something else for each app.
