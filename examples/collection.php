<?php

namespace App;

require(__DIR__ . '/../vendor/autoload.php');

class Collection implements \IteratorAggregate
{
    use \Understrike\Enumerable;

    protected $models;

    public function __construct(\Traversable $models)
    {
        $this->models = $models;
    }

    public function getIterator()
    {
        return $this->models;
    }
}

$begin = microtime(true);

$pdo = new \PDO('mysql:host=localhost;dbname=fuel_dev', 'root', 'root');
$stmt = $pdo->query('SELECT * FROM choices AS t0, choices AS t1');
$stmt->setFetchMode(\PDO::FETCH_CLASS, 'StdClass');

// slow and use huge memory
// $collection = (new Collection(new \ArrayIterator($stmt->fetchAll())))
//   ->filter(function($choice) { return $choice->valid == 1; });

// fast
$collection = (new Collection($stmt))
  ->lazy()
  ->filter(function($choice) { return $choice->valid == 1; });

$i = 0;
foreach ($collection as $model) {
    $i++;
}

printf('Memory: %s Count: %d Time: %.2f',
    number_format(memory_get_peak_usage(true)),
    $i,
    microtime(true) - $begin);
