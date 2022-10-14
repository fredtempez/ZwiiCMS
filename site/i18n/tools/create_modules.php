<?php
    $target = 'search';


    $data = json_decode(file_get_contents('fr_FR.json'), true);
    $dataTarget = json_decode(file_get_contents($target . '.json'), true);


    foreach($dataTarget as $key => $value)  {
        if (array_key_exists($key, $data)) {
            unset($dataTarget[$key]);
            echo $key;
            echo "<p>";
        }
    }

    file_put_contents ($target . '.json', json_encode($dataTarget, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
