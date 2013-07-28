<?php
namespace Underbar;
trait Enumerable{
abstract function getCollection();
abstract function getUnderbarImpl();
function map($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'map'),$this->getCollection(),$a);return $impl::chain($result);}
function filter($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'filter'),$this->getCollection(),$a);return $impl::chain($result);}
function sortBy($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sortBy'),$this->getCollection(),$a);return $impl::chain($result);}
function groupBy($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'groupBy'),$this->getCollection(),$a);return $impl::chain($result);}
function countBy($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'countBy'),$this->getCollection(),$a);return $impl::chain($result);}
function shuffle(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'shuffle'),$this->getCollection());return $impl::chain($result);}
function memoize(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'memoize'),$this->getCollection());return $impl::chain($result);}
function firstN($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'firstN'),$this->getCollection(),$a);return $impl::chain($result);}
function initial($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'initial'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function rest($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'rest'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function takeWhile($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'takeWhile'),$this->getCollection(),$a);return $impl::chain($result);}
function dropWhile($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'dropWhile'),$this->getCollection(),$a);return $impl::chain($result);}
function flatten($a=false){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'flatten'),$this->getCollection(),$a);return $impl::chain($result);}
function unzip(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'unzip'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function range($a=NULL,$b=1){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'range'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function cycle($a=-1){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'cycle'),$this->getCollection(),$a);return $impl::chain($result);}
function repeat($a=-1){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'repeat'),$this->getCollection(),$a);return $impl::chain($result);}
function iterate($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'iterate'),$this->getCollection(),$a);return $impl::chain($result);}
function reverse(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reverse'),$this->getCollection());return $impl::chain($result);}
function sort($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sort'),$this->getCollection(),$a);return $impl::chain($result);}
function concat(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'concat'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function each($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'each'),$this->getCollection(),$a);return $impl::chain($result);}
function collect($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'collect'),$this->getCollection(),$a);return $impl::chain($result);}
function reduce($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reduce'),$this->getCollection(),$a,$b);return $result;}
function inject($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'inject'),$this->getCollection(),$a,$b);return $result;}
function foldl($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'foldl'),$this->getCollection(),$a,$b);return $result;}
function reduceRight($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reduceRight'),$this->getCollection(),$a,$b);return $result;}
function foldr($a,$b){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'foldr'),$this->getCollection(),$a,$b);return $result;}
function find($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'find'),$this->getCollection(),$a);return $result;}
function detect($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'detect'),$this->getCollection(),$a);return $result;}
function select($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'select'),$this->getCollection(),$a);return $impl::chain($result);}
function where($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'where'),$this->getCollection(),$a);return $impl::chain($result);}
function findWhere($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'findWhere'),$this->getCollection(),$a);return $result;}
function reject($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'reject'),$this->getCollection(),$a);return $impl::chain($result);}
function every($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'every'),$this->getCollection(),$a);return $result;}
function all($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'all'),$this->getCollection(),$a);return $result;}
function some($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'some'),$this->getCollection(),$a);return $result;}
function any($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'any'),$this->getCollection(),$a);return $result;}
function contains($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'contains'),$this->getCollection(),$a);return $result;}
function invoke($a){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'invoke'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function pluck($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'pluck'),$this->getCollection(),$a);return $impl::chain($result);}
function max($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'max'),$this->getCollection(),$a);return $result;}
function min($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'min'),$this->getCollection(),$a);return $result;}
function sum(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sum'),$this->getCollection());return $result;}
function product(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'product'),$this->getCollection());return $result;}
function toArray(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'toArray'),$this->getCollection());return $result;}
function toList(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'toList'),$this->getCollection());return $result;}
function size(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'size'),$this->getCollection());return $result;}
function first($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'first'),$this->getCollection(),$a,$b);return $a===null?$result:$impl::chain($result);}function head($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'head'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function take($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'take'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function last($a=NULL,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'last'),$this->getCollection(),$a,$b);return $a===null?$result:$impl::chain($result);}function lastN($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'lastN'),$this->getCollection(),$a);return $result;}
function tail($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'tail'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function drop($a=1,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'drop'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function compact(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'compact'),$this->getCollection());return $impl::chain($result);}
function without(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'without'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function union(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'union'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function intersection(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'intersection'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function difference(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'difference'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function uniq($a=false,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'uniq'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function unique($a=false,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'unique'),$this->getCollection(),$a,$b);return $impl::chain($result);}
function zip(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'zip'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function zipWith(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'zipWith'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function object($a=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'object'),$this->getCollection(),$a);return $impl::chain($result);}
function indexOf($a,$b=0){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'indexOf'),$this->getCollection(),$a,$b);return $result;}
function lastIndexOf($a,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'lastIndexOf'),$this->getCollection(),$a,$b);return $result;}
function sortedIndex($a,$b=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'sortedIndex'),$this->getCollection(),$a,$b);return $result;}
function join($a=','){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'join'),$this->getCollection(),$a);return $result;}
function keys(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'keys'),$this->getCollection());return $impl::chain($result);}
function values(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'values'),$this->getCollection());return $impl::chain($result);}
function pairs(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'pairs'),$this->getCollection());return $impl::chain($result);}
function invert(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'invert'),$this->getCollection());return $impl::chain($result);}
function extend(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'extend'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function pick(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'pick'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function tap($a){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'tap'),$this->getCollection(),$a);return $result;}
function omit(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'omit'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function defaults(){$impl=$this->getUnderbarImpl();$result=call_user_func_array(array($impl,'defaults'), array_merge(array($this->getCollection()),func_get_args()));return $impl::chain($result);}
function isArray(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'isArray'),$this->getCollection());return $result;}
function isTraversable(){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'isTraversable'),$this->getCollection());return $result;}
function parMap($a,$b=NULL,$c=NULL){$impl=$this->getUnderbarImpl();$result=call_user_func(array($impl,'parMap'),$this->getCollection(),$a,$b,$c);return $impl::chain($result);}
}
