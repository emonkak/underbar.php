<?php

require(__DIR__ . '/../vendor/autoload.php');

date_default_timezone_set('UTC');

class Underbar_TestCase extends PHPUnit_Framework_TestCase
{
    public function provider()
    {
        $classes = array(
            array('Underbar\\Eager'),
            array('Underbar\\LazyIterator'),
        );

        if (class_exists('Generator', false)) {
            $classes[] = array('Underbar\\LazyGenerator');
            $classes[] = array('Underbar\\LazySafeGenerator');
        }

        return $classes;
    }
}
