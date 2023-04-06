<?php header("Content-type: text/javascript");
header("pragma: no-cache");
header("expires: 0");
echo file_get_contents('link_list.json');