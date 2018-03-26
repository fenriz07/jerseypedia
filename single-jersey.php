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





 ?>



<form id="featured_upload" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
	<input type="file" name="my_image_upload[]" id="my_image_upload[]"  multiple="true" />
  <input type="hidden" name="action" value="jersey_gallery_form">
	<input type="hidden" name="jersey_id" value="<?php echo $post->ID;?>">
	<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />

</form>
