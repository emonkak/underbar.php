<?php

require __DIR__ . '/../vendor/autoload.php';

$ref = new ReflectionClass('Underbar\\Strict');
$categoryPattern = '/\*\s+@category\s+(Collections|Arrays|Objects|Chaining)|^$/';

echo '<?php', PHP_EOL;
echo "namespace {$ref->getNamespaceName()};", PHP_EOL;
echo 'trait Enumerable{', PHP_EOL;

foreach ($ref->getMethods() as $method) {
    if (!$method->isPublic()
        || !preg_match($categoryPattern, $method->getDocComment())) {
        continue;
    }

    $args = array();
    foreach ($method->getParameters() as $i => $param) {
        if ($i === 0) {
            continue;
        }

        $arg = '';
        if ($param->isPassedByReference()) {
            $arg .= '&';
        }
        $arg .= '$_' . $i;
        if ($param->isOptional()) {
            $arg .= '=';
            $arg .= var_export($param->getDefaultValue(), true);
        }
        $args[] = $arg;
    }

    for ($i = max($method->getNumberOfParameters(), 1); $i < 10; $i++) {
        $args[] = '$_' . $i . '=NULL';
    }

    echo "function {$method->getName()}(";
    echo implode(',', $args);
    echo '){';
    echo "return Lazy::{$method->getName()}(\$this,\$_1,\$_2,\$_3,\$_4,\$_5,\$_6,\$_7,\$_8,\$_9);";
    echo '}', PHP_EOL;
}

echo '}';
