<?php

require_once libfile('class/xml');
$outdata = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/city.xml"));
?>