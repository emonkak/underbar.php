<?php
namespace Underbar;
trait Enumerable{
abstract function getUnderbarImpl();
abstract function value();
function map($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'map'),$this->value(),$a);return $impl::chain($result);}
function filter($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'filter'),$this->value(),$a);return $impl::chain($result);}
function sortBy($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sortBy'),$this->value(),$a);return $impl::chain($result);}
function groupBy($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'groupBy'),$this->value(),$a);return $impl::chain($result);}
function countBy($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'countBy'),$this->value(),$a);return $impl::chain($result);}
function shuffle(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'shuffle'),$this->value());return $impl::chain($result);}
function memoize(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'memoize'),$this->value());return $impl::chain($result);}
function firstN($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'firstN'),$this->value(),$a);return $impl::chain($result);}
function lastN($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'lastN'),$this->value(),$a);return $impl::chain($result);}
function initial($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'initial'),$this->value(),$a,$b);return $impl::chain($result);}
function rest($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'rest'),$this->value(),$a,$b);return $impl::chain($result);}
function takeWhile($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'takeWhile'),$this->value(),$a);return $impl::chain($result);}
function dropWhile($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'dropWhile'),$this->value(),$a);return $impl::chain($result);}
function flatten($a=false){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'flatten'),$this->value(),$a);return $impl::chain($result);}
function unzip(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'unzip'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function range($a=NULL,$b=1){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'range'),$this->value(),$a,$b);return $impl::chain($result);}
function cycle($a=-1){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'cycle'),$this->value(),$a);return $impl::chain($result);}
function repeat($a=-1){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'repeat'),$this->value(),$a);return $impl::chain($result);}
function iterate($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'iterate'),$this->value(),$a);return $impl::chain($result);}
function reverse(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reverse'),$this->value());return $impl::chain($result);}
function sort($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sort'),$this->value(),$a);return $impl::chain($result);}
function concat(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'concat'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function each($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'each'),$this->value(),$a);return $impl::chain($result);}
function collect($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'collect'),$this->value(),$a);return $impl::chain($result);}
function reduce($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reduce'),$this->value(),$a,$b);return $result;}
function inject($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'inject'),$this->value(),$a,$b);return $result;}
function foldl($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'foldl'),$this->value(),$a,$b);return $result;}
function reduceRight($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reduceRight'),$this->value(),$a,$b);return $result;}
function foldr($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'foldr'),$this->value(),$a,$b);return $result;}
function find($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'find'),$this->value(),$a);return $result;}
function detect($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'detect'),$this->value(),$a);return $result;}
function select($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'select'),$this->value(),$a);return $impl::chain($result);}
function where($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'where'),$this->value(),$a);return $impl::chain($result);}
function findWhere($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'findWhere'),$this->value(),$a);return $result;}
function reject($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reject'),$this->value(),$a);return $impl::chain($result);}
function every($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'every'),$this->value(),$a);return $result;}
function all($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'all'),$this->value(),$a);return $result;}
function some($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'some'),$this->value(),$a);return $result;}
function any($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'any'),$this->value(),$a);return $result;}
function contains($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'contains'),$this->value(),$a);return $result;}
function invoke($a){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'invoke'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function pluck($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'pluck'),$this->value(),$a);return $impl::chain($result);}
function max($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'max'),$this->value(),$a);return $result;}
function min($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'min'),$this->value(),$a);return $result;}
function sum(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sum'),$this->value());return $result;}
function product(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'product'),$this->value());return $result;}
function toArray(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'toArray'),$this->value());return $result;}
function toList(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'toList'),$this->value());return $result;}
function size(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'size'),$this->value());return $result;}
function first($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'first'),$this->value(),$a,$b);return $a===null?$result:$impl::chain($result);}function head($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'head'),$this->value(),$a,$b);return $impl::chain($result);}
function take($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'take'),$this->value(),$a,$b);return $impl::chain($result);}
function last($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'last'),$this->value(),$a,$b);return $a===null?$result:$impl::chain($result);}function tail($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'tail'),$this->value(),$a,$b);return $impl::chain($result);}
function drop($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'drop'),$this->value(),$a,$b);return $impl::chain($result);}
function compact(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'compact'),$this->value());return $impl::chain($result);}
function without(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'without'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function union(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'union'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function intersection(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'intersection'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function difference(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'difference'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function uniq($a=false,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'uniq'),$this->value(),$a,$b);return $impl::chain($result);}
function unique($a=false,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'unique'),$this->value(),$a,$b);return $impl::chain($result);}
function zip(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'zip'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function zipWith(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'zipWith'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function object($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'object'),$this->value(),$a);return $impl::chain($result);}
function indexOf($a,$b=0){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'indexOf'),$this->value(),$a,$b);return $result;}
function lastIndexOf($a,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'lastIndexOf'),$this->value(),$a,$b);return $result;}
function sortedIndex($a,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sortedIndex'),$this->value(),$a,$b);return $result;}
function join($a=','){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'join'),$this->value(),$a);return $result;}
function keys(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'keys'),$this->value());return $impl::chain($result);}
function values(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'values'),$this->value());return $impl::chain($result);}
function pairs(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'pairs'),$this->value());return $impl::chain($result);}
function invert(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'invert'),$this->value());return $impl::chain($result);}
function extend(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'extend'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function pick(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'pick'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function tap($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'tap'),$this->value(),$a);return $result;}
function omit(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'omit'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function defaults(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'defaults'), array_merge(array($this->value()),func_get_args()));return $impl::chain($result);}
function parMap($a,$b=NULL,$c=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'parMap'),$this->value(),$a,$b,$c);return $impl::chain($result);}
}
