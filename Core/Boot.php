<?php

$pathList = include('Configs/app.php');

foreach ($pathList as $path) {
    foreach (glob($path) as $filename) {
        include $filename;
    }
}
