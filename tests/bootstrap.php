<?php

date_default_timezone_set('UTC');

function provideClasses()
{
    $classes = array(
        array('Understrike\\Strict'),
        array('Understrike\\Lazy_Iterator'),
    );

    if (class_exists('Generator', false))
        $classes[] = array('Understrike\\Lazy_Generator');

    return $classes;
}

require(__DIR__ . '/../vendor/autoload.php');
