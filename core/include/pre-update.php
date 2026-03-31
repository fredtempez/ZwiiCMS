<?php
/**
 * Mise à jour avant v12
 * */
if (file_exists('site/data/core.json')) { $core = json_decode(file_get_contents('site/data/core.json'), true); $version = $core['core']['dataVersion']; if ( $version < 12000 ) { $languages = [ 'fr' => 'fr_FR', 'en' => 'en_EN', 'pt' => 'pt_PT' ]; foreach ($languages as $key => $value) { if ( is_dir('site/data/' . $key) && !is_dir('site/data/' . $value) ) { $end = rename('site/data/' . $key, 'site/data/' . $value); } } sleep(1); } if ( $version < 12400 ) { $t = [ 'site/data/languages.json' => 'site/data/language.json', 'site/data/fonts.json' => 'site/data/font.json' ]; foreach ($t as $k => $v) { if (file_exists($k)) { $d = file_get_contents($k); $d = str_replace('"' . basename($k, '.json') . '"' , '"' . basename($v, '.json') . '"', $d); file_put_contents($v, $d); unlink($k); } } } }
