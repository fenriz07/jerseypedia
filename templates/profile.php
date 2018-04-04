<h1>profile</h1>


<?php

$user = new JerseyUser();

//Jerseys Profile

$user->setData();
echo '<pre>';
var_dump($user->getData());
echo '</pre>';



//Jerseys Profile
// echo '<pre>';
// var_dump([JerseyModel::select()->jerseyUser()->base()->addMeta(['colours'])->get()]);
// echo '</pre>';



//Get JerseyTitle
// echo '<pre>';
// var_dump(JerseyModel::select()->jerseyUser()->base()->addMeta()->get());
// echo '</pre>';

 ?>

 <form class="" action="/profile" method="post" enctype="multipart/form-data">
   <input type="text" name="nickname" value="">
   <input type="text" name="description" value="">
   <input type="text" name="fb_jersey" value="">
   <input type="text" name="tw_jersey" value="">
   <input type="text" name="ig_jersey" value="">
   <input type="file" name="profile_img" id="profile_img"/>
   <input type="submit" name="" value="">
 </form>
