<?php


echo "<h1>epa</h1>";


echo '<pre>';
var_dump(JerseyModel::select()->recentContributions(3)->base()->addMeta()->get());
echo '</pre>';


echo '<pre>';
var_dump(JerseyModel::select()->rand(1)->base()->get());
echo '</pre>';



echo '<pre>';
var_dump(JerseyModel::select()->recentContributions(2)->base()->get());
echo '</pre>';

echo 'Contador';
echo '<h1>' . JerseyModel::set()->view() .'</h1>';

//
// echo '<pre>';
// var_dump(JerseyModel::select()->rand(2)->base()->addMeta(['type_kit','make','sponsors'])->get());
// echo '</pre>';
