<?php


echo "<h1>epa</h1>";

// echo '<pre>';
// var_dump(JerseyModel::select()->metabox()->field('league'));
// echo '</pre>';

// echo '<pre>';
// var_dump(JerseyModel::select()->jerseyUser()->base()->addMeta()->get());
// echo '</pre>';


// echo '<pre>';
// var_dump(JerseyModel::select()->recentContributions(3)->base()->addMeta()->get());
// echo '</pre>';
//
//
// echo '<pre>';
// var_dump(JerseyModel::select()->rand(1)->base()->get());
// echo '</pre>';
//
//
//
// echo '<pre>';
// var_dump(JerseyModel::select()->recentContributions(2)->base()->get());
// echo '</pre>';
//
// echo 'Contador';
// echo '<h1>' . JerseyModel::set()->view() .'</h1>';

//
// echo '<pre>';
// var_dump(JerseyModel::select()->rand(2)->base()->addMeta(['type_kit','make','sponsors'])->get());
// echo '</pre>';
?>



<form id="featured_upload" method="post" action="<?php echo get_site_url() . '/advanced-search'   ?>" enctype="multipart/form-data">
	<input type="text" name="title-jersey"/>
  <?php wp_nonce_field('jersey-search', 'advanced-search'); ?>
	<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
</form>
