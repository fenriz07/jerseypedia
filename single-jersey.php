<?php
//echo '<h1>' . JerseyModel::set()->view() .'</h1>';

 $jerseygallery = new jerseyGallery();
// $jerseygallery->hearPost();
 global $post;

?>


<?php

        echo '<pre>';
        var_dump($jerseygallery->getGalleryView($post->ID));
        echo '</pre>';

     // TODO: Funciones para mensajes Flash, hacer con cases, asignar al core...

      if (isset($_GET['m'])) {
          $id = $_GET['m'];

          if ($id == 'ia') {
              echo __('Imagenes Agregadas', JERSEY_DOMAIN_TEXT);
          } elseif ($id == 'si') {
              echo __('Error no ha subido imagenes', JERSEY_DOMAIN_TEXT);
          } elseif ($id == 'ee') {
              echo __('Error', JERSEY_DOMAIN_TEXT);
          }
      }





 ?>



<form id="featured_upload" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
	<input type="file" name="my_image_upload[]" id="my_image_upload[]"  multiple="true" />
  <input type="hidden" name="action" value="jersey_gallery_form">
	<input type="hidden" name="jersey_id" value="<?php echo $post->ID;?>">
	<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />

</form>
