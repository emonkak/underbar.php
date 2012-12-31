<?php

namespace Underscore;

class UnionGenerator
{
	public static function union(array $arrays)
	{
		foreach ($arrays as $array) {
			foreach ($array as $value)
				yield $value;
		}
	}
}

// __END__
// vim: expandtab softtabstop=2 shiftwidth=2
