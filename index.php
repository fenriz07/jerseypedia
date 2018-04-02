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
	<input type="checkbox" name="advanced_search" value="true">Advanced search

	<input type="radio" name="kit" value="1">Home kit
	<input type="radio" name="kit" value="2">Away kit
	<input type="radio" name="kit" value="3">Third kit
	<input type="radio" name="kit" value="4">Other

	<select class="" name="season">
		<option value="0">Ninguno</option>
		<option value="1990">1990</option>
		<option value="2000">2000</option>
		<option value="2010">2010</option>
		<option value="2015">2015</option>
	</select>

	<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
</form>
