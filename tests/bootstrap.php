<?php

date_default_timezone_set('UTC');

ini_set('memory_limit', '128M');

class Underbar_TestCase extends PHPUnit_Framework_TestCase
{
    public function provider()
    {
        $classes = array(
            array('Underbar\\Strict'),
            array('Underbar\\LazyIterator'),
        );

        if (class_exists('Generator', false)) {
            $classes[] = array('Underbar\\LazyGenerator');
        }

        return $classes;
    }
}

require(__DIR__ . '/../vendor/autoload.php');
