<?php

date_default_timezone_set('UTC');

function provideClasses()
{
    $classes = array(
        array('Underbar\\Strict'),
        array('Underbar\\Lazy_Iterator'),
    );

    if (class_exists('Generator', false))
        $classes[] = array('Underbar\\Lazy_Generator');

    return $classes;
}

require(__DIR__ . '/../vendor/autoload.php');
