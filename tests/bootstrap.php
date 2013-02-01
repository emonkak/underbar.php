<?php

date_default_timezone_set('UTC');

ini_set('memory_limit', '128M');

function provideClasses()
{
    $classes = array(
        array('Underbar\\Strict'),
        array('Underbar\\Lazy\\Iterator'),
    );

    if (class_exists('Generator', false))
        $classes[] = array('Underbar\\Lazy\\Generator');

    return $classes;
}

require(__DIR__ . '/../vendor/autoload.php');
