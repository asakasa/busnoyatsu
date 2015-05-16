<?php

$kutc_tt = file_get_contents('./tt/kutc.json');
$takatsuki_tt = file_get_contents('./tt/takatsuski.json');
$tonda_tt = file_get_contents('./tt/tonda.json');

$kutc = json_decode($kutc_tt,true);
$takatsuki = json_decode($takatsuki_tt,true);
$tonda = json_decode($tonda_tt,true);

//test

var_dump($kutc['kutc']['takatsuki']['week']);

