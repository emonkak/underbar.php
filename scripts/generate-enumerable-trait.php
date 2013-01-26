<?php echo '<?php', "\n"; ?>
namespace Understrike;
trait Enumerable {
<?php require __DIR__ . '/../vendor/autoload.php'; use Understrike\Strict as _; foreach (get_class_methods('Understrike\\Strict') as $m): ?>
public function <?php echo $m ?>(){$args=func_get_args();array_unshift($args,$this);return call_user_func_array('Understrike\\Strict::<?php echo $m ?>',$args);}
<?php endforeach ?>
public function lazy(){return Lazy::wrap($this);}
}
