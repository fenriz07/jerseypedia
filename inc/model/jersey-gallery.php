<?php

require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');



/**
 *
 */
class jerseyGallery
{
    public function __construct()
    {
        global $post;
        $this->current_user = wp_get_current_user();
        $this->key          = PREFIX_META_BOX_JP . 'jerseygallery';
    }

    private function getGallery($jersey_id)
    {
        $gallery = get_post_meta($jersey_id, $this->key, true);
        if ($gallery == null) {
            $gallery = serialize([]);

            delete_post_meta($jersey_id, $this->key);

            add_post_meta($jersey_id, $this->key, $gallery);
        }

        return unserialize($gallery);
    }

    public function getGalleryView($jersey_id)
    {
        return $this->getGallery($jersey_id);
    }

    /*
      Listen to the {post} from jerseysingle
    */
    public function hearPost()
    {
        add_action('admin_post_jersey_gallery_form', array( $this, 'processingPost' ));
    }

    private function prepareImage($files, $key)
    {
        $file = [
                  'name' => $files['name'][$key],
                  'type' => $files['type'][$key],
                  'tmp_name' => $files['tmp_name'][$key],
                  'error' => $files['error'][$key],
                  'size' => $files['size'][$key]
                ];

        return array("my_image_upload" => $file);
    }

    private function insert_UpdateGallery($list_images, $jersey_id)
    {
        $key     = "user-{$this->current_user->ID}";
        $gallery = $this->getGallery($jersey_id);


        if (array_key_exists($key, $gallery)) {
            $gallery[$key] = array_merge($gallery[$key], $list_images);
        } else {
            $gallery[$key] = $list_images;
            set_post_thumbnail($jersey_id, $list_images[0]);
            //Si es nuevo creamos la imagen destacada.
        }

        $gallery = serialize($gallery);

        update_post_meta($jersey_id, $this->key, $gallery);
    }

    public function gallerySingleJersey($images_gallery, $jersey_id, $message_c = null)
    {
        $files       = $images_gallery["my_image_upload"];
        $attach_ids  = [];
        $cod_message = '';


        if ($files['size'][0] != 0) {
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $_FILES = $this->prepareImage($files, $key);
                    foreach ($_FILES as $file => $array) {
                        $attach_id = media_handle_upload($file, -1);
                        array_push($attach_ids, $attach_id);
                    }
                }
            }
        } else {
            JPFlashMessage::FlashMessage(__('Error has not uploaded images', JERSEY_DOMAIN_TEXT));
        }

        if (is_wp_error($attach_id)) {
            JPFlashMessage::FlashMessage(__('Error', JERSEY_DOMAIN_TEXT));
        } else {
            $this->insert_UpdateGallery($attach_ids, $jersey_id);
            if ($message_c == null) {
                JPFlashMessage::FlashMessage(__('Images added', JERSEY_DOMAIN_TEXT));
            }
        }
    }

    public function processingPost()
    {

        //This function is executed only if the user is authenticated

        if (!isset($_POST['jersey_id'])) {
            JPFlashMessage::FlashMessage(__('Jersey not exist', JERSEY_DOMAIN_TEXT));
        }
        /* TODO: Validar con nonce....
        */
        $this->gallerySingleJersey($_FILES, $_POST['jersey_id']);
    }
}
