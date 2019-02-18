<?php

foreach (glob('System/*.php') as $filename) {
    include $filename;
}

foreach (glob('Helpers/*.php') as $filename) {
    include $filename;
}

foreach (glob('Classes/*.php') as $filename) {
    include $filename;
}

foreach (glob('App/Models/*.php') as $filename) {
    include $filename;
}
