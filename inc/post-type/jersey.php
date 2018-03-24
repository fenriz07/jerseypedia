<?php

define('PREFIX_META_BOX_JP', 'jersey-post-');

function jerseyCustomPost()
{
    $labels = array(
        'name'                => _x('Jersey', 'Post Type General Name', JERSEY_DOMAIN_TEXT),
        'singular_name'       => _x('Jersey', 'Post Type Singular Name', JERSEY_DOMAIN_TEXT),
        'menu_name'           => __('Jersey', JERSEY_DOMAIN_TEXT),
        'parent_item_colon'   => __('Parent Jersey:', JERSEY_DOMAIN_TEXT),
        'all_items'           => __('All Jersey', JERSEY_DOMAIN_TEXT),
        'view_item'           => __('View Jersey', JERSEY_DOMAIN_TEXT),
        'add_new_item'        => __('Add Jersey', JERSEY_DOMAIN_TEXT),
        'add_new'             => __('Add New', JERSEY_DOMAIN_TEXT),
        'edit_item'           => __('Edit Jersey', JERSEY_DOMAIN_TEXT),
        'update_item'         => __('Update Jersey', JERSEY_DOMAIN_TEXT),
        'search_items'        => __('Search Jerset', JERSEY_DOMAIN_TEXT),
        'not_found'           => __('Not found', JERSEY_DOMAIN_TEXT),
        'not_found_in_trash'  => __('Not found in Trash', JERSEY_DOMAIN_TEXT),
    );

    $args = array(
        'label'               => __('Jersey', JERSEY_DOMAIN_TEXT),
        'description'         => __('Jersey', JERSEY_DOMAIN_TEXT),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail',),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 8,
        'menu_icon'           => 'dashicons-hammer',
        'can_export'          => true,
        'has_archive'         => true,
        'rewrite' => array(
            'slug' => 'jersey'
        ),
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
    register_post_type('jersey', $args);
}

// Hook into the 'init' action
add_action('init', 'jerseyCustomPost', 0);

function jerseyPostMetaBox($meta_boxes)
{
    $meta_boxes[] = array(
        'id' => 'metabox-jersey-post',
        'title' => esc_html__('Meta Datos', JERSEY_DOMAIN_TEXT),
        'post_types' => array( 'jersey' ),
        'context' => 'advanced',
        'priority' => 'default',
        'autosave' => false,
        'fields' => array(
            array(
                'id' => PREFIX_META_BOX_JP . 'kit',
                'type' => 'select',
                'name' => esc_html__('Kit', JERSEY_DOMAIN_TEXT),
                'placeholder' => esc_html__('Kit', JERSEY_DOMAIN_TEXT),
                'options' => array(
                    1 => 'Home kit',
                    2 => 'Away kit',
                    3 => 'Third kit',
                    4 => 'Other'
                ),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'type_kit',
                'name' => esc_html__('Type Kit', JERSEY_DOMAIN_TEXT),
                'type' => 'select',
                'placeholder' => esc_html__('Select an Item', JERSEY_DOMAIN_TEXT),
                'options' => array(
                    1 => 'Home kit',
                    2 => 'Away kit',
                    3 => 'Third kit',
                    4 => 'Other'
                ),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'league',
                'type' => 'text',
                'name' => esc_html__('League', JERSEY_DOMAIN_TEXT),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'make',
                'type' => 'text',
                'name' => esc_html__('Make', JERSEY_DOMAIN_TEXT),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'sponsors',
                'type' => 'text',
                'name' => esc_html__('Sponsors', JERSEY_DOMAIN_TEXT),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'colours',
                'type' => 'text',
                'name' => esc_html__('Colours', JERSEY_DOMAIN_TEXT),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'fabric',
                'type' => 'text',
                'name' => esc_html__('Fabric', JERSEY_DOMAIN_TEXT),
            ),
            array(
                'id' => PREFIX_META_BOX_JP . 'views',
                'type' => 'hidden',
                'name' => esc_html__('Views', JERSEY_DOMAIN_TEXT),
                'std'  => 0,
            ),
        ),
    );

    return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'jerseyPostMetaBox');
