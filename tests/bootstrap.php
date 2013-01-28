<?php

date_default_timezone_set('UTC');

function provideClasses()
{
    $classes = array(
        array('Underdash\\Strict'),
        array('Underdash\\Lazy_Iterator'),
    );

    if (class_exists('Generator', false))
        $classes[] = array('Underdash\\Lazy_Generator');

    return $classes;
}

require(__DIR__ . '/../vendor/autoload.php');
