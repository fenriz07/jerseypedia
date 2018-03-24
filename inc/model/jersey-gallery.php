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

            add_post_meta($jersey_id, $this->key, $gallery_empty);
        }

        return unserialize($gallery);
    }

    public function getGalleryView($jersey_id)
    {
        return $this->getGallery($jersey_id);
    }

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
            $gallery[$key] = array_merge($gallery[$key], $list_images) ;
        } else {
            $gallery[$key] = $list_images;
        }

        $gallery = serialize($gallery);

        update_post_meta($jersey_id, $this->key, $gallery);
    }

    private function gallerySingleJersey($images_gallery, $jersey_id)
    {
        $parse_url   = parse_url($_SERVER['HTTP_REFERER']);
        $url         = $parse_url['host'] . $parse_url['path'] . '?m=%s';
        $files       = $images_gallery["my_image_upload"];
        $attach_ids  = [];
        $cod_message = '';


        if ($files['size'][0] != 0) {
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $_FILES = $this->prepareImage($files, $key);
                    foreach ($_FILES as $file => $array) {
                        // $newupload = my_handle_attachment($file,$post_id);
                        $attach_id = media_handle_upload($file, -1);
                        array_push($attach_ids, $attach_id);
                    }
                }
            }
        } else {
            $cod_message = 'si';
            $urlSet = sprintf($url, $cod_message);
            wp_redirect($urlSet);
            exit();
        }

        if (is_wp_error($attachment_id)) {
            $cod_message = 'ee';
        } else {
            $cod_message = 'ia';
            $this->insert_UpdateGallery($attach_ids, $jersey_id);
            //add attachs_ids for id_user
        }


        $urlSet = sprintf($url, $cod_message);

        wp_redirect($urlSet);
        exit();
    }

    public function processingPost()
    {

        // TODO: CREAR UNA FUNCION PARA UNIFICAR EL ID DEL MENSAJE CON LA REDIRECCION
        if (!isset($_POST['jersey_id'])) {
            echo 'mensaje de error';
        } elseif ($this->current_user->ID == 0) {
            echo 'usuario no autentificado';
        }
        /* TODO: Validar con nonce, para prevenir ataques. Si alguno de los 3 escenarios se da redireccionamos, salimos,no se procesa la peticion
           y mostramos un mensaje.
        */
        $this->gallerySingleJersey($_FILES, $_POST['jersey_id']);
    }
}
