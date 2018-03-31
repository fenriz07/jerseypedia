<h1>profile</h1>


<?php

//Jerseys Profile
echo '<pre>';
var_dump([JerseyModel::select()->jerseyUser()->base()->addMeta(['colours'])->get()]);
echo '</pre>';


//Get JerseyTitle
// echo '<pre>';
// var_dump(JerseyModel::select()->jerseyUser()->base()->addMeta()->get());
// echo '</pre>';


 ?>
