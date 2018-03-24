<?php

/**
 *
 */
class jerseyGallery
{
    public function __construct()
    {
        global $post;
        $this->post_id      = $post->ID;
        $this->current_user = wp_get_current_user();
        $this->key          = PREFIX_META_BOX_JP . 'jerseygallery';
    }

    private function getGallery()
    {
        $gallery = get_post_meta($this->post_id, $this->key, true);
        if ($gallery == null) {
            $gallery = serialize([]);

            delete_post_meta($this->post_id, $this->key);

            add_post_meta($this->post_id, $this->key, $gallery_empty);
        }

        return unserialize($gallery);
    }

    public function hearPost()
    {
        add_action('admin_post_jersey_gallery_form', array( $this, 'processingPost' ));
    }

    public function processingPost()
    {
        echo 'hola';
    }
}
