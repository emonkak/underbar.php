<?php

require __DIR__ . '/../vendor/autoload.php';

$ref = new ReflectionClass('Underbar\\ArrayImpl');
$CATEGORY_PATTERN = '/\*\s+@category\s+(Arrays|Collections|Objects|Parallel)|^$/';
$CHAINABE_PATTERN = '/\*\s+@chainable/';
$VARARGS_PATTERN = '/\*\s+@varargs/';

echo <<<EOF
<?php
/**
 * This file is part of the Underbar.php package.
 *
 * Copyright (C) 2013 Shota Nozaki <emonkak@gmail.com>
 *
 * Licensed under the MIT License
 */

namespace {$ref->getNamespaceName()};

trait Enumerable
{
    abstract function getUnderbarImpl();

    abstract function value();

EOF;

foreach ($ref->getMethods() as $method) {
    $docComment = $method->getDocComment();
    $isMatchedCategory = preg_match($CATEGORY_PATTERN, $docComment);
    $isChainable = preg_match($CHAINABE_PATTERN, $docComment);
    $isVarargs = preg_match($VARARGS_PATTERN, $docComment);

    if (!$method->isPublic()
        || !$isMatchedCategory
        || strpos($method->getName(), '_') === 0) {
        continue;
    }

    $argVars = array('$this->value()');
    $funcArgs = array();

    foreach ($method->getParameters() as $i => $param) {
        if ($i === 0) {
            continue;
        }

        $argVars[] = $funcArg = '$' . $param->getName();

        if ($param->isOptional()) {
            $defaultValue = $param->getDefaultValue();
            $funcArg .= ' = ';
            $funcArg .= $defaultValue !== null
                      ? var_export($param->getDefaultValue(), true)
                      : 'null';
        }

        $funcArgs[] = $funcArg;
    }

    $args = implode(', ', $funcArgs);
    echo <<<EOF

    public function {$method->getName()}($args)
    {
        \$impl = \$this->getUnderbarImpl();

EOF;

    if ($isVarargs) {
        echo <<<EOF
        \$result = call_user_func_array(
            array(\$impl, '{$method->getName()}'),
            array_merge(array(\$this->value()), func_get_args())
        );

EOF;
    } else {
        $args = implode(', ', $argVars);
        echo <<<EOF
        \$result = \$impl::{$method->getName()}($args);

EOF;
    }

    if ($isChainable) {
        if (in_array($method->getName(), ['first', 'last', 'sample'])) {
            echo <<<EOF
        return {$argVars[1]} !== null ? \$impl::chain(\$result) : \$result;
    }

EOF;
        } else {
            echo <<<EOF
        return \$impl::chain(\$result);
    }

EOF;
        }
    } else {
            echo <<<EOF
        return \$result;
    }

EOF;
    }
}

echo '}', PHP_EOL;
