<?php
$filefont = 'https://www.cdnfonts.com/sketched.font';
$doc = new DOMDocument();
$doc->loadHTMLFile($filefont, LIBXML_NOERROR);
$elements = $doc->getElementsByTagName('i');
var_dump ($elements);
foreach($elements as $element) {
    if ($element->$textContent === 'https://fonts.cdnfonts.com/css/sketched') {
      var_dump( $element['textContent'] );
    }
}