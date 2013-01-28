<?php require __DIR__ . '/../vendor/autoload.php'; echo '<?php', "\n"; ?>
namespace Underdash;
trait Enumerable {
<?php foreach (get_class_methods('Underdash\\Strict') as $m): ?>
<?php if (!in_array($m, array('range'))): ?>
public function <?php echo $m ?>(){$args=func_get_args();array_unshift($args,$this);return call_user_func_array('Underdash\\Strict::<?php echo $m ?>',$args);}
<?php endif ?>
<?php endforeach ?>
public function lazy(){return Lazy::chain($this);}
}
