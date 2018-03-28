<?php

define('JERSEY_DIR', trailingslashit(get_template_directory()));
define('JERSEY_URI', trailingslashit(get_template_directory_uri()));
define('JERSEY_DOMAIN_TEXT', 'jerseypedia-theme');

error_reporting(-1);
ini_set('display_errors', 'On');

require_once JERSEY_DIR . 'inc/jersey-core-class.php';

if (! function_exists('jersey_setup')) {
    function jersey_setup()
    {
        $runJersey = new JerseyCore();
    }
} // jersey_setup
add_action('after_setup_theme', 'jersey_setup');

add_action('init', 'jersey_session_start', 1);

function jersey_session_start()
{
    if (! session_id()) {
        session_start();
    }
}

// TODO: Mover al core y refactorizar

add_action('init', 'wpse26388_rewrites_init', 2);
function wpse26388_rewrites_init()
{
    add_rewrite_rule(
        'properties',
        'index.php?pagename=properties',
        'top'
    );
}



add_filter('template_include', function ($template) {

    // No es necesario ningún valor especifico del "gallery" query var,
    // tan sólo que esté presente y que la plantilla single-event-gallery.php existe

    // locate_template() devuelve el path de la plantilla, si existe, un string vacío si no
    // locate_template() es compatible con temas hijos
    $pagename = get_query_var('pagename');
    $gallery_template = locate_template('page-properties.php');
    if ($pagename == 'properties') {
        return $gallery_template;
    }

    return $template;
});
