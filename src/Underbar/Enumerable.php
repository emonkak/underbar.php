<?php
namespace Underbar;
trait Enumerable{
abstract function getUnderbarImpl();
function map($a){return call_user_func(array($this->getUnderbarImpl(),'map'),$this,$a);}
function filter($a){return call_user_func(array($this->getUnderbarImpl(),'filter'),$this,$a);}
function firstN($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'firstN'),$this,$a);}
function initial($a=1,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'initial'),$this,$a,$b);}
function rest($a=1,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'rest'),$this,$a,$b);}
function takeWhile($a){return call_user_func(array($this->getUnderbarImpl(),'takeWhile'),$this,$a);}
function dropWhile($a){return call_user_func(array($this->getUnderbarImpl(),'dropWhile'),$this,$a);}
function flatten($a=false){return call_user_func(array($this->getUnderbarImpl(),'flatten'),$this,$a);}
function unzip(){return call_user_func_array(array($this->getUnderbarImpl(),'unzip'), array_merge(array($this),func_get_args()));
}
function range($a=NULL,$b=1){return call_user_func(array($this->getUnderbarImpl(),'range'),$this,$a,$b);}
function cycle($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'cycle'),$this,$a);}
function repeat($a=-1){return call_user_func(array($this->getUnderbarImpl(),'repeat'),$this,$a);}
function iterate($a){return call_user_func(array($this->getUnderbarImpl(),'iterate'),$this,$a);}
function concat(){return call_user_func_array(array($this->getUnderbarImpl(),'concat'), array_merge(array($this),func_get_args()));
}
function each($a){return call_user_func(array($this->getUnderbarImpl(),'each'),$this,$a);}
function collect($a){return call_user_func(array($this->getUnderbarImpl(),'collect'),$this,$a);}
function reduce($a,$b){return call_user_func(array($this->getUnderbarImpl(),'reduce'),$this,$a,$b);}
function inject($a,$b){return call_user_func(array($this->getUnderbarImpl(),'inject'),$this,$a,$b);}
function foldl($a,$b){return call_user_func(array($this->getUnderbarImpl(),'foldl'),$this,$a,$b);}
function reduceRight($a,$b){return call_user_func(array($this->getUnderbarImpl(),'reduceRight'),$this,$a,$b);}
function foldr($a,$b){return call_user_func(array($this->getUnderbarImpl(),'foldr'),$this,$a,$b);}
function find($a){return call_user_func(array($this->getUnderbarImpl(),'find'),$this,$a);}
function detect($a){return call_user_func(array($this->getUnderbarImpl(),'detect'),$this,$a);}
function select($a){return call_user_func(array($this->getUnderbarImpl(),'select'),$this,$a);}
function where($a){return call_user_func(array($this->getUnderbarImpl(),'where'),$this,$a);}
function findWhere($a){return call_user_func(array($this->getUnderbarImpl(),'findWhere'),$this,$a);}
function reject($a){return call_user_func(array($this->getUnderbarImpl(),'reject'),$this,$a);}
function every($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'every'),$this,$a);}
function all($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'all'),$this,$a);}
function some($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'some'),$this,$a);}
function any($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'any'),$this,$a);}
function contains($a){return call_user_func(array($this->getUnderbarImpl(),'contains'),$this,$a);}
function invoke($a){return call_user_func_array(array($this->getUnderbarImpl(),'invoke'), array_merge(array($this),func_get_args()));
}
function pluck($a){return call_user_func(array($this->getUnderbarImpl(),'pluck'),$this,$a);}
function max($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'max'),$this,$a);}
function min($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'min'),$this,$a);}
function sum(){return call_user_func(array($this->getUnderbarImpl(),'sum'),$this);}
function product(){return call_user_func(array($this->getUnderbarImpl(),'product'),$this);}
function sortBy($a){return call_user_func(array($this->getUnderbarImpl(),'sortBy'),$this,$a);}
function groupBy($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'groupBy'),$this,$a);}
function countBy($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'countBy'),$this,$a);}
function shuffle(){return call_user_func(array($this->getUnderbarImpl(),'shuffle'),$this);}
function toArray(){return call_user_func(array($this->getUnderbarImpl(),'toArray'),$this);}
function toList(){return call_user_func(array($this->getUnderbarImpl(),'toList'),$this);}
function memoize(){return call_user_func(array($this->getUnderbarImpl(),'memoize'),$this);}
function size(){return call_user_func(array($this->getUnderbarImpl(),'size'),$this);}
function first($a=NULL,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'first'),$this,$a,$b);}
function head($a=NULL,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'head'),$this,$a,$b);}
function take($a=NULL,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'take'),$this,$a,$b);}
function last($a=NULL,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'last'),$this,$a,$b);}
function lastN($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'lastN'),$this,$a);}
function tail($a=1,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'tail'),$this,$a,$b);}
function drop($a=1,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'drop'),$this,$a,$b);}
function compact(){return call_user_func(array($this->getUnderbarImpl(),'compact'),$this);}
function without(){return call_user_func_array(array($this->getUnderbarImpl(),'without'), array_merge(array($this),func_get_args()));
}
function union(){return call_user_func_array(array($this->getUnderbarImpl(),'union'), array_merge(array($this),func_get_args()));
}
function intersection(){return call_user_func_array(array($this->getUnderbarImpl(),'intersection'), array_merge(array($this),func_get_args()));
}
function difference(){return call_user_func_array(array($this->getUnderbarImpl(),'difference'), array_merge(array($this),func_get_args()));
}
function uniq($a=false,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'uniq'),$this,$a,$b);}
function unique($a=false,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'unique'),$this,$a,$b);}
function zip(){return call_user_func_array(array($this->getUnderbarImpl(),'zip'), array_merge(array($this),func_get_args()));
}
function zipWith(){return call_user_func_array(array($this->getUnderbarImpl(),'zipWith'), array_merge(array($this),func_get_args()));
}
function span($a){return call_user_func(array($this->getUnderbarImpl(),'span'),$this,$a);}
function object($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'object'),$this,$a);}
function indexOf($a,$b=0){return call_user_func(array($this->getUnderbarImpl(),'indexOf'),$this,$a,$b);}
function lastIndexOf($a,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'lastIndexOf'),$this,$a,$b);}
function sortedIndex($a,$b=NULL){return call_user_func(array($this->getUnderbarImpl(),'sortedIndex'),$this,$a,$b);}
function reverse(){return call_user_func(array($this->getUnderbarImpl(),'reverse'),$this);}
function sort($a=NULL){return call_user_func(array($this->getUnderbarImpl(),'sort'),$this,$a);}
function join($a=','){return call_user_func(array($this->getUnderbarImpl(),'join'),$this,$a);}
function keys(){return call_user_func(array($this->getUnderbarImpl(),'keys'),$this);}
function values(){return call_user_func(array($this->getUnderbarImpl(),'values'),$this);}
function pairs(){return call_user_func(array($this->getUnderbarImpl(),'pairs'),$this);}
function invert(){return call_user_func(array($this->getUnderbarImpl(),'invert'),$this);}
function extend(){return call_user_func_array(array($this->getUnderbarImpl(),'extend'), array_merge(array($this),func_get_args()));
}
function pick(){return call_user_func_array(array($this->getUnderbarImpl(),'pick'), array_merge(array($this),func_get_args()));
}
function omit(){return call_user_func_array(array($this->getUnderbarImpl(),'omit'), array_merge(array($this),func_get_args()));
}
function defaults(){return call_user_func_array(array($this->getUnderbarImpl(),'defaults'), array_merge(array($this),func_get_args()));
}
function tap($a){return call_user_func(array($this->getUnderbarImpl(),'tap'),$this,$a);}
function isArray(){return call_user_func(array($this->getUnderbarImpl(),'isArray'),$this);}
function isTraversable(){return call_user_func(array($this->getUnderbarImpl(),'isTraversable'),$this);}
function chain(){return call_user_func(array($this->getUnderbarImpl(),'chain'),$this);}
function parMap($a,$b=NULL,$c=NULL){return call_user_func(array($this->getUnderbarImpl(),'parMap'),$this,$a,$b,$c);}
}
