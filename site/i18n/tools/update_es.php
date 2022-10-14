<?php

    $data = json_decode(file_get_contents('fr_FR.json'), true);
    $dataTarget = json_decode(file_get_contents('es.json'), true);

    foreach($data as $key => $value)  {
        if (!array_key_exists($key, $dataTarget)) {
            $dataTarget[$key] = '';
        }
    }

    file_put_contents ('es.json', json_encode($dataTarget, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
