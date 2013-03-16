<?php require __DIR__ . '/../vendor/autoload.php'; echo '<?php', "\n" ?>
<?php $r = new ReflectionClass('Underbar\\Strict') ?>
namespace <?php echo $r->getNamespaceName() ?>;
trait Enumerable {
<?php foreach ($r->getMethods() as $m): ?>
<?php if ($m->isPublic() && preg_match('/\*\s+@category\s+(Collections|Arrays|Objects|Chaining)|^$/', $m->getDocComment())): ?>
public function <?php echo $m->getName() ?>(){$args=func_get_args();array_unshift($args,$this);return call_user_func_array('<?php echo $r->getName().'::'.$m->getName() ?>',$args);}
<?php endif ?>
<?php endforeach ?>
public function lazy(){return Lazy::chain($this);}
}
