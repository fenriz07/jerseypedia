<?php

define('JERSEY_DIR', trailingslashit(get_template_directory()));
define('JERSEY_URI', trailingslashit(get_template_directory_uri()));
define('JERSEY_DOMAIN_TEXT', 'jerseypedia-theme');

require_once JERSEY_DIR . 'inc/jersey-core-class.php';

if (! function_exists('jersey_setup')) {
    function jersey_setup()
    {
        $runJersey = new JerseyCore();
    }
} // jersey_setup
add_action('after_setup_theme', 'jersey_setup');
