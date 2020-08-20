<?php

class RAS_Shortcode
{
    public function __construct()
    {
        add_shortcode('React-App', array($this, 'handle_shortcode'));
    }

    public function handle_shortcode($params, $content = null)
    {
        if (!$params['id'] || !get_post($params['id'])) {
            return;
        }
        return $this->render_app($params['id']);
    }

    private function render_app($id)
    {
        $path = get_post_meta($id, 'react_app_folder', true);
        $url = get_post_meta($id, 'react_app_url', true);
      
        if (!$path || !$url) {
            return;
        }
        $html = file_get_contents($path.'index.html');
        $parsed =  DOMDocument::loadHTML($html);
        $scripts = $parsed->getElementsByTagName('script');
        $links = $parsed->getElementsByTagName('link');

        ob_start();
        echo '<div id="root"></div>';
        $i=0;
        foreach ($scripts as $script) {
            $src = $script->getAttribute('src');
            $content = $script->textContent;
            if ($src) {
                $i++;
                $src = str_replace('/static/', $url.'static/', $src);
                wp_enqueue_script('react-script-'.$i, $src, array(), "1.0.0", true);
            }
            if ($content) {
                $content = str_replace("'/'", "'".$url."/'", $content);
                $content = str_replace('"/"', '"'.$url.'/"', $content);
                $content = str_replace(site_url(), "", $content);
                echo '<script>'.$content.'</script>';
            }
        }
        foreach ($links as $link) {
            if ($link->getAttribute('rel') === "stylesheet") {
                $href = $link->getAttribute('href');
                $href = str_replace('/static/', $url.'static/', $href);
                wp_enqueue_style('react-app-style', $href, array(), "1.0.0", 'all');
            }
        }
        return ob_get_clean();
    }
}
new RAS_Shortcode();
