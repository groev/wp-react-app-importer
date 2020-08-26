<?php

// WRAI_Shortcode handles the display of data within the site.

class WRAI_Shortcode
{
    public function __construct()
    {
        add_shortcode('React-App', array($this, 'handle_shortcode'));
    }

    public function handle_shortcode($atts, $content = null)
    {
        // Checking shortcode attritubes
        $a = shortcode_atts(array(
            'id' => null,
            'stylesheet' => "on"
        ), $atts);
        // if the post or id does not exist, return nothing
        if (!$a['id'] || !get_post($a['id'])) {
            return;
        }
        // render shortcode
        return $this->render_app($a['id'], $a['stylesheet']);
    }

    // render_app handles to output of the shortcode.
    private function render_app($id, $stylesheet)
    {
        if (is_admin()) {
            return;
        }
        $name = get_post_meta($id, 'react_app_name', true);
        if (!$name) {
            return;
        }
        $path = WRAIUPLOADPATH.$name.'/';
        $url = WRAIUPLOADURL.$name.'/';
        $html = file_get_contents($path.'index.html');
        libxml_use_internal_errors(true);
        $parsed =  DOMDocument::loadHTML($html); // Using DOMDocument to get all HTML Tags
        libxml_use_internal_errors(false);
        $divs = $parsed->getElementsByTagName('div'); // getting all divs, one needed
        $scripts = $parsed->getElementsByTagName('script'); // getting all scripts
        $links = $parsed->getElementsByTagName('link'); // getting all links (for styling)
        // Starting output buffer
        ob_start();
        $i=0;
        // going througha all divs and echo those with id.
        if ($divs) {
            foreach ($divs as $div) {
                $divId = $div->getAttribute('id');
                if ($divId) {
                    echo '<div id="'.esc_attr($divId).'"></div>';
                }
            }
        }
        // Looping through scripts
        if ($scripts) {
            foreach ($scripts as $script) {
                $src = $script->getAttribute('src');
                $content = $script->textContent;
                // if script has source, enque
                if ($src) {
                    $i++;
                    $fullUrl = rtrim($url, '/').$src;
                    wp_enqueue_script('react-script-'.$i, $fullUrl, array(), "1.0.0", true);
                }
                // if script has content, echo
                if ($content) {
                    $content = str_replace("'/'", "'".$url."/'", $content);
                    $content = str_replace('"/"', '"'.$url.'/"', $content);
                    $content = str_replace(site_url(), "", $content);
                    echo '<script>'.$content.'</script>';
                }
            }
        }
        if ($links && $stylesheet !== "off") {
            $s = 0;
            foreach ($links as $link) {
                // if link has rel, enque, footer position is potentially to be optimized.
                if ($link->getAttribute('rel') === "stylesheet") {
                    $s++;
                    $href = $link->getAttribute('href');
                    $fullUrl = rtrim($url, '/').$href;
                    if (!strpos($href, 'http')) {
                        wp_enqueue_style('react-app-style-'.$s, esc_url($fullUrl), array(), "1.0.0", 'all');
                    } else {
                        wp_enqueue_style('react-app-style-'.$s, esc_url($href), array(), "1.0.0", 'all');
                    }
                }
            }
        }

        return ob_get_clean();
    }
}
new WRAI_Shortcode();
