<?php

require __DIR__ . '/../vendor/autoload.php';

$ref = new ReflectionClass('Underbar\\ArrayImpl');
$categoryPattern = '/\*\s+@category\s+(Collections|Arrays|Parallel|Objects|Chaining)|^$/';
$varargsPattern = '/\*\s+@varargs/';

echo <<<EOF
<?php
namespace {$ref->getNamespaceName()};
trait Enumerable{
abstract function getUnderbarImpl();

EOF;

foreach ($ref->getMethods() as $method) {
    $docComment = $method->getDocComment();
    $isMatchedCategory = preg_match($categoryPattern, $docComment);
    $isVarargs = preg_match($varargsPattern, $docComment);

    if (!$method->isPublic()
        || !$isMatchedCategory
        || strpos($method->getName(), '_') === 0) {
        continue;
    }

    $defineArgs = array();
    $callArgs = array('$this');
    foreach ($method->getParameters() as $i => $param) {
        if ($i === 0) {
            continue;
        }

        $defineArg = '';
        if ($param->isPassedByReference()) {
            $defineArg .= '&';
        }
        $callArg = '$' . chr(ord('a') + $i - 1);
        $defineArg .= $callArg;
        if ($param->isOptional()) {
            $defineArg .= '=';
            $defineArg .= var_export($param->getDefaultValue(), true);
        }
        $defineArgs[] = $defineArg;
        $callArgs[] = $callArg;
    }

    if ($method->returnsReference()) {
        echo "function &{$method->getName()}(";
    } else {
        echo "function {$method->getName()}(";
    }
    echo implode(',', $defineArgs) . '){';

    if ($isVarargs) {
        echo <<<EOF
return call_user_func_array(array(\$this->getUnderbarImpl(),'{$method->getName()}'), array_merge(array(\$this),func_get_args()));

EOF;
    } else {
        echo <<<EOF
return call_user_func(array(\$this->getUnderbarImpl(),'{$method->getName()}'),
EOF;
        echo implode(',', $callArgs) . ');';
    }

    echo '}', PHP_EOL;
}

echo '}', PHP_EOL;
