<?php

require __DIR__ . '/../vendor/autoload.php';

$ref = new ReflectionClass('Underbar\\ArrayImpl');
$categoryPattern = '/\*\s+@category\s+(Arrays|Collections|Objects|Parallel)|^$/';
$chainabePattern = '/\*\s+@chainable/';
$varargsPattern = '/\*\s+@varargs/';

echo <<<EOF
<?php
namespace {$ref->getNamespaceName()};
trait Enumerable{
abstract function getUnderbarImpl();
abstract function value();

EOF;

foreach ($ref->getMethods() as $method) {
    $docComment = $method->getDocComment();
    $isMatchedCategory = preg_match($categoryPattern, $docComment);
    $isChainable = preg_match($chainabePattern, $docComment);
    $isVarargs = preg_match($varargsPattern, $docComment);

    if (!$method->isPublic()
        || !$isMatchedCategory
        || strpos($method->getName(), '_') === 0) {
        continue;
    }

    $defineArgs = array();
    $callArgs = array('$this->value()');
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
    echo '$impl=$this->getUnderbarImpl();';

    if ($isVarargs) {
        echo <<<EOF
\$result=call_user_func_array(array(\$impl,'{$method->getName()}'), array_merge(array(\$this->value()),func_get_args()));
EOF;
    } else {
        echo <<<EOF
\$result=call_user_func(array(\$impl,'{$method->getName()}'),
EOF;
        echo implode(',', $callArgs) . ');';
    }

    if ($isChainable) {
        if ($method->getName() === 'first'
            || $method->getName() === 'last') {
            echo 'return $a===null?$result:$impl::chain($result);}';
        } else {
            echo 'return $impl::chain($result);}', PHP_EOL;
        }
    } else {
        echo 'return $result;}', PHP_EOL;
    }
}

echo '}', PHP_EOL;
