<?php


echo "<h1>epa</h1>";

echo '<pre>';
var_dump(JerseyModel::select()->rand(1)->get());
echo '</pre>';
